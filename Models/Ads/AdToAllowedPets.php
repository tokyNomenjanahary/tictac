<?php

namespace App\Http\Models\Ads;
use Illuminate\Database\Eloquent\Model;

class AdToAllowedPets extends Model {

    protected $table = 'ad_to_allowed_pets';
   
    public function allowed_pets() {
        return $this->belongsTo('App\Http\Models\Property\AllowedPets', 'allowed_pets_id');
    }

}
