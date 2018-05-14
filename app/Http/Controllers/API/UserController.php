<?php

namespace App\Http\Controllers\API;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    public function login(Request $request)
    {
        $data  = $request->only(['email', 'password']);
        $valid = Validator::make($data, [
            'email'     => 'required|string|email|max:255',
            'password'  => 'required|string|min:6'
        ]);

        if ($valid->fails()) {
            return $this->apiErr(22002, $valid->messages(), 422);
        }

        return $this->__login($data['email'], $data['password'], $request);
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
