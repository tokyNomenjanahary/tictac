<?php

namespace App\Http\Models\Ads;
use Illuminate\Database\Eloquent\Model;

class Favourites extends Model {

    protected $table = 'favourites';       
    
    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function ads() {
        return $this->belongsTo('App\Http\Models\Ads\Ads', 'ad_id');
    }
    

}
