<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $fillable=['message_type','start_date','end_date','message','criteria','message_categories_id','message_criteria_id'];
    public function messageCategory(){
        return $this->belongsTo(MessageCategory::class,'message_categories_id','id');
    }
    public function messageCriteria(){
        return $this->belongsTo(MessageCriteria::class,'message_criteria_id','id');
    }
}