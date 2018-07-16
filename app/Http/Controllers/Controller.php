<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function apiOk ($data)
    {
        return response()->json([
            'error_code'    => 0,
            'data'          => $data
        ]);
    }

    public function apiErr ($errorCode, $msg, $httpStatus = 400)
    {
        return response()->json([
            'error_code'    => $errorCode,
            'message'       => $msg
        ], $httpStatus);
    }

    protected function withPrivacyWhere ($query, $user) {
        return $query->where(function($query) use($user) {
            $query->where('privacy', 0);

            if (isset($user)) {
                $query->orWhere(function($q) use($user) {
                    $q->where([ 'privacy' => 1, 'created_by' => $user['id'] ]);
                });
            }
        });
    }
    protected function checkFollow($checkUser){
        $checkUser->filter(function($query){
            if(isset($query->followUser)){
                $query->is_follow = true;
            }else{
                $query->is_follow = false;
            }
            unset($query->followUser);
        });
        return $checkUser;
    }
    protected function checkFollowElement($checkelement){
        $checkelement->filter(function($query){
            if(isset($query->followElement)){
                $query->is_follow = true;
            }else{
                $query->is_follow = false;
            }
            unset($query->followElement);
        });
        return $checkelement;
    }
}
