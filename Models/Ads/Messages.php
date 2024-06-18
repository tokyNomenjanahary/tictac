<?php

namespace App\Http\Models\Ads;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model {

    protected $table = 'messages';
    
    public function sender() {
        return $this->belongsTo('App\User', 'sender_id');
    }
    
    public function receiver() {
        return $this->belongsTo('App\User', 'receiver_id');
    }
    
}
