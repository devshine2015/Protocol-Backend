<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use Redirect;
use App\Element;
class ElementController extends Controller
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
    public function __construct(Element $element)
    {
        $this->middleware('guest')->except('logout');
        $this->model                = $element;
    }
    /**
     * destroy Delete element
     * @param  integer $element_id find by element id
     * @return boolean             retun true or false
     */
    public function destroy($element_id)
    {
        $element = $this->model->find(decrypt($element_id));
        if($element){
            $element->delete();
            return $this->apiOk(true);
        }
        return $this->apiErr('failed');
    }
}