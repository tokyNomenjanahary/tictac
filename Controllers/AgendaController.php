<?php

namespace App\Http\Controllers;

use App\Agenda;
use App\LocatairesGeneralInformations;
use App\Location;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Finance;
use App\backup_locataire;
use App\Http\Requests\StoreAgendaRequest;
use App\Http\Requests\UpdateAgendaRequest;
class AgendaController extends Controller
{
    //
    public function agenda()
    {
        //donnnes pour le clendrier et datatables
        $events = [];

        $rdvs = Agenda::where('userId_proprietaire', Auth::id())
                ->get();

        foreach ($rdvs as $rdv) {
            $locataire = LocatairesGeneralInformations::where('user_account_id',$rdv->userId_locataire)->first();
            $events[] = [
                'title' => $rdv->objet,
                'adresse' => $rdv->adresse_rdv,
                'description' => $rdv->description,
                'locataire' => $locataire->TenantFirstName,
                'start' => $rdv->start_time,
                'end' => $rdv->finish_time,
            ];
        }
        $locale = App::getLocale();
        return view('proprietaire.agenda.agenda', compact('events', 'rdvs','locale'));
    }

    public function nouveau()
    {
        $locations = Location::where('user_id', Auth::id())->get();
        return view('proprietaire.agenda.nouveau', compact('locations'));
    }

    public function saveorupdate(Request $request)
    {
        Validator::extend('heure_valide', function ($attribute, $value, $parameters, $validator) {
            $heure = date('H', strtotime($value));
            return $heure >= 8 && $heure <= 22;
        });
        $validator = Validator::make($request->all(), [
            'objet' => 'required',
            'locataire_id' => 'required',
            'debut' => 'required|date_format:Y-m-d\TH:i|heure_valide',
            'fin' => 'required|date_format:Y-m-d\TH:i|after:debut|heure_valide',
            'description' => 'required',
            'lieu' => 'required',
        ],
            [
//                'debut' => 'required|date_format:Y-m-d\TH:i|after:now|heure_valide',
                'heure_valide' => __('agenda.valide_heure'),
            ]);

        if ($validator->passes()) {
            //modification
            if($request->filled('agenda_id')){
                 $id=$request->agenda_id;
                 $agendas = Agenda::find($id);

                $agendas->objet = $request->objet;
                $agendas->description = $request->description;
                $agendas->start_time = $request->debut;
                $agendas->finish_time = $request->fin;
                $agendas->adresse_rdv = $request->lieu;
                $agendas->status = 0;
                $agendas->update();

                return response()->json(['success' => true, 'messages' => 'Modification success']);
             }else{
                 //insertion
                 $agendas = new Agenda();
                 $agendas->objet = $request->objet;
                 $agendas->description = $request->description;
                 $agendas->start_time = $request->debut;
                 $agendas->finish_time = $request->fin;
                 $agendas->adresse_rdv = $request->lieu;
                 $agendas->userId_proprietaire = Auth::id();
                 $agendas->userId_locataire = $request->locataire_id;
                 $agendas->locataire_id = $request->id_locataire;
                 $agendas->location_id = $request->locationId;
                 $agendas->status = 0;

                 $agendas->save();

                 return response()->json(['success' => true, 'messages' => 'Insertion success']);
             }

        } else {
            return response()->json(['errors' => $validator->errors()]);
        }
    }

    public function locataire(Request $request)
    {
        $location = Location::where('id', $request->locationId)->with('Locataire','Logement')->first();
        return response()->json($location);
    }
    public function index()
    {
        $user_id = Auth::id();
        $agendas = Agenda::where('userId_locataire', $user_id)
            ->get();
        $user_id = Auth::id();
        $locataires = backup_locataire::where('user_account_id', $user_id)->get();
        if ($locataires) {
            $locataireId = [];
            foreach ($locataires as $locataire) {
                $locataireId[] = $locataire->id;
            }
            $etat_finance = [];
            $locations = Location::whereIn('locataire_id', $locataireId)->get();
            if (count($locations) == 0) {
                $locations = "vide";
            } else {
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
        } else {
            $locations = "vide";
        }

        $events = [];

        foreach ($agendas as $agenda) {
            $event = [
                'id' => $agenda->id,
                'url' => '',
                'title' => $agenda->objet,
                'start' => $agenda->start_time, // Remplacez start_date par le nom de votre colonne contenant la date de début de l'événement
                'end' => $agenda->finish_time, // Remplacez end_date par le nom de votre colonne contenant la date de fin de l'événement
                'allDay' => false, // Assurez-vous d'avoir une colonne booléenne dans votre base de données pour gérer si l'événement dure toute la journée ou non
                'extendedProps' => [
                    'calendar' => $agenda->couleur,
                ],
                'description' => $agenda->description,
                'lieu' => $agenda->adresse_rdv,
                'cree_par' => $agenda->cree_par,
                'location_id' => $agenda->location_id,
                'status' => $agenda->status
            ];

            $events[] = $event;
        }
        // dd( $locations);
        $eventsJson = json_encode($events);
        // dd($eventsJson);
        return view('espace_locataire.agenda.index', compact('eventsJson', 'locations'));
    }


    public function ajout(Request $request)
    {
        // dd($request->data);
        $data = $request->data;
        $location = Location::find($data['location']);
        $agenda = Agenda::create([
            'objet' => $data['title'],
            'couleur' => $data['calendar'],
            'description' => $data['description'],
            'start_time' => $data['start'],
            'finish_time' => $data['end'],
            'adresse_rdv' => $data['lieu'],
            'status' => 0,
            'userId_proprietaire' => $location->user_id,
            'userId_locataire' => $location->Locataire->user_account_id,
            'cree_par' => 1,
            'location_id' => $data['location'],
            'locataire_id' => $location->Locataire->id
        ]);
        $event = [
            'id' => $agenda->id,
            'url' => '',
            'title' => $agenda->objet,
            'start' => $agenda->start_time, // Remplacez start_date par le nom de votre colonne contenant la date de début de l'événement
            'end' => $agenda->finish_time, // Remplacez end_date par le nom de votre colonne contenant la date de fin de l'événement
            'allDay' => false, // Assurez-vous d'avoir une colonne booléenne dans votre base de données pour gérer si l'événement dure toute la journée ou non
            'extendedProps' => [
                'calendar' => $agenda->couleur,
            ],
            'description' => $agenda->description,
            'lieu' => $agenda->adresse_rdv,
            'cree_par' => $agenda->cree_par,
            'location_id' => $agenda->location_id
        ];
        return response()->json($event);
    }

    public function Modifier(Request $request)
    {
        $data = $request->data;
        $location = Location::find($data['extendedProps']['location']);
        // dd($data);
        $agenda = Agenda::find($data['id']);
        $agenda->update([
            'objet' => $data['title'],
            'couleur' => $data['extendedProps']['calendar'],
            'description' => $data['extendedProps']['description'],
            'start_time' => $data['start'],
            'finish_time' => $data['end'],
            'adresse_rdv' => $data['lieu'],
            'status' => 1,
            'userId_proprietaire' => $location->user_id,
            'userId_locataire' => $location->Locataire->user_account_id,
            'location_id' => $data['extendedProps']['location'],
            'locataire_id' => $location->Locataire->id
        ]);
        $agenda = Agenda::find($data['id']);
        $event = [
            'id' => $agenda->id,
            // 'url' => '',
            'title' => $agenda->objet,
            'start' => $agenda->start_time, // Remplacez start_date par le nom de votre colonne contenant la date de début de l'événement
            'end' => $agenda->finish_time, // Remplacez end_date par le nom de votre colonne contenant la date de fin de l'événement
            // Assurez-vous d'avoir une colonne booléenne dans votre base de données pour gérer si l'événement dure toute la journée ou non
            'extendedProps' => [
                'calendar' => $agenda->couleur,
                'description' => $agenda->description,
                'lieu' => $agenda->adresse_rdv,
                'location_id' => $agenda->location_id,
                'status' => $agenda->status
            ],
        ];
        return response()->json($event);
    }
    public function getAgenda()
    {
        $agendas = Agenda::all();
        $events = [];

        foreach ($agendas as $agenda) {
            $event = [
                'id' => $agenda->id,
                'url' => '',
                'title' => $agenda->objet,
                'start' => $agenda->start_time, // Remplacez start_date par le nom de votre colonne contenant la date de début de l'événement
                'end' => $agenda->finish_time, // Remplacez end_date par le nom de votre colonne contenant la date de fin de l'événement
                'allDay' => false, // Assurez-vous d'avoir une colonne booléenne dans votre base de données pour gérer si l'événement dure toute la journée ou non
                'extendedProps' => [
                    'calendar' => 'ETC',
                ],
            ];

            $events[] = $event;
        }
        // dd( $locations);
        $eventsJson = json_encode($events);
        return $eventsJson;
    }

    public function supprimer(Request $request)
    {
        // dd($request);
        $agenda = Agenda::find($request->id);
        $agenda->delete();
        return response()->json();
    }
    public function editer($id)
    {
        $agenda = Agenda::where('id',$id)->first();
        $locationActuelle=Location::where('id',$agenda->location_id)->with('Locataire')->first();
        $locations = Location::where('user_id', Auth::id())->get();
        return view('proprietaire.agenda.nouveau',compact('agenda','locations','locationActuelle'));
    }
    public function status(Request $request)
    {
        $agenda=Agenda::find($request->id);
        $agenda->status=$request->status;
        $agenda->update();

        return response()->json(['success' => true]);
    }


    public function accepter(Request $request) {
        // dd($request->id);
        $agenda = Agenda::find($request->id);
        $agenda->update([
            'status' => 1
        ]);
        $agenda = Agenda::find($request->id);
        $event = [
            'id' => $agenda->id,
            // 'url' => '',
            'title' => $agenda->objet,
            'start' => $agenda->start_time, // Remplacez start_date par le nom de votre colonne contenant la date de début de l'événement
            'end' => $agenda->finish_time, // Remplacez end_date par le nom de votre colonne contenant la date de fin de l'événement
            // Assurez-vous d'avoir une colonne booléenne dans votre base de données pour gérer si l'événement dure toute la journée ou non
            'extendedProps' => [
                'calendar' => $agenda->couleur,
                'description' => $agenda->description,
                'lieu' => $agenda->adresse_rdv,
                'location_id' => $agenda->location_id,
                'status' => $agenda->status
            ],
        ];
        return response()->json($event);
    }

    public function refuser(Request $request) {
        // dd($request->id);
        $agenda = Agenda::find($request->id);
        $agenda->update([
            'status' => 2
        ]);
        $agenda = Agenda::find($request->id);
        $event = [
            'id' => $agenda->id,
            // 'url' => '',
            'title' => $agenda->objet,
            'start' => $agenda->start_time, // Remplacez start_date par le nom de votre colonne contenant la date de début de l'événement
            'end' => $agenda->finish_time, // Remplacez end_date par le nom de votre colonne contenant la date de fin de l'événement
            // Assurez-vous d'avoir une colonne booléenne dans votre base de données pour gérer si l'événement dure toute la journée ou non
            'extendedProps' => [
                'calendar' => $agenda->couleur,
                'description' => $agenda->description,
                'lieu' => $agenda->adresse_rdv,
                'location_id' => $agenda->location_id,
                'status' => $agenda->status
            ],
        ];
        return response()->json($event);
    }
}

