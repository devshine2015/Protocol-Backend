<?php

namespace App\Http\Controllers\API;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\SendEmailInterface;
use App\Interfaces\ShareInterface;
use Auth;
use App\Message;
use App\User;

class UserController extends Controller
{
	protected $messageModel;
    protected $user;
    protected $emailRepo;
    protected $shareRepo;

    public function __construct(Message $messageModel,User $user, SendEmailInterface $emailRepository,ShareInterface $shareRepository)
    {
        $this->messageModel                        = $messageModel;
        $this->model                               = $user;
        $this->emailRepo                           = $emailRepository;
        $this->shareRepo                           = $shareRepository;
    }
    /**
     * Show login message to share share section and web message model
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
    /**
     * Register new user using laravel oAuth
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
            'email'             => $data['email'],
            'name'              => $data['name'],
            'referral_code'     => $this->createUniqueReferralCode(),
            'password'  => bcrypt($data['password'])
        ];
        $user       = \App\User::create($userData);

        return $this->__login($data['email'], $data['password'], $request);
    }
    /**
     * remove oauth token and manage web and extension login and logout
     * @param  \Illuminate\Http\Request  $request
     * @return boolean              return true or false
     */
    public function logout(Request $request){
        $updateLogin = \App\User::where('email',$request->email)->update(["isloggedOut"=>0]);
        return $this->apiOk(true);
    }
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
            return $this->apiOk($data);
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
    /**
     * send mail for share note,bridge and elements
     * @param  \Illuminate\Http\Request  $request
     * @return boolean           return true and false
     */
    public function sendMail(Request $request) {
            $user = Auth::user();
            $shareData = '';
            $getData = $request->all();
            $email_data['to'] = $getData['email'];
            $email_data['notes'] = $getData['notes'];
            if($getData['type'] == 0){
                $email_data['subject'] = $user->name .' sent you a bridge.';
                $shareData = $this->shareRepo->shareBridge($getData['id']);
            }elseif($getData['type'] == 1){
                $email_data['subject'] = $user->name .' sent you a notes.';
                $shareData = $this->shareRepo->shareNote($getData['id']);
            }else{
                $email_data['subject'] = $user->name .' sent you a elements.';
                $shareData = $this->shareRepo->shareElement($getData['id']);
            }
            // $data = $shareData;
            // return view('email.share',compact('data'));
            $email_data['view'] = 'email.share';
            $this->emailRepo->send($email_data, $shareData);
            return $this->apiOk(true);
    }
    /**
     * createUniqueReferralCode for user for share referral link
     * @return string  return unique random string
     */
    public function createUniqueReferralCode()
    {
        $temp = strtoupper(str_random(10));
            while (User::where('referral_code', $temp)->first() == "") {
                return $temp;
            }
    }
}
