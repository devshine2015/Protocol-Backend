<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FollowElement extends Model
{
    protected $table = 'follow_element';

    protected  $fillable = ['user_id','follower_id'];
    protected $hidden = ['updated_at'];
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id','id');
    }
    public function followElement()
    {
        return $this->belongsTo('App\Element', 'element_id','id');
    }
}
