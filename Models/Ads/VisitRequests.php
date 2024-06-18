<?php

namespace App\Http\Models\Ads;
use Illuminate\Database\Eloquent\Model;

class VisitRequests extends Model {

    protected $table = 'visit_requests';
    
    public function sender_ads() {
        return $this->belongsTo('App\Http\Models\Ads\Ads', 'sender_ad_id');
    }
    
    public function receiver_ads() {
        return $this->belongsTo('App\Http\Models\Ads\Ads', 'ad_id');
    }
    
    public function slots() {
        return $this->belongsTo('App\Http\Models\Ads\AdVisitingDetails', 'slot_id');
    }
}
