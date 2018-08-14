<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use Redirect;
use App\Bridge;
use App\Note;
use App\Element;
use App\Message;
class ShareController extends Controller
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
    protected $bridgeModel;
    protected $noteModel;
    protected $elementModel;
    protected $messageModel;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Bridge $bridgeModel,Note $noteModel,Element $elementModel,Message $messageModel)
    {
        $this->middleware('guest')->except('logout');
        $this->bridgeModel = $bridgeModel;
        $this->noteModel = $noteModel;
        $this->elementModel = $elementModel;
        $this->messageModel = $messageModel;
    }
    public function shareBridge($id){
        $shareData = $this->bridgeModel->with('fromElement','toElement')->find($id);
        if($shareData){
            $shareData->type =0;
            $shareAdminMessage = $this->getMessage(1);
            if($shareAdminMessage){
                $shareData->adminMessage = $shareAdminMessage->message;
            }
            return view('admin.share.show',compact('shareData'));
        }
        return $this->apiErr(222003, 'Not Authorized');
    }
    public function shareNote($id){
        $shareData = $this->noteModel->with('targetData','relationData')->find($id);
        if($shareData){
            $shareData->type =1;
            $shareAdminMessage = $this->getMessage(2);
            if($shareAdminMessage){
                $shareData->adminMessage = $shareAdminMessage->message;
            }
            return view('admin.share.show',compact('shareData'));
        }
        return $this->apiErr(222003, 'Not Authorized');
    }
    public function shareElement($id){
        $shareData = $this->elementModel->find($id);
        // echo "<pre>";print_r($shareData);exit;
        if($shareData){
            $shareData->type =2;
            $shareAdminMessage = $this->getMessage(3);
            if($shareAdminMessage){
                $shareData->adminMessage = $shareAdminMessage->message;
            }
            return view('admin.share.show',compact('shareData'));
        }
        return $this->apiErr(222003, 'Not Authorized');
    }
    private function getMessage($type){
        if($messageData = $this->messageModel->where('message_categories_id',$type)->orderBy('created_at','desc')->first()){
            return $messageData;
        }
        return false;
    }
}