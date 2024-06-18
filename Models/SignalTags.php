<?php

namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;

class SignalTags extends Model {

    protected $table = 'signal_tags';       

    public function tag(){
        return $this->belongsTo('App\Http\Models\SignalTag', 'tag_id');
    }

    public function signal(){
        return $this->belongsTo('App\Http\Models\Signal', 'signal_id');
    }

}
