<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\FollowUser;
use Validator;

class UserFollowController extends Controller
{
    protected $model;
    public function __construct(FollowUser $followUser)
    {
        $this->model                = $followUser;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
}
