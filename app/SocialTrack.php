<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialTrack extends Model
{
    protected $table = 'social_tracks';
    protected $fillable=['user_id','type_id','type','social_type','shared_with','shared_message','is_read'];
    public function sharedWith()
    {
        return $this->belongsTo('App\User', 'shared_with','id');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id','id');
    }
    public function bridge()
    {
        return $this->belongsTo('App\Bridge', 'type_id','id');
    }
    public function note()
    {
        return $this->belongsTo('App\Note', 'type_id','id');
    }
    public function element()
    {
        return $this->belongsTo('App\Element', 'type_id','id');
    }
}
