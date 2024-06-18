<?php

namespace App\Http\Controllers;

use Session;
use App\revenu;
use App\Depense;
use App\Finance;
use App\Location;
use App\Logement;
use App\RevenuTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\LocatairesGeneralInformations;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;


class revenuController extends Controller
{
    public function index()
    {
        // $Finance = Finance::where('id', $id)->get();
        $logements  = Logement::where('user_id', Auth::id())->get();
        $locations  = Location::where('user_id', Auth::id())->get();
        $locataires  = LocatairesGeneralInformations::where('user_id', Auth::id())->get();

        return view('proprietaire.ajout-revenu', compact('logements', 'locations', 'locataires'));
    }
    public function documents(Request $request)
    {
        $jsonData = json_decode($request->all()["initialPreviewConfig"], true);
        $i = 1;
        foreach ($request->file("etat-files-update") as $file) {
            $uniqId = uniqid();
            $filename = 'revenu_img' . $uniqId . '.' . $file->extension();
            $path = $file->storeAs(
                'revenu',
                $filename,
                'public'
            );
            $documents = RevenuTable::create([
                'media_type' => 0,
                'alt' => $uniqId . '.' . $file->extension(),
                'size' => $file->getSize(),
                'ordre' => $i,
                'file_name' => $path,
                'etat_des_lieu_id' => count($jsonData) == 0 ? 0 : $jsonData[0]["extra"]["etat"],
            ]);
            $i++;
            pasteLogo(base_path() . '/storage/app/public/revenu/' . $filename);
            //  $etat_file_ids[] = $etatFile->id;
        }
        return response()->json([
            'initialPreview' => [
                "/storage/" . $path,
            ],
            'initialPreviewConfig' => [
                [
                    'caption' => $documents->alt,
                    'id' => $documents->id,
                    'size' => $documents->size,
                    'url' => route('delete-revenu'),
                    'key' => $documents->id,
                    'extra' => [
                        'id' => $documents->id,
                        "type" => "deleted",
                        'file_name' => $documents->file_name,
                        'etat' => count($jsonData) == 0 ? 0 : $jsonData[0]["extra"]["etat"]
                    ]
                ],
            ],
        ]);
    }
    public function deleterevenuFiles(Request $request)
    {
        $file_data = $request->input();
        $file = RevenuTable::findOrFail($file_data["id"]);
        $file->delete();
        Storage::disk('public')->delete($file_data["file_name"]);
        return response()->json(array());
    }
    public function deleteEtat(Request $request)
    {
        $ids = $request->etat_ids;
        foreach ($ids as $id) {
            $RevenuTable = RevenuTable::where("id", $id);
            // $etat_cle = $etat->first()->cle ? $etat->first()->cle->id : null;
            $etat_id = $RevenuTable->first()->id;
            // $etat_compteurs_eau = CompteurEau::where('etat_des_lieu_id', $id)->delete();
            // $etat_type_chauffage = TypeChauffage::where('etat_des_lieu_id', $id)->delete();
            // $etat_production_eau_chaude = ProductionEauChaude::where('etat_des_lieu_id', $id)->delete();
            // $etat_compteurs_elec = CompteurElectrique::where('etat_des_lieu_id', $id)->delete();
            // $cle = CleLocation::where('id', $etat_cle)->delete();
            $revenufile = RevenuTable::where('etat_des_lieu_id', $etat_id)->get();
            foreach ($revenufile as $file) {
                $file_ids['id'] = $file->id;
                $file_ids['file_name'] = $file->file_name;
                $cust_request = new Request();
                $cust_request->setMethod('POST');
                $cust_request->request->add($file_ids);
                $this->deleterevenuFiles($cust_request);
            }
        }
        $RevenuTable->delete();
        return response()->json(['success' => true, 'message' => 'Supprimé avec success'], 200);
    }

    public function enregistrer_revenu(Request $request)
    {
       if($request->Type == 'Loyer')
       {
        $validator = Validator::make($request->all(), [
            'Type'                => 'required',
            'locataire'           => 'required',
            'montant'             => 'required',
            'debutLoyer'          => 'required',
            'finLoyer'            => 'required',
            'bien'                => 'required',

        ]);
       }else
       {
        $validator = Validator::make($request->all(), [
            'Type'                => 'required',
            'locataire'           => 'required',
            'montant'             => 'required',
            'date'                => 'required',
            'bien'                => 'required',
        ]);
       }
        if ($validator->passes())
        if($request->Type == 'Loyer')
        {
            $data = array();
            $data['logement_id'] = $request->bien;
            $data['location_id'] = $request->location;
            $data['Description'] = $request->Type;
            $data['debut'] = $request->debutLoyer;
            $data['fin'] = $request->finLoyer;
            $data['locataire_id'] = $request->locataire;
            $data['montant'] = $request->montant;
            $data['loyer_HC']  = $request->montant;
            $data['type'] = 'loyer';
            $data['user_id'] = Auth::id();
            $finance = Finance::create($data);

            $donne = array();
            $donne['finance_id'] = $finance->id;
            $donne['frequence'] = $request->frequence;
            $donne['Description'] = $request->description;
            $donne['Autres_informations'] = $request->Autres_informations;

            revenu::create($donne);
            // envoi_quittance($finance->id);
            toastr()->success(__("finance.Revenu_ajoutées"));

            return response()->json(['success' => true, 'message' => '__(finance.Revenu_ajoutées)'], 200);
        }
       else{
        {
            $data = array();
            $data['logement_id'] = $request->bien;
            $data['location_id'] = $request->location;
            $data['Description'] = $request->Type;
            $data['debut'] = $request->date;
            $data['locataire_id'] = $request->locataire;
            $data['montant'] = $request->montant;
            $data['type'] = 'revenu';
            $data['user_id'] = Auth::id();
            $finance = Finance::create($data);

            $donne = array();
            $donne['finance_id'] = $finance->id;
            $donne['frequence'] = $request->frequence;
            $donne['Description'] = $request->description;
            $donne['Autres_informations'] = $request->Autres_informations;

            revenu::create($donne);
            toastr()->success(__("finance.Revenu_ajoutées"));

            return response()->json(['success' => true, 'message' => '__(finance.Revenu_ajoutées)'], 200);
        }
    }
        else {
            return response()->json(['errors' => $validator->errors()]);
        }
    }
    public function modifier_revenu($id)
    {
        $id = Crypt::decrypt($id);
        Session::put('id', $id);
        $finance = Finance::where('id', $id)->with('Logement', 'Locataire', 'Location', 'revenu')->first();
        $logements  = Logement::where('user_id', Auth::id())->get();
        $locations  = Location::where('user_id', Auth::id())->get();
        $locataires  = LocatairesGeneralInformations::where('user_id', Auth::id())->get();
        $Autres_information = revenu::where('finance_id', $id)->first();
        return view('proprietaire.revenu', compact('finance', 'logements', 'locations', 'locataires', 'Autres_information'));
    }

    public function sauver_revenu(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bien'                => 'required',
            'location'            => 'required',
            'Type'                => 'required',
            'locataire'           => 'required',
            'montant'             => 'required',
            'date'                => 'required',
        ]);
        if ($validator->passes()) {
            $data = array();
            $data['logement_id'] = $request->bien;
            $data['location_id'] = $request->location;
            $data['Description'] = $request->Type;
            $data['debut'] = $request->date;
            $data['locataire_id'] = $request->locataire;
            $data['montant'] = $request->montant;
            $finance = new Finance();
            $finance->where('id', Session::get('id'))->update($data);

            $donne['Autres_informations'] = $request->Autres_informations;
            $revenu = new revenu();
            $revenu->where('finance_id', Session::get('id'))->update($donne);

            toastr()->success(__("finance.Modification_de_revenu"));
            return response()->json(['success' => true, 'messages' => 'modifier avec success']);
        } else {
            return response()->json(['errors' => $validator->errors()]);
        }
    }
    public function delete_revenu($id)
    {
        $id = Crypt::decrypt($id);
        $Finance = Finance::find($id);
        $Finance->delete();
        $revenu = revenu::where('finance_id',$id);
        $revenu->delete();
        toastr()->success(__("finance.Suppression_success"));

        return back();
    }
    public function ajout_depense()
    {
        $logements  = Logement::where('user_id', Auth::id())->get();
        $locations  = Location::where('user_id', Auth::id())->get();
        $locataires  = LocatairesGeneralInformations::where('user_id', Auth::id())->get();
        return view('proprietaire.ajout_depense', compact('logements', 'locations', 'locataires'));
    }
    public function enregistrer_depense(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Type'                => 'required',
            'date'                => 'required',
            'locataire'           => 'required',
            'montant'             => 'required',
            'bien'             => 'required'

        ]);
        if ($validator->passes()) {
            $data = array();
            $data['logement_id'] = $request->bien;
            $data['location_id'] = $request->location;
            $data['Description'] = $request->Type;
            $data['debut'] = $request->date;
            $data['locataire_id'] = $request->locataire;
            $data['montant'] = $request->montant;
            $data['type'] = 'depense';
            $data['user_id'] = Auth::id();
            $finance = Finance::create($data);

            $donne = array();
            $donne['depense_Id'] = $finance->id;
            $donne['frequence'] = $request->frequence;
            $donne['finance_id'] = $finance->id;
            $donne['Autres_informations'] = $request->Autres_informations;
            Depense::create($donne);

            toastr()->success(__("finance.Dépense_ajoutées"));



            return response()->json(['success' => true, 'message' => 'Ajouter avec success'], 200);
        } else {
            return response()->json(['errors' => $validator->errors()]);
        }
    }
    public function modifier_depense($id)
    {
        $id = Crypt::decrypt($id);
        Session::put('id', $id);
        $finance = Finance::where('id', $id)->with('Logement', 'Locataire', 'Location', 'revenu')->first();
        $logements  = Logement::where('user_id', Auth::id())->get();
        $locations  = Location::where('user_id', Auth::id())->get();
        $locataires  = LocatairesGeneralInformations::where('user_id', Auth::id())->get();
        $Autres_information = Depense::where('finance_id', $id)->first();
        return view('proprietaire.depense', compact('finance', 'logements', 'locations', 'locataires', 'Autres_information'));
    }
    public function sauver_depense(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'Type'                => 'required',
            'date'                => 'required',
            'locataire'           => 'required',
            'montant'             => 'required',
        ]);
        if ($validator->passes()) {
            $data = array();
            $data['logement_id'] = $request->bien;
            $data['location_id'] = $request->location;
            $data['Description'] = $request->Type;
            $data['debut'] = $request->date;
            $data['locataire_id'] = $request->locataire;
            $data['montant'] = $request->montant;
            $finance = new Finance();
            $finance->where('id', Session::get('id'))->update($data);

            $donne['Autres_informations'] = $request->Autres_informations;
            $depense = new Depense();
            $depense->where('finance_id', Session::get('id'))->update($donne);

            // revenu::where('finance_id',$finances->id)->update()
            toastr()->success(__("finance.Modification_de_revenu"));


            return response()->json(['success' => true, 'messages' => 'modifier avec success']);
        } else {

            return response()->json(['errors' => $validator->errors()]);
        }
    }
    public function delete_depense($id)
    {
        $id = Crypt::decrypt($id);
        $Finance = Finance::find($id);
        $Finance->delete();
        $depense = Depense::where('finance_id',$id);
        $depense->delete();
        toastr()->success(__("finance.Suppression_success"));

        return back();
    }
    public function modifier_loyer($id)
{
    $id = Crypt::decrypt($id);
    Session::put('id', $id);
    $finance = Finance::where('id', $id)->with('Logement', 'Locataire', 'Location', 'revenu')->first();
    $logements  = Logement::where('user_id', Auth::id())->get();
    $locations  = Location::where('user_id', Auth::id())->get();
    $locataires  = LocatairesGeneralInformations::where('user_id', Auth::id())->get();
    // $Autres_informations = Depense::where('finance_id', $id)->get();

    return view('proprietaire.editionLoyer', compact('finance', 'logements', 'locations', 'locataires' ));
}
public function sauver_Loyer(Request $request)
{
    $validator = Validator::make($request->all(), [
        'Type'                => 'required',
        'fin'                => 'required',
        'locataire'           => 'required',
        'charge'             => 'required',
    ]);
    if ($validator->passes()) {
        $data = array();
        $data['logement_id'] = $request->bien;
        $data['location_id'] = $request->location;
        $data['Description'] = $request->Type;
        $data['debut'] = $request->date;
        $data['fin'] = $request->fin;
        $data['locataire_id'] = $request->locataire;
        $data['loyer_HC'] = $request->Loyer;
        $data['charge'] = $request->charge;
        $data['montant'] = $request->Loyer+$request->charge;
        // dd($data);
        $finance = new Finance();
        $finance->where('id', Session::get('id'))->update($data);

        toastr()->success(__("finance.Modification_de_revenu"));

        return response()->json(['status' => true, 'message' => __("finance.Modification_de_revenu")], 200);

    } else {

        return response()->json(['errors' => $validator->errors()]);
    }
}
}
