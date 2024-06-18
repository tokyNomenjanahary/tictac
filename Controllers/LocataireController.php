<?php

namespace App\Http\Controllers;

use App\SignatureUsers;
use PDF;
use App\User;
use locataire;
use App\Finance;
use App\Location;
use App\Logement;
use Carbon\Carbon;
use App\ContactLogement;
use App\backup_locataire;
use App\CategorieContact;
use App\LocataireUrgence;
use App\LocatairesGarants;
use Illuminate\Http\Request;
use App\Imports\LocationImport;
use App\Exports\ExportLocataire;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\LocatairesGeneralInformations;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Validator;
use App\LocatairesComplementaireInformations;
use App\Http\Requests\LocataireUrgenceRequest;
use App\Http\Requests\LocatairesGarantsRequest;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Requests\LocatairesGeneralInformationRequest;
use App\Http\Requests\LocatairesComplementairesInformationRequest;
use App\Rating;

class LocataireController extends Controller
{
    public function locataireList()
    {
        $allLocataires = LocatairesGeneralInformations::where('user_id', auth::id())
            ->where('archiver', 0)
            ->get();

        $allLocatairesArchives = LocatairesGeneralInformations::where('user_id', auth::id())
            ->where('archiver', 1)
            ->get();

        $logements = Logement::where('user_id', Auth::id())->get();
        return view('locataire.locataire', compact('allLocataires', 'allLocatairesArchives', 'logements'));
    }

    public function inviteMail(Request $Request)
    {
        $logement = Logement::find($Request->idLogement);

        sendMail($Request->email, 'emails.locataire.invitationAnnonce', [
            'subject' => 'test',
            'idLogement' =>  $logement->link_ads
        ]);
        return response()->json(['success' => true]);
    }

    public function locataireInfoGenerale(LocatairesGeneralInformationRequest $Request)
    {
        try {
            DB::beginTransaction();
            $requestAll = $Request->all();
            $requestAll['country_selector'] = $requestAll['country_selector_reference'];
            unset($requestAll['country_selector_reference']);
            $requestAll['TenantPhoto'] = ($requestAll['TenantPhoto'] == 'undefined') ? null : $requestAll['TenantPhoto'];
            $requestAll['TenantIDCard'] = ($requestAll['TenantIDCard'] == 'undefined') ? null : $requestAll['TenantIDCard'];
            $files = ['TenantPhoto', 'TenantIDCard'];
            $paths = [
                'TenantPhoto' => 'uploads/locataire/profil/',
                'TenantIDCard' => 'uploads/locataire/cin/'
            ];

            foreach ($files as $file) {
                if ($Request->file($file) !== null) {
                    $img = Image::make($Request->file($file));
                    $img->resize(400, 400);
                    $requestAll[$file] = time() . '.' . $Request->$file->getClientOriginalExtension();
                    $img->save(storage_path($paths[$file] . $requestAll[$file]));
                }
            }
            $requestAll['user_id'] = Auth::id();
            $nextId = LocatairesGeneralInformations::max('id') + 1;
            $requestAll['id'] = $nextId;
            // $passwordUser = "0000";
            // $passwordUser = uniqid();
            // $password = bcrypt($passwordUser);

            /*** verification si le locataire a deja de compte sur bailti ***/
            $user_locataire = DB::table('users')->where('email',$requestAll['TenantEmail'])->first();
            if(!$user_locataire){
                $requestAll['user_account_id'] = 0;
                $message = "Le locataire que vous avez inseré n'a pas encore de compte sur bailti. Invite le a s'inscrire sur le site pour qu'il puisse voir son espace locataire";
                toastr()->warning($message);
            }else{
                $requestAll['user_account_id'] = $user_locataire->id;
            }
            /*** Creation de compte de locataire ***/
            // $user = User::create([
            //     'first_name' => $Request->TenantFirstName,
            //     'last_name' => $Request->TenantLastName,
            //     'email' => $Request->TenantEmail,
            //     'password' => $password,
            //     'verified' => '1',
            //     'etape_inscription' => 3,
            //     'ip' => get_ip(),
            // ]);
            // $requestAll['user_account_id'] = $user->id;
            LocatairesGeneralInformations::create($requestAll);
            DB::commit();
            $user = Auth::user();
            if ($user->need_guide) {
                $user->update([
                    'owner_step' => 3
                ]);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            toastr()->error($th->getMessage());
            toastr()->error("Il y a une erreur sur l'enregistrement de locataire, Veuillez réessayer s'il vous plaît !");
        }
        return response()->json(['success' => true, 'locataireInfoGenerale' => $requestAll]);
    }

    public function locataireArchive($Request)
    {
        $archiveStatus = LocatairesGeneralInformations::where('id', $Request)->value('archiver');

        if(!$archiveStatus){
            LocatairesGeneralInformations::Where('id', $Request)->update([
                'date_archive' =>  Carbon::now()->toDateTimeString()
            ]);
        }else{
            LocatairesGeneralInformations::Where('id', $Request)->update([
                'date_desarchive' =>  Carbon::now()->toDateTimeString()
            ]);
        }
        LocatairesGeneralInformations::where('id', $Request)->update(['archiver' => !$archiveStatus]);
        $allLocatairesActifs = LocatairesGeneralInformations::where('user_id', auth::id())
            ->where('archiver', 0)
            ->get()
            ->toArray();

        $allLocatairesArchives = LocatairesGeneralInformations::where('user_id', auth::id())
            ->where('archiver', 1)
            ->get()
            ->toArray();
        return response()->json([
            'success' => 'Successfully',
            'allLocatairesActifs' => $allLocatairesActifs,
            'allLocatairesArchives' => $allLocatairesArchives
        ]);
    }

    public function locataireInfoComplementaire(LocatairesComplementairesInformationRequest $Request)
    {
        LocatairesComplementaireInformations::create($Request->all());
        return response()->json(['success' => true]);
    }
    public function suppGarantsEditLocataire($id)
    {
        $garants = LocatairesGarants::find($id);
        $garants->delete();
        $locataire_garant_en_corbeille = LocatairesGarants::withTrashed()->findOrFail($id);
        $identifiant = $locataire_garant_en_corbeille->garantPrenoms;
        Corbeille('Locataire','locataires_garants',$identifiant,$locataire_garant_en_corbeille->deleted_at,$locataire_garant_en_corbeille->id);

        return response()->json(['success' => true]);
    }
    public function suppUrgenceEditLocataire($id)
    {
        $contact = LocataireUrgence::find($id);
        $contact->delete();
        $locataire_urgence_en_corbeille = LocataireUrgence::withTrashed()->findOrFail($id);
        $identifiant = $locataire_urgence_en_corbeille->garantNom.' '.$locataire_urgence_en_corbeille->garantPrenoms;
        Corbeille('Locataire','locataire_urgences',$identifiant,$locataire_urgence_en_corbeille->deleted_at,$locataire_urgence_en_corbeille->id);
        return response()->json(['success' => true]);
    }

    public function locataireInfoGarants(LocatairesGarantsRequest $Request)
    {
        $locataire_garant = LocatairesGarants::create($Request->all());
        $garantCategorie = CategorieContact::where(DB::raw('LOWER(name)'), 'LIKE', '%garant%')->first();
        if (!is_null($garantCategorie)) {
            $contactGarant = new ContactLogement();
            $contactGarant->name = $locataire_garant->garantPrenoms;
            $contactGarant->first_name = $locataire_garant->garantNom;
            $contactGarant->email = $locataire_garant->garantEmail;
            $contactGarant->mobile = $locataire_garant->garantMobile;
            $contactGarant->type_conctact_logement = $garantCategorie->id;
            $contactGarant->garants_locataire = $locataire_garant->id;
            $contactGarant->user_id = Auth::id();
            $contactGarant->save();
        }

        return response()->json(['success' => true, 'locataireGarant' => $Request->all()]);
    }

    public function locataireInfoUrgence(LocataireUrgenceRequest $Request)
    {
        LocataireUrgence::create($Request->all());
        return response()->json(['success' => true]);
    }
    public function suppphotos($id)
    {
        $TenantPhoto = LocatairesGeneralInformations::find($id);
        $TenantPhoto->query()->update(['TenantPhoto' => null]);
        $path = storage_path('/uploads/locataire/profil/' . $TenantPhoto->TenantPhoto);
        if (File::exists($path)) {
            File::delete($path);
        }
        return response()->json(['success' => true]);
    }

    public function suppsignature($id)
    {
        $TenantPhoto = SignatureUsers::find($id);
        if (isset($TenantPhoto->name_file)) {
            $path = storage_path('/uploads/signature/' . $TenantPhoto->name_file);
            if (File::exists($path)) {
                File::delete($path);
            }
            $TenantPhoto->delete();
        }
        return response()->json(['success' => true]);
    }
    public function suppcard($id)
    {
        $TenantIDCard = LocatairesGeneralInformations::find($id);
        $TenantIDCard->query()->update(['TenantIDCard' => null]);
        $path = storage_path('/uploads/locataire/cin/' . $TenantIDCard->TenantIDCard);
        if (File::exists($path)) {
            File::delete($path);
        }
        return response()->json(['success' => true]);
    }
    public function locataireEdit($Request)
    {
        $locataire = LocatairesGeneralInformations::find($Request);

        return view('locataire.edit.editLocataire', compact('locataire'));
    }

    public function editLocataireInfoGenerale(LocatairesGeneralInformationRequest $Request)
    {
        $data = $Request->all();
        $data['country_selector'] = $data['country_selector_reference'];
        unset($data['country_selector_reference']);
        if ($data['TenantPhoto'] == 'undefined') {
            unset($data['TenantPhoto']);
        }
        if ($data['TenantIDCard'] == 'undefined') {
            unset($data['TenantIDCard']);
        }
        $id = $data['LocataireId'];
        unset($data['LocataireId']);
        $locataire = LocatairesGeneralInformations::findOrFail($id);
        $files = ['TenantPhoto', 'TenantIDCard'];
        $paths = [
            'TenantPhoto' => 'uploads/locataire/profil/',
            'TenantIDCard' => 'uploads/locataire/cin/'
        ];
        foreach ($files as $file) {
            if ($Request->file($file) !== null) {
                if ($locataire->$file) {
                    if (file_exists(storage_path($paths[$file] . $locataire->$file))) {
                        //suppression
                        unlink(storage_path($paths[$file] . $locataire->$file));
                    }
                }
                $img = Image::make($Request->file($file));
                $img->resize(400, 400);
                $data[$file] = time() . '.' . $Request->$file->getClientOriginalExtension();
                $img->save(storage_path($paths[$file] . $data[$file]));
            }
        }
        $locataire->update($data);
        return response()->json(['success' => true, 'locataireInfoGeneraleId' => $id]);
    }

    public function editLocataireInfoComplementaire(LocatairesComplementairesInformationRequest $Request)
    {
        $data = $Request->all();
        if (isset($data['idInfoComplementaire'])) {
            $id = $data['idInfoComplementaire'];
            $locataire = LocatairesComplementaireInformations::findOrFail($id);
            unset($data['idInfoComplementaire']);
            $locataire->update($data);
        } else {
            LocatairesComplementaireInformations::create($data);
        }
        return response()->json(['success' => true, 'editlocataireComplemetaire' => $data]);
    }

    public function editLocataireInfoGarants(LocatairesGarantsRequest $Request)
    {
        $data = $Request->all();
        $id = $data['idGarant'];
        $locataire = LocatairesGarants::findOrFail($id);
        unset($data['idGarant']);
        $locataire->update($data);

        /* ON MODIFIE LES INFORMATIONS DANS LA TABLE CONTACT */
        $contactGarant = ContactLogement::where('garants_locataire',$locataire->id)->first();
        if(!is_null($contactGarant)){
            $contactGarant->name = $locataire->garantPrenoms;
            $contactGarant->first_name = $locataire->garantNom;
            $contactGarant->email = $locataire->garantEmail;
            $contactGarant->mobile = $locataire->garantMobile;
            $contactGarant->user_id = Auth::id();
            $contactGarant->save();
        }
        return response()->json(['success' => true, 'locataireGarant' => $Request->all()]);
    }

    public function editlocataireInfoUrgence(LocataireUrgenceRequest $Request)
    {
        $data = $Request->all();
        $id = $data['idUrgence'];
        $locataire = LocataireUrgence::findOrFail($id);
        unset($data['idUrgence']);
        $locataire->update($data);
        return response()->json(['success' => true, 'locataireUrgence' => $Request->all()]);
    }

    public function delete_locataire($id)
    {
        // dd($id);
        $location  = Location::where('locataire_id', $id)->first();
        // dd($location);
        if ($location) {
            return redirect()->route('locataire.locataire')->with('error', 'Impossible de supprimer ce locataire car il est lié à une ou plusieurs locations.');
        } else {
            try {
                $locataire =  LocatairesGeneralInformations::find($id);
                $locataire->delete();
                // $locataire_en_corbeille = backup_locataire::withTrashed()->findOrFail($locataire->id);
                $locataire_en_corbeille = LocatairesGeneralInformations::withTrashed()->findOrFail($locataire->id);
                $identifiant = $locataire_en_corbeille->TenantFirstName.' '.$locataire_en_corbeille->TenantLastName;
                Corbeille('Locataire','locataires_general_informations','test',$locataire_en_corbeille->deleted_at,$locataire_en_corbeille->id);
            } catch (\Throwable $th) {
                toastr()->error("Il y a une erreur lors de la suppression de locataire. Veuillez réessayer s'il vous plaît !");
            }
            return redirect()->route('locataire.locataire')->with('success', 'Le locataire a été supprimé avec succès.');
        }
    }

    public function fiche_locataire($id)
    {
        $locataire = backup_locataire::find($id);
        $location  = Location::where('locataire_id', $id)->first();

        $en_attente    = Finance::where('locataire_id', $id)
            ->where('Etat', 'Pas payé')
            ->sum('montant');
        $revenue       = Finance::where('locataire_id', $id)
            ->where('type', 'loyer')
            ->whereIn('type', ['revenu', 'loyer'])
            ->sum('montant');
        // dd($location);
        return view('locataire.fiche_locataire', compact('locataire', 'location', 'en_attente', 'revenue'));
    }


    public function import(){
        // dd('tonga');
        return view('locataire.importLocataire');
    }
    public function importModel()
    {
        $pathToFile = public_path('Bailti-import-locataire.xlsx');
        $headers    = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        $fileName   = 'Bailti-import-locataire.xlsx';
        return response()->download($pathToFile, $fileName, $headers);
    }
    public function importDonne(Request $request){
        // dd('tonga');
        $user_id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv,ods'
        ]);

        if (!$validator->passes()) {
            return response()->json(['errors' => 'Fichier vide']);
        }

        $datas = Excel::toArray(new LocationImport, $request->file('file'));
        array_splice($datas[0], 0, 1);
        foreach ($datas[0] as $index => $data) {
            // $identifiant = $data[1];
            // $locataires  = $data[8];

            if (empty($data[0]) || empty($data[1]) || empty($data[2]) || empty($data[3]) || empty($data[4]) || empty($data[5])|| empty($data[6])) {
                $errors[] = "Ligne " . ($index + 1) . " : données non complet";
                continue;
            }

            // $logement = Logement::where('identifiant', $identifiant)->first();
            // $locataires = DB::table('locataires_general_informations')->where('TenantEmail', $locataires)->first();

            // if (!$logement || !$locataires) {
            //     $errors[] = "Ligne " . ($index + 1) . " : logement ou locataire introuvable.";
            //     continue;
            // }

            $locataires[] = [
                'TenantFirstName' => $data[0],
                'locataireType' => $data[1],
                'TenantMobilePhone' => $data[2],
                'TenantEmail' => $data[3],
                'TenantAddress' => $data[4],
                'civilite' => $data[5],
                'TenantProfession' => $data[6],
                'user_id' =>  $user_id,
            ];
        }

        if (!empty($errors)) {
            return response()->json(['errors' => $errors]);
        }

        foreach ($locataires as $locataire) {
            // Location::create($location);
            LocatairesGeneralInformations::create($locataire);

        }

        toastr()->success('Locataire importer avec success');
        return response()->json(['success' => 'Les données ont été importées avec succès.']);

    }

    public function downloadFilePdf($id, $type)
    {
        $locataire = LocatairesGeneralInformations::findOrFail($id);
        $data = [
            'locataire' => $locataire
        ];
        if ($type == "justificatif-d-assurance") {
            $ext =  'justificatif-d-assurance-' . $locataire->TenantFirstName . '-' . $locataire->TenantLastName . '.pdf';
            $locataire_pdf = PDF::loadView('locataire.pdf-file', $data);
        } else if ($type == "depart-du-locataire") {
            $ext =  'depart-du-locataire-' . $locataire->TenantFirstName . '-' . $locataire->TenantLastName . '.pdf';
            $locataire_pdf = PDF::loadView('locataire.pdf-file-depart', $data);
        } else {
            $ext = 'declaration-entree-' . $locataire->TenantFirstName . '-' . $locataire->TenantLastName . '.pdf';
            $locataire_pdf = PDF::loadView('locataire.pdf-file-entree', $data);
        }

        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $ext . '"',
        ];
        return $locataire_pdf->download($ext, $headers);
    }

    public function downloadFileDocx($id, $type)
    {
        $locataire = LocatairesGeneralInformations::findOrFail($id);
        $mytime = Carbon::now()->locale('fr')->isoFormat('DD MMMM YYYY');
        $birth_date = "";
        if ($locataire->TenantBirthDate) {
            $date = Carbon::createFromFormat('Y-m-d', $locataire->TenantBirthDate);
            $date->locale('fr');
            $birth_date = $date->isoFormat('DD MMMM YYYY');
        }
        if ($type == "justificatif-d-assurance") {
            $templateProcessor = new TemplateProcessor('word-template/locataire-justificatif-d-assurance.docx');
            $ext =  'justificatif-d-assurance-' . $locataire->TenantFirstName . '-' . $locataire->TenantLastName . '.docx';
        } else if ($type == "depart-du-locataire") {
            $templateProcessor = new TemplateProcessor('word-template/locataire-depart.docx');
            $ext =  'depart-du-locataire-' . $locataire->TenantFirstName . '-' . $locataire->TenantLastName . '.docx';
        } else {
            $templateProcessor = new TemplateProcessor('word-template/locataire-entree.docx');
            $ext = 'declaration-entree-' . $locataire->TenantFirstName . '-' . $locataire->TenantLastName . '.docx';
        }
        $templateProcessor->setValue('now', $mytime);
        $templateProcessor->setValue('name', $locataire->TenantFirstName);
        $templateProcessor->setValue('lastName', $locataire->TenantLastName);
        $templateProcessor->setValue('birth', $birth_date);
        $templateProcessor->saveAs('locataire-depart.docx');
        $headers = array(
            'Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        );
        return response()->download('locataire-depart.docx', $ext, $headers)->deleteFileAfterSend(true);
    }

    public function renderFileView($id)
    {
        $locataire = LocatairesGeneralInformations::findOrFail($id);
        return view('locataire.down_fichier_locataire', ['locataire' => $locataire]);
    }

    public function note_avis(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'user_id_locataire' => 'required',
            'note_star' => 'required',
            'avis' => 'required',
        ]);

        if ($validator->passes()) {
            $exist_note = DB::table('ratings')->where('user_id_proprio', Auth::id())->where('user_id_locataire',$request->user_id_locataire)->first();
            if(is_null($exist_note)){
                DB::table('ratings')->insert([
                    'Note' => $request->note_star,
                    'Avis' => $request->avis,
                    'user_id_proprio' => Auth::id(),
                    'user_id_locataire' => $request->user_id_locataire,
                    'location_id' => $request->location_id,
                    'locataire_id' => $request->locataire_id
                ]);
                toastr()->success('Vous avez donné une note de '.$request->note_star.'/5 à votre locataire.Note bien enregistrée');
                return redirect()->back();
            }else{
                toastr()->error('Vous avez deja donnée une note à ce locataire.');
                return redirect()->back();
            }
        }
    }

    public function exportLoctaire()
    {
        $user_id = Auth::user()->first_name;
        return Excel::download(new ExportLocataire, "Bailti-$user_id-locataire.xlsx");
    }
    public function exportexportLoctaireODS()
    {
        $user_id = Auth::user()->first_name;
        return Excel::download(new ExportLocataire, "Bailti-$user_id-locataire.ods");
    }
}
