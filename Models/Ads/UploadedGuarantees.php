<?php

namespace App\Http\Models\Ads;
use Illuminate\Database\Eloquent\Model;

class UploadedGuarantees extends Model {

    protected $table = 'uploaded_guarantees';
    
    public function guarantees() {
        return $this->belongsTo('App\Http\Models\Property\Guarantees', 'guarantee_id');
    }
   
}
