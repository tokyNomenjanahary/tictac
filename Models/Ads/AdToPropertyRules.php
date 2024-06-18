<?php

namespace App\Http\Models\Ads;
use Illuminate\Database\Eloquent\Model;

class AdToPropertyRules extends Model {

    protected $table = 'ad_to_property_rules';
   
    public function property_rules() {
        return $this->belongsTo('App\Http\Models\Property\PropertyRules', 'property_rules_id');
    }
}
