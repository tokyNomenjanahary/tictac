<?php

namespace App\Http\Controllers;

use App\Agenda;
use DateTime;
use Exception;
use App\Ticket;
use App\Finance;
use App\Location;
use App\Logement;
use App\Corbeille;
use App\Documents;
use Carbon\Carbon;
use App\Equipement;
use App\LogementFile;
use App\ListEquipement;
use App\ContactLogement;
use App\CategorieContact;
use App\Http\Models\Ads\Ads;
use Illuminate\Http\Request;
use App\TypeContratDiagnostic;
use App\Exports\ImportDataBien;
use App\Http\Models\Ads\AdFiles;
use App\ContratDiagnosticLogement;
use App\Http\Models\Ads\AdDetails;
use App\Http\Requests\NewLogement;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Exports\ExportInfoLogement;
use App\InfoComplementaireLogement;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Auth\Events\Validated;
use App\LocatairesGeneralInformations;
use App\Repositories\MasterRepository;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Models\Ads\AdTemporaryFiles;
use Illuminate\Support\Facades\Validator;

class Proprietaire extends Controller
{
    private $master;

    function __construct(MasterRepository $master)
    {
        $this->master = $master;
    }

    public function index()
    {

        $countBien = Logement::where('user_id', Auth::id())->where('archive', 0)->count();
        $countLocation = Location::where('user_id', Auth::id())->where('archive', 0)->count();
        $countLocataire = LocatairesGeneralInformations::where('user_id', Auth::id())->where('archiver', 0)->count();        

        // count des biens,Location et locataire

        //recuperation et annne et mois en cours
        $AnneeEnCours = Carbon::now()->year;
        $MoisEnCours = Carbon::now()->month;

        //recuperation depense
        $depense = Finance::where('finances.user_id', Auth::id())
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('finances.etat', '0')
            ->where('finances.type', 'depense')
            ->leftJoin('autres_paiements', 'finances.id', '=', 'autres_paiements.finance_id')
            ->selectRaw('finances.montant as montant_finance, autres_paiements.montant as montant_autres_paiements')
            ->selectRaw('IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant) as montant_total')
            ->sum(DB::raw('(IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant))'));

        //recuperation revenu brut
        $sommes = Finance::where('finances.user_id', Auth::id())
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('finances.etat', '0')
            ->where('finances.type', '<>', 'depense')
            ->leftJoin('autres_paiements', 'finances.id', '=', 'autres_paiements.finance_id')
            ->selectRaw('finances.montant as montant_finance, autres_paiements.montant as montant_autres_paiements')
            ->selectRaw('IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant) as montant_total')
            ->sum(DB::raw('(IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant))'));

        $revenuBrute = number_format($sommes, 2);

        //valeur net
        $totalNet = number_format(($sommes - $depense), 2);

        //payement en retard
        $loyerEnretard = Finance::where('user_id', Auth::id())
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('Etat', '1')->count();

        //payement en attente
        $loyerEnattente = Finance::where('user_id', Auth::id())
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('Etat', '2')->count();

        $agendas = Agenda::where('userId_proprietaire', Auth::id())
                        ->where('status', 1)
                        ->orderBy('start_time', 'asc')
                        ->take(5)
                        ->get();
        if (Auth::user()->need_guide) {
            return view('proprietaire.guide', compact('countBien', 'countLocation', 'countLocataire', 'loyerEnattente', 'loyerEnretard', 'revenuBrute', 'depense', 'totalNet', 'agendas'));
        } else {
            return view('proprietaire.bureau', compact('countBien', 'countLocation', 'countLocataire', 'depense', 'revenuBrute', 'totalNet', 'loyerEnretard', 'loyerEnattente', 'agendas'));
        }
    }

    public function logementList()
    {
        $user_id = Auth::id();

        /*** le property_types existe deja dans la base, et elle n'a pas de model alors on utilise la jointure ***/
        $listLogement = Logement::where('user_id', $user_id)->where('archive', 0)->where('property_type_id', '!=', 2)->with('files', 'locations.Locataire')
            ->join('property_types', 'property_types.id', '=', 'logements.property_type_id')
            ->select('logements.*', 'property_types.property_type')->get();
        /*** Liste des logements archivés ***/
        $listLogementArchive = Logement::where('user_id', $user_id)->where('archive', 1)->with('files', 'locations')
            ->join('property_types', 'property_types.id', '=', 'logements.property_type_id')
            ->select('logements.*', 'property_types.property_type')->get();

        $listLogementLocations = Location::where('user_id', $user_id)->with('Logement')->get();

        // $listLogementLocations = Logement::where('user_id',$user_id)->with('locations')->with('infoComplementaireLogement')->get();

        $valeurLocative = 0;
        $valeurActifs = 0;
        $listIdLogement = array();

        foreach ($listLogementLocations as $list) {
            /*** Verification de nombre de logement ***/
            if (!in_array($list->Logement->id, $listIdLogement)) {
                $valeurLocative += $list->Logement->loyer;
                /*** Recuperation de prix d'acquisition sur l'info complementaire de logement ***/
                $infoComplementaireLogement = InfoComplementaireLogement::where('logement_id', $list->Logement->id)->first();
                if ($infoComplementaireLogement) {
                    if (!is_null($infoComplementaireLogement->prix_acquisition)) {
                        $valeurActifs += $infoComplementaireLogement->prix_acquisition;
                    }
                }
                array_push($listIdLogement, $list->Logement->id);
            }
        }

        $countLogementLocation = count($listIdLogement);
        $logementType = $this->master->getMasters('property_types');
        return view('proprietaire.liste_\logement', compact('listLogement', 'listLogementArchive', 'valeurLocative', 'countLogementLocation', 'valeurActifs', 'logementType'));
    }

    public function listLogements($propertyType, $idPropertyType)
    {
        $idPropertyType = decrypt($idPropertyType);
        $user_id = Auth::id();

        /*** le property_types existe deja dans la base, et elle n'a pas de model alors on utilise la jointure ***/
        $listLogement = Logement::where('user_id', $user_id)->where('property_type_id', $idPropertyType)->where('archive', 0)->with('files', 'locations.Locataire')
            ->join('property_types', 'property_types.id', '=', 'logements.property_type_id')
            ->select('logements.*', 'property_types.property_type')->get();
        /*** Liste des logements archivés ***/
        $listLogementArchive = Logement::where('user_id', $user_id)->where('property_type_id', $idPropertyType)->where('archive', 1)->with('files', 'locations')
            ->join('property_types', 'property_types.id', '=', 'logements.property_type_id')
            ->select('logements.*', 'property_types.property_type')->get();

        $listLogementLocations = Logement::where('user_id', $user_id)->where('property_type_id',$idPropertyType)->with('locations')->get();

        $valeurLocative = 0;
        $valeurActifs = 0;
        $listIdLogement = array();
        $countLogementLocation = 0;
        foreach($listLogementLocations as $liste){

            if(!$liste->locations->isEmpty()){
                $countLogementLocation += 1;
            }
            $valeurLocative += $liste->loyer;
             /*** Recuperation de prix d'acquisition sur l'info complementaire de logement ***/
             $infoComplementaireLogement = InfoComplementaireLogement::where('logement_id', $liste->id)->first();
             if ($infoComplementaireLogement) {
                 if (!is_null($infoComplementaireLogement->prix_acquisition)) {
                     $valeurActifs += $infoComplementaireLogement->prix_acquisition;
                 }
             }
             array_push($listIdLogement, $liste->id);
        }

        /*** suppression des contrats et diagnostics sans logement ***/
        $this->deleteContratDiagnosticNoLogement();

        $logementType = $this->master->getMasters('property_types');
        return view('proprietaire.liste_logement', compact('listLogement', 'propertyType', 'listLogementArchive', 'valeurLocative', 'countLogementLocation', 'valeurActifs', 'logementType'));
    }

    public function importerBien()
    {
        return view('proprietaire.importerBien');
    }

    public function saveImporterBien(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv,ods'
        ]);
        if ($validator->passes()) {
            $datas = Excel::toArray(new ImportDataBien, $request->file('file'));
            $indice = 0;
            if (count($datas[0]) <= 1) {
                toastr()->error("Veuillez remplir votre fichier s'il vous pait.");
                return redirect()->back();
            }
            foreach ($datas as $data) {
                foreach ($data as $list) {
                    if ($indice != 0) {
                        if ($list[0] == "" || $list[1] == "" || $list[2] == "" || $list[3] == "" || $list[8] == "") {
                            toastr()->error("Veuillez remplir les champs requis de votre fichier s'il vous pait.");
                            return redirect()->back();
                        }
                        $getType = DB::table('property_types')->where('property_type', 'LIKE', '%' . $list[1] . '%')->first();
                        if ($getType) {
                            $type = $getType->id;
                        } else {
                            // Type appartement par defaut s'il n'y a pas de type correspondant dans la base
                            $type = 4;
                        }

                        $fullAdress = file_get_contents('https://api.geoapify.com/v1/geocode/search?text=' . $list[8] . '&lang=en&limit=5&format=json&apiKey=67cdc751af0945ecbd21621cbb483d83');
                        $test = json_decode($fullAdress);

                        if (count($test->results) > 0) {
                            $adress = $test->results[0]->formatted;
                            $longitude = $test->results[0]->lon;
                            $latitude = $test->results[0]->lat;
                        } else {
                            $adress = "Paris, IDF, France";
                            $longitude = 2.3483915;
                            $latitude = 48.8534951;
                        }
                        $logement = [
                            'user_id' => Auth::id(),
                            'property_type_id' => $type,
                            'identifiant' => $list[0],
                            'adresse' => $adress,
                            'latitude' => $longitude,
                            'longitude' => $latitude,
                            'superficie' => $list[2],
                            'loyer' => $list[3],
                            'charge' => $list[4],
                            'nbr_chambre' => $list[6],
                            'nbr_piece' => $list[5],
                            'annee_construction' => $list[7],
                        ];

                        Logement::create($logement);
                    }
                    $indice += 1;
                }
            }
        } else {
            toastr()->error("Veuillez suivre l'exemple sur le tableau de modèle en haut et de remplir tel qu'il est.");
            return redirect()->back();
        }
        $propertyType = DB::table('property_types')->where('id', $type)->first();
        toastr()->success("Votre logement est bien importé avec success");
        return redirect()->route('proprietaire.listLogements', ['propertyType' => $propertyType->property_type, 'idPropertyType' => encrypt($propertyType->id)]);
    }

    public function downloadExempleImportBien()
    {
        $pathToFile = public_path('Bailti-exemple-import-bien.xlsx');
        $headers    = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        $fileName   = 'Bailti-exemple-import-bien.xlsx';
        return response()->download($pathToFile, $fileName, $headers);
    }

    public function downloadExempleImportBienOds()
    {
        $pathToFile = public_path('Bailti-exemple-import-bien.ods');
        $headers    = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        $fileName   = 'Bailti-exemple-import-bien.ods';
        return response()->download($pathToFile, $fileName, $headers);
    }

    public function downloadExempleImportBienCsv()
    {
        $pathToFile = public_path('Bailti-exemple-import-bien.csv');
        $headers    = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        $fileName   = 'Bailti-exemple-import-bien.csv';
        return response()->download($pathToFile, $fileName, $headers);
    }

    public function listChambre()
    {
        $user_id = Auth::id();
        /*** le property_types existe deja dans la base, et elle n'a pas de model alors on utilise la jointure ***/
        $listChambre = Logement::where('user_id', $user_id)->where('archive', 0)->where('property_type_id', '=', 2)->with('files', 'locations.Locataire')
            ->join('property_types', 'property_types.id', '=', 'logements.property_type_id')
            ->select('logements.*', 'property_types.property_type')->get();
        /*** Liste des logements archivés ***/
        $listChambreArchive = Logement::where('user_id', $user_id)->where('archive', 1)->with('files', 'locations')
            ->join('property_types', 'property_types.id', '=', 'logements.property_type_id')
            ->select('logements.*', 'property_types.property_type')->get();
        /*** Liste des chambres en location ***/
        $listChambreLocations = Location::where('user_id', $user_id)
            ->whereHas('Logement', function ($query) {
                $query->where('property_type_id', 2);
            })
            ->with('Logement')
            ->get();

        $valeurLocative = 0;
        $valeurActifs = 0;
        $listIdLogement = array();

        foreach ($listChambreLocations as $list) {
            /*** Verification de nombre de logement ***/
            if (!in_array($list->Logement->id, $listIdLogement)) {
                $valeurLocative += $list->Logement->loyer;
                /*** Recuperation de prix d'acquisition sur l'info complementaire de logement ***/
                $infoComplementaireLogement = InfoComplementaireLogement::where('logement_id', $list->Logement->id)->first();
                if ($infoComplementaireLogement) {
                    if (!is_null($infoComplementaireLogement->prix_acquisition)) {
                        $valeurActifs += $infoComplementaireLogement->prix_acquisition;
                    }
                }
                array_push($listIdLogement, $list->Logement->id);
            }
        }

        $countChambreLocation = count($listIdLogement);
        $logementType = $this->master->getMasters('property_types');

        return view('proprietaire.listChambre', compact('listChambre', 'listChambreArchive', 'countChambreLocation', 'logementType', 'valeurLocative', 'valeurActifs'));
    }

    public function addLogementEnfant($idLogementMere)
    {
        $logementMere = Logement::where('id', $idLogementMere)->first();
        $listChambreSansMere = Logement::where('user_id', Auth::id())->where('logement_id', 0)->where('property_type_id', 2)->get();

        return view('proprietaire.addLogementEnfant', compact('logementMere', 'listChambreSansMere'));
    }

    public function saveLogementEnfant(Request $request, $idLogementMere)
    {
        if ($request->logementEnfants) {
            foreach ($request->logementEnfants as $logementEnfant) {
                Logement::where('id', $logementEnfant)->update(['logement_id' => $idLogementMere]);
            }
            toastr()->success('L\'ajout du chambre sur votre logement est bien enregistré avec succès'); //__("logement.ajout-chambre-sur-logement-success")
            return redirect()->route('proprietaire.detail', ['logementId' => $idLogementMere]);
        } else {
            toastr()->error('Veillez selectinner une chambre s\'il vous plait!'); //__("logement.selectionner-chambre-error")
            return redirect()->back();
        }
    }

    public function deleteLogementEnfant($idLogement)
    {
        Logement::where('id', $idLogement)->update(['logement_id' => 0]);
        $logement = Logement::where('id', $idLogement)->first();
        toastr()->success('Vous avez enlevé la chambre ' . $logement->identifiant . ' de son appartement'); //__("logement.vous-elevez-chambre")  __("logement.de-son-appartement")
        return redirect()->back();
    }

    public function creatChambreInLogement($idLogementMere)
    {
        $logementMere = Logement::where('id', $idLogementMere)->first();

        $logementType = $this->master->getMasters('property_types');
        $listEquipements = $this->master->getMasters('list_equipements');
        /*** Recuperation de la liste de type de contrat et diagnostic***/
        $listeTypeContractDiagnostic = TypeContratDiagnostic::all();
        /*** Recuperation liste des categorie de contact ***/
        $categorieContact = CategorieContact::orderBy('name', 'asc')->get();

        /*** Recuperation liste des contacts sur le logement mere s'il y en a ***/
        if ($logementMere->unique_id_contact != null) {
            $dataContacts = ContactLogement::where('unique_id_contact', $logementMere->unique_id_contact)->get();
        } else {
            $dataContacts = null;
        }

        /*** Recuperation liste des contrats et diagnostic sur le logement mere s'il y en a ***/
        if ($logementMere->unique_id_contrat_diagnostique != null) {
            $dataContratDiagnostics = ContratDiagnosticLogement::with('typeContratDiagnostic')->where('unique_contrat_diagnostic', $logementMere->unique_id_contrat_diagnostique)->get();
        } else {
            $dataContratDiagnostics = null;
        }

        return view('proprietaire.nouveaux', compact('logementMere', 'logementType', 'listEquipements', 'categorieContact', 'dataContacts', 'listeTypeContractDiagnostic', 'dataContratDiagnostics'));
    }

    public function chargeList(Request $request)
    {
        $id = $request->id;
        $charge = DB::table('logements')->where('id', $id)->get();
        return response()->json($charge);
    }

    public function deleteLogement($idLogement)
    {
        $verifLocation = Location::where('logement_id', $idLogement)->first();
        if ($verifLocation) {
            toastr()->error('Ce logement est encore en location. Veillez supprimer d\'abord la location.');
            //__("logement.suppression-error-logement-en-location")
        } else {

            /*** Suppression des logements enfants s'il y en a ***/
            $listLogementEnfants = Logement::where('id', $idLogement)->with('logementEnfants')->first();

            if (count($listLogementEnfants->logementEnfants) != 0) {
                foreach ($listLogementEnfants->logementEnfants as $logementEnfants) {
                    $verifLocationEnfant = Location::where('logement_id', $logementEnfants->id)->first();
                    if ($verifLocationEnfant) {
                        toastr()->error('Il y a de logement enfant en location. Veuillez supprimer d\'abord la location du chambre dans ce logement.');
                        //__("logement.suppression-error-logementEnfant-en-location")
                        return redirect()->back();
                    } else {
                        $this->deleteInfoComplementaireLogement($logementEnfants->id);
                        Logement::where('id', $logementEnfants->id)->delete();
                        $logement_en_corbeille = Logement::withTrashed()->findOrFail($logementEnfants->id);
                        Corbeille('Logement', 'logements', $logement_en_corbeille->identifiant, $logement_en_corbeille->deleted_at, $logement_en_corbeille->id);
                    }
                }
            }

            $this->deleteInfoComplementaireLogement($idLogement);

            try {
                Logement::where('id', $idLogement)->delete();
                $logement_en_corbeille = Logement::withTrashed()->findOrFail($idLogement);
                Corbeille('Logement', 'logements', $logement_en_corbeille->identifiant, $logement_en_corbeille->deleted_at, $logement_en_corbeille->id);
            } catch (\Throwable $th) {
                toastr()->error("Il y a une erreur lors de la suppression d'un logement. Veuillez réessayer s'il vous plaît !");
            }


            toastr()->success('Votre logement a été bien supprimée!');
            //__("logement.suppression-success-logement")
            //Session::flash('succes','Votre logement a été bien supprimée!');
        }

        return redirect()->back();
    }

    public function deleteInfoComplementaireLogement($idLogement)
    {
        $logement = Logement::find($idLogement);
        /***Suppression des contacts ***/
        if ($logement->unique_id_contact) {
            ContactLogement::where('unique_id_contact', $logement->unique_id_contact)->delete();
        }

        /*** Supression des fichiers images sur le disk ***/
        $listeImgs = LogementFile::where('logement_id', $idLogement)->get();
        if ($listeImgs) {
            foreach ($listeImgs as $listeImg) {
                File::delete(base_path() . '/storage/uploads/images_annonces/' . $listeImg->file_name);
            }
        }

        /*** Suppression des contrats ***/
        if ($logement->unique_id_contrat_diagnostique) {
            $contratDiagnosticLogement = ContratDiagnosticLogement::where('unique_contrat_diagnostic', $logement->unique_id_contrat_diagnostique)->get();
            foreach ($contratDiagnosticLogement as $contrat) {
                if ($contrat->document) {
                    /*** Suppression de document de contrat ***/
                    File::delete(base_path() . '/storage/uploads/document_logements/' . $contrat->document);
                    ContratDiagnosticLogement::find($contrat->id)->delete();
                }
            }
        }
    }

    public function deleteLogementMultiple(Request $request)
    {
        $nbrLogementLocation = 0;
        foreach ($request->data_id as $logement_id) {
            $verifLocation = Location::where('logement_id', $logement_id)->first();
            if ($verifLocation) {
                $nbrLogementLocation += 1;
            } else {
                /*** Suppression des logements enfants s'il y en a ***/
                $listLogementEnfants = Logement::where('id', $logement_id)->with('logementEnfants')->first();

                if (count($listLogementEnfants->logementEnfants) != 0) {
                    $nbrChambreLocation = 0;
                    foreach ($listLogementEnfants->logementEnfants as $logementEnfants) {
                        $verifLocationEnfant = Location::where('logement_id', $logementEnfants->id)->first();
                        if ($verifLocationEnfant) {
                            $nbrChambreLocation++;
                        }
                    }
                    if ($nbrChambreLocation != 0) {
                        return response()->json(['error' => true, 'message' => 'Il y a de logement enfant en location. Veuillez supprimer d\'abord la location du chambre dans ce logement.'], 400);
                    } else {
                        foreach ($listLogementEnfants->logementEnfants as $logementEnfants) {
                            $verifLocationEnfant = Location::where('logement_id', $logementEnfants->id)->first();
                            if (!$verifLocationEnfant) {
                                $this->deleteInfoComplementaireLogement($logementEnfants->id);
                                Logement::where('id', $logementEnfants->id)->delete();
                            }
                        }
                    }
                }
                /*** Suppression de logement ***/
                $this->deleteInfoComplementaireLogement($logement_id);

                /* ajout de l'information dans la corbeille  */
                Logement::where('id', $logement_id)->delete();
                $logement_en_corbeille = Logement::withTrashed()->findOrFail($logement_id);
                Corbeille('Logement', 'logements', $logement_en_corbeille->identifiant, $logement_en_corbeille->deleted_at, $logement_en_corbeille->id);
            }
        }

        if ($nbrLogementLocation == 0) {
            return response()->json(['success' => true, 'message' => 'La liste de logement que vous avez selectionné a été bien supprimé'], 200);
        } else {
            return response()->json(['error' => true, 'message' => 'Il y a ' . $nbrLogementLocation . ' logements en location, Veillez supprimer d\'abord la location'], 400);
        }
    }

    public function deleteImage(Request $request)
    {
        if ($request->id) {
            /*** recuperer l'information d'image dans la base ***/
            $logementFile = LogementFile::find($request->id);
            /*** suppression du fichier image ***/
            $destinationPathImages = base_path() . '/storage/uploads/images_annonces/' . $logementFile->file_name;
            File::delete($destinationPathImages);
            /*** suppression d'information d'image dans la base ***/
            $logementFile->delete();
        }
        return response()->json();
    }

    public function uploadImageLogement(Request $request)
    {

        $dataImg = $request->file('file_photos');

        foreach ($dataImg as $img) {
            //$img->store(base_path() . '/storage/uploads/images_annonces/');
            /*** Url pour stocké les images ***/
            $test = storage::url('uploads/images_annonces/');
            $destinationPathImages = base_path() . '/storage/uploads/images_annonces/';
            /*** Renomer la photo ***/
            $file_name = rand(999, 99999) . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
            $extention = $img->getClientOriginalExtension();
            /*** Nom original du photo ***/
            $user_filename = $img->getClientOriginalName();
            /*** Déplace la photo dans le dossier images_annonce ***/
            $img->move($destinationPathImages, $file_name);
            /*** place le logo du bailti sur la photo ***/
            pasteLogo($destinationPathImages . $file_name);
            $size = filesize($destinationPathImages . $file_name);
            if ($size > 40000) {
                compressImage($destinationPathImages . $file_name, removeExtension($file_name), $destinationPathImages, 65, 9);
            }
            $adFiles = new LogementFile();
            // $adFiles->unique_id_file = $unique_id_file;
            $adFiles->file_name = $file_name;
            $adFiles->user_filename = $user_filename;
            $adFiles->media_type = "0";
            $adFiles->ordre = 1;
            $adFiles->size = $size;
            $adFiles->save();
        }
        return response()->json([
            'initialPreview' => [URL::asset('uploads/images_annonces/' . $file_name)],
            'initialPreviewConfig' => [
                [
                    'caption' => $user_filename,
                    'url' => route('proprietaire.deleleteImageLogement', $adFiles->id),
                    'key' => $adFiles->id,
                    'extra' => [
                        'id' => $adFiles->id,
                        'file_name' => $user_filename,
                        'type' => "deleted"
                    ]
                ],
            ],
        ]);
    }

    public function deleteContactLogement($id_contact)
    {
        $contactLogement = ContactLogement::find($id_contact);
        if ($contactLogement) {
            $contactLogement->delete();
            return response()->json(['success' => true, 'message' => 'Contact bien supprimer']);
        } else {
            $messageAlreadyDelete = "Ce contact n'existe plus ou déjà supprimer";
            return response()->json(['error' => true, 'message' => $messageAlreadyDelete]);
        }
    }

    public function deleteContratDiagnostic($idContratDiagnostic)
    {
        $contratDiagnostic = ContratDiagnosticLogement::find($idContratDiagnostic);

        if ($contratDiagnostic) {
            /*** Suppression de fichier dans le dossier document_logements s'il y a des documents ***/
            if ($contratDiagnostic->document) {
                File::delete(base_path() . '/storage/uploads/document_logements/' . $contratDiagnostic->document);
            }
            $contratDiagnostic->delete();
            return response()->json(['success' => true, 'message' => 'Contact bien supprimer'], 200); //__("logement.suppression-contact-success")
        } else {
            $messageAlreadyDelete = "Ce contrat n'existe plus ou déjà supprimer"; //__("logement.contrat-pas-existe")
            return response()->json(['error' => true, 'message' => $messageAlreadyDelete], 400);
        }
    }

    public function saveContratDiagnostic(Request $request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            // 'type_contract' => 'required',
            'description' => 'required',
            'document' => 'required|file',
            // 'date_establishment' => 'required',
            // 'due_date' => 'required',
        ]);
        if ($validator->passes()) {
            /*** insertion du formulaire de contrat et diagnostic ***/
            $contratDiagnostic = ContratDiagnosticLogement::updateOrCreate(
                [
                    'id' => $id,
                ],
                [
                    'type_contrat_diagnostic_id' => $request->type_contract,
                    'description' => $request->description,
                    'date_establishment' => $request->date_establishment,
                    'due_date' => $request->due_date,
                    'user_id' => Auth::id(),

                    // 'unique_contrat_diagnostic' => $unique_id_contrat_diagnostique,
                ]
            );
            /*** Recuperation de fichier venant du formulaire ***/
            $dataDocument = $request->file('file_contrat');
            if ($dataDocument) {
                $Size =  $dataDocument->getSize();
                /*** Url pour stocké les documents ***/
                $destinationPathDocument = base_path() . '/storage/uploads/document_logements/';
                $document_original_name = $dataDocument->getClientOriginalName();
                $document = rand(999, 99999) . '_' . uniqid() . '.' . $dataDocument->getClientOriginalExtension();
                $dataDocument->move($destinationPathDocument, $document);
                ContratDiagnosticLogement::where('id', $contratDiagnostic->id)->update([
                    'document' => $document,
                    'document_original_name' => $document_original_name,
                    'size' => $Size
                ]);
                // $idLogement = '';
                // $Path = 'uploads/document_logements/';
                // save_document($idLogement, $document, $Path, $document_original_name,$Size);
            }

            $contratDiagnostic = ContratDiagnosticLogement::with('typeContratDiagnostic')->where('id', $contratDiagnostic->id)->first();

            return response()->json($contratDiagnostic, 200);
        } else {
            return response()->json(['error' => true, 'message' => $validator->errors()], 400);
        }
    }

    public function saveContactLogement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required',
            'adress' => 'required',
            'ville' => 'required',
            'email' => 'email'
        ]);

        if ($validator->passes()) {
            $contactLogement = new ContactLogement();
            $contactLogement->user_id = Auth::id();
            $contactLogement->contact_logement_id = $request->categorie;
            $contactLogement->type_conctact_logement = $request->type;
            $contactLogement->name = $request->name;
            if ($request->first_name) {
                $contactLogement->first_name = $request->first_name;
            } else {
                $contactLogement->first_name = "";
            }
            $contactLogement->email = $request->email;
            $contactLogement->mobile = $request->mobile;
            $contactLogement->adress = $request->adress;
            $contactLogement->ville = $request->ville;
            if ($request->code_postal) {
                $contactLogement->code_postal = $request->code_postal;
            } else {
                $contactLogement->code_postal = "";
            }
            if ($request->pays) {
                $contactLogement->pays = $request->pays;
            } else {
                $contactLogement->pays = "";
            }
            if ($request->comment) {
                $contactLogement->comment = $request->comment;
            } else {
                $contactLogement->comment = "";
            }
            $contactLogement->save();

            return response()->json($contactLogement, 200);
        } else {
            return response()->json(['error' => true, 'message' => $validator->errors()], 400);
        }
    }

    public function updateLogement(Request $request, $idLogement)
    {
        //dd($request->file_photos);
        $validator = Validator::make($request->all(), [
            'property_type_id' => 'required',
            'identifiant' => 'required',
            'adresse' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'loyer' => 'required',
            'superficie' => 'required'
        ]);
        if ($validator->passes()) {

            /*** Modification au niveau du logement ***/
            Logement::where('id', $idLogement)->update([
                'property_type_id' => $request->property_type_id,
                'identifiant' => $request->identifiant,
                'adresse' => $request->adresse,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'batiment' => $request->batiment,
                'escalier' => $request->escalier,
                'etage' => $request->etage,
                'numero' => $request->numero,
                'type_location' => $request->type_location,
                'loyer' => $request->loyer,
                'charge' => $request->charge,
                'superficie' => $request->superficie,
                'nbr_chambre' => $request->nbr_chambre,
                'nbr_piece' => $request->nbr_piece,
                'salle_bain' => $request->salle_bain,
                'description' => $request->description_logement,
                'note_privee' => $request->note_privee,
                'annee_construction' => $request->annee_construction,
            ]);

            /*** Modification sur le type d'habitat, coproprietaire et autre dependance***/
            if (
                !is_null($request->type_habitat) || !is_null($request->coproprietaire) || !is_null($request->autre_dependance) || !is_null($request->taxe_habitation) || !is_null($request->taxe_fonciere)
                || !is_null($request->prix_acquisition) || !is_null($request->frais_acquisition) || !is_null($request->valeur_actuel)
            ) {
                $data = [
                    'type_habitat' => $request->type_habitat,
                    'coproprietaire' => $request->coproprietaire,
                    'autre_dependance' => $request->autre_dependance,
                    'taxe_habitation' => $request->taxe_habitation,
                    'taxe_fonciere' => $request->taxe_fonciere,
                    'date_acquisition' => $request->date_acquisition,
                    'prix_acquisition' => $request->prix_acquisition,
                    'frais_acquisition' => $request->frais_acquisition,
                    'valeur_actuel' => $request->valeur_actuel,
                ];
                /*** Faire une update ou insertion s'il n'y a pas encore de l'information complementaire ***/
                $infoC = InfoComplementaireLogement::firstOrNew(['logement_id' => $idLogement]);
                $infoC->fill($data);
                $infoC->save();
            }

            /*** Modification au niveau de l'equipement  ***/
            if ($request->equipements) {
                Equipement::where('logement_id', $idLogement)->delete();
                foreach ($request->equipements as $key => $name) {
                    Equipement::create(['logement_id' => $idLogement, 'equipement' => $request->equipements[$key]]);
                }
            }

            /*** Modification des images ***/
            if ($request->file_ids) {
                $ids = explode(",", $request->file_ids);
                foreach ($ids as $id) {
                    $this->updateFile($idLogement, $id);
                }
            }

            /*** Ajout de contrat et diagnostic sur modification ***/
            $dataIdContrat = explode(",",$request->idContratDiagnostic[0]);
            foreach($dataIdContrat as $idContrat){
                if($idContrat){
                    ContratDiagnosticLogement::where('id',$idContrat)->update(['logement_id'=>$idLogement]);
                }
            }

            /*** Ajout de contact ***/
            $dataIdContact = explode(",",$request->idContact[0]);
            foreach($dataIdContact as $idContact){
                if($idContact){
                    ContactLogement::where('id',$idContact)->update(['logement_id'=>$idLogement]);
                }
            }


            $propertyType = DB::table('property_types')->where('id', $request->property_type_id)->first();

            toastr()->success('Votre logement a été modifié'); //__("logement.modifier-logement-success")
            return redirect()->route('proprietaire.listLogements', ['propertyType' => $propertyType->property_type, 'idPropertyType' => encrypt($propertyType->id)]);
        } else {
            toastr()->error('Il y a une erreur, veillez vérifier les champs obligatoires s\' il vous plait!');
            //__("logement.modifier-logement-error")
            //Session::flash('error','Il y a une erreur, veillez vérifier les champs obligatoires s\' il vous plait!');
            return back()->withErrors($validator)->withInput();
        }
    }

    public function saveLogement(Request $request)
    {
        $user_id = Auth::id();
        $newlogement = new Logement();
        $validator =  Validator::make($request->all(), [
            'property_type_id' => 'required',
            'identifiant' => 'required',
            'adresse' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'loyer' => 'required',
            'superficie' => 'required'
        ]);

        if ($validator->passes()) {

            if ($request->logement_mere_id) {
                $newlogement->logement_id = $request->logement_mere_id;
            }
            $newlogement->user_id = $user_id;
            $newlogement->property_type_id = $request->property_type_id;
            $newlogement->identifiant = $request->identifiant;
            $newlogement->adresse = $request->adresse;
            $newlogement->latitude = $request->latitude;
            $newlogement->longitude = $request->longitude;
            $newlogement->batiment = $request->batiment;
            $newlogement->escalier = $request->escalier;
            $newlogement->etage = $request->etage;
            $newlogement->numero = $request->numero;
            $newlogement->type_location = $request->type_location;
            $newlogement->loyer = $request->loyer;
            $newlogement->charge = $request->charge;
            $newlogement->superficie = $request->superficie;
            $newlogement->nbr_chambre = $request->nbr_chambre;
            $newlogement->nbr_piece = $request->nbr_piece;
            $newlogement->salle_bain = $request->salle_bain;
            $newlogement->description = $request->description_logement;
            $newlogement->note_privee = $request->note_privee;
            $newlogement->annee_construction = $request->annee_construction;
            $newlogement->save();
            $idLogement = $newlogement->id;

            /*** Ajout de contrat et diagnostic ***/
            $dataIdContrat = explode(",",$request->idContratDiagnostic[0]);
            foreach($dataIdContrat as $idContrat){
                if($idContrat){
                    ContratDiagnosticLogement::where('id',$idContrat)->update(['logement_id'=>$idLogement]);
                }
            }

            /*** Ajout de contact ***/
            $dataIdContact = explode(",",$request->idContact[0]);
            foreach($dataIdContact as $idContact){
                if($idContact){
                    ContactLogement::where('id',$idContact)->update(['logement_id'=>$idLogement]);
                }
            }

            $liste_contract = ContratDiagnosticLogement::where('logement_id', $idLogement)->get();
            foreach ($liste_contract as $item) {
                if (!is_null($item->document)) {
                    $Path = 'uploads/document_logements/';
                    save_document($idLogement, $item->document, $Path, $item->document_original_name, $item->size);
                }
            }

            if ($newlogement && $request->file_ids) {
                $ids = explode(",", $request->file_ids);
                foreach ($ids as $id) {
                    $this->updateFile($newlogement->id, $id);
                }
            }

            if (
                $request->type_habitat or $request->coproprietaire or $request->autre_dependance || !is_null($request->taxe_habitation) || !is_null($request->taxe_fonciere)
                || !is_null($request->prix_acquisition) || !is_null($request->frais_acquisition) || !is_null($request->valeur_actuel)
            ) {
                /*** Enregistrement de l'information complementaire ***/
                InfoComplementaireLogement::create([
                    'logement_id' => $idLogement,
                    'type_habitat' => $request->type_habitat,
                    'coproprietaire' => $request->coproprietaire,
                    'autre_dependance' => $request->autre_dependance,
                    'taxe_habitation' => $request->taxe_habitation,
                    'taxe_fonciere' => $request->taxe_fonciere,
                    'date_acquisition' => $request->date_acquisition,
                    'prix_acquisition' => $request->prix_acquisition,
                    'frais_acquisition' => $request->frais_acquisition,
                    'valeur_actuel' => $request->valeur_actuel,
                ]);
            }
            if ($request->equipements) {
                foreach ($request->equipements as $key => $name) {
                    /*** Enregistrement d'equipement du logement ***/
                    Equipement::create(['logement_id' => $idLogement, 'equipement' => $request->equipements[$key]]);
                }
            }
            //Session::flash('succes','Votre logement a été bien enregistré, vous pouvez completer l\'information de votre logement sur information complementaire.');

            $propertyType = DB::table('property_types')->where('id', $newlogement->property_type_id)->first();
            $user = Auth::user();

            if ($request->logement_mere_id) {
                $logement_mere = Logement::where('id', $request->logement_mere_id)->first();
                $propertyType = DB::table('property_types')->where('id', $logement_mere->property_type_id)->first();
                if ($user->need_guide) {
                    $user->update([
                        'owner_step' => 2
                    ]);
                    return redirect()->route('proprietaire.bureau');
                }
                toastr()->success('La chambre a été ajouté avec succès sur votre bien');
                //__("logement.ajouter-chambre-success")
                return redirect()->route('proprietaire.listLogements', ['propertyType' => $propertyType->property_type, 'idPropertyType' => encrypt($propertyType->id)])->with('idLogement', $idLogement);
            } else {
                if ($user->need_guide) {
                    $user->update([
                        'owner_step' => 2
                    ]);
                    return redirect()->route('proprietaire.bureau');
                }
                toastr()->success('Votre logement a été bien enregistré, vous pouvez completer l\'information de votre logement sur information complementaire.');
                //__("logement.ajouter-logement-success")
                return redirect()->route('proprietaire.listLogements', ['propertyType' => $propertyType->property_type, 'idPropertyType' => encrypt($propertyType->id)])->with('idLogement', $idLogement);
            }
        } else {
            //__("logement.ajouter-logement-error")
            Session::flash('error', 'Il y a une erreur, veillez vérifier les champs obligatoires s\' il vous plait!');
            return back()->withErrors($validator)->withInput();
        }
    }

    public function chambreInLogement(Request $request)
    {
        $logement = Logement::where('id', $request->data_id)->with('logementEnfants')->first();

        if (count($logement->logementEnfants) > 0) {
            $listCambre = $logement->logementEnfants;
            return response()->json($listCambre);
        } else {
            return response()->json(['error' => true, 'message' => 'Ce logement n\'a pas encore de chambre', 'idLogementMere' => $request->data_id], 400);
            //__("logement.logement-pas-de-chambre")
        }
    }

    public function updateContactLogement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_modif' => 'required',
            'name_modif' => 'required',
            'mobile_modif' => 'required',
            'adress_modif' => 'required',
            'ville_modif' => 'required',
            'email_modif' => 'email',
        ]);
        if ($validator->passes()) {
            ContactLogement::where('id', $request->id_modif)
                ->update([
                    'type_conctact_logement' => $request->type_modif,
                    'name' => $request->name_modif,
                    'first_name' => $request->first_name_modif,
                    'email' => $request->email_modif,
                    'mobile' => $request->mobile_modif,
                    'adress' => $request->adress_modif,
                    'ville' => $request->ville_modif,
                    'code_postal' => $request->code_postal_modif,
                    'pays' => $request->pays_modif,
                    'comment' => $request->comment_modif,
                ]);
            $contactLogement = ContactLogement::where('id', $request->id_modif)->first();
            // return response()->json(['success'=>true,'message'=>'Contact bien modifier','contactLogement'=>$contactLogement]);
            return response()->json($contactLogement);
        } else {
            return response()->json(['error' => true, 'message' => $validator->errors()], 400);
        }
    }

    public function editlogement($idLogement)
    {
        /***  Recuperation de donnée du logement avec l'information complementaire ***/
        $detailLogement = Logement::where('id', $idLogement)->with('files')->with('infoComplementaireLogement')->first();

        /*** Recuperation de l'equipement du logement ***/
        $getEquipements = Equipement::where('logement_id', $idLogement)->get();
        $equipements = array();
        foreach ($getEquipements as $eqmt) {
            /** Ajouter tous la valeur des equipement dans un tableau equipements */
            array_push($equipements, $eqmt->equipement);
        }

        /*** Recuperation liste des categorie de contact ***/
        $categorieContact = CategorieContact::orderBy('name', 'asc')->get();

        $logementType = $this->master->getMasters('property_types');
        $listEquipements = $this->master->getMasters('list_equipements');

        /*** Liste de Type Contract et Diagnostic ***/
        $listeTypeContractDiagnostic = TypeContratDiagnostic::all();

        /*** Liste des contrats et diagnostic de logement ***/
        $contratDiagEdits = ContratDiagnosticLogement::with('typeContratDiagnostic')->where('logement_id', $idLogement)->get();

        /*** Liste des contats de logement ***/
        $listContactsEdits = ContactLogement::where('logement_id', $idLogement)->get();

        return view('proprietaire.nouveaux', compact('detailLogement', 'logementType', 'equipements', 'listEquipements', 'categorieContact', 'listeTypeContractDiagnostic', 'contratDiagEdits', 'listContactsEdits'));
    }

    public function genererAnnonceLogement($idLogement)
    {
        //Obtenir l'information sur le logement
        $logement = Logement::where('id', $idLogement)->with('files')->first();

        //Verification si le logement est déjà publié
        if ($logement->publish == 1) {
            toastr()->error("Votre logement est déjà publié, veillez verifier sur votre tableau de bord !");
            // __("logement.logement-deja-publie")
            return back();
        }

        //insertion de l'annonce du logement sur Ads
        $ads = new Ads();

        $ads->user_id = $logement->user_id;
        $ads->title = $logement->identifiant;
        //la description ici c'est temporaire, on change avec la phrase ideal et avec la traduction apres
        $ads->description = "Je loue mon logement à " . $logement->adresse . $logement->description . "Merci de me contacté si vous etes interessés";
        //  __("logement.je-loue-logement")  __("logement.merci-de-me-contacte")
        $ads->address = $logement->adresse;
        $ads->latitude = $logement->latitude;
        $ads->longitude = $logement->longitude;
        $ads->scenario_id = 1;
        $ads->status = '1';
        $ads->complete = 1;
        $ads->admin_approve = '1';
        $ads->available_date = date('Y-m-d');
        $ads->url_slug = str_slug($logement->identifiant, '-');
        $ads->min_rent = save_conversion_devise($logement->loyer);
        $ads->save();
        $ad_id = $ads->id;

        //insertion d'autre information du logement sur adDetail
        $adDetail = new AdDetails();
        $adDetail->ad_id = $ads->id;
        $adDetail->property_type_id  = $logement->property_type_id;
        $adDetail->min_surface_area  = $logement->superficie;
        $adDetail->furnished  = $logement->type_location;
        $adDetail->budget  = save_conversion_devise($logement->loyer);
        $adDetail->save();

        //insertion d'image de logement sur l'annonce s'il y a des images
        if (count($logement->files) > 0) {
            foreach ($logement->files as $file) {
                $adFiles = new AdFiles();

                //enretrement sur addFile
                $adFiles->ad_id = $ads->id;
                $adFiles->filename = $file->file_name;
                $adFiles->user_filename = $file->user_filename;
                $adFiles->media_type = $file->media_type;
                $adFiles->ordre = $file->ordre;
                $adFiles->save();
            }
        }

        /*** Update de logement car le logement est déjà publié ***/
        Logement::where('id', $idLogement)->update(["publish" => 1]);

        //Pour generer l'url de l'annonce crée
        $urlAds = adUrl($ads->id);
        Logement::where('id', $idLogement)->update(["ads_id" => $ads->id]);
        Logement::where('id', $idLogement)->update(["link_ads" => $urlAds]);
        Session::flash('succes', "Votre logement a bien été publié");
        // __("logement.logement-publie-success")
        toastr()->success("Votre logement a bien été publié");
        // __("logement.logement-publie-success")

        return redirect()->back()->with(['urlAds' => $urlAds]);
        //dd($ads->id);
    }

    public function detailLogement($logementId)
    {
        $detailLogement = Logement::where('id', $logementId)->with('files')->first();
        /*** Recuper le type de proprieté du logement ***/
        $typeLogement = DB::table('property_types')->where('id', $detailLogement->property_type_id)->first();
        /*** Recuperer l'id de l'equipement du logement ***/
        $datailEquipement = Equipement::where('logement_id', $logementId)->get();
        $listEquipements = array();
        if ($datailEquipement) {
            foreach ($datailEquipement as $idEqpmt) {
                $equipement = ListEquipement::where('id', $idEqpmt->equipement)->first()->toArray();
                /*** Recuperation de liste des equipements ***/
                array_push($listEquipements, $equipement);
            }
        }
        $listLogementEnfants = Logement::where('id', $logementId)->with('logementEnfants')->first();
        $listLogementEnfant = $listLogementEnfants->logementEnfants;

        /* recuperer les locataire actif s'il y en a  */
        $locataireDuLogement = Location::select('locataires_general_informations.*', 'locations_proprietaire.fin as finBail', 'locations_proprietaire.debut as debutBail')->join('locataires_general_informations', 'locations_proprietaire.locataire_id', 'locataires_general_informations.id')->where('locations_proprietaire.depart', 0)->where('locations_proprietaire.logement_id', $logementId)->get();
        $documents = Documents::where('logement_id', $logementId)->get();

        $document_sans_dossier = [];
        $document_dossier = [];

        foreach ($documents as $doc) {
            $dossier = $doc->dossier()->first();
            if ($dossier && isset($document_dossier[$dossier->nom])) {
                $document_dossier[$dossier->nom][] = $doc;
                continue;
            } else if ($dossier && !isset($document_dossier[$dossier->nom])) {
                $temp = [];
                $temp[] = $doc;
                $document_dossier[$dossier->nom] = $temp;
                continue;
            }
            $document_sans_dossier[] = $doc;
        }

        return view('proprietaire.detailLogement', compact('detailLogement', 'typeLogement', 'listEquipements', 'listLogementEnfant', 'locataireDuLogement', 'document_sans_dossier', 'document_dossier'));
    }

    public function nouveauLogement()
    {
        $logementType = $this->master->getMasters('property_types');
        $listEquipements = $this->master->getMasters('list_equipements');
        /*** Recuperation de la liste de type de contrat et diagnostic***/
        $listeTypeContractDiagnostic = TypeContratDiagnostic::all();
        /*** Recuperation liste des categorie de contact ***/
        $categorieContact = CategorieContact::orderBy('name', 'asc')->get();

        /*** Recuperation liste des contacts en session s'il y a des contacts ajouter durant la session ***/
        if ($unique_id_contact = Session::get('Unique_id_contact')) {
            $dataContacts = ContactLogement::where('unique_id_contact', $unique_id_contact)->get();
        } else {
            $dataContacts = null;
        }

        /*** Recuperation liste des contrats et diagnostic en session s'il y en a durant la session ***/
        // if ($unique_contrat_diagnostic = Session::get('Unique_id_contrat_diagnostique')) {
        //     $dataContratDiagnostics = ContratDiagnosticLogement::with('typeContratDiagnostic')->where('unique_contrat_diagnostic', $unique_contrat_diagnostic)->get();
        // } else {
        //     $dataContratDiagnostics = null;
        // }

        /*** Recuperation liste des photos en session s'il y en a durant la session ***/
        $initialPreview = array();
        $initialPreviewConfig = array();
        if ($unique_id_file = Session::get('unique_id_file')) {
            $listeImgEnSession = LogementFile::where('unique_id_file', $unique_id_file)->get();
            if ($listeImgEnSession) {
                foreach ($listeImgEnSession as $img) {
                    $url = [URL::asset('uploads/images_annonces/' . $img->file_name)];
                    $config = [
                        'caption' => $img->user_filename,
                        'url' => route('proprietaire.deleleteImageLogement', $img->id),
                        'key' => $img->id,
                        'extra' => [
                            'id' => $img->id,
                            'file_name' => $img->user_filename,
                            'type' => "deleted"
                        ]
                    ];
                    array_push($initialPreview, $url);
                    array_push($initialPreviewConfig, $config);
                }
            }
        } else {
            $unique_id_file = null;
        }

        return view('proprietaire.nouveaux', compact('logementType', 'listEquipements', 'categorieContact', 'dataContacts', 'listeTypeContractDiagnostic', 'initialPreview', 'initialPreviewConfig'));
    }

    public function exportInfoLogement(Request $request)
    {
        $data_id = $request->all();
        return response()->json($data_id, 200);
    }

    public function downloadExelLogement($data_id)
    {
        $data = explode(',', base64_decode($data_id));
        $user_id = Auth::user()->first_name;

        return Excel::download(new ExportInfoLogement($data), "Bailti-" . $user_id . "-logement.xlsx");
    }

    public function archiveLogementMultiple($data_id)
    {
        $data = explode(',', base64_decode($data_id));
        foreach ($data as $logement_id) {
            $logement = Logement::find($logement_id);
            if ($logement->archive == 0) {
                /*** Archive logement ***/
                $logement->update(['archive' => 1]);
                $message = 'Votre logement sont bien archivé';
                // __("logement.logement-archive-success")
            } else {
                /*** Désarchive logement ***/
                $logement->update(['archive' => 0]);
                $message = 'Votre logement sont bien désarchivé';
                // __("logement.logement-desarchive-success")
            }
        }
        toastr()->success($message);
        return redirect()->back();
        //return response()->json(['success' => true, 'message'=>$message], 200);
    }

    public function locataireList()
    {
        return view('locataire.locataire');
    }

    public function addLocataire()
    {
        return view('locataire.AjoutLoc');
    }

    public function importLogement()
    {
        return view('proprietaire.import');
    }

    public function downloadModeleExcel()
    {
        $pathToFile = public_path('/modele_import_logement/Bailti-import-logement.xlsx');
        $headers    = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        $fileName   = 'Bailti-import-logement.xlsx';
        return response()->download($pathToFile, $fileName, $headers);
    }

    public function downloadModeleODS()
    {
        $pathToFile = public_path('/modele_import_logement/Bailti-import-logement.ods');
        $headers    = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        $fileName   = 'Bailti-import-logement.ods';
        return response()->download($pathToFile, $fileName, $headers);
    }

    public function downloadModeleCSV()
    {
        $pathToFile = public_path('/modele_import_logement/Bailti-import-logement.csv');
        $headers    = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        $fileName   = 'Bailti-import-logement.csv';
        return response()->download($pathToFile, $fileName, $headers);
    }

    public function searchContact(Request $request)
    {
        // Query the database for the search results.
        $results = ContactLogement::where('user_id', Auth::id())->where('name', 'like', '%' . $request->input('search') . '%')->get();
        return response()->json($results);
    }

    public function getsearchContact($id)
    {
        // Query the database for the search results.
        $results = ContactLogement::where('id', $id)->get();
        return response()->json($results);
    }

    public function updateFile($logement_id, $file_id)
    {
        if ($file_id) {
            $a = LogementFile::where('id', $file_id)->first();
            $a->update([
                'logement_id' => $logement_id
            ]);
        }
        return 'done';
    }

    /*** suppression de Contrats et diagnostics sans logement ***/
    public function deleteContratDiagnosticNoLogement()
    {
        $listContrat = DB::table('contrat_diagnostic_logements')->where('user_id', Auth::id())->where('logement_id',null)->get();

        if($listContrat){
            foreach($listContrat as $contrat){
                // ContratDiagnosticLogement::where('id',$contrat->id)->delete();
                DB::table('contrat_diagnostic_logements')->where('id',$contrat->id)->delete();
                // dd($listContrat);
            }
        }
        return 'done';
    }

    public function mynotification()
    {
        $user_id = Auth::id();
        $ajourdhui = Carbon::now();
        $loyerEnretard = Finance::where('user_id', Auth::id())
            ->with('Location')
            ->whereYear('debut', $ajourdhui->year)->whereMonth('debut', '<=', $ajourdhui->month)
            ->where('Etat', '1')->get();
        $revenue_en_attente = Finance::instance()->getRevenuByStatus(2);
        $revenue_pas_paye =Finance::instance()->getRevenuByStatus(1);
        $ticket_non_lus = Ticket::instance()->getNouveauTicketLocataire();
        return view('proprietaire.notification', compact('loyerEnretard','revenue_en_attente','revenue_pas_paye','ticket_non_lus'));
    }

    public function ready() {
        $user = Auth::user();
        if ($user->need_guide) {
            $user->update([
                'owner_step' => 4,
                'need_guide' => 0
            ]);
        }
        return redirect()->back();
    }
}
