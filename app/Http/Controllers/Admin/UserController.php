<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use Auth;
use App\Bridge;
use App\Note;
use Illuminate\Support\Facades\Input;
use Validator;
use App\FollowUser;
class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    protected $model;
    protected $bridgemodel;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user, Bridge $bridge,Note $note,FollowUser $followUser)
    {
        $this->middleware('guest')->except('logout');
        $this->model                = $user;
        $this->bridgemodel          = $bridge;
        $this->noteModel            = $note;
        $this->followUserModel      = $followUser;
    }
    public function userData($name,$id){
        $getallData = $this->getbridgeData($id);
        if(Auth::user()->id != $id){
            $bridgeData = $getallData['bridgeList']->where('created_by',$id)->where('privacy',0)->get();
            $noteData = $getallData['notes']->where('created_by',$id)->where('privacy',0)->get();
        }else{
            $bridgeData = $getallData['bridgeList']->where('created_by',$id)->where('privacy',1)->get();
            $noteData = $getallData['notes']->where('created_by',$id)->where('privacy',1)->get();
        }
        $allData = $bridgeData->merge($noteData);
        if(count($allData)>0){
             $allData->filter(function ($q){
                if(isset($q->title)){
                    $q->title = $q->title;
                    $q->comefromNote = 1;
                }else{
                    $q->comefromNote = 0;
                    $q->fromUrl =get_domain(parse_url($q->fromElement->url, PHP_URL_HOST));
                    $q->toUrl =get_domain(parse_url($q->toElement->url, PHP_URL_HOST));
                }
            });
        }
        //check user follow user or not
        $checkfollow = $this->followUserModel->where('user_id',$id)->where('follower_id', Auth::user()->id)->first();
        $this->response['bridge'] = $allData;
        $this->response['userId'] = $id;
        $this->response['userData'] = $this->model->whereId($id)->first();
        $this->response['is_follow'] = (isset($checkfollow)>0)?1:0;
        return view('admin.user.edit_profile')->with($this->response);
    }
     public function dashboard(){
        $id = Auth::user()->id;
        $getallData = $this->getbridgeData();
        $bridgeData = $getallData['bridgeList']->where('created_by',$id)->get();
        $noteData = $getallData['notes']->where('created_by',$id)->get();
        $allData = $bridgeData->merge($noteData);
        if(count($allData)>0){
             $allData->filter(function ($q){
                if(isset($q->title)){
                    $q->title = $q->title;
                    $q->comefromNote = 1;
                }else{
                    $q->comefromNote = 0;
                    $q->fromUrl =get_domain(parse_url($q->fromElement->url, PHP_URL_HOST));
                    $q->toUrl =get_domain(parse_url($q->toElement->url, PHP_URL_HOST));
                }
            });
        }
        $notifyData = $this->getbridgeData();
        $bridgeNotification = $notifyData['bridgeList']->whereHas('followUser',function($q){
            $q->where('follower_id',Auth::user()->id);
        })->where('created_by','!=',Auth::user()->id)->where('privacy',0)->get();
        $notesNotification = $notifyData['notes']->whereHas('followUser',function($q){
            $q->where('follower_id',Auth::user()->id);
        })->where('created_by','!=',Auth::user()->id)->where('privacy',0)->get();
        $allNotification = $bridgeNotification->merge($notesNotification);
        if(count($allNotification)>0){
             $allNotification->filter(function ($q){
                if(isset($q->title)){
                    $q->title = $q->title;
                    $q->comefromNote = 1;
                }else{
                    $q->comefromNote = 0;
                    $q->fromUrl =get_domain(parse_url($q->fromElement->url, PHP_URL_HOST));
                    $q->toUrl =get_domain(parse_url($q->toElement->url, PHP_URL_HOST));
                }
            });
        }
        $this->response['bridge'] = $allData;
        $this->response['notification'] = $allNotification;
        return view('admin.user.dashboard')->with($this->response);
    }
    public function followUser(Request $request){
        $user_id = Auth::user()->id;
        $data  = $request->only(['user_id']);
        $valid = Validator::make($data, [
            'user_id'     => 'required|exists:users,id'
        ]);
        if ($valid->fails()) {
            return $this->apiErr(22001, $valid->messages(), 422);
        }
        $id = $request->get('user_id');
        $check_user= $this->followUserModel->where('user_id',$id)->where('follower_id',$user_id)->first();
        if (!$check_user) {
            $this->followUserModel->follower_id =$user_id; //login user is follower
            $this->followUserModel->user_id = $id;
            if($this->followUserModel->save()){
               return $check_user;
            }
            return false;
        }else{
             $deletefollowedUser = $this->followUserModel->where('follower_id',$user_id)->where('user_id',$id)->delete();
            if ($deletefollowedUser) {
                return $check_user;
            }
            return false;
        }
    }
    public function getbridgeData(){
        $getData['bridgeList'] = $this->bridgemodel->with(['fromElement','toElement','relationData','user'])->orderBy('created_at','desc');
        $getData['notes'] = $this->noteModel->with(['relationData','user'])->orderBy('created_at','desc');
        
        return $getData;
    }
    public function updateuserData(Request $request){
        $user = $this->model->whereId(Auth::user()->id)->first();
        if($user){
            $userdata['name'] = isset($request->name)?$request->name:$user->name;
            if (isset($request->avatar)) {
                if ($request->hasFile('avatar')) {
                    $postImage = Input::file('avatar');
                    $userdata['avatar'] =uploadFile($postImage,'user');
                } else {
                    $userdata['avatar'] = $request->avatar;
                }
            }
            $user->update($userdata);
            return redirect()->back()->with('message', 'IT WORKS!');
        }
        return redirect()->back()->with('message', 'IT WORKS!');

    }
}