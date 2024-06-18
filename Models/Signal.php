<?php

namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;

class Signal extends Model {

    protected $table = 'signal_ad';       

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function ad(){
        return $this->belongsTo('App\Http\Models\Ads\Ads', 'ad_id');
    }

    public function tags(){
        return $this->hasMany(\App\Http\Models\SignalTags::class, 'signal_id');
    }

}
