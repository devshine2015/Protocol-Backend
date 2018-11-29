<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\FollowUser;
use App\Bridge;
use App\ContenLike;
use Validator;
use App\Events\AddPointEvent;

class UserFollowController extends Controller
{
    protected $model;
    public function __construct(FollowUser $followUser,ContenLike $contentLike,Bridge $bridge)
    {
        $this->model                        = $followUser;
        $this->contentLike                  = $contentLike;
        $this->bridge                       = $bridge;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $user = Auth::guard('api')->user();
        if ($user) {
            $getUser = $this->model->where('user_id',$user->id)->with('followUser')->get();
            if ($getUser) {
                return $this->apiOk($getUser->pluck('followUser'));
            }
            return $this->apiErr(22010,'something is wrong');
        }
        return $this->apiErr(22010,'something is wrong');
    }
    public function store(Request $request)
    {
        $userFollow =  $this->userFollow($request);
        if($userFollow){
            return $this->apiOk(true);
        }
        return $this->apiErr(22010, 'something is wrong in user follow');
    }
    public function userFollow($request){
        $user = Auth::guard('api')->user();
        $data  = $request->only(['user_id']);
        $valid = Validator::make($data, [
            'user_id'     => 'required|exists:users,id'
        ]);
        if ($valid->fails()) {
            return $this->apiErr(22001, $valid->messages(), 422);
        }
        $id = $request->get('user_id');
        $check_user= $this->model->where('user_id',$id)->where('follower_id',$user['id'])->first();
        if (!$check_user) {
            $this->model->follower_id =$user->id; //login user is follower
            $this->model->user_id = $id;
            if($this->model->save()){
               return true;
            }
            return false;
        }else{
             $deletefollowedUser = $this->model->where('follower_id',$user['id'])->where('user_id',$id)->delete();
            if ($deletefollowedUser) {
                return true;
            }
            return false;
        }
    }
    public function contentLike(Request $request){
        $user = Auth::guard('api')->user();
        $data  = $request->only(['user_id','type','type_id']);
        $type_id = 'required';
        $typeId = $request->get('type_id');
        //for add point and remove point
        if ($request->type == 0) {
            $pointData['user_id'] =  $this->bridge->where('id',$typeId)->pluck('created_by')->first();
            $pointData['type'] = 3;
            $pointData['type_id'] = $typeId;
        }//end
        if($request->type ==0){
            $type_id .='|exists:bridges,id';
        }
        else{
            $type_id .='|exists:notes,id';
        }
        $valid = Validator::make($data, [
            'type'        => 'required',
            'type_id'     => $type_id
        ]);
        if ($valid->fails()) {
            return $this->apiErr(22001, $valid->messages(), 422);
        }
        $request['user_id'] = $user->id;
        $check_content= $this->contentLike->where('user_id',$user->id)->where('type_id',$typeId)->where('type',$request->get('type'))->first();
        if (!$check_content) {
            $this->contentLike->fill($request->only('user_id','type_id', 'type', 'emoji_type'));
            if($this->contentLike->save()){
                //Add point on like
                if ($request->type == 0) {
                    $pointData['point'] = 50;
                    event(new AddPointEvent($pointData));
                }
                return $this->apiOk($this->contentLike->fresh());
            }
        }else{
             $deleteLikeContent = $this->contentLike->where('user_id',$user->id)->where('type_id',$typeId)->where('type',$request->get('type'))->delete();
                //Remove point on dislike
                if ($request->type == 0) {
                    $pointData['point'] = -50;
                    event(new AddPointEvent($pointData));
                }
            if ($deleteLikeContent) {
                return $this->apiOk(true);
            }
            return $this->apiErr(22010,'something is wrong');
        }
    }
}
