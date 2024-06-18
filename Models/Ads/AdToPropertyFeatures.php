<?php

namespace App\Http\Models\Ads;
use Illuminate\Database\Eloquent\Model;

class AdToPropertyFeatures extends Model {

    protected $table = 'ad_to_property_features';
   
    public function property_features() {
        return $this->belongsTo('App\Http\Models\Property\PropertyFeatures', 'property_features_id');
    }
   
}
