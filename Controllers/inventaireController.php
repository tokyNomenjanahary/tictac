<?php

namespace App\Http\Controllers;

use App\Location;
use App\Logement;
use App\EtatUsure;
use App\Inventaire;
use App\InventairePiece;
use Illuminate\Http\Request;
use App\InventaireElementPiece;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class inventaireController extends Controller
{
    //
    public function index()
    {
        $inventaires = Inventaire::where('user_id', Auth::id())->where('is_active', 1)->with('Logement')->get();
        $inventairesArchive = Inventaire::where('user_id', Auth::id())->where('is_active', 0)->with('Logement')->get();
        return view('inventaire.inventaire', compact('inventaires', 'inventairesArchive'));
    }

    public function nouveau()
    {
        $locations  =   DB::table('locations_proprietaire')
            ->where('user_id', Auth::id())
            ->get();
        $etat_usures = EtatUsure::all();
        $logements = Logement::instance()->getListeLogementDisponible(Auth::id());

        return view('inventaire.nouveauInventaire', compact('locations', 'logements', 'etat_usures'));
    }
    public function sauvegarder(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'identifiant'  => 'required'
        ]);
        if ($validator->passes()) {
            $data = array();
            $data['identifiant'] = $request->identifiant;
            $data['logement_id'] = $request->bien;
            $data['location_id'] = $request->location;
            $data['user_id']     = Auth::id();

            Inventaire::create($data);
            toastr()->success('Inventaire ajoutés avec succcèes');
            return response()->json(['success' => true, 'message' => 'Inventaire ajoutés avec succcèes'], 200);
        } else {
            return response()->json(['errors' => $validator->errors()]);
        }
    }

    public function enregistrer(Request $request)
    {

        $validator = Validator::make(json_decode($request->input('information'),true), [
            'identifiant' => 'required',
        ]);
        if ($validator->passes()) {
            $info_inventaire = json_decode($request->input('information'), true);
            try {
                Inventaire::instance()->enregistrer($info_inventaire);
            } catch (\Throwable $th) {
                return response()->json(['success' => false,'errors' =>['message' => $th->getMessage()]],400);
            }
            toastr()->success(__('inventaire.enregistrement_success'));
            return response()->json(['success' => true, 'message' => __('inventaire.enregistrement_success')], 200);
        }else{
            return response()->json(['success' => false,'errors' => $validator->errors()],400);
        }
    }

    // public function updateInventaire($id)
    // {
    //     $locations  =   DB::table('locations_proprietaire')->where('user_id', Auth::id())->get();
    //     $etat_usures = EtatUsure::all();
    //     $logements = Logement::instance()->getListeLogementDisponible(Auth::id());
    //     $inventaire = Inventaire::where('id',$id)->first();
    //     $inventaire_piece = InventairePiece::where('inventaire_id',$id)->get();
    //     $liste_id_piece = array();
    //     foreach($inventaire_piece as $piece){
    //         array_push($liste_id_piece,$piece->id);
    //     }
    //     $liste_element_piece = InventaireElementPiece::whereIn('inventaire_piece',$liste_id_piece)->get();

    //     return view('inventaire.nouveauInventaire', compact('locations', 'logements', 'etat_usures','inventaire','inventaire_piece','liste_element_piece'));
    // }


    public function updateInventaire($id)
    {
        $locations  =   DB::table('locations_proprietaire')->where('user_id', Auth::id())->get();
        $etat_usures = EtatUsure::all();
        $logements = Logement::instance()->getListeLogementDisponible(Auth::id());
        $inventaire = Inventaire::find($id);
        $inventaire_piece = InventairePiece::where('inventaire_id',$id)->get();
        return view('inventaire.nouveauInventaire', compact('locations', 'logements', 'etat_usures','inventaire'));
    }


    public function saveUpdateInventaire(Request $request,$id)
    {
        // dd(json_decode($request->input('information'),true));
        $validator = Validator::make(json_decode($request->input('information'),true), [
            'identifiant' => 'required',
        ]);
        if ($validator->passes()) {
            $data = json_decode($request->input('information'),true);
            try {
                $inventaire = Inventaire::find($id);
                $inventaire->saveUpdate($data);
            } catch (\Throwable $th) {
                return response()->json(['success' => false,'errors' =>['message' => $th->getMessage()]],400);
            }
            toastr()->success(__('inventaire.modification_success'));
            return response()->json(['success' => true, 'message' => __('inventaire.enregistrement_success')], 200);
        }else{
            return response()->json(['success' => false,'errors' => $validator->errors()],400);
        }
    }
    public function delete($id)
    {

        $Inventaire = Inventaire::find($id);
        $Inventaire->delete();
        $inventaire_en_corbeille = Inventaire::withTrashed()->findOrFail($id);
        Corbeille('Inventaire','inventaires',$inventaire_en_corbeille->identifiant,$inventaire_en_corbeille->deleted_at,$inventaire_en_corbeille->id);

         toastr()->success(__("finance.Suppression_success"));
         return back();
    }
     public function deleteMultiple(Request $request)
     {
        $ids = $request->inv_ids;
        Inventaire::whereIn('id', $ids)->delete();
        toastr()->success('Inventaire supprimé avec succcèes');
        return response()->json(['success' => true, 'message' => 'Supprimé avec success'], 200);
     }
    public function archive(Request $request)
    {
        $ids = $request->inv_ids;
        Inventaire::whereIn('id', $ids)
        ->update([
            'is_active' => DB::raw('CASE WHEN is_active = 1 THEN 0 ELSE 1 END')
        ]);
        return response()->json(['success' => true, 'message' => 'Archivé avec success'], 200);
    }

    public function rechercherBienLocation(Request $request)
    {
        $bien = $request->input('bien');
        $data = [];
        try {
            $data['result'] = Location::instance()->rechercheLocationParBien($bien,Auth::id());
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => 'error'], 422);
        }
        return response()->json($data, 200);
    }
}
