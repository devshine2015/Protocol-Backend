<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use Redirect;
use App\Message;
class AdminController extends Controller
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
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->middleware('guest')->except('logout');
        $this->model                = $message;
    }
    public function index(){
        if(Auth::user()->admin ==1){
            return view('admin.messages.index');
        }
        return redirect('search');
    }
    public function create()
    {
        return view('admin.messages.create');
    }
     public function store(Request $request)
    {
        
        if($request->language_type ==2){
            $request['chinese_message'] = $request->message;
            $request['message'] = '';
        }
        // print_r($request->all());exit;
        $request['message_type'] = !empty($request->get('message_type'))?$request->get('message_type'):0;
        $this->model->fill($request->all());
        if($this->model->save()){
            return Redirect::back()->withSuccess(['msg', 'success']);
        }
        return Redirect::back()->withError(['msg', 'error']);
    }
    public function edit($message_id)
    {
        $message = $this->model->with('messageCategory','messageCriteria')->find(decrypt($message_id));
        if($message){
            return view('admin.messages.edit',compact('message'));
        }
        return error('failed!');
        
    }
    public function update(Request $request, $message_id)
    {
        $message = $this->model->find(decrypt($message_id));
        if($message){
            if($request->language_type == 2){
                $request['chinese_message'] = $request->message;
                $request['message'] = '';
            }
            $request['message_type'] = !empty($request->get('message_type'))?$request->get('message_type'):$message->message_type;
            $message->fill($request->all());
            if($message->save()){
               return Redirect::back()->withSuccess(['msg', 'success']);
            }
        }
        return Redirect::back()->withError(['msg', 'error']);
    }
    public function destroy($message_id)
    {
        $message = $this->model->find(decrypt($message_id));
        if($message){
            $message->delete();
            return $this->apiOk(true);
        }
        return $this->apiErr('failed');
    }
    public function checkDate(Request $request){
        // print_r($request->all());exit;
        $getSelected = $request->get('start_date');
        $getDate = $this->model->where('message_categories_id',$request->get('message_category_id'))->pluck('start_date','end_date');
        $dates = [];
        $getDate->filter(function($key,$value)use(&$dates){
            $begin = new \DateTime($key);
            $end = new \DateTime($value);

            $daterange = new \DatePeriod($begin, new \DateInterval('P1D'), $end);
            foreach($daterange as $date){
                array_push($dates,$date->format('Y-m-d'));
            }
        });
        if($dates){
            return array_unique($dates);
        }
        return $getDate;
    }
    public function anyData(){
        $getData = Message::with('messageCategory','messageCriteria')->orderBy('created_at','desc');

        return \DataTables::of($getData->get())->addColumn('editAction', function ($message) {
            return '<a data-toggle="modal" data-target="#modal-right" href="' . route('messages.edit', ['id' => encrypt($message->id)]) . '" class="btn edit_name mr-2"><i data-success-callback="messageEditSuccess" data-error-callback="messageDeleteError" class="fa fa-edit"></i></a>&nbsp;&nbsp&nbsp;&nbsp;<a href="' . route('messages.destroy', ['id' => encrypt($message->id)]) .'"  data-success-callback="messageDeleteSuccess" name= "message" data-error-callback="messagesSaveError" class="confirm-delete edit_name" ><i class="fa fa-trash"></i></a>';
        })->addColumn('message_categories_id', function ($message) {
            return (isset($message->messageCategory->title))?$message->messageCategory->title:'';
        })->addColumn('message_criteria_id', function ($message) {
            return (isset($message->messageCriteria->criteria))?$message->messageCriteria->criteria:'';
        })
        ->addColumn('message_type', function ($message) {
            if ($message->message_type==1) {
               return 'Alert';
            }if ($message->message_type==2) {
               return 'Achievement';
            }
            return '';
        })
        ->addColumn('language_type', function ($message) {
            if ($message->language_type==1) {
               return 'English';
            }if ($message->language_type==2) {
               return 'Chinese';
            }
            return '';
        }) 
        ->rawColumns(['editAction'])->make(true);
    }
}