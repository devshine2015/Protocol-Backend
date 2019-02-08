<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Category;
use App\Note;
use App\Bridge;


class CategoryController extends Controller
{
    protected $model;
    protected $bridges;
    protected $notes;
    public function __construct(Category $category,Note $notes,Bridge $bridges)
    {
        $this->model                        = $category;
        $this->notes                        = $notes;
        $this->bridges                      = $bridges;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user   = Auth::guard('api')->user();
        $category =  $this->model->with(['subCategory'=>function($q)use($user){
            if($user->admin != 1){
                $q->where('created_by',$user->id);
            };
            $q->where('is_approved',1)->orderBy('name','asc');
        }])->with('bridges','notes')->where('status',1)->orderBy('name','asc')->get();
        $tags = array();
        $category->filter(function($q)use(&$tags){
            $getNoteTag = $q->notes->pluck('tags');
            $getBridgeTag = $q->bridges->pluck('tags');
            $getTag = $getNoteTag->merge($getBridgeTag);
            $tagList = collect($getTag)->filter(function($t)use(&$tags){
                $tagData = explode(',',$t);
                $mergeTag = array_push($tags,$tagData);
            });
            $tags = array_collapse($tags);
            $vals = array_count_values($tags);
            arsort( $vals );
            $q->tags = array_keys($vals);
        });
        if($category){
            return $this->apiOk($category);
        }
        return $this->apiErr(22010, 'something is wrong in user follow');
    }
    /**
     * categoryList List of the category which displays to the bridge,notes,list
     * @return [\Illuminate\Http\Response
     */
    public function categoryList(Request $request)
    {
        $user   = Auth::guard('api')->user();
        //get category data
        $category =  $this->model->with(['subCategory'=>function($q)use($user){
            if($user->admin != 1){
                $q->where('created_by',$user->id);
            };
            $q->where('is_approved',1)->orderBy('name','asc');
        }])->with('bridges','notes')->where('status',1)->orderBy('name','asc')->get();
        $tags = array();
        $category->filter(function($q)use(&$tags){
            $getNoteTag = $q->notes->pluck('tags');
            $getBridgeTag = $q->bridges->pluck('tags');
            $getTag = $getNoteTag->merge($getBridgeTag);
            $tagList = collect($getTag)->filter(function($t)use(&$tags){
                $tagData = explode(',',$t);
                $mergeTag = array_push($tags,$tagData);
            });
            $tags = array_collapse($tags);
            $vals = array_count_values($tags);
            arsort( $vals );
            $q->tags = array_keys($vals);
        });
        //get note category data
        $urlQuery   = $request->query();

        if (isset($urlQuery['ids'])) {
            $ids = is_array($urlQuery['ids']) ? $urlQuery['ids']: [$urlQuery['ids']];
            $noteCategoryQuery = \App\NoteCategory::whereIn('id', $ids);
        } else {
            $noteCategoryQuery = \App\NoteCategory::where(function($query) {
                $query->where([ 'status' => 0, 'type' => 1]);
            });
            if (isset($user)) {
                $noteCategoryQuery = $noteCategoryQuery->orWhere(function($query)  use($user) {
                    $query->where([ 'status' => 0, 'type' => 0,'created_by' => $user['id'] ]);
                });
            }
        }
        if(isset($urlQuery['locale']) && $urlQuery['locale'] == 'zh'){
            $note_categories = $noteCategoryQuery->get(['note_categories.chinese_name AS name', 'id','is_active','created_at','updated_at','created_by','status','privacy','type','sort_key','chinese_name']);
        }else{

            $note_categories = $noteCategoryQuery->get();
        }
        if($note_categories || $category){
            return $this->apiOk(['notecategory' => $note_categories,'category' => $category]);
        }
        return $this->apiErr(22010, 'something is wrong in user follow');
    }
}
