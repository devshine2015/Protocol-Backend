<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\FollowElement;
use Validator;

class ElementFollowController extends Controller
{
    protected $model;
    public function __construct(FollowElement $followElement)
    {
        $this->model                = $followElement;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $elementFollow =  $this->elementFollow($request);
        if($elementFollow){
            return $this->apiOk(true);
        }
        return $this->apiErr(22010, 'something is wrong in user follow');
    }
     public function elementFollow($request){
        $user = Auth::guard('api')->user();
        $data  = $request->only(['element_id']);
        $valid = Validator::make($data, [
            'element_id'     => 'required|exists:elements,id'
        ]);
        if ($valid->fails()) {
            return $this->apiErr(22001, $valid->messages(), 422);
        }
        $element_id = $request->get('element_id');
        $check_element= $this->model->where('user_id',$user['id'])->where('element_id',$element_id)->first();
        if (!$check_element) {
            $this->model->element_id =$element_id; //login user is follower
            $this->model->user_id = $user['id'];
            if($this->model->save()){
               return true;
            }
            return false;
        }else{
             $deletefollowedUser = $this->model->where('user_id',$user['id'])->where('element_id',$element_id)->delete();
            if ($deletefollowedUser) {
                return true;
            }
            return false;
        }
    }
}
