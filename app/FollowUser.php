<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FollowUser extends Model
{
    protected $table = 'follow_users';

    protected  $fillable = ['user_id','follower_id'];
    protected $hidden = ['updated_at'];
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id','id');
    }
    public function followUser()
    {
        return $this->belongsTo('App\User', 'follower_id','id');
    }
}
