<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSocialShare extends Model
{
    protected $table = 'user_social_shares';
    protected $fillable=['user_id','type_id','type'];
}
