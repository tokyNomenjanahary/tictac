<?php

namespace App\Http\Controllers;

use App\SignatureUsers;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use PDF;
// use Spatie\PdfToText\Pdf;
use Diff;
use toastr;
use Session;
use App\User;
use Exception;
use App\Garant;
use App\revenu;
use App\Depense;
use App\Finance;
use App\Location;
use App\Logement;
use App\Documents;
use Carbon\Carbon;
use App\TempGarant;
use App\ModePayment;
use App\TypePayment;
use App\Document_caf;
use App\NoteLocation;
use App\TypeLocation;
use App\File_location;
use App\AutresPaiement;
use App\Regularisation;
use setasign\Fpdi\Fpdi;
use App\ContactLogement;
use App\backup_locataire;
use App\CategorieContact;
use App\TerminerLocation;
use App\TravauxLocataire;
use Nette\Utils\DateTime;
// use Spatie\PdfToText\Pdf;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use App\TraveauxProprietaire;
use Illuminate\Http\Response;
use PhpOffice\PhpWord\PhpWord;
use App\Exports\ExportLocation;
use App\Imports\LocationImport;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Diff\Differ;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use App\LocatairesGeneralInformations;
use setasign\Fpdi\PdfParser\PdfParser;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Validator;
use setasign\Fpdi\PdfParser\StreamReader;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Month;
use Smalot\PdfParser\Parser;


class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id        = Auth::user()->id;
        $logements      = Logement::where('user_id', $user_id)->get();
        $type_locations = TypeLocation::all();
        $locations      = Location::where('user_id', $user_id)
            ->with(['Locataire', 'Logement', 'typepayement', 'typelocation','garants'])
            ->get();
        // dd($locations);
        $count_active   = Location::where('user_id', $user_id)
            ->where('etat', 0)
            ->get();
        $depot          = Location::where('user_id', $user_id)
            ->sum('garantie');
        // dd($depot);
        $etat_finance   = [];
        foreach ($locations as $location) {
            $finances = Finance::where('location_id', $location->id)
                ->where('type', 'loyer')
                ->get();
            foreach ($finances as $finance) {
                if ($finance->Etat == "Not paid" || $finance->Etat == "Pas payé") {
                    $etat_finance[] = [$finance->Etat, $location->id];
                    break;
                }
            }
        }
        // dd($etat_finance);
        return view('location.location', compact('locations', 'type_locations', 'logements', 'count_active', 'depot', 'etat_finance'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($logement_id = null)
    {
        $user_id        = Auth::user()->id;
        $mode_payments  = ModePayment::all();
        $type_payments  = TypePayment::all();
        // $logements      = Logement::where('user_id',$user_id)->get();
        $logements = Logement::instance()->getListeLogementDisponible($user_id);
        $count_logement = count($logements);
        $type_locations = TypeLocation::all();
        $locataires     = backup_locataire::where('user_id', $user_id)
            ->get();
        $garants = Garant::where('user_id', $user_id)->get();

        return view('location.ajouterLocation', compact('mode_payments', 'type_payments', 'logements', 'type_locations', 'count_logement', 'locataires', 'logement_id', 'garants'));
    }


    public function enregistre(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logement_id'         => 'required|not_in:null',
            'type_location_id'    => 'required|not_in:null',
            'identifiant'         => 'required',
            'debut'               => 'required',
            'fin'                 => 'required',
            'type_payment_id'     => 'required',
            'mode_payment_id'     => 'required',
            'date_payment'        => 'required',
            'loyer_HC'            => 'required',
            'locataire_id'        => 'required',
        ]);
        if ($validator->passes()) {
            // dd($request->all());
            DB::beginTransaction();
            try {
                $temp = TempGarant::where('user_id', Auth::id());
                $temp->delete();
                $locations = Location::create([
                    'logement_id'        => $request->logement_id,
                    'type_location_id'   => $request->type_location_id,
                    'identifiant'        => $request->identifiant,
                    'debut'              => $request->debut,
                    'date_finance'       => date('Y-m-d'),
                    'fin'                => $request->fin,
                    'dure'               => $request->dure,
                    'type_payment_id'    => $request->type_payment_id,
                    'mode_payment_id'    => $request->mode_payment_id,
                    'date_payment'       => $request->date_payment,
                    'loyer_HC'           => $request->loyer_HC,
                    'charge'             => $request->charge,
                    'loyer_HC'           => $request->LoyerLocation,
                    'charge'             => $request->ChargeLocation,
                    'montant'            => $request->LoyerLocation + $request->ChargeLocation,
                    'garantie'           => $request->garantie,
                    'allocation'         => $request->allocation,
                    'locataire_id'       => $request->locataire_id,
                    'conditions'         => $request->conditions,
                    'commentaires'       => $request->commentaires
                ]);

                $aujourdhui = date('m');
                $start = Carbon::parse($request->debut);
                $end = Carbon::now()->endOfMonth();
                $mois = date('m', strtotime($start));
                $annee = date('Y', strtotime($start));
                $YearEncours = date('Y');

                if ($annee <= $YearEncours) {
                    $months = [];
                    while ($start->lt($end)) {
                        $month = [
                            'start_date' => $start->copy()->startOfMonth(),
                            'end_date' => $start->copy()->endOfMonth()
                        ];
                        $months[] = $month;
                        $start->addMonth();
                    }
                    for ($i = 0; $i < count($months); $i++) {
                        if ($i == 0) {
                            if ($request->garantie) {
                                $data = array();
                                $data['logement_id'] = $request->logement_id;
                                $data['location_id'] = $locations->id;
                                $data['Description'] = __('revenu.depot');
                                $data['debut'] = $request->debut;
                                $data['locataire_id'] = $request->locataire_id;
                                $data['montant'] = $request->garantie;
                                $data['type'] = 'revenu';
                                $data['user_id'] = Auth::id();
                                Finance::create($data);
                            }
                        }
                        $charge = $i == 0 ? $request->charge : $request->ChargeLocation;
                        $Loyer = $i == 0 ? $request->loyer_HC : $request->LoyerLocation;
                        $addition = $i == 0 ? $request->summe : $request->LoyerLocation + $request->ChargeLocation;
                        $finance =  Finance::create([
                            'location_id'        => $locations->id,
                            'locataire_id'       => $request->locataire_id,
                            'logement_id'        => $request->logement_id,
                            'fin'                => $months[$i]['end_date'],
                            'montant'            => $addition,
                            'debut'              => $months[$i]['start_date'],
                            'loyer_HC'           => $Loyer,
                            'charge'             => $charge,
                            'type'               => 'loyer',
                            'Description'       => 'loyer',
                            'user_id'            => Auth::id()
                        ]);
                        //envoi de quittance lors de la creation de loyer
                        //envoi_quittance($finance->id);
                    }
                }
                if ($request->montant_locataire != '' || $request->description_locataire != '') {
                    TravauxLocataire::create([
                        'montant_locataire'     => $request->montant_locataire,
                        'description_locataire' => $request->description_locataire,
                        'location_id'           => $locations->id
                    ]);
                }
                if ($request->montant != '' || $request->description != '') {
                    TraveauxProprietaire::create([
                        'montant'     => $request->montant,
                        'description' => $request->description,
                        'location_id' => $locations->id
                    ]);
                }
                $nom = $request->nom;
                if ($nom != null) {
                    if ($nom[0] != null) {
                        $garantCategorie = CategorieContact::where(DB::raw('LOWER(name)'), 'LIKE', '%garant%')->first();

                        for ($i = 0; $i < count($nom); $i++) {
                            $insertedGarant = Garant::create([
                                'nom'            => $request->nom[$i],
                                'prenom'         => $request->prenom[$i],
                                'date_naissance' => $request->date_naissance[$i],
                                'lieu'           => $request->lieu[$i],
                                'email'          => $request->email[$i],
                                'mobil'          => $request->mobil[$i],
                                'location_id'    => $locations->id,
                                'user_id'        => Auth::id()
                            ]);
                            if (!is_null($garantCategorie)) {
                                $contactGarant = new ContactLogement();
                                $contactGarant->name = $insertedGarant->nom;
                                $contactGarant->first_name = $insertedGarant->prenom;
                                $contactGarant->email = $insertedGarant->email;
                                $contactGarant->mobile = $insertedGarant->mobil;
                                $contactGarant->type_conctact_logement = $garantCategorie->id;
                                $contactGarant->garants_location = $insertedGarant->id;
                                $contactGarant->user_id = Auth::id();
                                $contactGarant->save();
                            }
                        }
                    }
                }
                // $file = $request->file;
                // /* check si les fichiers peuvent être engestrée en fonction de leur taille et l'espace libre de l'utilisateur */
                //  Documents::instance()->isSaveable($file);
                // if ($request->file != null)
                //     foreach ($file as $f) {
                //         // $destinationPathImages = base_path() . '/storage/app/public/files_location/';
                //         $filename = rand(999, 99999) . '_' . uniqid() . '.' . $f->getClientOriginalExtension();
                //         $folder    = uniqid() . '-' . now()->timestamp;
                //         $user_filename = $f->getClientOriginalName();
                //         $f->storeAs('files_location/', $filename, 'public');
                //         File_location::create([
                //             'folder'      => $folder,
                //             'image'       => $filename,
                //             'location_id' => $locations->id
                //         ]);
                //         // pasteLogo($destinationPathImages . $filename);
                //         $path = 'app/public/files_location/';
                //         save_document($request->IdBien, $filename, $path, $user_filename, $f->getSize());
                //     }

                if ($locations) {
                    if ($request->location_doc) {
                        foreach ($request->location_doc as $file) {
                            $this->updateFile($locations->id, $file);
                        }
                    }
                }
                $user = Auth::user();
                if ($user->need_guide) {
                    $user->update([
                        'owner_step' => 4,
                    ]);
                }
                DB::commit();
                toastr()->success('Location crée avec success');
            } catch (\Throwable $th) {
                DB::rollback();
                $message = $th->getMessage();
                if (strpos($message, 'Bailti_erreur:') !== false) {
                    $message = str_replace('Bailti_erreur:', "", $message);
                    toastr()->error($message);
                } else {
                    toastr()->error($th->getMessage());
                }
            }
        }
        return response()->json(['errors' => $validator->errors()]);
    }
    public function saveTempGarant(Request $request)
    {
        $data         = $request->all();
        $file         = tmpfile();
        $metaDatas    = stream_get_meta_data($file);
        $tmpFilename  = $metaDatas['uri'];
        file_put_contents($tmpFilename, json_encode($data));
        return response()->json($data);
        fclose($file);
        return response()->json($data);
    }
    public function saveTempLoc(Request $request)
    {
        // dd($request->all());
        if ($request->id == null) {
            // dd($request->id);
            $locataire = LocatairesGeneralInformations::create([
                'TenantEmail'       => $request->emaillocataire,
                'TenantMobilePhone' => $request->mobillocataire,
                'TenantLastName'    => $request->prenom,
                'TenantFirstName'   => $request->nom,
                'locataireType'     => $request->typelocataire,
                'user_id'           => Auth::id()
            ]);
            $data = [
                'id'              => $locataire->id,
                'typelocataire'   => $locataire->locataireType,
                'nom'             => $locataire->TenantFirstName,
                'prenom'          => $locataire->TenantLastName,
                'mobillocataire'  => $locataire->TenantMobilePhone,
                'emaillocataire'  => $locataire->TenantEmail
            ];
            return response()->json($data);
        } else {
            $locataire = backup_locataire::find($request->id);
            // dd($locataire);
            $locataire->update([
                'TenantEmail'       => $request->emaillocataire,
                'TenantMobilePhone' => $request->mobillocataire,
                'TenantLastName'    => $request->prenom,
                'TenantFirstName'   => $request->nom,
                'locataireType'     => $request->typelocataire,
            ]);
            // dd('ato2');
            $data         = $request->all();
            $file         = tmpfile();
            $metaDatas    = stream_get_meta_data($file);
            $tmpFilename  = $metaDatas['uri'];
            file_put_contents($tmpFilename, json_encode($data));
            fclose($file);
            return response()->json($data);
        }
    }
    public function saveTempTable(Request $request)
    {

        if (is_null($request->check)) {
            $garants = new TempGarant();
            $garants->nom = $request->nom;
            $garants->prenom = $request->prenoms;
            $garants->numero = $request->mobil;
            $garants->lieu = $request->lieu;
            $garants->date = $request->date;
            $garants->email = $request->email;
            $garants->user_id = Auth::id();
            $garants->save();
            $data = TempGarant::where('user_id', Auth::id())->get();
            return response()->json($data);
        } else {
            $garants = TempGarant::where('id', $request->check);
            $garants->update([
                'nom' => $request->nom,
                'prenom' => $request->prenoms,
                'numero' => $request->mobil,
                'lieu' => $request->lieu,
                'date' => $request->date,
                'email' => $request->email
            ]);
            $data = TempGarant::where('user_id', Auth::id())->get();
            return response()->json($data);
        }
    }

    public function supp_tempGar($id)
    {
        $temp = TempGarant::find($id);
        $temp->delete();
        return response()->json(['status' => true, 'message' => "suppression success"]);
    }
    public function getgarantById($id)
    {
        $getgarantById = TempGarant::find($id);
        return response()->json($getgarantById);
    }
    public function getgarant()
    {
        $getgarant = TempGarant::where('user_id', Auth::id())->get();
        return response()->json($getgarant);
    }
    public function deleteGarants()
    {
        $getgarant = TempGarant::where('user_id', Auth::id())->truncate();
    }
    public function cherchelocataire(Request $request)
    {
        $query   = $request->input('query');
        $results = backup_locataire::where('tenant_first_name', 'like', '%' . $query . '%')
            ->get();
        return response()->json($results);
    }

    public function getLocataire(Request $request)
    {
        $id      = $request->id;
        $results = backup_locataire::where('id', $id)->get();
        return response()->json($results);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->strIds;
        $data_id_location = explode(",", $ids);
        foreach ($data_id_location as $id) {
            DB::beginTransaction();
            try {
                Location::where('id', $id)->delete();
                $location_en_corbeille = Location::withTrashed()->findOrFail($id);
                Corbeille('Location', 'locations_proprietaire', $location_en_corbeille->identifiant, $location_en_corbeille->deleted_at, $location_en_corbeille->id);
                $finance = Finance::where('location_id', $id)->first();
                $findFinance = Finance::find($finance->id);
                $findFinance->forceDelete();
                revenu::where('finance_id', $findFinance->id)->forceDelete();
                Depense::where('finance_id', $findFinance->id)->forceDelete();
                AutresPaiement::where('finance_id', $findFinance->id)->forceDelete();
                DB::commit();
            } catch (\Throwable $th) {

                DB::rollback();
                // toastr()->error("Il y a une erreur lors de la suppression de location. Veuillez réessayer s'il vous plaît !");
            }
        }

        return response()->json(['status' => true, 'message' => "location suprimer"]);
    }

    public function archiveMultiple(Request $request)
    {
        $ids = $request->strIds;
        Location::WhereIn('id', explode(",", $ids))->update([
            'archive' => 1,
            'date_archive' =>  Carbon::now()->toDateTimeString()
        ]);
        return response()->json(['status' => true, 'message' => "location archiver"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edition($id)
    {
        $id = Crypt::decrypt($id);
        // dd($id);
        $user_id         = Auth::user()->id;
        $mode_payments   = ModePayment::all();
        $type_payments   = TypePayment::all();
        $logements       = Logement::where('user_id', $user_id)->get();
        $count_logement  = count($logements);
        $locataires      = backup_locataire::where('user_id', $user_id)->get();
        $type_locations  = TypeLocation::all();
        $garants         = Garant::where('location_id', $id)
            ->get();

        $location        = Location::where('id', $id)
            ->with('Locataire', 'travauxLocataire', 'travauxProprietaire', 'files')
            ->first();
        return view('location.modification.modification', compact('location', 'mode_payments', 'type_payments', 'logements', 'type_locations', 'locataires', 'garants'));
    }

    public function modification(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'logement_id'         => 'required|not_in:null',
            'type_location_id'    => 'required|not_in:null',
            'identifiant'         => 'required',
            'debut'               => 'required',
            'fin'                 => 'required',
            'type_payment_id'     => 'required',
            'mode_payment_id'     => 'required',
            'date_payment'        => 'required',
            'locataire_id'        => 'required',
        ]);

        if ($validator->passes()) {
            $location = Location::find($id);
            $location->update($request->all());
            toastr()->success('Location modifier avec success');
            return redirect('/location');
        } else {
            toastr()->error('Erreur de modification');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $finances = Finance::where('location_id', $id)->get();
            foreach ($finances as $finance) {

                $finance_en_corbeille = Finance::withTrashed()->findOrFail($finance->id);
                $logementLoue = Logement::where('id', $finance->logement_id)->first();
                $locataire = LocatairesGeneralInformations::where('id', $finance->locataire_id)->first();
                $identifiant = "Finance de location du logement " . $logementLoue->identifiant . " de " . $locataire->civilite . " " . $locataire->TenantFirstName . " " . $locataire->TenantLastName;
                revenu::where('finance_id', $finance_en_corbeille->id)->forceDelete();
                Depense::where('finance_id', $finance_en_corbeille->id)->forceDelete();
                $finance->forceDelete();
            }
            $location = Location::find($id);
            $location = Location::find($id);
            $location->delete();
            $location_en_corbeille = Location::withTrashed()->findOrFail($id);
            Corbeille('Location', 'locations_proprietaire', $location_en_corbeille->identifiant, $location_en_corbeille->deleted_at, $location_en_corbeille->id);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            toastr()->error("Il y a une erreur lors de la suppression de location. Veuillez réessayer s'il vous plaît !");
            // toastr()->error( $th->getMessage());
            return back();
        }

        toastr()->success('Location suprimer avec success');
        return back();
    }

    public function archiver(Request $request)
    {
        $id       = $request->id;
        $location = Location::find($id);
        $location->update([
            'archive' => 1,
            'date_archive' =>  Carbon::now()->toDateTimeString()
        ]);
        return response()->json($location);
    }

    public function ficheLocation($id)
    {
        $id            = Crypt::decrypt($id);
        $location      = Location::where('id', $id)
            ->with(['Locataire', 'Logement', 'typepayement', 'typelocation','files'])
            ->first();
        $notes         = NoteLocation::where('location_id', $id)
            ->get();
        $en_attente    = Finance::where('location_id', $id)
            ->where('Etat', '1')
            ->sum('montant');
        $revenue       = Finance::where('location_id', $id)
            ->where('type', 'loyer')
            ->whereIn('type', ['revenu', 'loyer'])
            ->sum('montant');
        return view('location.ficheLocation', compact('location', 'notes', 'en_attente', 'revenue'));
    }


    public function deleteFile(Request $request)
    {
        $file_data = $request->input();
        $file      = File_location::findOrFail($file_data["key"]);
        $file->delete();
        Storage::disk('public')->delete($file_data["file"]);
        return response()->json(array());
    }

    public function modificationGarant(Request $request)
    {
        $id = $request->id;
        for ($i = 0; $i < count($id); $i++) {
            $garant = Garant::find($id[$i]);
            $garant->update([
                'nom'            => $request->nom[$i],
                'prenom'         => $request->prenom[$i],
                'date_naissance' => $request->date_naissance[$i],
                'lieu'           => $request->lieu[$i],
                'email'          => $request->email[$i],
                'mobil'          => $request->mobil[$i],
            ]);

            $contactGarant = ContactLogement::where('garants_location', $garant->id)->first();
            if (!is_null($contactGarant)) {
                $contactGarant->name = $garant->nom;
                $contactGarant->first_name = $garant->prenom;
                $contactGarant->email = $garant->email;
                $contactGarant->mobile = $garant->mobil;
                $contactGarant->garants_location = $garant->id;
                $contactGarant->user_id = Auth::id();
                $contactGarant->save();
            }
        }
        toastr()->success('Location modifier avec success');
        return redirect('/location');
    }

    public function ajoutGarant(Request $request)
    {
        $nom = $request->noms;
        for ($i = 0; $i < count($nom); $i++) {
            Garant::create([
                'nom'             => $request->noms[$i],
                'prenom'          => $request->prenoms[$i],
                'date_naissance'  => $request->date_naissance[$i],
                'lieu'            => $request->lieus[$i],
                'email'           => $request->emails[$i],
                'mobil'           => $request->mobils[$i],
                'location_id'     => $request->location_id
            ]);
        }
        toastr()->success('Location modifier avec success');
        return redirect('/location');
    }

    public function export()
    {
        $user_id = Auth::user()->first_name;
        return Excel::download(new ExportLocation, "Bailti-$user_id-location.xlsx");
    }
    public function exportODS()
    {
        $user_id = Auth::user()->first_name;
        return Excel::download(new ExportLocation, "Bailti-$user_id-location.ods");
    }

    public function modification_complementaire(Request $request, $id)
    {
        $location = Location::find($id);
        $location->update([
            'conditions'   => $request->conditions,
            'commentaires' => $request->commentaires
        ]);
        $travProprietaire = [
            'Montant'     => $request->montant,
            'description' => $request->description,
            'location_id' => $id
        ];
        TraveauxProprietaire::updateOrCreate(
            ['location_id' => $id],
            $travProprietaire
        );

        TravauxLocataire::updateOrCreate(
            ['location_id' => $id],
            ['montant_locataire' => $request->montant_locataire, 'description_locataire' => $request->description_locataire]
        );
        toastr()->success('Location modifier avec success');
        return redirect('/location');
    }

    public function modification_document(Request $request)
    {
        if ($request->location_doc) {
            foreach ($request->location_doc as $file) {
                $this->updateFile($request->id, $file);
            }
        }
        toastr()->success('Location modifier avec success');
        return response()->json();
    }

    public function import()
    {
        return view('location.import');
    }

    public function download()
    {
        $pathToFile = public_path('Bailti-import-location.xlsx');
        $headers    = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        $fileName   = 'Bailti-import-location.xlsx';
        return response()->download($pathToFile, $fileName, $headers);
    }

    public function importData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv,ods'
        ]);
        if ($validator->passes()) {
            $extension = $request->file('file')->getClientOriginalExtension();
            if (in_array($extension, ['xlsx', 'xls', 'csv', 'ods'])) {
                $data = Excel::toArray(new LocationImport, $request->file('file'));
                array_splice($data[0], 0, 1);

                return response()->json(['data' => $data[0]]);
            } else {
                return response()->json('tsisy');
            }
        }
        return response()->json('tsisy');
    }

    public function importD(Request $request)
    {
        $user_id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv,ods'
        ]);

        if (!$validator->passes()) {
            return response()->json(['errors' => 'Fichier vide']);
        }

        $datas = Excel::toArray(new LocationImport, $request->file('file'));
        array_splice($datas[0], 0, 1);

        $errors = [];
        $locations = [];

        foreach ($datas[0] as $index => $data) {
            $identifiant = $data[1];
            $locataires  = $data[8];

            if (empty($data[0]) || empty($identifiant) || empty($data[2]) || empty($data[3]) || empty($data[4]) || empty($data[5]) || empty($locataires)) {
                $errors[] = "Ligne " . ($index + 1) . " : données non complet";
                continue;
            }

            $logement = Logement::where('identifiant', $identifiant)->first();
            $locataires = backup_locataire::where('TenantEmail', $locataires)->first();

            if (!$logement || !$locataires) {
                $errors[] = "Ligne " . ($index + 1) . " : logement ou locataire introuvable.";
                continue;
            }

            $locations[] = [
                'identifiant' => $data[0],
                'logement_id' => $logement->id,
                'debut' => $data[2],
                'fin' => $data[3],
                'loyer_HC' => $data[4],
                'charge' => $data[5],
                'locataire_id' => $locataires->id,
                'user_id' =>  $user_id,
            ];
        }

        if (!empty($errors)) {
            return response()->json(['errors' => $errors]);
        }

        foreach ($locations as $location) {
            Location::create($location);
        }

        toastr()->success('Location importer avec success');
        return response()->json(['success' => 'Les données ont été importées avec succès.']);
    }

    public function desarchive(Request $request)
    {
        $id       = $request->id;
        $location = Location::find($id);
        $location->update([
            'archive' => 0,
            'date_desarchive' =>  Carbon::now()->toDateTimeString()

        ]);
        // dd($location);
        return response()->json('teste');
    }

    public function note(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notes' => 'required'
        ]);
        if ($validator->passes()) {
            $note =  NoteLocation::create($request->all());
        } else {
            return response()->json('erreur');
        }
        return response()->json($note);
    }

    public function deleteNote(Request $request)
    {
        $id   = $request->id;
        $note = NoteLocation::find($id);
        $note->delete();
        return response()->json();
    }

    public function terminer($id)
    {
        $id = Crypt::decrypt($id);
        $location = Location::find($id);
        $depart   = TerminerLocation::where('location_id', $id)
            ->first();
        return view('location.terminer_location', compact('location', 'depart'));
    }

    public function depart(Request $request)
    {
        $location = Location::find($request->location_id);
        $depart = [
            'date_depart'      => $request->date_depart,
            'Adresse'          => $request->Adresse,
            'Commentaire'      => $request->Commentaire,
            'depot'            => $request->depot,
            'date_restitution' => $request->date_restitution,
            'loyer'            => $request->loyer,
            'charge'           => $request->charge,
            'fin_location'     => $location->fin
        ];
        $finance = [
            'logement_id'       => $location->logement_id,
            'locataire_id'      => $location->locataire_id,
            'montant'           => $request->depot,
            'debut'             => $request->date_depart,
            'location_id'       => $location->id,
            'Description'       => 'Remboursement : Dépôt de garantie <br> Restitution dépôt de garantie' . $location->identifiant,
            'type'              => 'depense'
        ];
        $terminer = TerminerLocation::updateOrCreate(
            ['location_id' => $request->location_id],
            $depart
        );

        Finance::updateOrCreate(
            ['terminerLocation_id' => $terminer->id],
            $finance
        );
        $location->update([
            'depart' => 1,
            'fin'  => $request->date_depart
        ]);
        toastr()->success('Enregistré avec success');
        return redirect('/location');
    }

    public function annuler_depart($id)
    {
        $id = Crypt::decrypt($id);
        $location = Location::find($id);
        $terminer = TerminerLocation::where('location_id', $id)->first();
        $location->update([
            'depart' => 0,
            'fin'  => $terminer->fin_location
        ]);

        $terminer->delete();
        return back()->with('success', 'Depart annuler');
    }

    public function downloadODS()
    {
        $pathToFile = public_path('Bailti-import-location.ods');
        $headers    = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        $fileName   = 'Bailti-import-location.ods';
        return response()->download($pathToFile, $fileName, $headers);
    }
    public function downloadCSV()
    {
        $pathToFile = public_path('Bailti-import-location.csv');
        $headers    = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        $fileName   = 'Bailti-import-location.csv';
        return response()->download($pathToFile, $fileName, $headers);
    }

    public function regularisation($id)
    {
        $id = Crypt::decrypt($id);
        $user_id          = Auth::user()->id;
        $locations        = Location::where('user_id', $user_id)->get();
        $location_actuele = Location::find($id);
        return view('location.regularisationCharge', compact('locations', 'location_actuele'));
    }

    public function reviserLoyer($id)
    {
        $id = Crypt::decrypt($id);
        // return view('location.revisionLoyer');
    }

    public function enregistrement_regularisation(Request $request)
    {
        $checkbox_value = $request->input('notifier');
        $notifier = 0;
        if ($checkbox_value == 'on') {
            $notifier = 1;
        }
        $validator = Validator::make($request->all(), [
            'date_debut' => 'required',
            'date_fin'   => 'required'
        ]);
        if ($validator->passes()) {
            $regul = Regularisation::create([
                'location_id' => $request->location_id,
                'date_debut'  => $request->date_debut,
                'date_fin'    => $request->date_fin,
                'notifier'    => $notifier
            ]);
            $regularisation = Regularisation::with('location')
                ->where('id', $regul->id)->first();
            return redirect()->route('apercu_regularisation', ['id' => $regularisation->id]);
        } else {
            return back()->withErrors($validator)->withInput();
        }
    }

    public function apercu_regularisation($id)
    {
        $regularisation = Regularisation::with('location')
            ->where('id', $id)->first();
        return view('location.apercu_regularisation', compact('regularisation'));
    }

    public function formatPDF($id)
    {
        $regularisation = Regularisation::with('location')
            ->where('id', $id)->first();
        $pdf = PDF::loadView('location.regularisationPDF', compact('regularisation'));
        return $pdf->download('Bailti-regularisation_charge.pdf');
        // return view('location.regularisationPDF',compact('regularisation'));
    }

    public function confirmation($id)
    {
        $regularisation = Regularisation::with('location')
            ->where('id', $id)->first();
        $regularisation->update([
            'etat' => 1
        ]);
        $charge = $regularisation->location->Logement->charge;
        if ($charge == null) {
            $charge = 0;
        }
        $finance = [
            'logement_id'  => $regularisation->location->logement_id,
            'locataire_id' => $regularisation->location->Locataire->id,
            'montant'      => $charge,
            'debut'        => $regularisation->date_debut,
            'fin'          => $regularisation->date_fin,
            'location_id'  => $regularisation->location_id,
            'Description'  => 'Régularisation des charges <br> Régularisation des charges Période ' . $regularisation->date_debut . ' - ' . $regularisation->date_fin,
            'type'         => 'revenu'
        ];
        Finance::create($finance);
        $fichier  = PDF::loadView('location.regularisationPDF', compact('regularisation'));
        $infoLocataire = [];
        $infoLocataire['Nom']             = $regularisation->location->Locataire->civilite . ' ' . $regularisation->location->Locataire->TenantFirstName . ' ' . $regularisation->location->Locataire->TenantLastName;
        $infoLocataire['AdresseLogement'] = $regularisation->location->Logement->adresse;
        $infoLocataire['charge']          = $regularisation->location->Logement->charge;
        $infoLocataire['debut']           = \Carbon\Carbon::parse($regularisation->date_debut)->format('Y-m-d');
        $infoLocataire['fin']             = \Carbon\Carbon::parse($regularisation->date_fin)->format('Y-m-d');

        if ($regularisation->notifier == 1) {
            sendMail($regularisation->location->Locataire->TenantEmail, 'emails.regulartisation_charge', [
                "infoLocataire" => $infoLocataire,
                "subject"       => 'Regularisation des charge locative',
                "lang"          => getLangUser(Auth::id())
            ], [
                "file"   => $fichier,
                "title"  => 'regularisation_charge.pdf'
            ]);
        }
        toastr()->success('Regularisation sauvegarder');
        return redirect('/finance');
    }


    public function formatWord($id)
    {
        $regularisation = Regularisation::with('location')
            ->where('id', $id)
            ->first();
        $template = new TemplateProcessor('word-template/regularisation.docx');
        $template->setValue('proprietaire', Auth::user()->first_name);
        $template->setValue('locataire', $regularisation->location->Locataire->civilite . ' ' . $regularisation->location->Locataire->TenantFirstName . ' ' . $regularisation->location->Locataire->TenantLastName);
        $template->setValue('adresseLoc', $regularisation->location->Locataire->TenantAddress);
        $template->setValue('city', $regularisation->location->Locataire->TenantCity);
        $template->setValue('zip', $regularisation->location->Locataire->TenantZip);
        $template->setValue('date', \Carbon\Carbon::now()->format('Y-m-d'));
        $template->setValue('adresseLogement', $regularisation->location->Logement->adresse);
        $template->setValue('charge', $regularisation->location->Logement->charge);
        $template->setValue('debut', \Carbon\Carbon::parse($regularisation->date_debut)->format('Y-m-d'));
        $template->setValue('fin', \Carbon\Carbon::parse($regularisation->date_fin)->format('Y-m-d'));
        $template->saveAs('regularisation.docx');
        return response()->download('regularisation.docx')->deleteFileAfterSend(true);
    }

    public function anulationRegularisation($id)
    {
        $regularisation = Regularisation::find($id);
        $regularisation->delete();
        return redirect('/location');
    }

    public function ajout_comentaire($id)
    {
        $id = Crypt::decrypt($id);
        $location = Location::where('id', $id)->first();
        return view('location.ajouterCommentaire', compact('location'));
    }

    public function enregistrementComment(Request $request)
    {
        $location = Location::where('id', $request->location_id)->first();
        $location->update([
            'commentaires' => $request->commentaire
        ]);
        toastr()->success('Commentaire sauvegarder');
        return redirect('/location');
    }

    public function desactivation_location(Request $request)
    {
        // dd($request->all());
        $location = Location::find($request->location_desativation_id);
        $location->update([
            'etat' => 2
        ]);
        toastr()->success('Location désactiver');
        return back();
    }
    public function reactivation_location(Request $request)
    {
        // dd($request->all());
        $location = Location::find($request->location_desativation_id);
        $location->update([
            'etat' => 0
        ]);
        toastr()->success('Location reactivé');
        return back();
    }

    public function rapelle_assurance(Request $request)
    {
        // dd($request->all());
        $infoLocataire = [];
        $location = Location::find($request->location_id);
        $infoLocataire['Nom']             = $location->Locataire->civilite . ' ' . $location->Locataire->TenantFirstName . ' ' . $location->Locataire->TenantLastName;
        sendMail($location->Locataire->TenantEmail, 'emails.rapelle_assurance', [
            "infoLocataire" => $infoLocataire,
            "subject"       => 'Rapelle d\'assurance locative',
            "lang"          => getLangUser(Auth::id())
        ]);
        toastr()->success('Message envoyer');
        return redirect('/location');
    }


    public function revision($id)
    {
        $id = Crypt::decrypt($id);
        $location = Location::find($id);
        $revisions = DB::table('location_revision_loyer')->where('location_id', $id)->orderBy('id', 'DESC')->get();
        return view('location.revision_loyer', compact('location', 'revisions'));
    }

    public function regeneration(Request $request)
    {

        // dd('tonga');
        // $id = Crypt::decrypt($id);
        $id = $request->location_regeneration_id;
        $location = Location::find($id);
        $finances = Finance::where('location_id', $id)
            ->where('type', 'loyer')
            ->get();
        foreach ($finances as $finance) {
            $finance->delete();
        }
        $start = Carbon::parse($location->debut);

        // Date de fin (mois actuel)
        $end = Carbon::now()->endOfMonth();

        // Tableau pour stocker les mois
        $months = [];

        // Boucle pour ajouter chaque mois au tableau
        while ($start->lt($end)) {
            $month = [
                // 'name' => $start->format('F Y'), // Format "nom du mois année"
                'start_date' => $start->copy()->startOfMonth(), // Date de début du mois
                'end_date' => $start->copy()->endOfMonth() // Date de fin du mois
            ];
            $months[] = $month;
            $start->addMonth(); // Ajouter un mois à chaque itération
        }

        // Afficher les mois récupérés
        // dd($months[0]['start_date']);

        for ($i = 0; $i < count($months); $i++) {
            Finance::create([
                'location_id'        => $location->id,
                'locataire_id'       => $location->Locataire->id,
                'logement_id'        => $location->logement_id,
                'fin'                => $months[$i]['end_date'],
                'montant'            => $location->montant,
                'debut'              => $months[$i]['start_date'],
                'loyer_HC'           => $location->loyer_HC,
                'type'               => 'loyer',
                'user_id'            => Auth::id()
            ]);
        }
        toastr()->success('Loyer regenerer avec success');
        return redirect('/finance');
    }
    public function voir_bilan($id)
    {
        $AnneeEnCours = Carbon::now()->year;
        $MoisEnCours = Carbon::now()->month;

        $revenuBrutes = Finance::where('location_id', $id)
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('finances.type', '<>', 'depense')
            ->leftJoin('autres_paiements', 'finances.id', '=', 'autres_paiements.finance_id')
            ->selectRaw('finances.montant as montant_finance, autres_paiements.montant as montant_autres_paiements')
            ->selectRaw('IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant) as montant_total')
            ->sum(DB::raw('(IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant))'));
        $revenuBrute = number_format($revenuBrutes, 2);

        $depense = Finance::where('location_id', $id)
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('finances.etat', '0')
            ->where('finances.type', 'depense')
            ->leftJoin('autres_paiements', 'finances.id', '=', 'autres_paiements.finance_id')
            ->selectRaw('finances.montant as montant_finance, autres_paiements.montant as montant_autres_paiements')
            ->selectRaw('IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant) as montant_total')
            ->sum(DB::raw('(IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant))'));


        $total = number_format(($revenuBrutes - $depense), 2);

        $sommeHC = Location::where('id', $id)
            ->sum('loyer_HC');

        $sommeAvec = number_format(($sommeHC - 20), 2);

        $sommeC = Location::where('id', $id)
            ->sum('charge');

        // $autreRevenu = DB::table('finances')
        // ->where('location_id',$id)
        // ->where('type','revenu')
        // ->sum('montant');

        $autreRevenus = Finance::where('location_id', $id)
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('finances.etat', '0')
            ->where('finances.type', 'revenu')
            ->leftJoin('autres_paiements', 'finances.id', '=', 'autres_paiements.finance_id')
            ->selectRaw('finances.montant as montant_finance, autres_paiements.montant as montant_autres_paiements')
            ->selectRaw('IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant) as montant_total')
            ->sum(DB::raw('(IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant))'));

        $autreRevenu = number_format($autreRevenus, 2);


        $finances = Finance::where('location_id', $id)
            ->orderBy('debut')
            ->get()
            ->groupBy(function ($finance) {
                if (!$finance->debut instanceof \DateTime) {
                    $finance->debut = new \DateTime($finance->debut);
                }
                return $finance->debut->format('F Y');
            });
        $revenues = [];
        $expenses = [];
        // $labelsCustom = [];

        foreach ($finances as $month => $data) {
            // $dateTime = date_create_from_format('F Y', $month);

            // $labelsCustom[] = $dateTime->format('F Y');

            $revenue = $data->whereIn('type', ['revenu', 'loyer'])->sum('montant');
            $expense = $data->where('type', 'depense')->sum('montant');

            $revenues[$month] = $revenue;
            $expenses[$month] = $expense;
        }
        return view('proprietaire.bilan', compact('revenues', 'expenses', 'revenuBrute', 'total', 'depense', 'sommeHC', 'sommeC', 'autreRevenu', 'sommeAvec'));
    }
    public function voir_finance($id)
    {
        Session::put('id', $id);
        $AnneeEnCours = Carbon::now()->year;
        $MoisEnCours = Carbon::now()->month;
        $locations = Finance::where('location_id', $id)
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->with(['Locataire', 'Logement', 'AutresPaiements'])->orderBy('debut', 'desc')->get();
        $logements = Logement::where('user_id', Auth::id())->get();
        $locataires = LocatairesGeneralInformations::where('user_id', Auth::id())->get();
        $loyerEnretard = Finance::where('location_id', $id)
            ->with('Location')
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('Etat', '1')->get();

        $months = $locations->groupBy(function ($finance) {
            return Carbon::parse($finance->debut)->format('F Y');
        });

        return view("location.revenu", compact('months', 'locations', 'logements', 'locataires', 'loyerEnretard'));
    }
    public function getlocation($id)
    {
        $dateFs = Carbon::now()->month;
        $AnneeEnCours = Carbon::now()->year;
        $MoisEnCours = Carbon::now()->month;
        $finances = Finance::where('location_id', $id)
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->sum('loyer_HC');
        $sommeHC = number_format($finances, 2);

        $depense = Finance::where('finances.location_id', $id)
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('finances.etat', '0')
            ->where('finances.type', 'depense')
            ->leftJoin('autres_paiements', 'finances.id', '=', 'autres_paiements.finance_id')
            ->selectRaw('finances.montant as montant_finance, autres_paiements.montant as montant_autres_paiements')
            ->selectRaw('IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant) as montant_total')
            ->sum(DB::raw('(IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant))'));

        $sommes = Finance::where('finances.location_id', $id)
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('finances.etat', '0')
            ->where('finances.type', '<>', 'depense')
            ->leftJoin('autres_paiements', 'finances.id', '=', 'autres_paiements.finance_id')
            ->selectRaw('finances.montant as montant_finance, autres_paiements.montant as montant_autres_paiements')
            ->selectRaw('IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant) as montant_total')
            ->sum(DB::raw('(IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant))'));

        $revenuBrute = number_format($sommes, 2);
        $total = number_format(($sommes - $depense), 2);
        // $total = number_format((($revenu + $autres_paiements) - $depense), 2);

        $sommeCs = Finance::where('location_id', $id)
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->sum('charge');
        $sommeC = number_format($sommeCs, 2);

        $veleurEnAttentes = Finance::where('location_id', $id)
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('etat', "1")
            ->sum('montant');
        $veleurEnAttente = number_format($veleurEnAttentes, 2);

        $valeurDeloyer = Finance::where('location_id', $id)
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('type', "loyer")
            ->sum('montant');
        $valeurDeloyer = number_format($valeurDeloyer, 2);

        $valeurDeloyerPaye = Finance::where('location_id', $id)
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('type', "loyer")
            ->where('etat', '0')
            ->sum('montant');
        $valeurDeloyerPaye = number_format($valeurDeloyerPaye, 2);

        $response['data'] = [$sommeHC, $total, $sommeC, $depense, $veleurEnAttente, $valeurDeloyer, $valeurDeloyerPaye, $revenuBrute];
        return response()->json($response['data']);
    }
    public function message($id)
    {
        // $id = Crypt::decrypt($id);
        $locataire = backup_locataire::find($id);
        return view('location.messageLocation', compact('locataire'));
        // dd($id);
    }

    public function envoie_message(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'adresse'   => 'required',
            'message'   => 'required',
            'sujet'     => 'required',
            'fichier'   => 'nullable|file|max:2048'
        ]);
        if ($validator->passes()) {
            $file = $request->file('fichier');
            $email = $request->input('adresse');
            $sujet = $request->sujet;
            Mail::send('emails.toLocataire', ['monMessage' => $request->input('message')], function ($message) use ($file, $email, $sujet) {
                $message->to($email);
                $message->subject($sujet);
                if (isset($file)) {
                    $message->attach($file->getRealPath(), [
                        'as' => $file->getClientOriginalName(),
                        'mime' => $file->getClientMimeType(),
                    ]);
                }
            });
            Mail::raw('Bonjour, Vous venez de recevoir un nouveau message, veuillez consulter votre espace locataire pour pouvoir voir et répondre', function (Message $message) use ($email) {
                $message->to($email);
                $message->subject('Message reçu');
            });
            return back()->with('success', 'message envoyer');
        } else {
            return back()->withErrors($validator)->withInput();
        }
    }

    public function uploadLocFiles(Request $request)
    {
        $jsonData = json_decode($request->all()["initialPreviewConfig"], true);
        foreach ($request->file("input-doc") as $file) {
            $uniqId = uniqid();
            $filename = 'file-' . $uniqId . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('files_location', $filename, 'public');
            $locationFile = File_location::create([
                'folder'      => $filename,
                'image'       => $path,
                'size'        => $file->getSize(),
                'location_id' => count($jsonData) == 0 ? 0 : $jsonData[0]["extra"]["etat"]
            ]);
            pasteLogo(base_path() . '/storage/app/public/files_location/' . $filename);
            $type = in_array($file->getClientOriginalExtension(), ["docx", "doc", "pdf", "txt", "odt", "rtf", "pptx", "ppt", "xlsx", "xls"]) ? "file" : "image";
        }

        return response()->json([
            'initialPreview' => [
                "/storage/" . $path,
            ],
            'initialPreviewConfig' => [
                [
                    'type' => $type,
                    'caption' => "doc" . uniqid(),
                    'id' => $locationFile->id,
                    'url' => route('delete-location-files'),
                    'key' => $locationFile->id,
                    'extra' => [
                        'id' => $locationFile->id,
                        'file_name' => $locationFile->image,
                        'type' => "deleted",
                        'etat' => count($jsonData) == 0 ? 0 : $jsonData[0]["extra"]["etat"]
                    ]
                ],
            ],
        ]);
    }


    public function deleteLocFiles(Request $request)
    {
        $file_data = $request->input();
        $file = File_location::findOrFail($file_data["id"]);
        $file->delete();
        Storage::disk('public')->delete($file_data["file_name"]);
        return response()->json(array());
    }

    public function updateFile($location_id, $file_id)
    {
        $path = 'app/public/files_location/';
        $IdBien = '';
        if ($file_id) {
            $a = File_location::where('id', $file_id)->first();
            $a->update([
                'location_id' => $location_id
            ]);
            save_document($IdBien, $a->folder, $path, $a->folder, $a->size);
        }
        return 'done';
    }


    public function saveRevisionLoyer(Request $request)
    {
        $data = json_decode($request->input('information'), true);
        try {
            $location = Location::instance()->find($data['location_id']);
            $location->reviserLoyer($data);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'errors' => ['message' => $th->getMessage()]], 400);
        }

        return response()->json(['success' => true, 'message' => "Sauvegardé avec succès !"], 200);
    }

    public function annulerRevision(Request $request)
    {
        $id_revision =  $request->id_revision;
        try {
            $location = Location::instance()->annulerRevision($id_revision);
        } catch (\Throwable $th) {
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
        toastr()->success('Révision annulée avec succès');
        return back();
        return redirect()->route('location.revision', ['encoded_id' => $location->encoded_id]);
    }
    public function getcaution($id)
    {
        $locations = Location::where('id', $id)->with(['Locataire', 'Logement', 'garants'])->get();
        $pdf =  PDF::loadView('location.caution', compact('locations'));
        $nom_fichier = 'Bailti-caution';
        return $pdf->download($nom_fichier . '.pdf');
    }

    public function getJustificatifAssurance($id)
    {
        $location = Location::where('id', $id)->with(['Locataire', 'Logement'])->first();
        $nomproprio = Auth::user()->first_name;
        $signatureProprietaire = SignatureUsers::where('user_id', Auth::user()->id)->first();
        if (isset($signatureProprietaire->name_file)) {
            $signaturePro = $this->getSignature($signatureProprietaire->name_file);
        } else {
            $signaturePro = null;
        }
        $pdf = PDF::loadView('location.justificatif_assurance', compact('location', 'nomproprio', 'signaturePro'));
        $nom_fichier = 'Bailti-justificatif-assurance';
        return $pdf->download($nom_fichier . '.pdf');
    }

    public function getJustificatifAssuranceDoc($id)
    {
        $ext = "Justificatif_assurance.docx";
        $aujourdhui = Carbon::now()->format('d/m/Y');
        $nomproprio = Auth::user()->first_name;
        $location = Location::where('id', $id)->with(['Locataire', 'Logement'])->first();
        $signatureProprietaire = SignatureUsers::where('user_id', Auth::user()->id)->first();
        $templateProcessor = new TemplateProcessor('word-template/Justificatif_assurance.docx');
        $templateProcessor->setValue('proprio', $nomproprio);
        $templateProcessor->setValue('date_demande', $aujourdhui);
        $templateProcessor->setValue('debut_location', Carbon::parse($location->debut)->format('d.m.Y'));
        $templateProcessor->setValue('locataire', $location->Locataire->TenantLastName);
        $templateProcessor->setValue('adresse_locataire', $location->Logement->adresse);
        $templateProcessor->setValue('proprio_bas_page', $nomproprio);
        if (isset($signatureProprietaire->name_file)) {
            $signaturePro = $this->getSignature($signatureProprietaire->name_file);
            $templateProcessor->setImageValue('proprio_signature', array('path' => $signaturePro ['path'], 'width' => 2000, 'height' => 2000));
        } else {
            $templateProcessor->setValue('proprio_signature', '');
        }
        $templateProcessor->saveAs('Justificatif_assurance.docx');
        $headers = array(
            'Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        );
        return response()->download('Justificatif_assurance.docx', $ext, $headers)->deleteFileAfterSend(true);
    }

    public function getcautiondoc($id)
    {
        $ext="Bailti_caution.docx";
        $aujourdhui=Carbon::now()->format('d.m.Y');
        $nomproprio=Auth::user()->first_name;
        $locations = Location::where('id', $id)->with(['Locataire', 'Logement', 'garants'])->get();
        foreach($locations as $location)
        {
            $nomlocataire=$location->Locataire->TenantLastName;
            $demeurantlocataire=$location->Locataire->TenantBirthPlace;
            $naissance=$location->Locataire->TenantAddress;
            $debutcontrat=$location->debut;
            $fincontrat=$location->fin;
            $loyer=$location->loyer_HC;
            $charge=$location->charge;
            $adressebien=$location->Logement->adresse;
            foreach($location->garants as $garant){
               $nomGarant=$garant->prenom;
            }
        }
     $templateProcessor = new TemplateProcessor('word-template/Bailti_caution.docx');
     $templateProcessor->setValue('aujourdhui', $aujourdhui);
     $templateProcessor->setValue('nomproprio', $nomproprio);
     $templateProcessor->setValue('adressebien', $adressebien);
     $templateProcessor->setValue('nomlocataire', $nomlocataire);
     $templateProcessor->setValue('demeurantlocataire', $demeurantlocataire);
     $templateProcessor->setValue('naissance', $naissance);
     $templateProcessor->setValue('debutcontrat', $debutcontrat);
     $templateProcessor->setValue('fincontrat', $fincontrat);
     $templateProcessor->setValue('loyer', $loyer);
     $templateProcessor->setValue('charge', $charge);
     $templateProcessor->setValue('nomGarant', $nomGarant);
     $templateProcessor->saveAs('Bailti_caution.docx');
     $headers = array(
        'Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    );
    return response()->download('Bailti_caution.docx', $ext, $headers)->deleteFileAfterSend(true);
}


    public function telechargementContrat($id)
    {
        $proprio = User::where('id', Auth::id())->first();
        $location = Location::where('id', $id)
            ->with(['Locataire', 'Logement', 'typepayement', 'typelocation', 'travauxLocataire', 'travauxProprietaire'])
            ->first();
        $garants = Garant::where('location_id', $id)->get();

        $signatureProprietaire = SignatureUsers::where('user_id', Auth::user()->id)->first();

        if (isset($signatureProprietaire->name_file)) {
            $signaturePro = $this->getSignature($signatureProprietaire->name_file);
        } else {
            $signaturePro = null;
        }

        $signatureLocataire = SignatureUsers::where('user_id', $location->Locataire->id)->first();

        if (isset($signatureLocataire->name_file)) {
            $signatureLoc = $this->getSignature($signatureLocataire->name_file);
        } else {
            $signatureLoc = null;
        }
        $pdf = PDF::loadView('location.contrat', compact('location', 'garants', 'proprio', 'signaturePro', 'signatureLoc'));
        return $pdf->download('Bailti-contrat.pdf');
    }

    private function getSignature($name_file,$type = null)
    {
        $signature = [];
        if (isset($name_file)) {
            $path = storage_path('/uploads/signature/' . $name_file);
            if (File::exists($path)) {
                $desiredWidth = 300;
                $desiredHeight = 150;
                $image = Image::make($path);
                $image->resize($desiredWidth, $desiredHeight);
                $image->save();
                $imageSize = getimagesize($path);
                $imageWidth = $imageSize[0];
                $imageHeight = $imageSize[1];
                // Calculer les nouvelles dimensions
                $desiredWidth = 130; // Largeur souhaitée
                $desiredHeight = ($imageHeight / $imageWidth) * $desiredWidth; // Calculer la hauteur proportionnelle
                // Insérer l'image en spécifiant les nouvelles dimensions
                if ($type){
                    $signature ['path'] = storage_path('uploads/signature/' . $name_file);
                } else {
                    $signature ['path'] = $path;
                }
                $signature ['desiredWidth'] = $desiredWidth;
                $signature ['desiredHeight'] = $desiredHeight;
            }
        }
        return $signature;
    }

    public function telechargementWord($id){
        $proprio        = User::where('id',Auth::id())->first();
        $location       = Location::where('id', $id)
                        ->with(['Locataire', 'Logement', 'typepayement', 'typelocation','travauxLocataire', 'travauxProprietaire'])
                        ->first();
        $garants        = Garant::where('location_id',$id)->get();
        $signatureProprietaire = SignatureUsers::where('user_id', Auth::user()->id)->first();
        $signatureLocataire = SignatureUsers::where('user_id', $location->Locataire->id)->first();
        $template = new TemplateProcessor('word-template/Bail-Nouveau-bien.docx');
        $template->setValue('type',$location->typelocation->description);
        $template->setValue('nomBailleur',$proprio->first_name . ' ' . $proprio->last_name);
        $template->setValue('addresseBailleur',$proprio->address_register);
        $template->setValue('nomLocataire',$location->Locataire->TenantFirstName . ' ' . $location->Locataire->TenantLastName);
        $template->setValue('addresseLocataire',($location->Locataire->TenantAddress) ? $location->Locataire->TenantAddress : ' Neant' );
        $template->setValue('telephoneLocataire',($location->Locataire->TenantMobilePhone) ? $location->Locataire->TenantMobilePhone : ' Neant');
        $template->setValue('naissanceLocataire',($location->Locataire->TenantBirthDate) ? $location->Locataire->TenantBirthDate . ' ' . $location->Locataire->TenantBirthPlace  : ' Neant' );
        $template->setValue('loyer',$location->loyer_HC);
        $template->setValue('charge',$location->charge);
        $template->setValue('identifiant',$location->identifiant);
        $template->setValue('depot',($location->garantie) ? $location->garantie : '0');
        $template->setValue('typeLogement',$location->Logement->typeLogement->property_type);
        $template->setValue('nbPiece',($location->Logement->nbr_piece) ? $location->Logement->nbr_piece : '0');
        $template->setValue('superficie',($location->Logement->superficie) ? $location->Logement->superficie . ' m²' : 'Neant');
        $template->setValue('addresseLogement',$location->Logement->adresse);
        $template->setValue('batiment',($location->Logement->batiment) ? $location->Logement->batiment : 'Neant');
        $template->setValue('escalier',($location->Logement->escalier) ? $location->Logement->escalier : 'Neant');
        $template->setValue('etage',($location->Logement->etage) ? $location->Logement->etage : 'Neant');
        $template->setValue('dateConstruction',($location->Logement->annee_construction !== '0000-00-00') ? $location->Logement->annee_construction : 'Neant');
        $template->setValue('description',($location->Logement->description) ? $location->Logement->description : 'Neant');
        $template->setValue('dure',$location->dure);
        $template->setValue('debut',\Carbon\Carbon::parse($location->debut)->format('d.m.Y'));
        $template->setValue('fin',\Carbon\Carbon::parse($location->fin)->format('d.m.Y'));
        $template->setValue('tavauxProprio',!empty($location->travauxProprietaire->description) ? $location->travauxProprietaire->description : '');
        $template->setValue('montantTraveauxProprio',!empty($location->travauxProprietaire->Montant) ? $location->travauxProprietaire->Montant : '0.00');
        $template->setValue('travauxLocataire',!empty($location->travauxLocataire->description) ? $location->travauxLocataire->description : ' ');
        $template->setValue('montantTraveauxLocataire',!empty($location->travauxLocataire->Montant) ? $location->travauxLocataire->Montant : '0.00');

        if (isset($signatureProprietaire->name_file)) {
            $signaturePro = $this->getSignature($signatureProprietaire->name_file);
            $template->setImageValue('signaturePro', array('path' => $signaturePro ['path'], 'width' => 2000, 'height' => 2000));
        } else {
            $template->setValue('signaturePro', '');
        }
        if (isset($signatureLocataire->name_file)) {
            $signatureLoc = $this->getSignature($signatureLocataire->name_file);
            $template->setImageValue('signatureLoc', array('path' => $signatureLoc ['path'], 'width' => 2000, 'height' => 2000));
        } else {
            $template->setValue('signatureLoc', '');

        }

        $template->cloneBlock('garant', count($garants), true, true);
        foreach ($garants as $key => $garant) {
            $template->setValue('nomGarant#' . ($key + 1), $garant->nom . '' . $garant->prenom);
        }
        $template->saveAs('Bail-Nouveau-bien.docx');
        return response()->download('Bail-Nouveau-bien.docx')->deleteFileAfterSend(true);
    }

    public function liste_locataire(){
        $user_id = Auth::id();
        $locataires = backup_locataire::where('user_account_id',$user_id)->get();
        if($locataires){
            $locataireId = [];
            foreach($locataires as $locataire){
                $locataireId[] = $locataire->id;
            }
            $etat_finance   = [];
            $locations = Location::whereIn('locataire_id',$locataireId)->get();
            if(count($locations) == 0){
                $locations = "vide";
            }else{
                foreach ($locations as $location) {
                    $finances = Finance::where('location_id', $location->id)
                        ->where('type', 'loyer')
                        ->get();
                    foreach ($finances as $finance) {
                        if ($finance->Etat == "Not paid" || $finance->Etat == "Pas payé") {
                            $etat_finance[] = [$finance->Etat, $location->id];
                            break;
                        }
                    }
                }
            }
        }else{
            $locations = "vide";
        }
        return view('espace_locataire.location.index',compact('locations','etat_finance'));
    }

    public function detail_location_locataire($id){
        // $id = Crypt::decrypt($id);
        // dd($id);
        $location = Location::find($id);
        return view('espace_locataire.location.detail',compact('location'));
    }
    public function downDocuments($id)
    {
        $document = File_location::findOrFail($id);
        $path = storage_path('app/public/' . $document->image);
        return response()->download($path);

    }


    public function getAttestationLoyer($id){
        $document = Document_caf::find($id);
        $filename = $document->Path; // Chemin relatif du fichier à partir du dossier "storage/app/public"
        $filePath = storage_path('app/public/' . $filename); // Chemin complet du fichier

        $newFileName = $document->Filename; // Nouveau nom de fichier

        $outputFilePath = storage_path('app/public/' . $filename);
        $this->fillPDFFile($document->id, $filePath, $outputFilePath);

        // Définir le nouveau nom de fichier dans l'en-tête de la réponse

        $document->update([
            'Etat' => '2'
        ]);

        // Retourner la réponse de téléchargement
        toastr()->success('Information remplit');
        return response()->file($outputFilePath);
        // return back();
    }

    public function fillPDFFile($id, $file, $outputFilePath)
    {
        $user_id = Auth::id();
        $user_proprietaire = DB::table('users')->where('id', $user_id)->where('role_id', 1)->first();
        $telephone = DB::table('user_profiles')->select('mobile_no')->where('user_id', $user_id)->first();
        $document = Document_caf::find($id);
        $signature_proprietaire = $user_proprietaire ? SignatureUsers::where('user_id', $user_proprietaire->id)->first() : SignatureUsers::where('user_id', $document->location->user_id)->first();
        $fpdi = new Fpdi();
        $count = $fpdi->setSourceFile($file);
        $template = $fpdi->importPage(1);
        $size = $fpdi->getTemplateSize($template);
        $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
        $fpdi->useTemplate($template);
        $fpdi->setFont('Arial', '', 10);

        //Nom et prenom
        $left = 130;
        $top = 40.8;
        $text = $document->location->user->first_name . ' ' . $document->location->user->last_name;
        $fpdi->Text($left, $top, $text);

        //Adresse
        $left2 = 30;
        $top2 = 45.5;
        $text2 = $document->location->user->address_register;
        $fpdi->Text($left2, $top2, $text2);

        //telephone
        $topp = 50.5;
        $fontSizee = 10;
        $leftt = 36.5;

        $text = $telephone->mobile_no;
        $fpdi->SetFont('Arial', '', $fontSizee);

        for ($i = 0; $i < strlen($text); $i++) {
            $fpdi->Text($leftt, $topp, $text[$i]);

            // Incrémenter le leftt de 2 tous les deux chiffres
            if (($i + 1) % 2 == 0) {
                $leftt += 2;
            }

            $leftt += 4;
        }


        //numeros de
        $topFax = 55.5;
        $fontFax = 10;
        $leftFax = 26.5;

        for ($tel = 0; $tel < 10; $tel++) {
            $text = '';
            $fpdi->SetFont('Arial', '', $fontFax);
            $fpdi->Text($leftFax, $topFax, $text);

            // Incrémenter le left et le décaler de 2 à chaque 2 lettres
            $leftFax += 4;
            if (($tel + 1) % 2 == 0) {
                $leftFax += 2;
            }
        }

        //adresse email
        $email = $document->location->user->email;
        $tabEmail = explode('@', $email);
        $leftAdresse = 112;
        $topAdresse = 55;
        $textadresse = $tabEmail[0];
        $fpdi->SetFont('Arial', '', 10);
        $fpdi->Text($leftAdresse, $topAdresse, $textadresse);

        //dommain
        $leftAdresse = 160;
        $topAdresse = 55;
        $textadresse = $tabEmail[1];
        $fpdi->SetFont('Arial', '', 10);
        $fpdi->Text($leftAdresse, $topAdresse, $textadresse);

        //siret
        $leftAdresse = 30;
        $topAdresse = 60;
        $textadresse = "";
        $fpdi->SetFont('Arial', '', 10);
        $fpdi->Text($leftAdresse, $topAdresse, $textadresse);


        //locataire
        $leftAdresse = 170;
        $topAdresse = 64.5;
        $textadresse = $document->location->Locataire->TenantFirstName . ' ' . $document->location->Locataire->TenantLastName;
        $fpdi->SetFont('Arial', '', 10);
        $fpdi->Text($leftAdresse, $topAdresse, $textadresse);

        //debut location
        $left = 68;
        $top = 69;
        // $textadresse = "";
        $date = \Carbon\Carbon::parse($document->location->debut)->format('d-m-Y');// Obtient la date actuelle au format jour-mois-année


        $fpdi->SetFont('Arial', '', 12);
        $fpdi->Text($left, $top, substr($date, 0, 2)); // Affiche le jour
        $left += 10; // Décale de 2

        $fpdi->Text($left, $top, substr($date, 3, 2)); // Affiche le mois
        $left += 10; // Décale de 2

        $fpdi->Text($left, $top, substr($date, 6)); // Affiche l'année

        //Adresse
        $leftAdresse = 30;
        $topAdresse = 74.6;
        $textadresse = $document->location->Logement->adresse;
        $fpdi->SetFont('Arial', '', 10);
        $fpdi->Text($leftAdresse, $topAdresse, $textadresse);


        //wc
        $leftAdresse = 86.5;
        $topAdresse = 79;
        $textadresse = "x";
        $fpdi->SetFont('Arial', '', 10);
        $fpdi->Text($leftAdresse, $topAdresse, $textadresse);


        //superficie
        $leftAdresse = 86.5;
        $topAdresse = 84;
        $textadresse = $document->location->Logement->superficie ?? '0';
        $fpdi->SetFont('Arial', '', 10);
        $fpdi->Text($leftAdresse, $topAdresse, $textadresse);


        //collocation
        $leftAdresse = 95.5;
        $topAdresse = 88.8;
        $textadresse = "X";
        $fpdi->SetFont('Arial', '', 10);
        $fpdi->Text($leftAdresse, $topAdresse, $textadresse);


        //mois d'entrée
        $leftAdresse = 95.5;
        $topAdresse = 98;
        $textadresse = "";
        $fpdi->SetFont('Arial', '', 10);
        $fpdi->Text($leftAdresse, $topAdresse, $textadresse);

        //Loyer
        $leftAdresse = 52.5;
        $topAdresse = 103;
        $textadresse = $document->location->Logement->loyer;
        $fpdi->SetFont('Arial', '', 10);
        $fpdi->Text($leftAdresse, $topAdresse, $textadresse);

        //charge
        $leftAdresse = 103.5;
        $topAdresse = 103;
        $textadresse = $document->location->Logement->charge ?? '0';
        $fpdi->SetFont('Arial', '', 10);
        $fpdi->Text($leftAdresse, $topAdresse, $textadresse);

        //charge
        $leftAdresse = 125;
        $topAdresse = 122;
        $textadresse = "X";
        $fpdi->SetFont('Arial', '', 10);
        $fpdi->Text($leftAdresse, $topAdresse, $textadresse);


        //hotel
        $leftAdresse = 97.3;
        $topAdresse = 141;
        $textadresse = "X";
        $fpdi->SetFont('Arial', '', 10);
        $fpdi->Text($leftAdresse, $topAdresse, $textadresse);

        //premiere fois
        $leftAdresse = 55.3;
        $topAdresse = 190;
        $textadresse = "X";
        $fpdi->SetFont('Arial', '', 10);
        $fpdi->Text($leftAdresse, $topAdresse, $textadresse);


        //fait le
        $leftAdresse = 20.3;
        $topAdresse = 235.5;
        $textadresse = "Paris";
        $fpdi->SetFont('Arial', '', 10);
        $fpdi->Text($leftAdresse, $topAdresse, $textadresse);

        //date
        $top = 235.5;
        $fontSize = 13;
        $left = 78;

        $date = date('d-m-Y'); // Obtient la date actuelle au format jour-mois-année

        $fpdi->SetFont('Arial', '', $fontSize);
        $fpdi->Text($left, $top, substr($date, 0, 2)); // Affiche le jour
        $left += 10; // Décale de 2

        $fpdi->Text($left, $top, substr($date, 3, 2)); // Affiche le mois
        $left += 10; // Décale de 2

        $fpdi->Text($left, $top, substr($date, 6)); // Affiche l'année

        if (isset($signature_proprietaire->name_file)) {
            $path = storage_path('/uploads/signature/' . $signature_proprietaire->name_file);
            if (File::exists($path)) {
                $desiredWidth = 300;
                $desiredHeight = 150;
                $image = Image::make($path);
                $image->resize($desiredWidth, $desiredHeight);
                $image->save();
                $imageSize = getimagesize($path);
                $imageWidth = $imageSize[0];
                $imageHeight = $imageSize[1];
                // Calculer les nouvelles dimensions
                $desiredWidth = 25; // Largeur souhaitée
                $desiredHeight = ($imageHeight / $imageWidth) * $desiredWidth; // Calculer la hauteur proportionnelle
                // Insérer l'image en spécifiant les nouvelles dimensions
                $fpdi->Image($path, 168, 237, $desiredWidth, $desiredHeight);
            }
        }

        $template2 = $fpdi->importPage(2);
        $size2 = $fpdi->getTemplateSize($template2);
        $fpdi->AddPage($size2['orientation'], array($size2['width'], $size2['height']));
        $fpdi->useTemplate($template2);

        return $fpdi->Output($outputFilePath, 'F');

    }


    public function documentCAF($id){
        $location = Location::find($id);
        $document_caf = Document_caf::where('location_id',$id)->get();
        return view('location.documentCAF',compact('document_caf','location'));
    }

    function extractHeader($filePath)
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);

        $text = $pdf->getText();
        $text = str_replace(["\n", ' '], '', $text);

        // Extraire la première phrase
        $sentences = preg_split('/(?<=[.?!])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

        $firstSentence = $sentences[0];
        $first22Letters = substr($firstSentence, 0, 22);
        if (preg_match('/^Header: (.+)$/m', $text, $matches)) {
            $header = $matches[1];
        }
        return $first22Letters;
    }
    public function enregistrement_documentCAF(Request $request){
        $validator = Validator::make($request->all(), [
            'fichier_pdf' => 'required|mimes:pdf|max:1048',
        ]);
        if($validator->passes()){
            $filePath2 = storage_path('app/public/pdfs/teste2.pdf');

            $header1 = $this->extractHeader($request->file('fichier_pdf'));
            $header2 = $this->extractHeader($filePath2);
            if($header1 === $header2){
                $file = $request->file('fichier_pdf');
                $filename = $file->getClientOriginalName();
                if(isTenant()){
                    $cree = 2;
                }else{
                    $cree = 1;
                }

                $path = $file->store('pdfs', 'public');
                DB::table('document_caf')->insert([
                    'location_id' => $request->location_id,
                    'File_name' => $filename,
                    'path' => $path,
                    'Etat' => 1,
                    'cree_par' => $cree

                ]);
                toastr()->success('Enregistré avec success');
                return redirect()->back();
            }else{
                toastr()->error('Entrée un document caf valide');
                return redirect()->back();
            }
        }
        toastr()->error('Selectioné une fichier valide');
        return redirect()->back();
    }

    public function telechargement_caf($id){
        $document = Document_caf::find($id);
        $filename = $document->Path; // Chemin relatif du fichier à partir du dossier "storage/app/public"
        $filePath = storage_path('app/public/' . $filename);

        $newFilename = $document->File_name; // Nouveau nom de fichier pour le téléchargement
        toastr()->error('Selectioné une fichier valide');
        return response()->download($filePath, $newFilename);
    }

}
