<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Socialite;

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

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback(Request $request, $provider)
    {
        $user   = Socialite::driver($provider)->stateless()->user();
        $client = \App\OAuthClient::where('password_client', 1)->first();
        $proxy  = Request::create(
            '/oauth/token',
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

        return view('oauth_result', [ 'result' => $content ]);
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
