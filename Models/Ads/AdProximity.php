<?php

namespace App\Http\Models\Ads;

use Illuminate\Database\Eloquent\Model;

class AdProximity extends Model
{
    protected $table = 'ad_proximity';

    public function proximity() {
        return $this->hasOne('App\Http\Models\PointProximity', 'id', 'point_proximity_id');
    }
}
