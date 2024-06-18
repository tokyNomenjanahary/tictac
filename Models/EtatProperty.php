<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtatProperty extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function etat_usure()
    {
        return $this->belongsTo(EtatUsure::class);
    }
}
