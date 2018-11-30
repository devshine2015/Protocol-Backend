<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Element extends Model
{
    protected $table = 'elements';
    protected $fillable = [
        'type', 'url', 'start_locator','start_offset','end_locator','end_offset','image','imagePath','text','rect','status','name','desc','category_id','sub_category','tags','saveBoard'
    ];
    public function toArray($options = 0)
    {
        $json = parent::toArray();
        $json['image'] = env('APP_URL') . Storage::url($json['image']);
        $json['imagePath'] = env('APP_URL') . Storage::url($json['imagePath']);

        return $json;
    }
    public function followElement(){
        return $this->belongsTo(FollowElement::class,'id','element_id');
    }
    public function fromElement(){
        return $this->hasMany(Bridge::class,'from','id');
    }
    public function toElement(){
        return $this->hasMany(Bridge::class,'to','id');
    }
    public function notes(){
        return $this->hasMany(Note::class,'target','id');
    }

}
