<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Interfaces\ShareInterface;
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
    protected $shareRepo;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Bridge $bridgeModel,Note $noteModel,Element $elementModel,Message $messageModel ,ShareInterface $shareRepository)
    {
        $this->middleware('guest')->except('logout');
        $this->bridgeModel  = $bridgeModel;
        $this->noteModel    = $noteModel;
        $this->elementModel = $elementModel;
        $this->messageModel = $messageModel;
        $this->shareRepo    = $shareRepository;
    }
    public function shareBridge($bridge_id){
        $shareData = $this->shareRepo->shareBridge($bridge_id);
        if($shareData){
            return view('admin.share.show',compact('shareData'));
        }
        return $this->apiErr(222003, 'Not Authorized');
    }
    public function shareNote($note_id){
        $shareData = $this->shareRepo->shareNote($note_id);
        if($shareData){
            return view('admin.share.show',compact('shareData'));
        }
        return $this->apiErr(222003, 'Not Authorized');
    }
    public function shareElement($element_id){
        $shareData = $this->shareRepo->shareNote($element_id);
        if($shareData){
            return view('admin.share.show',compact('shareData'));
        }
        return $this->apiErr(222003, 'Not Authorized');
    }
}