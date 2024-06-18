@extends('proprietaire.index')

@section('contenue')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card mb-4 p-3">
                <div class="row align-items-end mb-3">
                    <div class="col">
                        <h4 style="color:blue;" class="m-0">{{ __('ticket.details') }}</h4>
                    </div>
                    <div class="w-auto">
                        @if ($id_conversation != 0)
                            <a href="{{ route('espaceMessage.showConversation', $id_conversation) }}" class="btn btn-primary"
                                style="color: white;" role="button">Voir conversation</a>
                        @endif
                    </div>
                </div>
                <div class="mb-3 p-2 border border-2 rounded-0">
                    <h5>{{ __('ticket.sujet') }}</h5>

                    <p>{{ $ticket->Subject }} </p>
                    <h5>{{ __('ticket.location') }}</h5>
                    <p>{{ $ticket->locations->identifiant }} </p>
                    <div class="row">
                        <div class="col-md-4">
                            <h5>{{ __('ticket.chauffage') }}</h5>
                            <span
                                style="background-color: white;padding: 4px 10px 4px 10px;">{{ $ticket->gettickets->Name }}</span>
                        </div>
                        <div class="col-md-4">
                            <h5>{{ __('ticket.Priorité') }}</h5>
                            <span
                                style="background-color: white;padding: 4px 10px 4px 10px;">{{ $ticket->Priority[1] }}</span>
                        </div>
                        <div class="col-md-4">
                            <h5> {{ __('ticket.Etat') }} </h5>
                            <span style="background-color: white;padding: 4px 10px 4px 10px;">{{ $ticket->Status }}</span>
                        </div>
                    </div>
                </div>
                <div class="mb-3 p-2 border border-2 rounded-0">
                    <h5>{{ __('ticket.Déscription') }}</h5>
                    <p>{{ $ticket->Subject }}</p>
                </div>
                @if ($ticket->getdepense)
                    <div class="mb-3 p-2 border border-2 rounded-0">
                        @foreach ($ticket->getdepense as $depense)
                            <p class="text-info"> {{ Auth::user()->first_name }}{{ __('ticket.rajoute') }}
                                {{ $depense->date_depense }}</p>
                            <h5>{{ __('ticket.depense') }}: {{ $depense->montant }}</h5>
                            <h5>{{ __('ticket.Déscription') }}: {{ $depense->description }}</h5>
                            <h5>{{ __('ticket.paye') }}: {{ $depense->payer_a }}</h5>
                            <hr>
                        @endforeach
                    </div>
                @endif
                <div class="mb-3 p-2 border border-2 rounded-0">
                    <h5>{{ __('ticket.Documents') }} : </h5>
                    @foreach ($ticketDocument as $document)
                        @if ($document->type == 'pdf' || $document->type == 'docx')
                            <div class="mt-2" style="color: blue;"><span>{{ $document->name }}</span></div>
                        @else
                            <img style="width: 100px;height:100px;" src="/uploads/ticket/document/{{ $document->name }}"
                                alt="document">
                        @endif
                    @endforeach
                </div>
            </div>
            {{-- <div class="card mb-4 p-3">
                    <div class="p-2 border border-2 rounded-0">
                        <h5 class="text-warning">{{__("ticket.Réponse")}}</h5>
                        <form>
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-phone">{{__("ticket.Locataire")}}</label>
                                <input type="text" readonly id="basic-default-phone" value="{{$ticket->locations->Locataire->TenantFirstName }}" class="form-control phone-mask">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-message">{{__("ticket.Message")}} </label>
                                <textarea id="basic-default-message" placeholder="votre nouveau message ici..." class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-phone">{{__("ticket.Documents")}} </label>
                                <input type="file" id="basic-default-phone"
                                       class="form-control phone-mask">
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    {{__('depense.Annuler')}}
                                </button>
                                <button type="button" class="btn btn-primary">{{__('depense.Sauvegarder')}}</button>
                            </div>
                        </form>
                    </div>
                </div> --}}
        </div>
    </div>

@endsection
