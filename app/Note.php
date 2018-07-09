<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'notes';
    public function relationData(){
        return $this->belongsTo(Relation::class,'relation_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'created_by','id');
    }
}
