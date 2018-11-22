<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryType extends Model
{
    protected $table = 'category_types';
    protected $hidden = ['updated_at'];
    protected $fillable=['name','category_id','created_by','is_approved'];
    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id','id');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'created_by','id');
    }
}

