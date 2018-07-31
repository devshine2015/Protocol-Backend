<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'notes';
    public function relationData(){
        return $this->belongsTo(NoteCategory::class,'relation','id');
    }
    public function relationOldData(){
        return $this->belongsTo(Relation::class,'relation_old','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function targetData(){
        return $this->belongsTo(Element::class,'target','id');
    }
    public function followUser(){
        return $this->belongsTo(FollowUser::class,'created_by','user_id');
    }
    public function follownoteElement(){
        return $this->hasManyThrough(FollowElement::class,Element::class,'id','element_id','target','id');
    }
}
