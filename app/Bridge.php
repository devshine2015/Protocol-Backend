<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bridge extends Model
{
    protected $table = 'bridges';
    public function relationData(){
        return $this->belongsTo(Relation::class,'relation','id');
    }
    public function relationName(){
        return $this->belongsTo(NoteCategory::class,'relation','id')->select('name','id','chinese_name');
    }
    public function category(){
        return $this->belongsTo(Category::class,'category','id')->select('name','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function fromElement(){
        return $this->belongsTo(Element::class,'from','id');
    }
    public function toElement(){
        return $this->belongsTo(Element::class,'to','id');
    }
    public function followUser(){
        return $this->belongsTo(FollowUser::class,'created_by','user_id');
    }
    public function followFromElement(){
        return $this->hasManyThrough(FollowElement::class,Element::class,'id','element_id','from','id');
    }
    public function like(){
        return $this->hasMany(ContenLike::class,'type_id','id')->where('type',0);
    }
    public function followtoElement(){
        return $this->hasManyThrough(FollowElement::class,Element::class,'id','element_id','to','id');
    }
    public function getCategoryAttribute($value)
    {
        if($value==0){
            return null;
        }
        return $value;
    }
}
