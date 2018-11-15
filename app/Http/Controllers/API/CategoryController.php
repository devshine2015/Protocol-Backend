<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Category;

class CategoryController extends Controller
{
    protected $model;
    public function __construct(Category $category)
    {
        $this->model                        = $category;
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
        }])->where('status',1)->orderBy('name','asc')->get();
        if($category){
            return $this->apiOk($category);
        }
        return $this->apiErr(22010, 'something is wrong in user follow');
    }
}
