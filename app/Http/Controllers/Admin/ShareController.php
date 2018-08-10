<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use Redirect;
use App\UserSocialShare;
class ShareController extends Controller
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
    public function __construct(UserSocialShare $socialShare)
    {
        $this->middleware('guest')->except('logout');
        $this->model                = $socialShare;
    }
    public function show($id){
        return view('admin.share.show');
    }
}