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
}
