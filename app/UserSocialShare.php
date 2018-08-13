<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSocialShare extends Model
{
    protected $table = 'user_social_shares';
    protected $fillable=['user_id','type_id','type'];

    public function notes(){
    	return $this->belongsTo(Note::class,'type_id','id');
    }
    public function bridges(){
    	return $this->belongsTo(Bridge::class,'type_id','id');
    }
    public function elements(){
    	return $this->belongsTo(Element::class,'type_id','id');
    }
}
