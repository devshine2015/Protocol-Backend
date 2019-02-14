<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\SocialTrack;
use Validator;

class SocialTrackController extends Controller
{
    protected $model;
    public function __construct(SocialTrack $socialTrack)
    {
        $this->model                        = $socialTrack;
    }
    /**
     * Store social share detail
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::guard('api')->user();
        $data  = $request->only(['user_id','type','type_id','social_type']);
        $type_id = 'required';
        if($request->type ==0){
            $type_id .='|exists:bridges,id';
        }elseif($request->type ==1 ){
            $type_id .='|exists:notes,id';
        }elseif($request->type ==3){
            $type_id .='|exists:lists,id';
        }
        else{
            $type_id .='|exists:elements,id';
        }
        $valid = Validator::make($data, [
            'type'        => 'required',
            'type_id'     => $type_id,
            'social_type' => 'required'
        ]);
        if ($valid->fails()) {
            return $this->apiErr(22001, $valid->messages(), 422);
        }
        $request['user_id'] = $user->id;
        $this->model->fill($request->only('user_id','type_id', 'type', 'social_type'));
        $this->model->save();
        return $this->apiOk(true);
    }
}
