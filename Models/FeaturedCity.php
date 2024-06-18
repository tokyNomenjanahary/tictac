<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeaturedCity extends Model {

    protected $table = 'featured_cities';
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function location_data() {
        return $this->belongsTo('App\Http\Models\Location', 'location_id');
    }

}
