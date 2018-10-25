<?php

namespace App\Http\Controllers\API;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $data  = $request->only(['email', 'password', 'name']);
        $valid = Validator::make($data, [
            'email'     => 'required|string|email|max:255|unique:users',
            'name'      => 'required|string|max:255',
            'password'  => 'required|string|min:6'
        ]);

        if ($valid->fails()) {
            return $this->apiErr(22001, $valid->messages(), 422);
        }

        $userData   = [
            'email'     => $data['email'],
            'name'      => $data['name'],
            'password'  => bcrypt($data['password'])
        ];
        $user       = \App\User::create($userData);

        return $this->__login($data['email'], $data['password'], $request);
    }

    public function login(Request $request, \Illuminate\Contracts\Encryption\Encrypter $cookieEncrypt)
    {
        $data  = $request->only(['email', 'password']);
        if(!$request->header('Authorization')){
            $valid = Validator::make($data, [
                'email'     => 'required|string|email|max:255',
                'password'  => 'required|string|min:6'
            ]);

            if ($valid->fails()) {
                return $this->apiErr(22002, $valid->messages(), 422);
            }
        }else{
            //generate for refresh token
            $data['token_type'] =  "Bearer";
            $data['expires_in'] =  31536000;
            $data['access_token'] =  Auth::user()->createToken('MyApp')->accessToken;
            $data['refresh_token'] =  Auth::user()->createToken('MyApp')->accessToken;
            return json_encode($data);
        }
        $loginResp = $this->__login($data['email'], $data['password'], $request);
        // if($loginResp->getStatusCode() === 200){
        //     $userObj = \App\User::whereEmail($data['email'])->first();
        //     Auth::login($userObj);
        //     $resp = json_decode($loginResp->getContent());
        //     $resp->cookies = \Crypt::encrypt($cookieEncrypt->decrypt($_COOKIE['bridgit_session']));
        //     // $resp->_cookie_bridgit_session = $_COOKIE['bridgit_session'];
        //     // $resp->encrypted = rawurlencode($cookieEncrypt->encrypt('JOjPcce7ZkKCBeDpYeBLAVLG8OhxNj0gmqtc5p3t'));
        //     $loginResp->setContent(json_encode($resp));
        // }
        return $loginResp;
    }

    private function __login($email, $password, $request) {
        $client = \App\OAuthClient::where('password_client', 1)->first();
        $request->request->add([
            'grant_type'    => 'password',
            'client_id'     => $client->id,
            'client_secret' => $client->secret,
            'username'      => $email,
            'password'      => $password,
            'scope'         => null
        ]);

        $proxy = Request::create(
            'oauth/token',
            'POST'
        );
        return \Route::dispatch($proxy);
    }
}
