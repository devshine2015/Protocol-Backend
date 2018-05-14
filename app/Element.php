<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Element extends Model
{
    protected $table = 'elements';

    public function toArray($options = 0)
    {
        $json = parent::toArray();
        $json['image'] = env('APP_URL') . Storage::url($json['image']);

        return $json;
    }
}
