<?php

namespace App\Http\Models\Ads;
use Illuminate\Database\Eloquent\Model;

class AdToAmenities extends Model {

    protected $table = 'ad_to_amenities';
   
    public function amenities() {
        return $this->belongsTo('App\Http\Models\Property\Amenities', 'amenities_id');
    }
   
}
