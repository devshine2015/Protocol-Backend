<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryType extends Model
{
    protected $table = 'category_types';
    protected $hidden = ['updated_at'];
    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id','id');
    }
}

