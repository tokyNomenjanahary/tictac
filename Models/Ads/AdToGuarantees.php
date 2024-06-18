<?php

namespace App\Http\Models\Ads;
use Illuminate\Database\Eloquent\Model;

class AdToGuarantees extends Model {

    protected $table = 'ad_to_guarantees';
   
    public function guarantees() {
        return $this->belongsTo('App\Http\Models\Property\Guarantees', 'guarantees_id');
    }
   
}
