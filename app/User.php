<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar','isLoggedOut','referral_code','referred_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function getAvatarAttribute($value)
    {
        if(filter_var($value, FILTER_VALIDATE_URL)){
            return ($value) ? ($value) : url('images/avtar.png');
        }
        return ($value) ? url('images/user/'.$value) : url('images/avtar.png');
    }
    public function userPoint()
    {
        return $this->hasMany('App\UserPoint', 'user_id','id');
    }
}
