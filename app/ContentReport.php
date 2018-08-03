<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContentReport extends Model
{
    protected $table = 'content_reports';
    protected $fillable=['user_id','type_id','type','report','comment'];
}
