<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtatPiece extends Model
{
    use HasFactory;

    protected $table = 'etat_pieces';
    protected $guarded = [];

    public function equipements()
    {
        return $this->hasMany(EtatEquipement::class);
    }

    public function properties()
    {
        return $this->hasMany(EtatProperty::class);
    }

}
