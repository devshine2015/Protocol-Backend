<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use Redirect;
use App\Relation;
class RelationController extends Controller
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
    public function __construct(Relation $relation)
    {
        $this->middleware('guest')->except('logout');
        $this->model                = $relation;
    }
     public function edit($relation_id)
    {
        $relation = $this->model->with('user')->find(decrypt($relation_id));
        if($relation){
            return view('admin.relation.edit',compact('relation'));
        }
        return error('failed!');
        
    }
    public function update(Request $request, $relation_id)
    {
        $relation = $this->model->find(decrypt($relation_id));
        if($relation){
            $request['is_approved'] = ($relation->is_approved != '1') ? : 0;
            $relation->fill($request->all());
            if($relation->save()){
               return Redirect::back()->withSuccess(['msg', 'success']);
            }
        }
        return Redirect::back()->withError(['msg', 'error']);
    }
    public function destroy($relation_id)
    {
        $relation = $this->model->find(decrypt($relation_id));
        if($relation){
            $relation->delete();
            return $this->apiOk(true);
        }
        return $this->apiErr('failed');
    }
    public function anyData(){
        $getData = $this->model->with('user')->orderBy('is_approved','asc')->orderBy('created_at','desc');;
        // print_r($getData->get()->toArray());exit;
        return \DataTables::of($getData->get())->addColumn('editAction', function ($relation) {

            return '<a data-toggle="modal" data-target="#modal-right" href="' . route('relations.edit', ['id' => encrypt($relation->id)]) . '" class="btn edit_name mr-2"><i data-success-callback="relationsEditSuccess" data-error-callback="relationsDeleteError" class="fa fa-edit"></i></a>&nbsp;&nbsp&nbsp;&nbsp;';
        })->addColumn('created_by', function ($relation) {
            return (isset($relation->user->name))?$relation->user->name:'';
        })->addColumn('is_approved', function ($relation) {
           ($relation->is_approved==1)?$state = 'checked' : $state = '';
            return '<input type = "checkbox" class="my-checkbox" name="status" data-toggle="toggle" id= "checkBox-'.$relation->id.'" onchange="callCheck('.$relation->id.');" data-objectId = "'.$relation->id.'" data-on="Approved" data-off = "Not Approved" '.$state.'  value="'.$relation->id.'" data-onstyle="success" data-offstyle="danger" data-size="small" data-style="ios">';
        })
        ->rawColumns(['editAction','is_approved'])->make(true);
    }
    public function changeStatus($relation_id){
        if($relation = $this->model->whereId($relation_id)->first()){
            $relation->is_approved = ($relation->is_approved != '1') ? : 0;
            $relation->save();
            if ($relation->status==0) {
                return json_encode(['status' => '1','message' => 'relation approved by admin']);
            }else{
                return json_encode(['status' => '1','message' => 'relation rejected by admin']);
            }
        }
        return json_encode(['status' => '0','message' => "something is wrong"]);
    }
}