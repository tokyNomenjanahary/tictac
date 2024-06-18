<?php

namespace App\Http\Controllers;

use App\quittance;
use App\SignatureUsers;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use PDF;
use Session;
use App\revenu;
use ZipArchive;
use App\Depense;
use App\Finance;
use App\Location;
use App\Logement;
use Carbon\Carbon;
use Dompdf\Dompdf;
use App\UserProfiles;
use App\AutresPaiement;
use App\Exports\ExportBilanFiscal;
use Nette\Utils\DateTime;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Foreach_;
use App\Exports\ExportTransaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\LocatairesGeneralInformations;
use function PHPUnit\Framework\isNull;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\Crypt;

use App\Http\Requests\StoreFinanceRequest;
use App\Http\Requests\UpdateFinanceRequest;
use App\Suggestion;
use Illuminate\Support\Facades\Response as FileResponse;

class FinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $AnneeEnCours = Carbon::now()->year;
        $MoisEnCours = Carbon::now()->month;
        $locations = Finance::where('user_id', Auth::id())
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->with(['Locataire', 'Logement', 'AutresPaiements'])->orderBy('debut', 'desc')->get();
        $logements = Logement::where('user_id', Auth::id())->get();

        $locataires = LocatairesGeneralInformations::where('user_id', Auth::id())->get();
        $loyerEnretard = Finance::where('user_id', Auth::id())
            ->with('Location')
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('Etat', '1')->get();

        $months = $locations->groupBy(function ($finance) {
            return Carbon::parse($finance->debut)->format('m');
        });

        return view("proprietaire.finance", compact('months', 'locations', 'logements', 'locataires', 'loyerEnretard'));
    }

    public function Enregistrer_paiement($id, $type)
    {
        $id = Crypt::decrypt($id);
        $AnneeEnCours = Carbon::now()->year;
        $MoisEnCours = Carbon::now()->month;
        Session::put('id', $id);
        Session::put('type', $type);
        $finance = Finance::where('id', $id)
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->with('Logement', 'Locataire')->first();
        $Autres_information = revenu::where('finance_id', $id)->first();
        return view("proprietaire.paiement", compact('finance', 'Autres_information'));
    }
    public function paiement_sauver(Request $request)
    {
        $montant = $request->input('montant');
        $locataire = $request->input('locataire');
        $mode_paiement = $request->input('mode');
        $date = $request->input('date');
        $finance_id = Session::get('id');
        if ($montant) {
            for ($i = 0; $i < count($montant); $i++) {
                $paiements = new AutresPaiement();
                $paiements->montant = $montant[$i];
                $paiements->locataire = $locataire[$i];
                $paiements->mode_paiement = $mode_paiement[$i];
                $paiements->date = $date[$i];
                $paiements->finance_id = $finance_id;
                $paiements->type = Session::get('type');
                $paiements->save();
            }
            toastr()->success(__("finance.paiement_save"));
            return redirect('/finance');
        } else {
            toastr()->success(__("finance.paiement_save"));
            return redirect('/finance');
        }
    }
    public function Etat_partiel(Request $request)
    {
        Session::put('id', $request->id);
        Session::put('type', $request->type);
        $finance = Finance::where('id', $request->id)->with('Logement', 'Locataire')->first();
        $Autres_information = revenu::where('finance_id', $request->id)->first();
        return view("proprietaire.paiement", compact('finance', 'Autres_information'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Finance  $finance
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $id = Crypt::decrypt($id);
        $Finance = Finance::where('id',$id)->delete();
        revenu::where('finance_id', $id)->delete();
        Depense::where('finance_id', $id)->delete();
        AutresPaiement::where('finance_id', $id)->delete();
        toastr()->success(__("finance.Suppression_success"));
        return back();
    }
    public function suppMultiple(Request $request)
    {

        $ids = $request->strIds;
        $idsDecryptes = [];
        if (!empty($ids)) {
            // Décrypter les IDs et les stocker dans un tableau
            foreach (explode(",", $ids) as $id) {
                $idsDecryptes[] = decrypt($id);
            }
        }
        Finance::whereIn('id', $idsDecryptes)->delete();
        revenu::whereIn('finance_id', $idsDecryptes)->delete();
        Depense::whereIn('finance_id', $idsDecryptes)->delete();
        AutresPaiement::whereIn('finance_id', $idsDecryptes)->delete();
        return response()->json(['status' => true, 'message' => __("finance.Suppression_success")], 200);
    }
    public function getfinance()
    {
        $dateFs = Carbon::now()->month;
        // $dateF = Carbon::now()->endOfMonth();

        $AnneeEnCours = Carbon::now()->year;
        $MoisEnCours = Carbon::now()->month;
        $finances = Finance::where('user_id', Auth::id())
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->sum('loyer_HC');
        $sommeHC = number_format($finances, 2);

        $depense =Finance::
            where('finances.user_id', Auth::id())
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('finances.etat', '0')
            ->where('finances.type', 'depense')
            ->leftJoin('autres_paiements', 'finances.id', '=', 'autres_paiements.finance_id')
            ->selectRaw('finances.montant as montant_finance, autres_paiements.montant as montant_autres_paiements')
            ->selectRaw('IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant) as montant_total')
            ->sum(DB::raw('(IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant))'));

        $sommes = Finance::
            where('finances.user_id', Auth::id())
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

        $sommeCs = Finance::where('user_id', Auth::id())
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->sum('charge');
        $sommeC = number_format($sommeCs, 2);

        $veleurEnAttentes = Finance::where('user_id', Auth::id())
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('etat', "2")
            ->sum('montant');
        $veleurEnAttente = number_format($veleurEnAttentes, 2);

        $valeurDeloyer = Finance::where('user_id', Auth::id())
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('type', "loyer")
            ->sum('montant');
        $valeurDeloyer = number_format($valeurDeloyer, 2);

        $valeurDeloyerPaye = Finance::where('user_id', Auth::id())
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('type', "loyer")
            ->where('etat', '0')
            ->sum('montant');
        $valeurDeloyerPaye = number_format($valeurDeloyerPaye, 2);

        $response['data'] = [$sommeHC, $total, $sommeC, $depense, $veleurEnAttente, $valeurDeloyer, $valeurDeloyerPaye, $revenuBrute];
        return response()->json($response['data']);
    }
    public function exporter()
    {
        $user_id = Auth::user()->first_name;
        return Excel::download(new ExportTransaction, "Bailti-$user_id-transaction.xlsx");
    }
    public function exportation()
    {
        $user_id = Auth::user()->first_name;
        return Excel::download(new ExportTransaction, "Bailti-$user_id-transaction.ods");
    }
    public function telecharge_recu($id)
    {
        $id = Crypt::decrypt($id);
        $user_id = Auth::user()->id;
        $location = Finance::where('user_id', $user_id) ->where('id', $id) ->with(['Locataire', 'Logement'])->first();
        $NumberPhone = UserProfiles::where('user_id', $user_id)->first();
        $signatureProprietaire = SignatureUsers::where('user_id', Auth::user()->id)->first();
        $signature = [];
        if (isset($signatureProprietaire->name_file)) {
            $path = storage_path('/uploads/signature/' . $signatureProprietaire->name_file);
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
                $signature ['path'] = '/uploads/signature/' . $signatureProprietaire->name_file;
                $signature ['desiredWidth'] = $desiredWidth;
                $signature ['desiredHeight'] = $desiredHeight;
            }
        }

        return view("proprietaire.recu", compact('location', 'NumberPhone','signature'));
    }
    public function voirPdf_recu($id)
    {
        $user_id = Auth::user()->id;
        $location = Finance::where('user_id', $user_id)
            ->where('id', $id)
            ->with(['Locataire', 'Logement'])
            ->first();
        $NumberPhone = UserProfiles::where('user_id', $user_id)->first();
        $signatureProprietaire = SignatureUsers::where('user_id', Auth::user()->id)->first();
        $signature = [];
        if (isset($signatureProprietaire->name_file)) {
            $path = storage_path('/uploads/signature/' . $signatureProprietaire->name_file);
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
                $signature ['path'] = $path;
                $signature ['desiredWidth'] = $desiredWidth;
                $signature ['desiredHeight'] = $desiredHeight;
            }
        }

        $pdf = PDF::loadView('proprietaire.recuFichier', compact('location', 'NumberPhone','signature'));

        return $pdf->download('Reçu.pdf');
    }

    public function sendrecu($id)
    {
        $id = Crypt::decrypt($id);
        $finance=Finance::where('id',$id)->with(['Logement','Locataire','Location'])->first();
        $bien=$finance->Logement->identifiant;
        $locataire_email=$finance->Locataire->TenantEmail;
        $locataire_destinataire_id=$finance->Locataire->user_account_id;
        $location_id=$finance->location_id;
        $finance_id=$finance->id;

        $location = Finance::where('user_id', Auth::user()->id)->with(['Locataire', 'Logement'])->where('id', $id)->first();
        $NumberPhone = UserProfiles::where('user_id',Auth::user()->id)->first();
        $signatureProprietaire = SignatureUsers::where('user_id', Auth::user()->id)->first();
        $signature = [];
        if (isset($signatureProprietaire->name_file)) {
            $path = storage_path('/uploads/signature/' . $signatureProprietaire->name_file);
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
                $signature ['path'] = $path;
                $signature ['desiredWidth'] = $desiredWidth;
                $signature ['desiredHeight'] = $desiredHeight;
            }
        }

        $fichier = PDF::loadView('proprietaire.recuFichier', compact('location', 'NumberPhone','signature'));
        //nom de recu obtenu
        $nom_fichier = 'recu_'.$finance->Location->identifiant;

      //  sauvegarder de recu dans dossier
        $path_to_file = public_path('uploads/Finance/') . $nom_fichier . '.pdf';
        $fichier->save($path_to_file);

        //sauvegarde dans bdd
        $quittance=new quittance();
        $quittance->bien =$bien;
        $quittance->user_id_destinataire =$locataire_destinataire_id;
        $quittance->user_id_sender =Auth::user()->id;
        $quittance->montant =$finance->montant;
        $quittance->location_id =$location_id;
        $quittance->finance_id =$finance_id;
        $quittance->description ='Depot de garantie';
        $quittance->quittance =$nom_fichier .'.pdf';
        $quittance->save();

        //notification de locataire
        Mail::raw('Bonjour, Votre reçu de loyer est téléchargeable, merci de consulter votre espace locataire.', function (Message $message) use ($locataire_email) {
            $message->to($locataire_email);
            $message->subject('Message reçu');
        });

        toastr()->success(__("finance.received_succes"));
        return back();
    }
    public function Changer_Etat(Request $request)
    {
        // Mettre à jour le champ dans la base de données
        DB::table('finances')
            ->where('id', $request->id) // Remplacer $id par l'ID de l'enregistrement à mettre à jour
            ->update(['etat' => $request->etat]);
        toastr()->success(__("finance.Etat_a_jour"));
        return response()->json(['success' => true]);
    }
    public function bilan()
    {
        /* liste des biens */
        $biens = Logement::where('user_id', Auth::id())->get();
        $AnneeEnCours = Carbon::now()->year;
        $MoisEnCours = Carbon::now()->month;
        $finances = Finance::where('user_id', Auth::id())
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
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
            // $dateTime = date_create_from_format('Y-m', $month);

            // $labelsCustom[] = $dateTime->format('F Y');

            $revenue = $data->whereIn('type', ['revenu', 'loyer'])->sum('montant');
            $expense = $data->where('type', 'depense')->sum('montant');

            $revenues[$month] = $revenue;
            $expenses[$month] = $expense;
        }
        $AnneeEnCours = Carbon::now()->year;
        $MoisEnCours = Carbon::now()->month;
        $depense = Finance::
            where('finances.user_id', Auth::id())
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('finances.etat', '0')
            ->where('finances.type', 'depense')
            ->leftJoin('autres_paiements', 'finances.id', '=', 'autres_paiements.finance_id')
            ->selectRaw('finances.montant as montant_finance, autres_paiements.montant as montant_autres_paiements')
            ->selectRaw('IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant) as montant_total')
            ->sum(DB::raw('(IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant))'));




        $sommes = Finance::
             where('finances.user_id', Auth::id())
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('finances.etat', '0')
            ->where('finances.type', '<>', 'depense')
            ->leftJoin('autres_paiements', 'finances.id', '=', 'autres_paiements.finance_id')
            ->selectRaw('finances.montant as montant_finance, autres_paiements.montant as montant_autres_paiements')
            ->selectRaw('IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant) as montant_total')
            ->sum(DB::raw('(IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant))'));

        $total = number_format(($sommes - $depense), 2);
        $revenuBrute = number_format($sommes, 2);

        $dateFs = Carbon::now()->month;
        $finances = Finance::where('user_id', Auth::id())
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->sum('loyer_HC');
        $sommeHC = number_format($finances, 2);

        $sommeAvec = number_format(($finances - 20), 2);

        $sommeCs = Finance::where('user_id', Auth::id())
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->sum('charge');
        $sommeC = number_format($sommeCs, 2);

       $totales =  $finances+$sommeCs ;
       $totals = number_format($totales, 2);
        $autreRevenu = Finance::
             where('finances.user_id', Auth::id())
            ->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
            ->where('finances.etat', '0')
            ->where('finances.type', '<>', 'depense')
            ->where('finances.type', '<>', 'loyer')
            ->leftJoin('autres_paiements', 'finances.id', '=', 'autres_paiements.finance_id')
            ->selectRaw('finances.montant as montant_finance, autres_paiements.montant as montant_autres_paiements')
            ->selectRaw('IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant) as montant_total')
            ->sum(DB::raw('(IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant))'));
        $autreRevenu = number_format($autreRevenu, 2);

        return view('proprietaire.bilan', compact('totals','revenues', 'expenses', 'revenuBrute', 'total', 'depense', 'sommeHC', 'sommeC', 'autreRevenu', 'sommeAvec', 'biens'));
    }

    public function downloadExelBilanFiscal($annee, $bilanFoncier, $bien_id=null)
    {
        $data_bilan = ['annee'=>$annee, 'bilanFoncier'=>$bilanFoncier,'bien_id'=>$bien_id];
        return Excel::download(new ExportBilanFiscal($data_bilan), 'Bailti-bilan-'.$bilanFoncier.'-'.$annee.'.xlsx');
    }

    public function filtrebilan(Request $request)
    {
        $annee = $request->input('annee');
        $bien = $request->input('bien');
        $data = [];
        try {
            $AnneeEnCours = $annee;
            $MoisEnCours = 12;
            if ($annee == now()->year) {
                $MoisEnCours = now()->month;
            }
            /* on prerpare la requete de base (pour l'annee/utilisateur/mois )*/
            $finance_query = Finance::where('user_id', Auth::id())->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)->orderBy('debut');
            /* si on a selectionné un bien dans notre filtre, on ajoute l'id_logement dans la requete */
            if (!empty($bien)) {
                $finance_query = $finance_query->where('logement_id', $bien);
            }

            $finances = $finance_query->get()
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
                // $dateTime = date_create_from_format('Y-m', $month);

                // $labelsCustom[] = $dateTime->format('F Y');

                $revenue = $data->whereIn('type', ['revenu', 'loyer'])->sum('montant');
                $expense = $data->where('type', 'depense')->sum('montant');

                $revenues[$month] = $revenue;
                $expenses[$month] = $expense;
            }


            /*  depense finance */
            $depense_query =  Finance::
                where('finances.user_id', Auth::id());
            if (!empty($bien)) {
                $depense_query = $depense_query->where('logement_id', $bien);
            }
            $depense = $depense_query->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
                ->where('finances.etat', '0')
                ->where('finances.type', 'depense')
                ->leftJoin('autres_paiements', 'finances.id', '=', 'autres_paiements.finance_id')
                ->selectRaw('finances.montant as montant_finance, autres_paiements.montant as montant_autres_paiements')
                ->selectRaw('IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant) as montant_total')
                ->sum(DB::raw('(IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant))'));


            /*  */
            $somme_query =  Finance::
                where('finances.user_id', Auth::id());
            if (!empty($bien)) {
                $somme_query = $somme_query->where('logement_id', $bien);
            }
            $sommes = $somme_query->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
                ->where('finances.etat', '0')
                ->where('finances.type', '<>', 'depense')
                ->leftJoin('autres_paiements', 'finances.id', '=', 'autres_paiements.finance_id')
                ->selectRaw('finances.montant as montant_finance, autres_paiements.montant as montant_autres_paiements')
                ->selectRaw('IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant) as montant_total')
                ->sum(DB::raw('(IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant))'));

            $total = number_format(($sommes - $depense), 2);
            $revenuBrute = number_format($sommes, 2);



            $finances_query =  Finance::where('user_id', Auth::id());
            if (!empty($bien)) {
                $finances_query = $finances_query->where('logement_id', $bien);
            }
            $finances =$finances_query->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
                ->sum('loyer_HC');
            $sommeHC = number_format($finances, 2);

            $sommeAvec = number_format(($finances - 20), 2);


            /*  ------   */
            $sommeCs_query = Finance::where('user_id', Auth::id());
            if (!empty($bien)) {
                $sommeCs_query = $sommeCs_query->where('logement_id', $bien);
            }
            $sommeCs = $sommeCs_query->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
                ->sum('charge');
            $sommeC = number_format($sommeCs, 2);

            /* total */
            $totales =  $finances+$sommeCs ;
            $totals = number_format($totales, 2);

            /* autres revenu */
            $autreRevenu_query =   $autreRevenu =Finance::where('finances.user_id', Auth::id());
            if (!empty($bien)) {
                $autreRevenu = $autreRevenu->where('logement_id', $bien);
            }
            $autreRevenu = $autreRevenu->whereYear('debut', $AnneeEnCours)->whereMonth('debut', '<=', $MoisEnCours)
                ->where('finances.etat', '0')
                ->where('finances.type', '<>', 'depense')
                ->where('finances.type', '<>', 'loyer')
                ->leftJoin('autres_paiements', 'finances.id', '=', 'autres_paiements.finance_id')
                ->selectRaw('finances.montant as montant_finance, autres_paiements.montant as montant_autres_paiements')
                ->selectRaw('IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant) as montant_total')
                ->sum(DB::raw('(IF(autres_paiements.montant IS NULL, finances.montant, finances.montant + autres_paiements.montant))'));
            $autreRevenu = number_format($autreRevenu, 2);


            $data['data'] = compact('revenues', 'expenses', 'revenuBrute', 'total', 'depense', 'sommeHC', 'sommeC', 'autreRevenu', 'sommeAvec','totals');
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => 'error'], 422);
        }
        return response()->json($data, 200);
    }
    public function suggestion(Request $request)
    {
        $suggestion = new Suggestion();
        $suggestion->insert([
            "suggestion" => $request->suggestion
        ]);
        toastr()->success(__("finance.Suggestion_send"));

        return back();
    }
    public function annuler()
    {
        toastr()->info('Annulation succes');
        return redirect()->route('proprietaire.finance');
    }
}
