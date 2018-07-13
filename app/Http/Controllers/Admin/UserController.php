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
    public function __construct(User $user, Bridge $bridge,Note $note)
    {
        $this->middleware('guest')->except('logout');
        $this->model = $user;
        $this->bridgemodel = $bridge;
        $this->noteModel = $note;
    }
    public function userData($id){
       $allData = $this->getbridgeData();
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
        $this->response['bridge'] = $allData;
        $this->response['userId'] = $id;
        return view('admin.user.edit_profile')->with($this->response);
    }
     public function dashboard(){
        $allData = $this->getbridgeData();
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
        $this->response['bridge'] = $allData;
        return view('admin.user.dashboard')->with($this->response);
    }
    public function getbridgeData(){
        $bridgeList = $this->bridgemodel->with(['fromElement','toElement','relationData','user'])->orderBy('created_at','desc')->get();
        $notes = $this->noteModel->with(['relationData','user'])->orderBy('created_at','desc')->get();
        $allData = $bridgeList->merge($notes);
        return $allData;
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