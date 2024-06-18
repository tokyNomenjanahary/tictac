@extends('espace_locataire.index')

@section('locataire-contenue')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            @foreach ($tickets as $ticket)
                <div class="card mb-4 p-3">
                    <div class="row align-items-end">
                        <div class="col">
                            <h4 style="color:blue;" class="m-0">Information</h4>
                        </div>
                        <div class="w-auto">
                            @if ($id_conversation != 0)
                                <a href="{{ route('espaceMessage.showConversation',$id_conversation) }}"  class="btn btn-primary"  style="color: white;" role="button">Voir conversation</a>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3 p-2 border border-2 rounded-0">

                        <h5>Popriétaire: </h5>
                        <p> <span class="badge badge-center rounded-pill bg-primary"
                                style="width:40px;height:40px;">{{ strtoupper(substr($proprietaire->first_name . ' ' . $proprietaire->last_name, 0, 2)) }}</span>
                            {{ $proprietaire->first_name . ' ' . $proprietaire->last_name }}</p>
                        <p><b>Sujet</b>: {{ $ticket->Subject }} </p>
                        <div class="row">
                            <div class="col-md-4">
                                <p><b> Type:</b> <span style="background-color: white;">
                                        {{ $ticket->gettickets->Name }}</span>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Priorité:</b><span style="background-color: white;"> {{ $ticket->Priority[1] }}</span>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p> <b>Etat: </b><span style="background-color: white"> {{ $ticket->Status }}</span>
                                </p>
                            </div>
                        </div>
                        <p><b>Crée le</b>: {{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:m') }}</p>
                        <p><b>Déscription</b></p>
                        <p style=" text-indent: 30px;">{{ $ticket->Subject }} </p>
                    </div>
                    <h5 style="margin-bottom: 0px;">Message(s)</h5>
                    <hr>
                    @foreach ($ticket->getmessage as $message)
                        <div class="mb-3 p-2 border border-2 rounded-0">
                            <p>{{ $message->Message }}</p>
                        </div>
                    @endforeach
                    <h5 style="margin-bottom: 0px;">Document(s)</h5>
                    <hr>
                    @if (count($ticketDocument) != 0)
                        <div class="mb-3 p-2 border border-2 rounded-0">

                            @foreach ($ticketDocument as $document)
                                @if ($document->type == 'pdf' || $document->type == 'docx')
                                    <div class="mt-2" style="color: blue;"><span>{{ $document->name }}</span></div>
                                @else
                                    <img style="width: 100px;height:100px;"
                                        src="/uploads/ticket/document/{{ $document->name }}" alt="document">
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p style="margin-left:5px">0 document</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection
