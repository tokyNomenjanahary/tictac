<?php

namespace App\Http\Models\Ads;
use Illuminate\Database\Eloquent\Model;

class AdDetails extends Model {

    protected $table = 'ad_details';
   
    public function property_type() {
        return $this->belongsTo('App\Http\Models\Property\PropertyTypes', 'property_type_id');
    }

}
