<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EtatDesLieu extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table   = "etat_des_lieux";

    protected $guarded = [];

    public function type_etat()
    {
        return $this->belongsTo(TypeEtat::class);
    }

    public function compteur_eaux()
    {
        return $this->hasMany(CompteurEau::class);
    }

    public function compteur_electriques()
    {
        return $this->hasMany(CompteurElectrique::class);
    }

    public function type_chauffages()
    {
        return $this->hasMany(TypeChauffage::class);
    }

    public function production_eau_chaudes()
    {
        return $this->hasMany(ProductionEauChaude::class);
    }

    public function cle()
    {
        return $this->belongsTo(CleLocation::class, 'cle_location_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function etat_files()
    {
        return $this->hasMany(EtatFile::class);
    }

    public function etat_pieces()
    {
        return $this->hasMany(EtatPiece::class)->with('equipements.fonctionnement','equipements.etat_usure','properties');
    }

}
