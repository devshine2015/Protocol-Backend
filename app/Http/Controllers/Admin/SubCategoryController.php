<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use Redirect;
use App\CategoryType;
class SubCategoryController extends Controller
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
    public function __construct(CategoryType $category)
    {
        $this->middleware('guest')->except('logout');
        $this->model                = $category;
    }
     /**
     * edit model for update subcategory
     * @param  int  $relation_id
     * @return open model of relation update
     */
     public function edit($category_id)
    {
        $category = $this->model->with('category','user')->find(decrypt($category_id));
        if($category){
            return view('admin.subcategory.edit',compact('category'));
        }
        return error('failed!');
        
    }
    /**
     * Update subcategory data by admin which are created by the extension user
     * @param  Request $request
     * @param  int  $category_id
     * @return  redirect back to the subcategory list page
     */
    public function update(Request $request, $category_id)
    {
        // print_r($request->all());exit;
        $category = $this->model->find(decrypt($category_id));
        if($category){
            $request['is_approved'] = ($category->is_approved != '1') ? : 0;
            $category->fill($request->all());
            if($category->save()){
               return Redirect::back()->withSuccess(['msg', 'success']);
            }
        }
        return Redirect::back()->withError(['msg', 'error']);
    }
    /**
     * Admin can delete any subcategory
     * @param  int $category_id
     * @return boolean    return true or false
     */
    public function destroy($category_id)
    {
        $category = $this->model->find(decrypt($category_id));
        if($category){
            $category->delete();
            return $this->apiOk(true);
        }
        return $this->apiErr('failed');
    }
    /**
     * list of subcategory data to show in data table
     * @return list of subcategory which are added by the user
     */
    public function anyData(){
        $getData = $this->model->with('category','user')->orderBy('is_approved','asc')->orderBy('created_at','desc');
        return \DataTables::of($getData->get())->addColumn('editAction', function ($subCategory) {
            return '<a data-toggle="modal" data-target="#modal-right" href="' . route('subCategories.edit', ['id' => encrypt($subCategory->id)]) . '" class="btn edit_name mr-2"><i data-success-callback="subCategoriesEditSuccess" data-error-callback="subCategoriesDeleteError" class="fa fa-edit"></i></a>&nbsp;&nbsp&nbsp;&nbsp;';
        })->addColumn('created_by', function ($subCategory) {
            return (isset($subCategory->user->name))?$subCategory->user->name:'';
        })->addColumn('category_id', function ($subCategory) {
            return (isset($subCategory->category->name))?$subCategory->category->name:'';
        })
         ->addColumn('is_approved', function ($subCategory) {
           ($subCategory->is_approved==1)?$state = 'checked' : $state = '';
            // return '<input type = "checkbox" class="my-checkbox" name="status" id= "checkBox-'.$subCategory->id.'" onchange="callCheck('.$subCategory->id.');" data-objectId = "'.$subCategory->id.'" data-on-text="Approved" data-off-text = "Not Approved" '.$state.'  value="'.$subCategory->id.'">';
            return '<input type = "checkbox" class="my-checkbox" name="status" data-toggle="toggle" id= "checkBox-'.$subCategory->id.'" onchange="callCheckCategory('.$subCategory->id.');" data-objectId = "'.$subCategory->id.'" data-on="Approved" data-off = "Not Approved" '.$state.'  value="'.$subCategory->id.'" data-onstyle="success" data-offstyle="danger" data-size="small" data-style="ios">';
        })
        ->rawColumns(['editAction','is_approved'])->make(true);
    }
    /**
     * admin can apporve or reject subcategory which are added by the user
     * @param  int $category_id
     * @return Redirect back with status change
     */
    public function changeStatus($category_id){
        if($subCategory = $this->model->whereId($category_id)->first()){
            $subCategory->is_approved = ($subCategory->is_approved != '1') ? : 0;
            $subCategory->save();
            if ($subCategory->status==0) {
                return json_encode(['status' => '1','message' => 'Category approved by admin']);
            }else{
                return json_encode(['status' => '1','message' => 'Category rejected by admin']);
            }            
        }
        return json_encode(['status' => '0','message' => "something is wrong"]);
    }
}