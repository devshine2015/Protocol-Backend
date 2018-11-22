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
use App\NotificationStatus;
use App\UserPoint;
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
    protected $notification;
    protected $bridgemodel;
    protected $userPoint;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user, Bridge $bridge,Note $note,FollowUser $followUser,NotificationStatus $notification_status,UserPoint $userPoint)
    {
        $this->middleware('guest')->except('logout');
        $this->model                = $user;
        $this->bridgemodel          = $bridge;
        $this->noteModel            = $note;
        $this->followUserModel      = $followUser;
        $this->notification         = $notification_status;
        $this->userPoint            = $userPoint;
    }
    public function checkLogin(Request $request){
        if(auth::check()){
            $user = Auth::guard('api')->user();
            $checkUser = Auth::user();
            if($checkUser){
                Auth::guard('web')->login(User::whereEmail($user->email)->first());
                return json_encode($user);
            }
        }
    }
    public function userData($name,$id){
        $getallData = $this->getbridgeData($id);
        if(Auth::user()->id != $id){
            $bridgeData = $getallData['bridgeList']->where('created_by',$id)->where('privacy',0)->get();
            $noteData = $getallData['notes']->where('created_by',$id)->where('privacy',0)->get();
        }else{
            $bridgeData = $getallData['bridgeList']->where('created_by',$id)->get();
            $noteData = $getallData['notes']->where('created_by',$id)->where('privacy',1)->get();
        }
        $allData = $bridgeData->merge($noteData)->sortByDesc('created_at');
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
        $readData = 0;
        $getallData = $this->getbridgeData();
        $getPoint = $this->userPoint->where('user_id',$id)->pluck('point');
        $bridgeData = $getallData['bridgeList']->where('created_by',$id)->get();
        $noteData = $getallData['notes']->where('created_by',$id)->get();
        $allData = $bridgeData->merge($noteData)->sortByDesc('created_at');
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
        $bridgeNotification = $notifyData['bridgeList']->with(['followFromElement'=> function($query){
                $query->where('user_id',Auth::user()->id);
            }])->with(['followtoElement'=> function($query){
                $query->where('user_id',Auth::user()->id);
            }])->where('created_by','!=',Auth::user()->id)->where('privacy',0)->where(function($q){
            $q->orWhereHas('followUser',function($query){
                $query->where('follower_id',Auth::user()->id);
            })
            ->orWhereHas('followFromElement',function($query){
                    $query->where('user_id',Auth::user()->id);
            })->with('followFromElement')->orWhereHas('followtoElement',function($query){
                    $query->where('user_id',Auth::user()->id);
            })->with('followtoElement');
        })->get();
        // echo "<pre>";print_r( $notifyData['notes']->with('follownoteElement')->get()->toArray());exit;
        $notesNotification = $notifyData['notes']->with(['follownoteElement'=> function($query){
                $query->where('user_id',Auth::user()->id);
            }])->where('created_by','!=',Auth::user()->id)->where('privacy',0)->where(function($q){
            $q->orWhereHas('followUser',function($query){
                $query->where('follower_id',Auth::user()->id);
            })
            ->orWhereHas('follownoteElement',function($query){
                $query->where('user_id',Auth::user()->id);
            })->with('follownoteElement');
        })->get();
        $getReadNotify  = $this->notification->where('user_id',$id)->pluck('type','type_id');
        $allNotification = $bridgeNotification->merge($notesNotification)->sortByDesc('created_at');
        $readData = 0;
        if(count($allNotification)>0){
<<<<<<< HEAD

=======
>>>>>>> e9de5391d68ed23b444f4270d0de121b8bed153a
             $allNotification->filter(function ($q)use($getReadNotify,&$readData){
                $notifyType = 2;
                $q->is_read = 0;
                if(array_key_exists($q->id,$getReadNotify->toArray())){
                    $notifyType = $getReadNotify[$q->id];
                    $readData  = $readData+1;
                }
                if(isset($q->followUser)){
                    $q->is_follow = 1;
                }else{
                    $q->is_follow=0;
                }
                if(isset($q->title)){
                    $q->title = $q->title;
                    $q->comefromNote = 0;
                    $q->comefrombridge =  3;
                     if($notifyType==1){
                        $q->is_read =1;
                     }
                }else{
                    if($notifyType==0){
                        $q->is_read =1;
                    }
                    $q->comefrombridge = 0;
                     $q->comefromNote = 3;
                    $q->fromUrl =get_domain(parse_url($q->fromElement->url, PHP_URL_HOST));
                    $q->toUrl =get_domain(parse_url($q->toElement->url, PHP_URL_HOST));
                }
                if(isset($q->followFromElement) && count($q->followFromElement)>0 || isset($q->followtoElement) && count($q->followtoElement)>0){
                    $q->comefrombridge = 2;
                     $q->comefromNote = 3;
                }
                if(isset($q->follownoteElement) && count($q->follownoteElement)>0){
                    $q->comefromNote = 2;
                     $q->comefrombridge =  3;
                }
            });
        }
        $this->response['userPoint'] = $getPoint->sum();
        $this->response['bridge'] = $allData;
        $this->response['notification'] = $allNotification;
        $this->response['notification_count'] = $allNotification->count() - $readData;
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
               return $this->followUserModel;
            }
            return false;
        }else{
             $deletefollowedUser = $this->followUserModel->where('follower_id',$user_id)->where('user_id',$id)->delete();
            if ($deletefollowedUser) {
                return $this->followUserModel;
            }
            return false;
        }
    }
    public function updateNotification(Request $request){
        $user_id = Auth::user()->id;
        $userNotify = $this->notification->where('type_id',$request->get('type_id'))->where('user_id',$user_id)->where('type',$request->get('type'))->first();
        if(!isset($userNotify)){
            $userNotify = new NotificationStatus;
            $userNotify->user_id         = $user_id;
            $userNotify->type_id         = $request->get('type_id');
            $userNotify->type            = $request->get('type');
            $userNotify->is_read         = 1;
            $userNotify->save();
            return '1';
        }else{
            return '0';
        }
    }
    public function getbridgeData(){
        $getData['bridgeList'] = $this->bridgemodel->where('tags', 'NOT LIKE', '%test%')->with(['followUser'=>function($q){
                    $q->where('follower_id',Auth::user()->id);
                }])->with(['fromElement','toElement','relationData','user'])->orderBy('created_at','desc');
        $getData['notes'] = $this->noteModel->where('tags', 'NOT LIKE', '%test%')->with(['followUser'=>function($q){
                    $q->where('follower_id',Auth::user()->id);
                }])->with(['relationData','user','targetData'])->orderBy('created_at','desc');
        
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