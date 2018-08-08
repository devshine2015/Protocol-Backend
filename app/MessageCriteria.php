<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageCriteria extends Model
{
    protected $table = 'message_criteria';
    protected $fillable=['criteria'];
}
