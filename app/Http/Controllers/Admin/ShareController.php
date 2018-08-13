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
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Bridge $bridgeModel,Note $noteModel,Element $elementModel)
    {
        $this->middleware('guest')->except('logout');
        $this->bridgeModel = $bridgeModel;
        $this->noteModel = $noteModel;
        $this->elementModel = $elementModel;
    }
    public function shareBridge($id){
        $shareData = $this->bridgeModel->with('fromElement','toElement')->find($id);
        if($shareData){
            $shareData->type =0;
            return view('admin.share.show',compact('shareData'));
        }
        return $this->apiErr(222003, 'Not Authorized');
    }
    public function shareNote($id){
        $shareData = $this->noteModel->with('targetData','relationData')->find($id);
        if($shareData){
            $shareData->type =1;
            return view('admin.share.show',compact('shareData'));
        }
        return $this->apiErr(222003, 'Not Authorized');
    }
    public function shareElement($id){
        $shareData = $this->elementModel->find($id);
        // echo "<pre>";print_r($shareData);exit;
        if($shareData){
            $shareData->type =2;
            return view('admin.share.show',compact('shareData'));
        }
        return $this->apiErr(222003, 'Not Authorized');
    }
}