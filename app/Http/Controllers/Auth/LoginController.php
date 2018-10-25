<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Socialite;
use App\User;
use Auth;
use DB;
class LoginController extends Controller
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
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback(Request $request, $provider)
    {

        $user   = Socialite::driver($provider)->stateless()->user();
        $client = \App\OAuthClient::where('password_client', 1)->first();
        //check routr for extension or web
        $path = '';
        if($request->query('platform') == 'web'){
            $path = env('APP_URL');
        }
        $proxy  = Request::create(
            $path . '/oauth/token',
            'POST',
            [
                'grant_type'    => 'social',
                'client_id'     => $client->id,
                'client_secret' => $client->secret,
                'network'       => $provider,
                'access_token'  => $user->token,
            ]
        );
        $response = \App::handle($proxy);
        $content  = $response->getContent();
        if($request->query('platform') == 'web'){
            auth()->login(User::whereEmail($user->email)->first());
            return redirect('search');
        }
        return view('oauth_result', [ 'result' => $content, 'providerName' => ucfirst($provider) ]);
    }
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
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function logout()
        {
            auth()->logout();
            return redirect("/search");
        }
        public function logoutWeb(){
            auth()->logout();
            $data['deleted'] =  true;
            return json_encode($data);
            
        }
}
