<?php

namespace App\Http\Controllers\API;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Message;

class UserController extends Controller
{
	protected $messageModel;
    public function __construct(Message $messageModel)
    {
        $this->messageModel                        = $messageModel;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginMessage(Request $request){
    	$getMessage = $this->messageModel->where('message_categories_id',6)->orderBy('created_at','desc')->first();
    	return [
       		'error_code'    => 0,
       		'data'		=> $getMessage
    	];
    }
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
    public function logout(Request $request){
        $updateLogin = \App\User::where('email',$request->email)->update(["isloggedOut"=>0]);
        return $this->apiOk(true);
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
            $updateLogin = \App\User::where('email',Auth::user()->email)->update(["isloggedOut"=>1]);
            return json_encode($data);
        }
        $updateLogin = \App\User::where('email',$request->email)->update(["isloggedOut"=>1]);
        $loginResp = $this->__login($data['email'], $data['password'], $request);
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
