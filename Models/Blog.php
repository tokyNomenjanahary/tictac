<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model {

    protected $table = 'blogs';
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    
    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

}
