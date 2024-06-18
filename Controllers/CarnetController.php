<?php

namespace App\Http\Controllers;

use Auth;
use App\Garant;
use App\Imports\LocationImport;
use Maatwebsite\Excel\Facades\Excel;
use App\ContactLogement;
use App\CategorieContact;
use App\LocatairesGarants;
use Illuminate\Http\Request;
use App\Exports\ExportContact;
use App\Logement;
use Illuminate\Support\Facades\DB;
 use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\VarDumper\VarDumper;
use Termwind\Components\Dd;

class CarnetController extends Controller
{
    //
    public function index()
    {
        $for_tenant = isTenant() ? 1 : 0;
        $contacts = ContactLogement::where('user_id', Auth::id())->where('for_tenant',$for_tenant)->with('logement')->get();
        $count = $contacts->count();
        $logements = Logement::select('identifiant')->where('user_id', Auth::id())->get();
        return view('carnet.contact', compact('contacts', 'logements','count'));
    }
    public function newContact()
    {
        /*** Recuperation liste des categorie de contact ***/
        $categorieContact = CategorieContact::orderBy('name', 'asc')->get();
        return view('carnet.ajoutContact', compact('categorieContact'));
    }

    public function saveConctact(Request $request)
    {
         $for_tenant = isTenant() ? 1 : 0;
         $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required|integer|min:1',
            'adress' => 'required',
            'ville' => 'required',
            'email' => 'email'
        ], [
            'name.required' => 'Vous deviez remplire le nom de votre contact.',
            'mobile.required' => 'Le mobile de votre contact est obligatoire',
            'mobile.integer' => 'Veuillez entrer une valeur valide',
            'mobile.min' => 'Veuillez entrer une valeur valide',
            'adress.required' => 'L\'adresse est requise',
            'email.required' => 'L\'email de votre contact requis',
            'ville.required' => 'Veuillez entrez votre ville'
        ]);

        if ($validator->passes()) {
            if ($request->has('partage')) {
                $partage = 1;
            } else {
                $partage = 0;
            }
            $newContact = new ContactLogement();
            $newContact->user_id = Auth::id();
            $newContact->name = $request->name;
            $newContact->first_name = $request->first_name;
            $newContact->mobile = $request->mobile;
            $newContact->adress = $request->adress;
            $newContact->ville = $request->ville;
            $newContact->email = $request->email;
            $newContact->code_postal = $request->code_postal;
            $newContact->comment = $request->comment;
            $newContact->pays = $request->pays;
            $newContact->for_tenant = $for_tenant;
            $newContact->partage = $partage;
            $newContact->type_conctact_logement = $request->categorie;
            $newContact->save();

            toastr()->success('Votre contact a été bien enregistrer.');
            return redirect()->route('carnet.index');
        } else {
            toastr()->error('Veuillez vérifier les champs requis.');
            return back()->withErrors($validator)->withInput();
        }
    }

    public function show($id)
    {
        $contact = ContactLogement::with('category')->findOrFail($id);
        return view('carnet.fiche-contact', compact('contact'));
    }

    public function editContact($id)
    {
        $contact = ContactLogement::where('id', $id)->first();
        /*** Recuperation liste des categorie de contact ***/
        $categorieContact = CategorieContact::orderBy('name', 'asc')->get();
        return view('carnet.ajoutContact', compact('contact', 'categorieContact'));
    }

    public function saveContact(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required|integer|min:1',
            'adress' => 'required',
            'email' => 'email'
        ], [
            'name.required' => 'Vous deviez remplire le nom de votre contact.',
            'mobile.required' => 'le mobile de votre contact est obligatoire',
            'mobile.integer' => 'Veuillez entrer une valeur valide',
            'mobile.min' => 'Veuillez entrer une valeur valide',
            'adress.required' => 'L\'adresse est requise',
            'email.required' => 'L\'email de votre contact requis'
        ]);

        if ($validator->passes()) {
            if ($request->has('partage')) {
                $partage = 1;
            } else {
                $partage = 0;
            }
            DB::beginTransaction();
            try {
                ContactLogement::where('id', $id)->update([
                    "name" => $request->name,
                    "first_name" => $request->first_name,
                    "mobile" => $request->mobile,
                    "adress" => $request->adress,
                    "ville" => $request->ville,
                    "partage" => $partage,
                    "email" => $request->email,
                    "code_postal" => $request->code_postal,
                    "comment" => $request->comment,
                    "pays" => $request->pays,
                    "type_conctact_logement" => $request->categorie,
                ]);

                $contact = ContactLogement::find($id);

                if (!is_null($contact->garants_locataire)) {
                    $garant = LocatairesGarants::find($contact->garants_locataire);
                    if (!is_null($garant)) {
                        $garant->garantNom = $request->name;
                        $garant->garantPrenoms = $request->first_name;
                        $garant->garantEmail = $request->email;
                        $garant->garantMobile = $request->mobile;
                        $garant->save();
                    }
                }
                if (!is_null($contact->garants_location)) {
                    $garant = Garant::find($contact->garants_location);
                    if (!is_null($garant)) {
                        $garant->nom = $request->name;
                        $garant->prenom = $request->first_name;
                        $garant->email = $request->email;
                        $garant->mobil = $request->mobile;
                        $garant->save();
                    }
                }
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                toastr()->error('Veuillez vérifier les champs requis.');
                return back()->withErrors($validator)->withInput();
            }

            toastr()->success('Votre contact a été bien modifié.');
            return redirect()->route('carnet.index');
        } else {
            toastr()->error('Veuillez vérifier les champs requis.');
            return back()->withErrors($validator)->withInput();
        }
    }

    public function delete($id){
        $contact = ContactLogement::find($id);
        $contact->delete();
        $contact_logement_en_corbeille = ContactLogement::withTrashed()->findOrFail($id);
        $identifiant = $contact_logement_en_corbeille->name.' '.$contact_logement_en_corbeille->first_name;
        Corbeille('Contact','contact_logements',$identifiant,$contact_logement_en_corbeille->deleted_at,$contact_logement_en_corbeille->id);
   
        toastr()->success('Votre contact a bien été supprimer.');
        return redirect()->route('carnet.index');
    }
    public function importercontact()
    {
       return view('carnet.import');
    }
    public function downloadExcel()
    {
        $pathToFile = public_path('Bailti-Contacts.xlsx');
        $headers    = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        $fileName   = 'Bailti-Contacts.xlsx';
        return response()->download($pathToFile, $fileName, $headers);
    }
    public function impordonnecontact(Request $request)
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
        foreach ($datas[0] as $index => $data) {
            if (empty($data[0]) || empty($data[1]) || empty($data[2]) || empty($data[3]) || empty($data[4]) || empty($data[5] || empty($data[6]) || empty($data[7]))) {
                $errors[] = "Ligne " . ($index + 1) . " : données non complet";
             }
            $contact_logements [] = [
                'name' => $data[0],
                'first_name' => $data[1],
                'email' => $data[2],
                'mobile' => $data[3],
                'adress' => $data[4],
                'ville' => $data[5],
                'comment' => $data[6],
                'user_id' =>  $user_id,
            ];
        }
        if (!empty($errors)) {
            return response()->json(['errors' => $errors]);
        }
        foreach ($contact_logements as $contact_logement) {
            ContactLogement::create($contact_logement);

        }

   }

    public function exportContact(Request $request){
        $contact_ids =json_decode($request->ids_liste, true);
        $type = $request->input('type');
        if($type=='excel'){
            return Excel::download(new ExportContact($contact_ids), 'Carnet.xlsx');
        }
        else if($type == 'open_office'){
            return Excel::download(new ExportContact($contact_ids), 'Carnet.ods');
        }
    }
}
