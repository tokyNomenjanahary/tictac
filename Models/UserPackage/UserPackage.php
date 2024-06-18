<?php

namespace App\Http\Models\UserPackage;
use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model {

    protected $table = 'user_packages';
    
    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function package() {
        return $this->belongsTo('App\Http\Models\Package', 'package_id');
    }
    
    public function payment() {
        return $this->belongsTo('App\Http\Models\Payment\Payment', 'payment_id');
    }

}
