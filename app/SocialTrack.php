<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialTrack extends Model
{
    protected $table = 'social_tracks';
    protected $fillable=['user_id','type_id','type','social_type'];
}
