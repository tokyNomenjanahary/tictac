<?php

namespace App\Http\Controllers;

use App\Agenda;
use App\Documents;
use App\Finance;
use App\LocatairesGeneralInformations;
use App\quittance;
use App\Ticket;
use App\User;
use Carbon\Carbon;
use App\Location;
use App\UserProfiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class EspaceLocataireController extends Controller
{
    public function index()
    {
        //recuperation de locatoin et loyer , en cas de la necessite de liste utilisons get pour les listes
        $user_id = Auth::id();
        $locataires = LocatairesGeneralInformations::where('user_account_id',$user_id)->get();
        $locataireId = [];
        foreach($locataires as $locataire){
            $locataireId[] = $locataire->id;
        }
        //recuperation de location
        $locations = Location::whereIn('locataire_id',$locataireId)->get();
        //finances not payd
        $etat_finance   = [];
        $etat_location=[];
        foreach ($locations as $location) {
            //fin du bail
                $etat_location[] = [$location->identifiant];
            $finances = Finance::where('location_id', $location->id)
                ->where('Etat', 1)
                ->get();
            foreach ($finances as $finance) {
                $etat_finance[] = [$location->identifiant];
            }
        }

        //reuperation ticket locataire
        $tickets = Ticket::where('User_created_ticket', Auth::id())
                        ->orWhere('User_destinated_ticket', Auth::id())
                        ->where('archive', 0)
                        ->count();

        //recupereation des quittances
        $quittances=quittance::where('user_id_destinataire',Auth::user()->id)->count();

        /*** Recuperation des 5 prochains rendez-vous du locataire ***/
        $agendas = Agenda::where('userId_locataire', Auth::id())
                        ->where('status', 1)
                        ->orderBy('start_time', 'asc')
                        ->take(5)
                        ->get();

// dd($agendas);
        return view('espace_locataire.layouts.dashboard',compact('etat_finance','etat_location','tickets','quittances', 'agendas'));
    }

    public function listTicket()
    {
        $tickets = Ticket::where('User_created_ticket', Auth::id())
                            ->orWhere('User_destinated_ticket', Auth::id())
                            ->where('archive', 0)
                            ->with(['locations', 'gettickets'])
                            ->get();
        //return view('espace_lo');

    }
    public  function mesinfo()
    {
        $locataireInfo = LocatairesGeneralInformations::where('user_account_id', Auth::user()->id)->with(['LocatairesComplementaireInformations','LocatairesGarants','LocataireUrgence'])->first();
        return view('espace_locataire.information.locataireInfo',compact('locataireInfo'));
    }
    public  function mesproprio(){
        $proprios = LocatairesGeneralInformations::where('user_account_id', Auth::user()->id)
        ->join('users', 'users.id', '=', 'locataires_general_informations.user_id')
            ->join('user_profiles', 'user_profiles.user_id', '=', 'locataires_general_informations.user_id')
            ->select('users.*','user_profiles.*','locataires_general_informations.id as loc_id')
            ->get();
//         dd($proprios);
        return view('espace_locataire.information.proprietaireInfo',compact('proprios'));
    }
    public  function  infoplus($id)
    {
//        $id ici est id de locataire
        $proprio = LocatairesGeneralInformations::where('locataires_general_informations.id',$id)
            ->join('users', 'users.id', '=', 'locataires_general_informations.user_id')
            ->join('user_profiles', 'user_profiles.user_id', '=', 'locataires_general_informations.user_id')
            ->join('logements', 'logements.user_id', '=', 'locataires_general_informations.user_id')
            ->select('users.*','logements.*','user_profiles.*','locataires_general_informations.id as loc_id')
            ->first();

        return view('espace_locataire.information.proprietaireDetailsInfo',compact('proprio'));
    }
    public  function  quittance()
    {
        $quittances=quittance::where('user_id_destinataire',Auth::user()->id)->get();

        //marquer comme lue tous les quittances lorsque le locataire accede au page quittance
            foreach ($quittances as $quittance) {
                $quittance->is_lue = 1;
                $quittance->save();
        }
        return view('espace_locataire.information.listequittance',compact('quittances'));
    }
    public function suppression($id)
    {
        $quittance= quittance::findOrFail($id);
        $path = public_path('uploads/Finance/' . $quittance->quittance);
        if (File::exists($path)) {
            File::delete($path);
        }
        $quittance->delete();

        toastr()->success(__("finance.Suppression_success"));
        return back();
    }
    public function  download($id){
        $quittance = quittance::findOrFail($id);
        $path = public_path('uploads/Finance/' . $quittance->quittance);
        return response()->download($path);
    }
    public function visualiser($id)
    {
        $quittance=quittance::where("id",$id)->first();
        $proprietaire=User::where('id',$quittance->user_id_sender)->first();
        $location = Finance::where('id', $quittance->finance_id)->with(['Locataire', 'Logement'])->first();
        $numbre = UserProfiles::where('user_id', Auth::user()->id)->first();
        return view('espace_locataire.information.showquittance',compact('location','numbre','proprietaire','quittance'));
    }
    public  function visualiserrecu($id)
    {
        $quittance=quittance::where("id",$id)->first();
        $proprietaire=User::where('id',$quittance->user_id_sender)->first();
        $location = Finance::where('id', $quittance->finance_id)->with(['Locataire', 'Logement'])->first();
        $numbre = UserProfiles::where('user_id', Auth::user()->id)->first();;
        return view("espace_locataire.information.recuLocataire", compact('location','numbre','proprietaire','quittance'));
    }
    public function mynotification()
    {
        $user_id = Auth::id();
        $locataires = LocatairesGeneralInformations::where('user_account_id',$user_id)->get();
        $locataireId = [];
        foreach($locataires as $locataire){
            $locataireId[] = $locataire->id;
        }
        //recuperation de location
        $locations = Location::whereIn('locataire_id',$locataireId)->get();
        //finances not payd
            $etat_finance   = [];
            $etat_location=[];
        foreach ($locations as $location) {
            $date_fin = Carbon::parse($location->fin);
            $date_actuelle=date('m');
            //fin du bail
            if ($date_fin->month==$date_actuelle)
            {
                $etat_location[] = [$location->identifiant];
            }
            $finances = Finance::where('location_id', $location->id)
                ->where('Etat', 1)
                ->get();
            foreach ($finances as $finance) {
                $etat_finance[] = [$location->identifiant];
            }
        }
//        dd($etat_finance,$etat_location);
      return view('espace_locataire.information.notification',compact('etat_finance','etat_location'));
    }
}
