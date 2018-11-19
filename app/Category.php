<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $hidden = ['updated_at'];
    public function subCategory()
    {
        return $this->hasMany('App\CategoryType', 'category_id','id');
    }
    public function bridges()
    {
        return $this->hasMany('App\Bridge', 'category','id')->select('tags','category');
    }
    public function notes()
    {
        return $this->hasMany('App\Note', 'category','id')->select('tags','category');
    }

}

