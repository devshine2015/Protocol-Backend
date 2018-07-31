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
            $notes = $this->noteModel->with(['relationData','user','followUser','target'])->orderBy('created_at','desc');
            //check for user login
           if(\Auth::check()){
                //check privacy for bridge
                $bridgeList = $bridgeList->with(['followUser'=>function($q){
                    $q->where('follower_id',Auth::user()->id);
                }])->where('created_by',Auth::user()->id)->orWhere(function($q){
                    $q->where('privacy',0)->where('created_by','!=',Auth::user()->id);
                })->get();
                //check privacy for notes
                $notesData = $notes->with(['followUser'=>function($q){
                    $q->where('follower_id',Auth::user()->id);
                }])->where('created_by',Auth::user()->id)->orWhere(function($q){
                    $q->where('privacy',0)->where('created_by','!=',Auth::user()->id);
                })->get();
            }else{
               $bridgeList = $bridgeList->where('privacy',0)->get();
               $notesData = $notes->where('privacy',0)->get();
            }
            $allData = $bridgeList->merge($notesData)->sortByDesc('created_at');
        if(count($allData)>0){
            $allData->filter(function ($q){
                $q->tags = explode(',',$q->tags);
                if(isset($q->followUser)){
                    $q->is_follow = 1;
                }else{
                    $q->is_follow=0;
                }
                unset($q->followUser);
                if(isset($q->title)){
                    $q->title = $q->title;
                    $q->comefromNote = 1;
                }else{
                    $q->fromUrl =get_domain(parse_url($q->fromElement->url, PHP_URL_HOST));
                    $q->toUrl =get_domain(parse_url($q->toElement->url, PHP_URL_HOST));
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
        //show all result when all result selected;
         if(isset($request->all_result)){
            unset($request['my_result']);
        };
        $bridges =  $this->model
        ->where(function($q)use($request){
        if(isset($request->my_result)){
            $q->where('created_by',Auth::user()->id);
        }})->where(function($q)use($search){
            $q->orWhere('tags', 'Ilike', '%'.$search.'%')->orWhere('desc', 'like', '%'.$search.'%')
            ->orWhereHas('fromElement',function($query)use($search){
            $query->where('url', 'Ilike', '%'.$search.'%');
            })->orWhereHas('toElement',function($query)use($search){
                $query->where('url', 'Ilike', '%'.$search.'%');
            })->orWhereHas('user',function($query)use($search){
            $query->where('name', 'Ilike', '%'.$search.'%');
            });
        })
       
        ->with('relationData','user')->orderBy('created_at','desc');
        //get notesdata
        $notes = $this->noteModel->where(function($q)use($request){
            if(isset($request->my_result)){
                $q->where('created_by',Auth::user()->id);
            }
            })->where(function($q)use($search){
                $q->orWhere('desc', 'Ilike', '%'.$search.'%')
                ->orWhere('tags', 'Ilike', '%'.$search.'%')
                ->orWhereHas('user',function($query)use($search){
                    $query->where('name', 'Ilike', '%'.$search.'%');
                });
            })->with('relationData','user','target')->orderBy('created_at','desc');
        //check auth
        if(\Auth::check()){
            $bridgeData = $bridges
                ->with(['followUser'=>function($q){
                    $q->where('follower_id',Auth::user()->id);
                }])->where(function($q){
                $q->where('created_by',Auth::user()->id)
                ->orwhere('privacy',0);
            })->get();
             $noteData = $notes->with(['followUser'=>function($q){
                $q->where('follower_id',Auth::user()->id);
            }])->where(function($q){
                $q->where('created_by',Auth::user()->id)
                ->orwhere('privacy',0);
            })->get();
        }else{
             $bridgeData    = $bridges->where('privacy',0)->get();
             $noteData      = $notes->where('privacy',0)->get();
        }
        //merge all data
        $allData = $bridgeData->merge($noteData)->sortByDesc('created_at');
        if(count($allData)>0){
             $allData->filter(function ($q){
                $q->tags = explode(',',$q->tags);
                if(isset($q->followUser)){
                    $q->is_follow = 1;
                }else{
                    $q->is_follow=0;
                }
                unset($q->followUser);
                if(isset($q->title)){
                    $q->title = $q->title;
                    $q->comefromNote = 1;
                }else{
                    $q->fromUrl =get_domain(parse_url($q->fromElement->url, PHP_URL_HOST));
                    $q->toUrl =get_domain(parse_url($q->toElement->url, PHP_URL_HOST));
                    $q->comefromNote = 0;
                    $q->title = $q->fromElement->url.' to '.$q->toElement->url;
                }
            });
        }
        $this->response['count'] = $allData->count();
        if(isset($request->page_based)){
            $allData =customPagination($allData);
            $allData->withPath(url('searchData'));
        }
        $this->response['bridge'] = $allData;

        $this->response['search'] = $search;
        $this->response['my_result'] = isset($request->my_result)?1:0;
        $this->response['all_result'] = isset($request->all_result)?1:0;
        $this->response['page_based'] = isset($request->page_based)?1:0;
        // print_r($this->response);exit;
        return view('admin.search')->with($this->response);
    }
}
