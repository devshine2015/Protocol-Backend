<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use App\Note;
use Auth;
use App\Bridge;
class SearchController extends Controller
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
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/search';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Bridge $bridge,Note $noteModel)
    {
        $this->middleware('guest')->except('logout');
        $this->model = $bridge;
        $this->noteModel = $noteModel;
    }
    public function search(){
        
            $bridgeList = $this->model->with(['fromElement','toElement','relationData','user'])->orderBy('created_at','desc');
            $notes = $this->noteModel->with(['relationData','user'])->orderBy('created_at','desc');
            //check for user login
           if(\Auth::check()){
                //check privacy for bridge
                $bridgeList = $bridgeList->where('created_by',Auth::user()->id)->orWhere(function($q){
                    $q->where('privacy',0)->where('created_by','!=',Auth::user()->id);
                })->get();
                //check privacy for notes
                $notesData = $notes->where('created_by',Auth::user()->id)->orWhere(function($q){
                    $q->where('privacy',0)->where('created_by','!=',Auth::user()->id);
                })->get();
            }else{
               $bridgeList = $bridgeList->where('privacy',0)->get();
               $notesData = $notes->where('privacy',0)->get();
            }
            $allData = $bridgeList->merge($notesData);
        if(count($allData)>0){
            $allData->filter(function ($q){
                $q->tags = explode(',',$q->tags);
                if(isset($q->title)){
                    $q->title = $q->title;
                    $q->comefromNote = 1;
                }else{
                    $q->fromUrl =$this->get_domain(parse_url($q->fromElement->url, PHP_URL_HOST));
                    $q->toUrl =$this->get_domain(parse_url($q->toElement->url, PHP_URL_HOST));
                    $q->comefromNote = 0;
                }
            });
        }
        $this->response['bridge'] = $allData;
        $this->response['count'] = $this->response['bridge']->count();
        return view('admin.search')->with($this->response);
    }
    public function searchData(Request $request){
        $search = (!empty($request->get('search')) ? $request->get('search') : '');
        $bridges =  $this->model
        ->where(function($q)use($request){
        if(($request->my_result==1)){
            $q->where('created_by',Auth::user()->id);
        }})->where(function($q)use($search){
            $q->orWhere('tags', 'like', '%'.$search.'%')->orWhere('desc', 'like', '%'.$search.'%')
            ->orWhereHas('fromElement',function($query)use($search){
            $query->where('url', 'like', '%'.$search.'%');
            })->orWhereHas('toElement',function($query)use($search){
                $query->where('url', 'like', '%'.$search.'%');
            });
        })
       
        ->with('relationData','user')->orderBy('created_at','desc');
        //get notesdata
        $notes = $this->noteModel->where(function($q)use($request){
            if(($request->my_result==1)){
                $q->where('created_by',Auth::user()->id);
            }
            })->where(function($q)use($search){
                $q->orWhere('desc', 'like', '%'.$search.'%')
                ->orWhere('tags', 'like', '%'.$search.'%');
            })->with('relationData','user')->orderBy('created_at','desc');
        //check auth
        if(\Auth::check()){
            $bridgeData = $bridges->where(function($q){
                $q->where('created_by',Auth::user()->id)
                ->orwhere('privacy',0);
            })->get();
             $noteData = $notes->where(function($q){
                $q->where('created_by',Auth::user()->id)
                ->orwhere('privacy',0);
            })->get();
        }else{
             $bridgeData    = $bridges->where('privacy',0)->get();
             $noteData      = $notes->where('privacy',0)->get();
        }
        //merge all data
        $allData = $bridgeData->merge($noteData);
        if(count($allData)>0){
             $allData->filter(function ($q){
                $q->tags = explode(',',$q->tags);
                if(isset($q->title)){
                    $q->title = $q->title;
                    $q->comefromNote = 1;
                }else{
                    $q->fromUrl =$this->get_domain(parse_url($q->fromElement->url, PHP_URL_HOST));
                    $q->toUrl =$this->get_domain(parse_url($q->toElement->url, PHP_URL_HOST));
                    $q->comefromNote = 0;
                    $q->title = $q->fromElement->url.' to '.$q->toElement->url;
                }
            });
        }
        $this->response['bridge'] = $allData;
        $this->response['search'] = $search;
        $this->response['count'] = $this->response['bridge']->count();
        return view('admin.search')->with($this->response);
    }
    public function get_domain($url)
        {
          $domain = isset($url['host']) ? $url['host'] : $url;
          if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            $domainUrl = explode('.',$regs['domain']);
            return $domainUrl[0];
          }
          return false;
        }
}
