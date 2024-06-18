<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtatEquipement extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function etat_usure()
    {
        return $this->belongsTo(EtatUsure::class);
    }

    public function fonctionnement()
    {
        return $this->belongsTo(Fonctionnement::class);
    }

}
