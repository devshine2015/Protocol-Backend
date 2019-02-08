<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\CategoryType;
use App\Category;
// use App\Http\Requests\CategoryTypes;

class SubCategoryController extends Controller
{
    protected $model;
    protected $category;
    private $fieldsRequired = [
        'name',
        'category_id'
    ];
    public function __construct(CategoryType $subCategory, Category $category)
    {
        $this->model                        = $subCategory;
        $this->category                        = $category;
    }
    /**
     * Store subcategory detail
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user   = Auth::guard('api')->user();
        $subCategory = new \App\CategoryType;
        foreach ($this->fieldsRequired as $f) {
            $subCategory->$f = $request->$f;
        }
        $checkCategory = $this->category->whereId($request->category_id)->first();
        if (!$checkCategory) {
            return $this->apiErr(404, 'Category does not exist');
        }
        $checkName = $this->model->where('name','ilike',$request->name)->where('created_by',$user->id)->first();
        if ($checkName) {
            return $this->apiErr(404, 'This subcategory is already exist.');
        }
        if ($user->admin == 1) {
            $subCategory->is_approved = 1;
        }
        $subCategory->created_by = $request->user()['id'];
        $subCategory->save();
        return $this->apiOk($subCategory);
        
    }
}
