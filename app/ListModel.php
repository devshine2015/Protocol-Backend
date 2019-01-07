<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListModel extends Model
{
    protected $table = 'lists';
    use SoftDeletes;
    public function elementData(){
        return $this->belongsTo(Element::class,'target','id');
    }
    public function getCategoryAttribute($value)
    {
        if($value==0){
            return null;
        }
        return $value;
    }
}
