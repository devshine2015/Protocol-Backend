<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    protected $table = 'relations';
    protected $fillable=['active_name','passive_name','is_active','is_approved','created_by','updated_by','status','type','sort_key','chinese_active_name','chinese_passive_name'];
    public function user()
    {
        return $this->belongsTo('App\User', 'created_by','id');
    }
}
