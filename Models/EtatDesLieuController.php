<?php

namespace App\Http\Controllers;

use PDF;
use App\EtatFile;
use App\Location;
use App\Logement;
use App\TypeEtat;
use App\EtatPiece;
use App\EtatUsure;
use App\Inventaire;
use App\CleLocation;
use App\CompteurEau;
use App\EtatDesLieu;
use App\EtatProperty;
use App\TypeChauffage;
use App\EtatEquipement;
use App\Fonctionnement;
use App\CompteurElectrique;
use App\ProductionEauChaude;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Validator;


class EtatDesLieuController extends Controller
{
    public function index()
    {
        $etat_lieux = EtatDesLieu::with('compteur_eaux', 'compteur_electriques', 'type_chauffages', 'production_eau_chaudes')
            ->where('user_id', Auth::user()->id)
            ->get();
        $count_arc = EtatDesLieu::where('user_id', Auth::user()->id)
            ->where('is_active', 0)
            ->count();
        $count_active = EtatDesLieu::where('user_id', Auth::user()->id)
            ->where('is_active', 1)
            ->count();
        return view('proprietaire.etat-des-lieux', compact('etat_lieux', 'count_arc', 'count_active'));
    }

    public function create($id = null, $nom = null)
    {
        $etat_usures = EtatUsure::all();
        $user = Auth::user();
        $locations = Location::where('user_id', $user->id)->get();
        $type_etats = TypeEtat::all();
        $fonctionnements = Fonctionnement::all();
        if ($id) {
            $etat_lieu = EtatDesLieu::with('compteur_eaux', 'compteur_electriques', 'type_chauffages', 'production_eau_chaudes', 'type_etat', 'cle', 'etat_files', 'etat_pieces')
                ->findOrFail($id);
        }
        if ($nom && $id) {
            return view('proprietaire.etat-profil', compact('locations', 'type_etats', 'fonctionnements', 'etat_usures', 'etat_lieu'));
        } elseif ($id) {
            return view('proprietaire.ajout-etat', compact('locations', 'type_etats', 'fonctionnements', 'etat_usures', 'etat_lieu'));
        }
        return view('proprietaire.ajout-etat', compact('locations', 'type_etats', 'fonctionnements', 'etat_usures'));
    }


    public function createWith($type_id, $location_id)
    {
        $etat_usures = EtatUsure::all();
        $user = Auth::user();
        $locations = Location::where('user_id', $user->id)->get();
        $type_etats = TypeEtat::all();
        $fonctionnements = Fonctionnement::all();
        return view('proprietaire.ajout-etat', compact('locations', 'type_etats', 'fonctionnements', 'etat_usures', 'type_id', 'location_id'));
    }
    public function inventaireLocation($location_id)
    {   
        $identifiant="true";
        $locations  =   DB::table('locations_proprietaire')
        ->where('user_id', Auth::id())
        ->where('id', $location_id)
        ->get();
        $etat_usures = EtatUsure::all();
        $logements = Logement::instance()->getListeLogementDisponible(Auth::id());

    return view('inventaire.nouveauInventaire', compact('locations', 'logements', 'etat_usures','identifiant'));
    }

    public function createWithInventaire($id){
        $etat_usures = EtatUsure::all();
        $user = Auth::user();
        $locations = Location::where('user_id', $user->id)->get();
        $type_etats = TypeEtat::all();
        $fonctionnements = Fonctionnement::all();
        $etat_lieu = Inventaire::with('etat_pieces','location','type_etat','compteur_eaux')
                ->findOrFail($id);

        $a = 1;
        return view('proprietaire.ajout-etat', compact('locations', 'type_etats', 'fonctionnements', 'etat_usures', 'etat_lieu','a'));

    }

    public function storeOrUpdate(Request $request, $update = null)
    {
        // dd($request->all());
        $user = Auth::user()->id;
        $donne_compteur_eau = [
            'name_eaux' => $request->name_eaux,
            'numero_eaux' => $request->numero_eaux,
            'volume_eaux' => $request->volume_eaux,
            'observation_eaux' => $request->observation_eaux,
            'fontion_eaux' => $request->fontion_eaux,
            'compteur_eaux_modif' => $request->compteur_eaux_modif
        ];

        $donne_compteur_electrique = [
            'name_electriques' => $request->name_electriques,
            'numero_electriques' => $request->numero_electriques,
            'volume_electriques' => $request->volume_electriques,
            'observartion_electriques' => $request->observartion_electriques,
            'fonction_electriques' => $request->fonction_electriques,
            'compteur_electriques_modif' => $request->compteur_electriques_modif
        ];

        $donne_type_chauffage = [
            'name_chauffages' => $request->name_chauffages,
            'numero_chauffages' => $request->numero_chauffages,
            'volume_chauffages' => $request->volume_chauffages,
            'observation_chauffages' => $request->observation_chauffages,
            'fonction_chauffages' => $request->fonction_chauffages,
            'type_chauffage_modif' => $request->type_chauffage_modif
        ];

        $donne_production_eau_chaude = [
            'name_production_eaux' => $request->name_production_eaux,
            'observation_production_eaux' => $request->observation_production_eaux,
            'fonction_production_eaux' => $request->fonction_production_eaux,
            'production_eau_chaude_modif' => $request->production_eau_chaude_modif
        ];

        $etat_validator = Validator::make($request->all(), [
            'etat_name' => 'required',
        ]);

        if ($etat_validator->passes()) {
            if ($update) {
                if ($request->cle_modif) {
                    $cle = CleLocation::findOrFail($request->cle_modif);
                    if ($request->name_cle || $request->nombre_cle || $request->date_cle) {
                        $cle->update([
                            'type' => $request->name_cle,
                            'nombre' => $request->nombre_cle,
                            'date' => $request->date_cle,
                            'observation' => $request->commentaire_cle,
                        ]);
                    }
                } else {
                    if ($request->name_cle || $request->nombre_cle || $request->date_cle) {
                        $cle = CleLocation::create([
                            'type' => $request->name_cle,
                            'nombre' => $request->nombre_cle,
                            'date' => $request->date_cle,
                            'observation' => $request->commentaire_cle,
                        ]);
                    }
                }
                $etat_lieu = EtatDesLieu::findOrFail($update);
                $etat_lieu->update([
                    'name' => $request->etat_name,
                    'location_id' => $request->etat_location_id,
                    'type_etat_id' => $request->type_etat_id,
                    'observation' => $request->etat_obs,
                    'cle_location_id' => isset($cle) ? $cle->id : null,
                    'user_id' => $user
                ]);
                if (isset($request->get_pi)) {
                    $this->deletePieces($request->get_pi);
                }
            } else {
                if ($request->name_cle || $request->nombre_cle || $request->date_cle) {
                    $cle = CleLocation::create([
                        'type' => $request->name_cle,
                        'nombre' => $request->nombre_cle,
                        'date' => $request->date_cle,
                        'observation' => $request->commentaire_cle,
                    ]);
                }
                $etat_lieu = EtatDesLieu::create([
                    'name' => $request->etat_name,
                    'location_id' => $request->etat_location_id,
                    'type_etat_id' => $request->type_etat_id,
                    'observation' => $request->etat_obs,
                    'cle_location_id' =>  isset($cle) ? $cle->id : null,
                    'user_id' => $user
                ]);
            }

            // UPDATE props
            if ($etat_lieu) {
                if ($request->etat_files) {
                    // link file to an state
                    foreach ($request->etat_files as $file_id) {
                        $etat_file = EtatFile::findOrFail($file_id);
                        $etat_file->etat_des_lieu_id = $etat_lieu->id;
                        $etat_file->save();
                    }
                }
                if (isset($request->piTitl, $request->mur_val, $request->mur_revetement, $request->mur_usure, $request->mur_commentaire, $request->equi_el, $request->equi_mat, $request->equi_usure, $request->equi_fonc, $request->equi_com)) {
                    $this->saveOrUpdatePi($request->piTitl, $request->mur_val, $request->mur_revetement, $request->mur_usure, $request->mur_commentaire, $request->equi_el, $request->equi_mat, $request->equi_usure, $request->equi_fonc, $request->equi_com, $etat_lieu->id, $request->prop_id, $request->equip_id, $request->pi_titl_id);
                }

                // create water computer for the status
                if (isset($donne_compteur_eau['name_eaux'])) {
                    $this->saveOrUpdateCompteurEau($donne_compteur_eau, $etat_lieu, $update);
                }
                // create elec computer for the status
                if (isset($donne_compteur_electrique['name_electriques'])) {
                    $this->saveOrUpdateCompteurElectrique($donne_compteur_electrique, $etat_lieu, $update);
                }
                // create type heating for the status // chauffage
                if (isset($donne_type_chauffage['name_chauffages'])) {
                    $this->saveOrUpdateTypeChauffage($donne_type_chauffage, $etat_lieu, $update);
                }
                // create hot water production
                if (isset($donne_production_eau_chaude['name_production_eaux'])) {
                    $this->saveOrUpdateProductionEauChaude($donne_production_eau_chaude, $etat_lieu, $update);
                }
            }
            if($update){
                toastr()->success('Etat des lieux modifier avec success');
            }else{
                toastr()->success('Etat des lieux crée avec success');
            }
            return response()->json(['success' => true, 'message' => 'Ajouter avec success'], 200);
        }
        return response()->json(['success' => false, 'errors' => $etat_validator->errors()], 400);
    }
    public function storeOrUpdateInv(Request $request, $update = null)
    {
        // dd($request->all());
        $user = Auth::user()->id;
        $donne_compteur_eau = [
            'name_eaux' => $request->name_eaux,
            'numero_eaux' => $request->numero_eaux,
            'volume_eaux' => $request->volume_eaux,
            'observation_eaux' => $request->observation_eaux,
            'fontion_eaux' => $request->fontion_eaux,
            'compteur_eaux_modif' => $request->compteur_eaux_modif
        ];

        $donne_compteur_electrique = [
            'name_electriques' => $request->name_electriques,
            'numero_electriques' => $request->numero_electriques,
            'volume_electriques' => $request->volume_electriques,
            'observartion_electriques' => $request->observartion_electriques,
            'fonction_electriques' => $request->fonction_electriques,
            'compteur_electriques_modif' => $request->compteur_electriques_modif
        ];

        $donne_type_chauffage = [
            'name_chauffages' => $request->name_chauffages,
            'numero_chauffages' => $request->numero_chauffages,
            'volume_chauffages' => $request->volume_chauffages,
            'observation_chauffages' => $request->observation_chauffages,
            'fonction_chauffages' => $request->fonction_chauffages,
            'type_chauffage_modif' => $request->type_chauffage_modif
        ];

        $donne_production_eau_chaude = [
            'name_production_eaux' => $request->name_production_eaux,
            'observation_production_eaux' => $request->observation_production_eaux,
            'fonction_production_eaux' => $request->fonction_production_eaux,
            'production_eau_chaude_modif' => $request->production_eau_chaude_modif
        ];

        $etat_validator = Validator::make($request->all(), [
            'etat_name' => 'required',
        ]);

        if ($etat_validator->passes()) {

                $etat_lieu = EtatDesLieu::create([
                    'name' => $request->etat_name,
                    'location_id' => $request->etat_location_id,
                    'type_etat_id' => $request->type_etat_id,
                    'observation' => $request->etat_obs,
                    'cle_location_id' =>  isset($cle) ? $cle->id : null,
                    'user_id' => $user
                ]);


            // UPDATE props
            if ($etat_lieu) {
                if ($request->etat_files) {
                    // link file to an state
                    foreach ($request->etat_files as $file_id) {
                        $etat_file = EtatFile::findOrFail($file_id);
                        $etat_file->etat_des_lieu_id = $etat_lieu->id;
                        $etat_file->save();
                    }
                }
                if (isset($request->piTitl, $request->mur_val, $request->mur_revetement, $request->mur_usure, $request->mur_commentaire, $request->equi_el, $request->equi_mat, $request->equi_usure, $request->equi_fonc, $request->equi_com)) {
                    $this->saveOrUpdatePi($request->piTitl, $request->mur_val, $request->mur_revetement, $request->mur_usure, $request->mur_commentaire, $request->equi_el, $request->equi_mat, $request->equi_usure, $request->equi_fonc, $request->equi_com, $etat_lieu->id, $request->prop_id, $request->equip_id, $request->pi_titl_id);
                }

                // create water computer for the status
                if (isset($donne_compteur_eau['name_eaux'])) {
                    $this->saveOrUpdateCompteurEau($donne_compteur_eau, $etat_lieu, $update);
                }
                // create elec computer for the status
                if (isset($donne_compteur_electrique['name_electriques'])) {
                    $this->saveOrUpdateCompteurElectrique($donne_compteur_electrique, $etat_lieu, $update);
                }
                // create type heating for the status // chauffage
                if (isset($donne_type_chauffage['name_chauffages'])) {
                    $this->saveOrUpdateTypeChauffage($donne_type_chauffage, $etat_lieu, $update);
                }
                // create hot water production
                if (isset($donne_production_eau_chaude['name_production_eaux'])) {
                    $this->saveOrUpdateProductionEauChaude($donne_production_eau_chaude, $etat_lieu, $update);
                }
            }


            return response()->json(['success' => true, 'message' => 'Ajouter avec success'], 200);
        }
        return response()->json(['success' => false, 'errors' => $etat_validator->errors()], 400);
    }

    public function saveOrUpdateCompteurEau($donne_compteur_eau, $etat_lieu, $update = null)
    {
        for ($i = 0; $i < count($donne_compteur_eau['name_eaux']); $i++) {
            if (
                $donne_compteur_eau['name_eaux'][$i] ||
                $donne_compteur_eau['numero_eaux'][$i] ||
                $donne_compteur_eau['volume_eaux'][$i] ||
                $donne_compteur_eau['observation_eaux'][$i] ||
                $donne_compteur_eau['fontion_eaux'][$i]
            ) {
                if ($update) {
                    if ($donne_compteur_eau['compteur_eaux_modif'][$i]) {
                        $compteur_eau = CompteurEau::findOrFail($donne_compteur_eau['compteur_eaux_modif'][$i]);
                        $compteur_eau->update([
                            'name' => $donne_compteur_eau['name_eaux'][$i],
                            'numero' => $donne_compteur_eau['numero_eaux'][$i],
                            'volume' => $donne_compteur_eau['volume_eaux'][$i],
                            'observation' => $donne_compteur_eau['observation_eaux'][$i],
                            'fonctionnement_id' => $donne_compteur_eau['fontion_eaux'][$i],
                            'etat_des_lieu_id' => $etat_lieu->id,
                        ]);
                    } else {
                        $compteur_eau = CompteurEau::create([
                            'name' => $donne_compteur_eau['name_eaux'][$i],
                            'numero' => $donne_compteur_eau['numero_eaux'][$i],
                            'volume' => $donne_compteur_eau['volume_eaux'][$i],
                            'observation' => $donne_compteur_eau['observation_eaux'][$i],
                            'fonctionnement_id' => $donne_compteur_eau['fontion_eaux'][$i],
                            'etat_des_lieu_id' => $etat_lieu->id,
                        ]);
                    }
                } else {
                    $compteur_eau = CompteurEau::create([
                        'name' => $donne_compteur_eau['name_eaux'][$i],
                        'numero' => $donne_compteur_eau['numero_eaux'][$i],
                        'volume' => $donne_compteur_eau['volume_eaux'][$i],
                        'observation' => $donne_compteur_eau['observation_eaux'][$i],
                        'fonctionnement_id' => $donne_compteur_eau['fontion_eaux'][$i],
                        'etat_des_lieu_id' => $etat_lieu->id,
                    ]);
                }
            }
        }
        return 'done';
    }

    public function saveOrUpdateCompteurElectrique($donne_compteur_electrique, $etat_lieu, $update = null)
    {
        for ($i = 0; $i < count($donne_compteur_electrique['name_electriques']); $i++) {
            if (
                $donne_compteur_electrique['name_electriques'][$i] ||
                $donne_compteur_electrique['numero_electriques'][$i] ||
                $donne_compteur_electrique['volume_electriques'][$i] ||
                $donne_compteur_electrique['observartion_electriques'][$i] ||
                $donne_compteur_electrique['fonction_electriques'][$i]
            ) {
                if ($update) {
                    if ($donne_compteur_electrique['compteur_electriques_modif'][$i]) {
                        $compteur_electrique = CompteurElectrique::findOrFail($donne_compteur_electrique['compteur_electriques_modif'][$i]);
                        $compteur_electrique->update([
                            'name' => $donne_compteur_electrique['name_electriques'][$i],
                            'numero' => $donne_compteur_electrique['numero_electriques'][$i],
                            'volume' => $donne_compteur_electrique['volume_electriques'][$i],
                            'observation' => $donne_compteur_electrique['observartion_electriques'][$i],
                            'fonctionnement_id' => $donne_compteur_electrique['fonction_electriques'][$i],
                            'etat_des_lieu_id' => $etat_lieu->id,
                        ]);
                    } else {
                        $compteur_electrique = CompteurElectrique::create([
                            'name' => $donne_compteur_electrique['name_electriques'][$i],
                            'numero' => $donne_compteur_electrique['numero_electriques'][$i],
                            'volume' => $donne_compteur_electrique['volume_electriques'][$i],
                            'observation' => $donne_compteur_electrique['observartion_electriques'][$i],
                            'fonctionnement_id' => $donne_compteur_electrique['fonction_electriques'][$i],
                            'etat_des_lieu_id' => $etat_lieu->id,
                        ]);
                    }
                } else {
                    $compteur_electrique = CompteurElectrique::create([
                        'name' => $donne_compteur_electrique['name_electriques'][$i],
                        'numero' => $donne_compteur_electrique['numero_electriques'][$i],
                        'volume' => $donne_compteur_electrique['volume_electriques'][$i],
                        'observation' => $donne_compteur_electrique['observartion_electriques'][$i],
                        'fonctionnement_id' => $donne_compteur_electrique['fonction_electriques'][$i],
                        'etat_des_lieu_id' => $etat_lieu->id,
                    ]);
                }
            }
        }
        return 'done';
    }

    public function saveOrUpdateTypeChauffage($donne_type_chauffage, $etat_lieu, $update = null)
    {
        for ($i = 0; $i < count($donne_type_chauffage['name_chauffages']); $i++) {
            if (
                $donne_type_chauffage['name_chauffages'][$i] ||
                $donne_type_chauffage['numero_chauffages'][$i] ||
                $donne_type_chauffage['volume_chauffages'][$i] ||
                $donne_type_chauffage['observation_chauffages'][$i] ||
                $donne_type_chauffage['fonction_chauffages'][$i]
            ) {
                if ($update) {
                    if ($donne_type_chauffage['type_chauffage_modif'][$i]) {
                        $type_chauffage = TypeChauffage::findOrFail($donne_type_chauffage['type_chauffage_modif'][$i]);
                        $type_chauffage->update([
                            'name' => $donne_type_chauffage['name_chauffages'][$i],
                            'numero' => $donne_type_chauffage['numero_chauffages'][$i],
                            'volume' => $donne_type_chauffage['volume_chauffages'][$i],
                            'observation' => $donne_type_chauffage['observation_chauffages'][$i],
                            'fonctionnement_id' => $donne_type_chauffage['fonction_chauffages'][$i],
                            'etat_des_lieu_id' => $etat_lieu->id,
                        ]);
                    } else {
                        $type_chauffage = TypeChauffage::create([
                            'name' => $donne_type_chauffage['name_chauffages'][$i],
                            'numero' => $donne_type_chauffage['numero_chauffages'][$i],
                            'volume' => $donne_type_chauffage['volume_chauffages'][$i],
                            'observation' => $donne_type_chauffage['observation_chauffages'][$i],
                            'fonctionnement_id' => $donne_type_chauffage['fonction_chauffages'][$i],
                            'etat_des_lieu_id' => $etat_lieu->id,
                        ]);
                    }
                } else {
                    $type_chauffage = TypeChauffage::create([
                        'name' => $donne_type_chauffage['name_chauffages'][$i],
                        'numero' => $donne_type_chauffage['numero_chauffages'][$i],
                        'volume' => $donne_type_chauffage['volume_chauffages'][$i],
                        'observation' => $donne_type_chauffage['observation_chauffages'][$i],
                        'fonctionnement_id' => $donne_type_chauffage['fonction_chauffages'][$i],
                        'etat_des_lieu_id' => $etat_lieu->id,
                    ]);
                }
            }
        }
        return 'done';
    }

    public function saveOrUpdateProductionEauChaude($donne_production_eau_chaude, $etat_lieu, $update = null)
    {
        for ($i = 0; $i < count($donne_production_eau_chaude['name_production_eaux']); $i++) {
            if (
                $donne_production_eau_chaude['name_production_eaux'][$i] ||
                $donne_production_eau_chaude['observation_production_eaux'][$i] ||
                $donne_production_eau_chaude['fonction_production_eaux'][$i]
            ) {
                if ($update) {
                    if ($donne_production_eau_chaude['production_eau_chaude_modif'][$i]) {
                        $production_eau_chaude = ProductionEauChaude::findOrFail($donne_production_eau_chaude['production_eau_chaude_modif'][$i]);
                        $production_eau_chaude->update([
                            'name' => $donne_production_eau_chaude['name_production_eaux'][$i],
                            'observation' => $donne_production_eau_chaude['observation_production_eaux'][$i],
                            'fonctionnement_id' => $donne_production_eau_chaude['fonction_production_eaux'][$i],
                            'etat_des_lieu_id' => $etat_lieu->id,
                        ]);
                    } else {
                        $production_eau_chaude = ProductionEauChaude::create([
                            'name' => $donne_production_eau_chaude['name_production_eaux'][$i],
                            'observation' => $donne_production_eau_chaude['observation_production_eaux'][$i],
                            'fonctionnement_id' => $donne_production_eau_chaude['fonction_production_eaux'][$i],
                            'etat_des_lieu_id' => $etat_lieu->id,
                        ]);
                    }
                } else {
                    $production_eau_chaude = ProductionEauChaude::create([
                        'name' => $donne_production_eau_chaude['name_production_eaux'][$i],
                        'observation' => $donne_production_eau_chaude['observation_production_eaux'][$i],
                        'fonctionnement_id' => $donne_production_eau_chaude['fonction_production_eaux'][$i],
                        'etat_des_lieu_id' => $etat_lieu->id,
                    ]);
                }
            }
        }
        return 'done';
    }

    public function saveOrUpdatePi($pi_titl, $mur_val, $mur_revetement, $mur_usure, $mur_commentaire, $equi_el, $equi_mat, $equi_usure, $equi_fonc, $equi_com, $etat, $prop_id, $equip_id, $pi_titl_id)
    {
        for ($i = 0; $i < count($pi_titl); $i++) {
            $etat_piece = EtatPiece::updateOrCreate(
                [
                    'id' => $pi_titl_id[$i]
                ],
                [
                    'identifiant' => $pi_titl[$i],
                    'commentaire' => "comms",
                    'etat_des_lieu_id' => $etat
                ]
            );
            if ($etat_piece) {
                $mur_val_decode = json_decode($mur_val[$i]);
                $mur_revetement_decode = json_decode($mur_revetement[$i]);
                $mur_commentaire_decode = json_decode($mur_commentaire[$i]);
                $mur_usure_decode = json_decode($mur_usure[$i]);
                $equi_el_decode = json_decode($equi_el[$i]);
                $equi_mat_decode = json_decode($equi_mat[$i]);
                $equi_com_decode = json_decode($equi_com[$i]);
                $equi_fonc_decode = json_decode($equi_fonc[$i]);
                $equi_usure_decode = json_decode($equi_usure[$i]);
                for ($in = 0; $in < count($mur_val_decode); $in++) {
                    if (
                        $mur_val_decode[$in] ||
                        $mur_revetement_decode[$in] ||
                        $mur_commentaire_decode[$in] ||
                        $mur_usure_decode[$in]
                    ) {
                        $etat_props = EtatProperty::updateOrCreate(
                            [
                                'id' => json_decode($prop_id[$i])[$in]
                            ],
                            [
                                "element" => $mur_val_decode[$in],
                                "revetement" => $mur_revetement_decode[$in],
                                "commentaire" => $mur_commentaire_decode[$in],
                                "etat_usure_id" => $mur_usure_decode[$in],
                                "etat_piece_id" => $etat_piece->id
                            ]
                        );
                    }
                }
                for ($k = 0; $k < count($equi_el_decode); $k++) {
                    if (
                        $equi_el_decode[$k] ||
                        $equi_mat_decode[$k] ||
                        $equi_com_decode[$k] ||
                        $equi_fonc_decode[$k] ||
                        $equi_usure_decode[$k]
                    ) {
                        $etat_equ = EtatEquipement::updateOrCreate(
                            [
                                'id' => json_decode($equip_id[$i])[$k],
                            ],
                            [
                                "element" => $equi_el_decode[$k],
                                "materiaux" => $equi_mat_decode[$k],
                                "commentaire" => $equi_com_decode[$k],
                                "fonctionnement_id" => $equi_fonc_decode[$k],
                                "etat_usure_id" => $equi_usure_decode[$k],
                                "etat_piece_id" => $etat_piece->id
                            ]
                        );
                    }
                }
            }
        }
    }

    public function deleteEtat(Request $request)
    {
        $ids = $request->etat_ids;
        $etat = EtatDesLieu::whereIn('id', $ids)->delete();
        foreach($ids as $id){
            $etat_des_lieu_en_corbeille = EtatDesLieu::withTrashed()->findOrFail($id);
            Corbeille('Etat des lieux','etat_des_lieux',$etat_des_lieu_en_corbeille->name,$etat_des_lieu_en_corbeille->deleted_at,$etat_des_lieu_en_corbeille->id);
        }

        toastr()->success('Etat des lieux supprimé avec success');
        return response()->json(['success' => true, 'message' => 'Supprimé avec success'], 200);
    }

    public function deleteEtatFiles(Request $request)
    {
        $file_data = $request->input();
        $file = EtatFile::findOrFail($file_data["id"]);
        $file->delete();
        Storage::disk('public')->delete($file_data["file_name"]);
        return response()->json(array());
    }

    public function uploadEtatFiles(Request $request)
    {
        $jsonData = json_decode($request->all()["initialPreviewConfig"], true);
        $i = 1;
        foreach ($request->file("etat-files-update") as $file) {
            $uniqId = uniqid();
            $filename = 'etat_img_' . $uniqId . '.' . $file->extension();
            $path = $file->storeAs(
                'etatFile',
                $filename,
                'public'
            );
            $etatFile = EtatFile::create([
                'media_type' => 0,
                'alt' => $uniqId . '.' . $file->extension(),
                'size' => $file->getSize(),
                'ordre' => $i,
                'file_name' => $path,
                'etat_des_lieu_id' => count($jsonData) == 0 ? 0 : $jsonData[0]["extra"]["etat"],
            ]);
            $i++;
            pasteLogo(base_path() . '/storage/app/public/etatFile/' . $filename);
            // $etat_file_ids[] = $etatFile->id;
        }
        return response()->json([
            'initialPreview' => [
                "/storage/" . $path,
            ],
            'initialPreviewConfig' => [
                [
                    'caption' => $etatFile->alt,
                    'id' => $etatFile->id,
                    'size' => $etatFile->size,
                    'url' => route('delete-etat-lieux-files'),
                    'key' => $etatFile->id,
                    'extra' => [
                        'id' => $etatFile->id,
                        "type" => "deleted",
                        'file_name' => $etatFile->file_name,
                        'etat' => count($jsonData) == 0 ? 0 : $jsonData[0]["extra"]["etat"]
                    ]
                ],
            ],
        ]);
    }

    public function activity($is_active)
    {
        $etat_lieux = EtatDesLieu::with('compteur_eaux', 'compteur_electriques', 'type_chauffages', 'production_eau_chaudes')
            ->where('user_id', Auth::user()->id)
            ->where('is_active', $is_active)
            ->get();
        if ($is_active) {
            return view('proprietaire.etat-active', compact('etat_lieux'));
        }
        return view('proprietaire.etat-archive', compact('etat_lieux'));
    }

    public function archiverEtat(Request $request)
    {
        $ids = $request->etat_ids;
        EtatDesLieu::whereIn('id', $ids)
            ->update([
                'is_active' => DB::raw('CASE WHEN is_active = 1 THEN 0 ELSE 1 END')
            ]);
        return response()->json(['success' => true, "message" => "dans l'archive"]);
    }

    public function exporter($id)
    {
        $etat_lieu = EtatDesLieu::with('compteur_eaux', 'compteur_electriques', 'type_chauffages', 'production_eau_chaudes', 'type_etat', 'cle', 'etat_files', 'etat_pieces')
            ->findOrFail($id);
        view()->share('proprietaire', $etat_lieu);
        $data = [
            'etat_lieu' => $etat_lieu
        ];
        $ext = $etat_lieu->name . '.pdf';
        $etat_pdf = PDF::loadView('proprietaire.index-pdf', $data);
        return $etat_pdf->download($ext);
    }

    public function deletePieces($ids)
    {
        foreach ($ids as $value) {
            EtatPiece::findOrFail($value)->delete();
        }
    }

    public function exportToWord($id)
    {
        $data = setVar($id);
        $templateProcessor = new TemplateProcessor('word-template/etat.docx');
        $templateProcessor->setValue('title', $data[0]);
        $templateProcessor->setValues($data[1]);
        $templateProcessor->cloneRowAndSetValues('name', $data[2]);
        $templateProcessor->cloneRowAndSetValues('name', $data[3]);
        $templateProcessor->cloneRowAndSetValues('name', $data[4]);
        $templateProcessor->cloneRowAndSetValues('name', $data[5]);
        $templateProcessor->cloneRowAndSetValues('type', $data[6]);
        $templateProcessor->cloneBlock('block', 0, true, false, $data[7]);
        for ($i = 0; $i < count($data[9]); $i++) {
            $templateProcessor->cloneRowAndSetValues('el', $data[8][$i]);
            $templateProcessor->cloneRowAndSetValues('el', $data[9][$i]);
        }

        $templateProcessor->cloneBlock('list_image', count($data[11]), true, true);
        foreach ($data[11] as $key => $image) {
                $templateProcessor->setImageValue('image#'.($key+1), array('path' => storage_path('app/public/' . $image->file_name), 'width' => 350, 'height' => 350, 'ratio' => false));
        }

        $templateProcessor->saveAs('etat.docx');
        $headers = array(
            'Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        );
        return response()->download('etat.docx', $data[10] . '.docx', $headers)->deleteFileAfterSend(true);
    }


}
