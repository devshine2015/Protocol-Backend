<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContenLike extends Model
{
    protected $table = 'content_likes';
    protected $fillable=['user_id','type_id','type','emoji_type','shared_on'];
}
