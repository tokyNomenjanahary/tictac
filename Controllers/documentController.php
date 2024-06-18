<?php

namespace App\Http\Controllers;

use App\backup_locataire;
use App\SignatureUsers;
use Intervention\Image\ImageManagerStatic as Image;
use PDF;
use locataire;
use ZipArchive;
use App\Location;
use App\Logement;
use App\Documents;
use App\DossierDocumentProprietaire;
use App\DossierProprietaire;
use App\ModeleDocument;
use App\TypeModeleDocument;
use Illuminate\Http\Request;
use App\packagesStockageDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use League\CommonMark\Node\Block\Document;

class documentController extends Controller
{
    public function gestionSignature()
    {
        $signature = SignatureUsers::where('user_id', Auth::id())->first();
        return view('documents.signature.signature', compact('signature'));
    }

    public function uploadGestionSignatureNopad(Request $request)
    {
        $requestAll = $request->all();

        $requestAll['name_file'] = ($requestAll['name_file'] == 'undefined') ? null : $requestAll['name_file'];

        if ($request->file('name_file') !== null) {
            $img = Image::make($request->file('name_file'));
            $img->resize(400, 400);
            $requestAll['name_file'] = time() . '.' . $request->name_file->getClientOriginalExtension();
            $img->save(storage_path('uploads/signature/' . $requestAll['name_file']));
        }

        $data = [];
        $data['user_id'] = Auth::id();
        $data['name_file'] = $requestAll['name_file'];
        $this->deleteFileSignature();
        $signature = SignatureUsers::create($data);
        return response()->json(['success' => true, 'id' => $signature->id]);
    }

    public function uploadGestionSignature(Request $request)
    {
        if (isset($request->signed)) {
            $folderPath = storage_path('uploads/signature/');

            $image_parts = explode(";base64,", $request->signed);

            $image_type_aux = explode("image/", $image_parts[0]);

            $image_type = $image_type_aux[1];

            $image_base64 = base64_decode($image_parts[1]);

            $file = $folderPath . uniqid() . '.' . $image_type;
            $filename = pathinfo($file, PATHINFO_BASENAME);
            file_put_contents($file, $image_base64);
            $data = [];
            $data['user_id'] = Auth::id();
            $data['name_file'] = $filename;
            $this->deleteFileSignature();
            SignatureUsers::create($data);
            return back()->with('success', 'success Full upload signature');
        } else {
            return back()->with('error', 'Aucune signature dessinée  n\'a été détecter. Vérifier que vous avez bien signé avant de valider');
        }
    }

    private function deleteFileSignature()
    {
        $allSignatures = SignatureUsers::where('user_id', Auth::id())->get();
        foreach ($allSignatures as $allSignature) {
            $path = storage_path('/uploads/signature/' . $allSignature->name_file);
            if (File::exists($path)) {
                File::delete($path);
            }
            $allSignature->delete();
        }
    }

    public function index()
    {
        $for_tenant = isTenant() ? 1 : 0;
        $documents = Documents::where('user_id', Auth::id())
            ->where('archive', 0)
            ->where('for_tenant', $for_tenant)
            ->with(['getBien'])
            ->get();
        $document_archives = Documents::where('user_id', Auth::id())
            ->where('archive', 1)
            ->where('for_tenant', $for_tenant)
            ->get();
        $storage_status = getStorageStatusInMegabytes();
        $message_status = Documents::instance()->getStorageStatus($storage_status);
        $bien = Logement::where('user_id', Auth::id())->get();
        $percentage_used_space = ($storage_status['used_space'] / $storage_status['total_space']) * 100;
        return view('proprietaire.documents', compact('documents', 'storage_status', 'message_status', 'bien', 'percentage_used_space', 'document_archives'));
    }

    public function subscriptionDocuments()
    {
        $Package1 = packagesStockageDocument::find(1);
        $Package2 = packagesStockageDocument::find(2);
        $Package3 = packagesStockageDocument::find(3);
        return view('proprietaire.subscriptionDocumentStorage', [
            'Package1' => $Package1,
            'Package2' => $Package2,
            'Package3' => $Package3
        ]);
    }

    private function convertBytesToMegabytes($bytes)
    {
        return $bytes / 1024 / 1024;
    }

    public function modifier($id)
    {
        $data = [];
        $data['document'] = Documents::findOrFail($id);
        if (isTenant()) {
            $locataire = backup_locataire::where('TenantEmail', Auth::user()->email)->first();
            $data['locations'] = $locations = Location::where('locataire_id', $locataire->id)
                                                    ->where('archive', 0)
                                                    ->with('Logement')
                                                    ->get();
        } else {
            $data['biens'] = Logement::where('user_id', Auth::id())->get();
            $data['locations'] = Location::where('user_id', Auth::id())->get();
        }
        return view('proprietaire.modifier_document', $data);
    }

    public function saveModification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fichier' => 'file|max:5048|mimes:jpeg,png,pdf,doc,docx,xls,xlsx'
        ]);
        if ($validator->passes()) {
            $document = Documents::findOrFail($request->id_document);
            $file = $request->fichier;
            try {
                $document->modifierDocument($file, $request->input(), $document);
            } catch (\Throwable $th) {
                $message = $th->getMessage();
                if (strpos($message, 'Bailti_erreur:') !== false) {
                    $message = str_replace('Bailti_erreur:', "", $message);
                    toastr()->error($message);
                } else {
                    toastr()->error(__('alert.iventaire_error'));
                }
                return back();
            }
            return redirect()->route('documents.index')->with('success', __('documents.success_modification'));
            // return back()->with('success','document modifié avec success');
        } else {
            return back()->withErrors($validator)->withInput();
        }
    }


    public function telecharger($id)
    {
        $document = Documents::findOrFail($id);
        $path = storage_path($document->path . $document->file_name);
        return response()->download($path);
    }

    public function listeModeleDocument()
    {
        $data['type_document'] = TypeModeleDocument::all();
        return view('documents.modeles', $data);
    }

    public function telechargerModeleDocument($id)
    {
        $document = ModeleDocument::findOrFail($id);
        $path = storage_path($document->path . $document->nom_fichier);
        return response()->download($path);
    }

    public function rechercheModele(Request $request)
    {
        $key_word = $request->input('key_word');
        $data = [];
        try {
            $data['result'] = ModeleDocument::instance()->recherche($key_word);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => 'error'], 422);
        }
        return response()->json($data, 200);
    }

    public function archiver($id)
    {
        $document = Documents::findOrFail($id);
        $document->update([
            'archive' => 1
        ]);
        toastr()->success("Document archiver avec success");
        return back();
    }
    public function desarchive($id)
    {
        $document = Documents::findOrFail($id);
        $document->update([
            'archive' => 0
        ]);
        toastr()->success("Document désarchiver avec success");
        return back();
    }
    public function supprimer($id)
    {
        $document = Documents::findOrFail($id);
        $path = storage_path($document->path . $document->file_name);
        if (File::exists($path)) {
            File::delete($path);
        }
        $document->delete();
        $document_en_corbeille = Documents::withTrashed()->findOrFail($id);
        Corbeille('Documents', 'document', $document_en_corbeille->file_name, $document_en_corbeille->deleted_at, $document_en_corbeille->id);
        toastr()->success(__("finance.Suppression_success"));
        return back();
    }
    public function nouveau()
    {
        if (isTenant()) {
            $locataire = backup_locataire::where('TenantEmail', Auth::user()->email)->first();
            $locations = Location::where('locataire_id', $locataire->id)
                ->where('archive', 0)
                ->with('Logement')
                ->get();
                // dd($locations);
            return view('proprietaire.nouveauxDoc', compact('locations'));
        } else {
            $biens     = Logement::where('user_id', Auth::id())->get();
            $locations = Location::where('user_id', Auth::id())->get();
            return view('proprietaire.nouveauxDoc', compact('locations', 'biens'));
        }
    }
    public function deleteMultiple(Request $request)
    {

        $ids = $request->strIds;
        $documents = Documents::whereIn('id', explode(",", $ids))->get(); // Utiliser get() pour récupérer les objets
        foreach ($documents as $document) {
            $path = storage_path($document->path . '/' . $document->file_name);
            if (File::exists($path)) {
                File::delete($path);
            }
            $document->delete();
            $document_en_corbeille = Documents::withTrashed()->findOrFail($document->id);
            Corbeille('Documents', 'document', $document_en_corbeille->file_name, $document_en_corbeille->deleted_at, $document_en_corbeille->id);
        }
        return response()->json(['status' => true, 'message' => __("finance.Suppression_success")], 200);
    }

    public function enregistrement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fichier' => 'required|file|max:5048|mimes:jpeg,png,pdf,doc,docx,xls,xlsx'
        ]);
        if ($validator->passes()) {
            if ($request->has('partage')) {
                $partage = 1;
            } else {
                $partage = 0;
            }
            $file = $request->fichier;
            try {
                if ($request->fichier != null) {
                    Documents::instance()->isSaveable(array($file));
                    $filename = rand(999, 99999) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $folder    = uniqid() . '-' . now()->timestamp;
                    $user_filename = $file->getClientOriginalName();
                    $file->storeAs('files_location/', $filename, 'public');
                    $path = 'app/public/files_location/';
                    Documents::create([
                        'logement_id'      => $request->logement_id,
                        'location_id'       => $request->location_id,
                        'user_id' => Auth::id(),
                        'nomFichier' => $user_filename,
                        'file_name' => $filename,
                        'description' => $request->description,
                        'path' => $path,
                        'date' => $request->date,
                        'size' => $file->getSize(),
                        'for_tenant' => isTenant() ? 1 : 0
                    ]);
                    return redirect()->route('documents.index')->with('success', 'document enregistré avec success');
                }
            } catch (\Throwable $th) {
                $message = $th->getMessage();
                if (strpos($message, 'Bailti_erreur:') !== false) {
                    $message = str_replace('Bailti_erreur:', "", $message);
                    toastr()->error($message);
                } else {
                    toastr()->error('Une erreur est survenue lors de la modification du document. Veuillez réessayer');
                }
                return back();
            }

            return back()->with('error', 'erreur d\'importation');
        } else {
            return back()->withErrors($validator)->withInput();
        }
    }
    public function downloadE()
    {
        $Documents = Documents::where('user_id', Auth::id())
            ->get();
        $pdfs = [];

        $pdf = PDF::loadView('proprietaire.zippFile', ['Documents' => $Documents]);
        $pdfs[] = $pdf->output();


        // Compresser tous les PDF générés en un fichier ZIP
        $zip = new ZipArchive;
        $filename = 'documents.zip';
        if ($zip->open($filename, ZipArchive::CREATE) === TRUE) {
            foreach ($pdfs as $key => $pdf) {
                $zip->addFromString('document.pdf', $pdf);
            }
            $zip->close();
            return response()->download($filename)->deleteFileAfterSend(true);
        }
    }

    public function gestionDossier()
    {
        $storage_status = getStorageStatusInMegabytes();
        $message_status = Documents::instance()->getStorageStatus($storage_status);
        $listeDossier = DossierProprietaire::where('user_id',Auth::id())->get();
        return view('proprietaire.indexDossierProprietaire', compact('storage_status','message_status', 'listeDossier'));
    }

    public function saveDossier(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required'
        ]);
        if ($validator->passes()) {

            DossierProprietaire::create([
                'user_id'=>Auth::id(),
                'nom'=>$request->nom,
            ]);
            toastr()->success('Dossier bien crée.');
        }else{
            toastr()->error('Veulliez remplir les champs require s\'il vous plait!');
            return back()->withErrors($validator)->withInput();
        }
        return redirect()->route('documents.gestionDossier');
    }

    public function containedDossier($id_dossier)
    {
        $containedDossier = DossierProprietaire::where('id',decrypt($id_dossier))->with('getDossierDocument')->first();
        $listeTousDocUser = Documents::where('user_id', Auth::id())->get();
        $listeDocumentDossier = array();
        if($containedDossier){
            foreach ($containedDossier->getDossierDocument as $listeIdDocument) {
                $document = Documents::where('id',$listeIdDocument->document_id)->first();
                array_push($listeDocumentDossier,$document);
            }
        }
        // dd($containedDossier);
        return view('proprietaire.containedDossier', compact('containedDossier', 'listeTousDocUser', 'listeDocumentDossier'));
    }

    public function removeDocumentDossier($document_id)
    {

        $getDossierDocument = DossierDocumentProprietaire::where('document_id',decrypt($document_id))->get();
        if($getDossierDocument){
            DossierDocumentProprietaire::where('document_id',decrypt($document_id))->delete();
        }else{
            toastr()->error('Ce document n\'est plus dans ce dossier');
            return redirect()->back();
        }
        toastr()->success('Le document est bien enlevé dans ce dossier');
        return redirect()->back();
    }

    public function addDocumentDossier($dossier_id)
    {
        $containedDossier = DossierProprietaire::where('id',decrypt($dossier_id))->first();
        $listeDocuments = Documents::where('user_id',Auth::id())->where('for_tenant',0)
                                ->whereNotIn('id', function ($query) {
                                        $query->select('document_id')
                                            ->from('dossier_document_proprietaires');
                                    })
                                ->get();
        return view('proprietaire.addDocumentDossier', compact('containedDossier','listeDocuments'));
    }

    public function saveDocumentDossier(Request $request)
    {
        if($request->document_id){
            foreach ($request->document_id as $document_id) {
                DossierDocumentProprietaire::create([
                    'dossier_proprietaires_id'=>$request->dossier_id,
                    'document_id'=>$document_id,
                ]);
            }
            toastr()->success('Document bien inseré dans votre dossier');
            return redirect()->route('documents.containedDossier',encrypt($request->dossier_id));
        }else{
            toastr()->error('Veuillez selectionner un document s\'il vous plait!');
            return redirect()->back();
        }
    }
}
