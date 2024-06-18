<?php

namespace App\Http\Models\Ads;

use Illuminate\Database\Eloquent\Model;

class BoostedAds extends Model {

    protected $table = 'boosted_ads';

    public function ads() {
        return $this->belongsTo('App\Http\Models\Ads\Ads', 'ad_id');
    }

}
