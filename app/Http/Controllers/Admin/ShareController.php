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
use App\SocialTrack;
use Carbon\Carbon;
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
    /**
     * share bridge by the user
     * @param  int $bridge_id
     * @return  redirect in share bridge page with bridge info
     */
    public function shareBridge($bridge_id){
        $shareData = $this->shareRepo->shareBridge($bridge_id);
        if($shareData){
            return view('admin.share.show',compact('shareData'));
        }
        return $this->apiErr(222003, 'Not Authorized');
    }
     /**
     * share note by the user
     * @param  int $note_id
     * @return  redirect in share note page with note info
     */
    public function shareNote($note_id){
        $shareData = $this->shareRepo->shareNote($note_id);
        if($shareData){
            return view('admin.share.show',compact('shareData'));
        }
        return $this->apiErr(222003, 'Not Authorized');
    }
     /**
     * share element by the user
     * @param  int $element_id
     * @return  redirect in share element page with element info
     */
    public function shareElement($element_id){
        $shareData = $this->shareRepo->shareElement($element_id);
        if($shareData){
            return view('admin.share.show',compact('shareData'));
        }
        return $this->apiErr(222003, 'Not Authorized');
    }
    /**
     * share list by the user
     * @param  int $list_id
     * @return  redirect in share list page with list info
     */
    public function shareList($list_id){
         $shareData = $this->shareRepo->shareList($list_id);
        if($shareData){
            return view('admin.share.show',compact('shareData'));
        }
        return $this->apiErr(222003, 'Not Authorized');
    }
    /**
     * List of the shared data
     * @return with share data
     */
    public function anyData(){
        $getData = SocialTrack::with('user')->where('social_type',4)->where('shared_with',Auth::user()->id)
        ->with('bridge','note','element')
        ->orderBy('created_at','desc');
        return \DataTables::of($getData->get())->addColumn('name', function ($shareData) {
            return '<span class="badge">'.ucfirst($shareData->user->name[0]).'</span>  <span class="inboxFont">'. ucfirst($shareData->user->name).' sent</span><span class="inboxFont" style="float:right;">'. $shareData->created_at->diffForHumans().'</span> <br><span class="ml-4">'.ucfirst($shareData->shared_message).'</span><br><span class="ml-4 inboxFont">'.'sdasdasdasdasd</span>';
        })
        ->rawColumns(['name'])->make(true);
    }
}