<?php

namespace App\Http\Controllers;

use Session;
use Exception;
use App\Ticket;
use App\Depense;
use App\Finance;
use App\EtatFile;
use App\Location;
use App\Documents;
use Carbon\Carbon;
use App\TypeTicket;
use App\EspaceMessage;
use App\MessageTicket;
use App\TicketDocument;
use App\backup_locataire;
use App\DiscussionMessage;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreTicketRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Requests\DepenseTicketRequest;
use Intervention\Image\ImageManagerStatic as Image;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $created_by = 1;
        $received_by = 0;
        if (isTenant()) {
            $created_by = 0;
            $received_by = 1;
            //marquer comme lue les notification dans tickets
            $tickets = Ticket::where('is_modifie', 1)->get();
            foreach ($tickets as $ticket) {
                $ticket->is_modifie = 0;
                $ticket->save();
            }
        }

        $tickets = Ticket::where(function ($query) use ($created_by) {
            $query->where('User_created_ticket', Auth::id())
                ->where('for_tenant', $created_by)
                ->where('archive', 0);
        })
            ->orWhere(function ($query) use ($received_by) {
                $query->where('User_destinated_ticket', Auth::id())
                    ->where('for_tenant', $received_by)
                    ->where('archive', 0);
            })
            ->with(['locations', 'gettickets'])
            ->get();

        $tickets_archive = Ticket::where(function ($query) {
            $query->where('User_created_ticket', Auth::id())
                ->orWhere('User_destinated_ticket', Auth::id());
        })
            ->where('archive', '!=', 0)
            ->where('for_tenant', 1)
            ->with(['locations'])
            ->get();
        $locations = Location::where('user_id',  Auth::id())->get();
        $types = TypeTicket::all();


        /* marquer les tickets reçus comme lu */
        if(!isTenant()){
            $ticket_non_lus = Ticket::where('User_destinated_ticket', Auth::id())
            ->where('for_tenant', 0)
            ->where('archive', 0)
            ->where('is_read', false)
            ->update(
                [
                    'is_read' => true
                ]
            );
        }
        return view('Ticket.index', compact('tickets', 'tickets_archive', 'locations', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function formulaire($location = null)
    {
        $documents = Documents::where('user_id', Auth::id())
            ->where('archive', 0)
            ->get();
        $types_tickets = DB::table('type_tickets')->get();
        $proprietaire = [];
        $locataire = [];
        if (isTenant()) {
            $locataire = backup_locataire::where('TenantEmail', Auth::user()->email)->first();
            if ($locataire) {
                $in_location = Location::where('locataire_id', $locataire->id)
                    ->where('archive', 0)
                    ->get();
            } else {
                toastr()->warning("Vous n'êtes dans aucune location.");
                return redirect()->back();
            }
            if (!$locataire || $in_location->isEmpty()) {
                toastr()->warning("Vous n'êtes dans aucune location.");
                return redirect()->back();
            }
            foreach ($locataire->locations as $locataire_location) {
                $proprietaire[$locataire_location->user->id] = $locataire_location->user->first_name . ' ' . $locataire_location->user->last_name;
            }
            $locations = Location::whereIn('user_id', $proprietaire)
                ->where('locataire_id', $locataire->id)
                ->where('archive', 0)
                ->get();
        } else {
            $locations = Location::where('user_id', Auth::id())->select('id', 'identifiant', 'locataire_id')->where('archive', 0)->with(['Locataire'])->get();
        }

        $proprietaire = array_unique($proprietaire);

        if (!is_null($location)) {
            if (!intval($location)) {
                $id = Crypt::decrypt($location);
            } else {
                $id = $location;
            }
            $location_reference = Location::find($id);
            return view('Ticket.create', compact('locations', 'types_tickets', 'location_reference', 'documents', 'proprietaire'));
        }
        // dd($locations);
        return view('Ticket.create', compact('locations', 'types_tickets', 'documents', 'proprietaire', 'locataire'));
    }



    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTicketRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTicketRequest $request)
    {
        $this->deleteOldTicketFiles();
        $data = $request->all();
        DB::beginTransaction();

        $location = Location::find($data['location']);
        if (!isTenant() && $location->Locataire->user_account_id == 0) {
            toastr()->error("Votre locataire n'a pas de compte. Veuillez l'inviter s'il vous plait");
            return back()->withInput();
        }

        /*  liste des documents deja existant */
        $documents_ids = [];
        foreach ($data as $key => $value) {
            if (is_int(stripos($key, "doc-"))) {
                $documents_ids[] = $value;
            }
        };

        try {
            $ticket = new Ticket();
            $ticket->type_ticket_id = $data['type_ticket_id'];
            $ticket->Subject = $data['sujet'];
            $ticket->Priority = $data['priorite'];
            $ticket->Date_dernier_modif = now();
            $ticket->location_id = $data['location'];
            $ticket->Status = 0;
            $ticket->User_created_ticket = Auth::id();
            if (isTenant()) {
                $ticket->for_tenant = 0;
                $ticket->User_destinated_ticket = $location->user_id;
                $email = $location->user->email;
            } else {
                $ticket->for_tenant = 1;
                $ticket->User_destinated_ticket = $location->Locataire->user_account_id;
                $email = $request->emailLocataire;
            }

            $ticket->save();

            if ($data['plugin']) {
                $plugins = explode(",", $data['plugin']);
                foreach ($plugins as $plugin) {
                    TicketDocument::where('id', $plugin)->update([
                        'ticket_id' => $ticket->id,
                    ]);
                }
            }
            /* documents dèjà existant */
            for ($i = 0; $i < count($documents_ids); $i++) {
                Documents::whereIn('id',   $documents_ids)
                    ->update([
                        'ticket_id' => $ticket->id
                    ]);
            }

            if ($request->hasFile('input-file')) {
                $files = $request->file('input-file');
                foreach ($files as $file) {
                    if ($file !== null) {
                        $validator = Validator::make(['file' => $file], ['file' => 'image']);
                        if ($validator->fails()) {
                            $fileName = time() . uniqid() . '.' . $file->getClientOriginalExtension();
                            $file->storeAs('uploads/ticket/document', $fileName, 'file');
                            TicketDocument::create([
                                'ticket_id' => $ticket->id,
                                'name' => $fileName,
                                'type' => $file->getClientOriginalExtension(),
                            ]);
                        } else {
                            $fileName = time() . uniqid() . '.' . $file->getClientOriginalExtension();
                            $img = Image::make($file);
                            $img->save(storage_path('uploads/ticket/document/' . $fileName), 75);
                            TicketDocument::create([
                                'ticket_id' => $ticket->id,
                                'name' => $fileName,
                                'type' => $file->getClientOriginalExtension(),
                            ]);
                        }
                    }
                }
            }

            //notifier par email le locataire ou le proprietaire
            Mail::raw('Bonjour, Vous venez de recevoir un nouveau message, veuillez consulter votre espace locataire pour pouvoir voir et répondre', function (Message $message) use ($email) {
                $message->to($email);
                $message->subject('Message reçu');
            });


            /* debut discussion */
            if ($request->has('creer_discussion')) {
                $espace_message = new EspaceMessage();
                $espace_message->id_ticket = $ticket->id;
                $espace_message->sujet =  $ticket->Subject;
                $espace_message->id_user_sender = Auth::id();
                $espace_message->for_tenant = $ticket->for_tenant;
                if (isTenant()) {
                    $espace_message->id_user_receiver = $location->user_id;
                } else {
                    $espace_message->id_user_receiver = $location->Locataire->user_account_id;
                }
                $espace_message->save();

                /* message d'une discussion */
                $discussion = new DiscussionMessage();

                $variables = ['x_destinataire', 'x_idenfitifiant', 'x_adresse', 'x_date', 'x_sujet', 'x_expediteur'];
                $variables_values = [$espace_message->getUserReceiver->first_name, $location->Logement->identifiant, $location->Logement->adresse, Carbon::now()->format('d/m/Y'), $ticket->Subject, Auth::user()->first_name];
                $message_text = replaceVariableWithValue(__('espace_message.ticket'), $variables, $variables_values);
                $discussion->message = $message_text;
                $discussion->espace_message_id = $espace_message->id;
                $discussion->id_user_sender = Auth::id();
                $discussion->id_user_receiver =  $espace_message->id_user_receiver;
                $discussion->save();
            }

            /* fin discussion */
            DB::commit();
            toastr()->success(__('Ticket crée avec succès'));
        } catch (\Throwable $th) {
            DB::rollBack();
            toastr()->error(__('alert.iventaire_error' . $th));
            //toastr()->error($th->getMessage());
            return back()->withInput();
        }
        return redirect()->route('ticket.index');
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->strIds;
        $tickets = Ticket::WhereIn('id', explode(",", $ids))->get();
        foreach ($tickets as $ticket) {
            $ticket->delete();
            $ticket_en_corbeille = Ticket::withTrashed()->findOrFail($ticket->id);
            Corbeille('ticket', 'tickets', $ticket_en_corbeille->Subject, $ticket_en_corbeille->deleted_at, $ticket_en_corbeille->id);
        }
        toastr()->success(__('Ticket supprimer avec succès'));
        return response()->json(['status' => true, 'message' => "location archiver"]);
    }



    public function archive($id)
    {
        Ticket::where('id',  $id)
            ->update([
                'archive' => DB::raw('CASE WHEN archive = 1 THEN 0 ELSE 1 END')
            ]);
        toastr()->success(__('Ticket archivé avec succès'));
        return redirect()->route('ticket.index');
    }
    public function archiveMultiple(Request $request)
    {
        $ids = $request->strIds;
        Ticket::whereIn('id',  explode(",", $ids))
            ->update([
                'archive' => DB::raw('CASE WHEN archive = 1 THEN 0 ELSE 1 END')
            ]);
        toastr()->success(__('Ticket archivé avec succès'));
        return response()->json(['status' => true, 'message' => " "]);
    }


    public function changeStatus(Request $request)
    {
        $this->deleteOldTicketFiles();
        $ticket = Ticket::find($request->ticket_id);
        if ($request->valeur == 2) {
            $ticket->update([
                'Status' => $request->valeur,
                'archive' => !$ticket->archive,
                'is_modifie' => 1
            ]);
        }
        $ticket->update([
            'Status' => $request->valeur,
            'is_modifie' => 1
        ]);
        toastr()->success(__('Statut changé avec success'));
        return response()->json();
    }

    public function TickectDepense($id)
    {
        $detailTicket = Ticket::where('id', $id)->first();
        if (!$detailTicket) {
            toastr()->error('Ce ticket n\'existe plus!');
            return redirect('/ticket');
        }
        $location = Location::where('id', $detailTicket->Location_id)->with('Logement')->first();
        $ticket_id = $id;
        // $bien = $location->Logement->identifiant;
        // dd($location);
        return view('Ticket.TicketDepense', compact('location', 'ticket_id'));
    }

    public function saveDepenseTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'bien' => 'required',
            'logement_id' => 'required',
            'ticket_type' => 'required',
            'payer_a' => 'required',
            'date_depense' => 'required',
            'montant' => 'required',
            'location_id' => 'required',
            'locataire_id' => 'required',
        ]);

        if ($validator->passes()) {
            $data = array();
            $data['logement_id'] = $request->logement_id;
            $data['location_id'] = $request->location_id;
            $data['locataire_id'] = $request->locataire_id;
            $data['Description'] = $request->description;
            $data['debut'] = $request->date_depense;
            $data['montant'] = $request->montant;
            $data['type'] = 'depense';
            $data['etat'] = 2;
            $data['user_id'] = Auth::id();
            $finance = Finance::create($data);
            $depense = Depense::create([
                'finance_id' => $finance,
                'Autres_informations' => $request->description
            ]);
            DB::table('depense_tickets')->insert([
                'description' => $request->description,
                'ticket_id' => $request->ticket_id,
                'logement_id' => $request->logement_id,
                'ticket_type' => $request->ticket_type,
                'payer_a' => $request->payer_a,
                'date_depense' => $request->date_depense,
                'montant' => $request->montant,
                'location_id' => $request->location_id,
                'locataire_id' => $request->locataire_id,
            ]);
            toastr()->success('Depense de ticket bien enregistré');
            return redirect('/ticket');
        } else {
            toastr()->error('Veuillez remplir les champs requis.');
            return back()->withErrors($validator)->withInput();
        }
    }


    public function suppression($id)
    {
        $this->deleteOldTicketFiles();
        Ticket::WhereIn('id', explode(",", $id))->delete();
        $ticket_en_corbeille = Ticket::withTrashed()->findOrFail($id);
        Corbeille('ticket', 'tickets', $ticket_en_corbeille->Subject, $ticket_en_corbeille->deleted_at, $ticket_en_corbeille->id);
        toastr()->success(__('Ticket supprimer avec succès'));
        return back();
        Ticket::WhereIn('id', explode(",", $id))->delete();
        $ticket_en_corbeille = Ticket::withTrashed()->findOrFail($id);
        Corbeille('ticket', 'tickets', $ticket_en_corbeille->Subject, $ticket_en_corbeille->deleted_at, $ticket_en_corbeille->id);
        toastr()->success(__('Ticket supprimer avec succès'));
        return back();
    }

    public function modification($id)
    {
        $this->deleteOldTicketFiles();

        $documents = Documents::where('user_id', Auth::id())
            ->where('archive', 0)
            ->get();

        $types_tickets = DB::table('type_tickets')->get();
        $locations = Location::where('user_id', Auth::id())->select('id', 'identifiant', 'locataire_id')->where('archive', 0)->with(['Locataire'])->get();
        $ticket = Ticket::where('id', $id)->with(['locations', 'gettickets'])->first();
        $ticketDocument = TicketDocument::where('ticket_id', $id)->get();
        Session::put('id', $id);
        $document_existant = Documents::where('ticket_id', $id)->get();
        return view('Ticket.modifTicket', compact('locations', 'documents', 'types_tickets', 'ticket', 'ticketDocument', 'document_existant'));
    }

    public function uploadTicketFiles(Request $request)
    {
        $this->deleteOldTicketFiles();
        if ($request->hasFile('input-file')) {
            $files = $request->file('input-file');
            $file = $files[0];
            if ($file !== null) {
                $validator = Validator::make(['file' => $file], ['file' => 'image']);
                if ($validator->fails()) {
                    $fileName = time() . uniqid() . '.' . $file->getClientOriginalExtension();
                    $document = TicketDocument::create([
                        'ticket_id' => 0,
                        'name' => $fileName,
                        'type' => $file->getClientOriginalExtension(),
                    ]);
                    $file->storeAs('uploads/ticket/document', $fileName, 'file');
                } else {
                    $fileName = time() . uniqid() . '.' . $file->getClientOriginalExtension();
                    $img = Image::make($file);
                    $document = TicketDocument::create([
                        'ticket_id' => 0,
                        'name' => $fileName,
                        'type' => $file->getClientOriginalExtension(),
                    ]);
                    $img->save(storage_path('uploads/ticket/document/' . $fileName), 75);
                }
            }

            $type = in_array($file->getClientOriginalExtension(), ["docx", "doc", "pdf", "txt", "odt", "rtf", "pptx", "ppt", "xlsx", "xls"]) ? "file" : "image";

            return response()->json([
                'initialPreview' => [
                    '/uploads/ticket/document/' . $fileName,
                ],
                'initialPreviewConfig' => [
                    [
                        'type' => $type,
                        'caption' => $document->name,
                        'id' => $document->id,
                        'url' => route('ticket.DeleteTicketFiles'),
                        'key' => $document->id,
                        'extra' => [
                            'id' => $document->id,
                            "type" => "deleted",
                            'file_name' => $document->name,
                        ]
                    ],
                ],
            ]);
        }
    }


    public function DeleteTicketFiles(Request $request)
    {
        $file_data = $request->input();
        $file = TicketDocument::findOrFail($file_data["id"]);
        $file->delete();
        if (is_file(storage_path('uploads/ticket/document/' . $file_data['file_name']))) {
            unlink(storage_path('uploads/ticket/document/' . $file_data['file_name']));
        }
        return response()->json(['success' => true]);
    }

    public function deleteOldTicketFiles()
    {
        $dateLimite = Carbon::now()->subDays(3);
        $oldRecords = TicketDocument::where('ticket_id', 0)->where('created_at', '<', $dateLimite)->get();
        foreach ($oldRecords as $record) {
            if (is_file(storage_path('uploads/ticket/document/' . $record->name))) {
                unlink(storage_path('uploads/ticket/document/' . $record->name));
            }
            $record->delete();
        }
    }

    public function sauvermodif(Request $request)
    {
        $id = Session::get('id');
        $data = $request->all();
        // dd($data);
        try {
            if ($data['plugin']) {
                $plugins = explode(",", $data['plugin']);
                foreach ($plugins as $plugin) {
                    TicketDocument::where('id', $plugin)->update([
                        'ticket_id' => $id,
                    ]);
                }
            }

            /* documents dèjà existant */
            $documents_ids = [];
            foreach ($data as $key => $value) {
                if (is_int(stripos($key, "doc-"))) {
                    $documents_ids[] = $value;
                }
            };

            Documents::where('ticket_id', $id)
                ->update([
                    'ticket_id' => null
                ]);
            for ($i = 0; $i < count($documents_ids); $i++) {
                Documents::whereIn('id',   $documents_ids)
                    ->update([
                        'ticket_id' => $id
                    ]);
            }

            /* fin documents dèjà existant */

            DB::table('tickets')->where('id', $id)->update([
                'subject' => $data['sujet'],
                'type_ticket_id' => $data['type_ticket'],
                'Priority' => $data['priorite'],
                'Date_dernier_modif' => now()
            ]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            throw new Exception();
        }
        toastr()->success('modification succèes');
        return redirect()->route('ticket.index');
    }
    public  function  Tickectdetails($id)
    {
        /* Pour l'espace locataire (role_id 2 pour un locataire) */
        $conversation = EspaceMessage::where('id_ticket', $id)->first();
        if (!$conversation) {
            $id_conversation = 0;
        } else {
            $id_conversation = $conversation->id;
        }
        if (Auth::user()->role_id == 2) {
            $id_proprio = Ticket::find($id)->locations->user_id;
            $proprietaire = DB::table('users')->select('first_name', 'last_name')->where('id', $id_proprio)->first();
            $tickets = Ticket::where('id', $id)->with(['locations', 'gettickets', 'getdepense', 'getmessage'])->get();
            $ticketDocument = TicketDocument::where('ticket_id', $id)->get();
            return view('espace_locataire.ticket.details', compact('tickets', 'ticketDocument', 'proprietaire', 'id_conversation'));
        }
        $ticket = Ticket::where('id', $id)->with(['locations', 'gettickets', 'getdepense', 'getmessage'])->first();
        $ticketDocument = TicketDocument::where('ticket_id', $id)->get();

        return view('Ticket.detailticket', compact('ticket', 'ticketDocument', 'id_conversation'));
    }

    public function reouvrir($id)
    {
        $ticket = Ticket::find($id);
        $ticket->update([
            'Status' =>  1,
            'archive' => 0
        ]);
        return redirect()->route('ticket.index');
    }

    public function renderSelectView($locataire_id, $user_proprietiare_id)
    {
        $locations = Location::where('user_id', $user_proprietiare_id)
            ->where('locataire_id', $locataire_id)
            ->where('archive', 0)
            ->get();
        return view('Ticket.location_select_form', compact('locations'));
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTicketRequest  $request
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
