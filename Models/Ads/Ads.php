<?php

namespace App\Http\Models\Ads;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ads extends Model {

    protected $table = 'ads';
    /*use SoftDeletes;*/
    
    protected $dates = ['deleted_at'];
   
    public function ad_details() {
        return $this->hasOne('App\Http\Models\Ads\AdDetails', 'ad_id')->with('property_type');
    }

    public function type_location()
    {
        return $this->belongsTo('App\Http\Models\Ads\SousTypeLoc', 'sous_type_loc');
    }
    
    public function ad_files() {
        return $this->hasMany('App\Http\Models\Ads\AdFiles', 'ad_id');
    }
    
    public function nearby_facilities() {
        return $this->hasMany('App\Http\Models\Ads\NearbyFacilities', 'ad_id');
    }
    
    public function ad_to_allowed_pets() {
        return $this->hasMany('App\Http\Models\Ads\AdToAllowedPets', 'ad_id');
    }
    
    public function ad_to_property_features() {
        return $this->hasMany('App\Http\Models\Ads\AdToPropertyFeatures', 'ad_id');
    }
    
    public function ad_to_amenities() {
        return $this->hasMany('App\Http\Models\Ads\AdToAmenities', 'ad_id');
    }
    
    public function ad_to_guarantees() {
        return $this->hasMany('App\Http\Models\Ads\AdToGuarantees', 'ad_id');
    }
    
    public function ad_to_property_rules() {
        return $this->hasMany('App\Http\Models\Ads\AdToPropertyRules', 'ad_id');
    }
    
    public function ad_visiting_details() {
        return $this->hasMany('App\Http\Models\Ads\AdVisitingDetails', 'ad_id');
    }
    
    public function ad_rental_applications() {
        return $this->hasOne('App\Http\Models\Ads\AdRentalApplications', 'ad_id');
    }
    
    public function ad_uploaded_guarantees() {
        return $this->hasMany('App\Http\Models\Ads\UploadedGuarantees', 'ad_id');
    }
    
    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
	
    public function boosted_ads() {
        return $this->hasOne('App\Http\Models\Ads\BoostedAds', 'ad_id');
    }

    public function ad_proximities() {
        return $this->hasMany('App\Http\Models\Ads\AdProximity', 'ad_id');
    }

    public function ad_trackings() {
        return $this->hasMany('App\Http\Models\Ads\AdTracking', 'ads_id');
    }

    public function duplication_ads()
    {
        return $this->hasOne('App\Ads_duplication','url_slug');
    }

}
