<style>
    #showLocataire_length {
        display: none;
    }
    #showLocataire_filter{
        display: none;
    }
    th {
        color: blue !important;
        font-size: 10px !important;
    }

    td {
        font-size: 13px;
    }

    .dataTables_empty {
        display: none !important;
    }

    .dataTables_info {
        display: none !important;
    }

    .h5 {
        display: none;
    }
</style>
<div id="navs-top-actif">
    <div class="table-responsive text-nowrap">
        <table id="showLocataire" class="table">
            <thead>
                <tr>
                    <th class="d-none">type</th>
                    <th><input type="checkbox" class="checkbox_input align-middle select_all_state "
                            style="height: 20px;width:20px;"></th>
                    <th>{{ __('revenu.Location') }}</th>
                    <th>{{ __('ticket.sujet') }}</th>
                    <th class="d-none">Etat</th>
                    <th>{{ __('ticket.etat')}}</th>
                    <th>{{ __('ticket.createur')}}</th>
                    <th>{{ __('ticket.mis_a_jour')}}</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($tickets as $ticket)
                    <tr>
                        <td class="d-none">{{ $ticket->gettickets->Name }}</td>
                        <td style="width: 5px" class="check">
                            <input type="checkbox" id="selectionner"
                                class="checkbox_input align-middle checkbox sub_chk" style="height: 15px;width:15px;"
                                data-id="{{ $ticket->id }}">
                        </td>
                        <td><a href="{{route('ficheLocation',$ticket->locations->encoded_id)}}">{{ $ticket->locations->identifiant }}</a></td>
                        <td>{{ $ticket->Subject }}</td>
                        <td class="d-none">
                            <span>{{$ticket->Status}}</span>
                        </td>
                        <td>
                            <select name="" class="statut_ticket form-select" style="width: 120px" ticket-id="{{ $ticket->id }}">
                                <option value="3" selected hidden>{{$ticket->Status}}</option>
                                <option value="0">{{ __('ticket.nouveau') }}</option>
                                <option value="1">{{ __('ticket.en_cours') }}</option>
                                <option value="2">{{ __('ticket.termine') }}</option>
                            </select>
                        </td>
                        <td>
                            {{ $ticket->getAuteur() }}
                        </td>
                        <td>{{ $ticket->Date_dernier_modif }}</td>
                        <td>
                            <div class="dropdown" style="position: static !important;">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                </button>
                                <div class="dropdown-menu">

                                    <a class="dropdown-item" href="{{route('ticket.details', $ticket->id)}}" ><i class="fa-solid fa-info-circle"></i> {{  __('ticket.detail') }}</a>
                                    <a class="dropdown-item" href="{{route('location.message',$ticket->locations->Locataire->id)}}"><i
                                        class="fa fa-comments me-1"></i>
                                    {{__('location.envoiMessage')}}</a>
                                    <a class="dropdown-item" href="{{ route('ticket.depense', $ticket->id) }}"><i class="fa-solid fa-coins"></i> {{__('ticket.depense')}}</a>
                                    @if ($ticket->User_created_ticket == Auth::id())
                                        <a class="dropdown-item" href="{{ route('ticket.modification', $ticket->id) }}"><i class="fa fa-pencil"></i></i> {{ __('finance.Modifier') }}</a>
                                        <a class="dropdown-item" href="{{ route('ticket.archive', $ticket->id) }}"><i class="fas fa-archive me-1"></i> {{__('documents.Archiver')}}</a>
                                        <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deleteticket{{$ticket->id}}"><i class="fa-solid fa-trash" style="color:red;"></i> {{__('finance.Supprimer')}}</a>
                                    @endif
                                </div>
                            </div>
                            <div class="modal fade" id="deleteticket{{ $ticket->id }}" tabindex="-1"
                                data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
                                aria-labelledby="modalTitleId" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm"
                                    role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalTitleId">{{ __('quittance.suppression') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                        {{ __('quittance.Voulez-vous') }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary "
                                                data-bs-dismiss="modal">{{ __('depense.Annuler') }}</button>
                                            <a href="{{ route('ticket.suppression', ['id' => $ticket->id]) }}"
                                                type="button"
                                                class="btn btn-  ">{{ __('finance.Supprimer') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach

            </tbody>

        </table>
        @if (count($tickets) < 1)
            <div class="card" style="margin-top: 5px">
                <center>
                    <img src="https://www.lexpressproperty.com/local/cache-gd2/5c/bdc26a1bd667d2212c86fd1c86c3a7.jpg?1647583702"
                        alt="" style="width:300px;height:200px;" class="img-responsive">
                </center>
                <br>
                <h4 class="text-center">{{ __('finance.rien') }}...</h4>
                <p class="text-center"></p>


            </div>
        @endif
    </div>
</div>





<div class="modal fade" id="TelechargerModele" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #F5F5F9">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body-content">
                <div class="d-flex justify-content-center" id="file-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
    integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        // changement de status
        $('.statut_ticket').on('change',function(){
            var ticket_id = $(this).attr('ticket-id')
            var valeur = $(this).val()
            $.ajax({
                type: "GET",
                url: "/changeStatusTicket",
                data: {
                    ticket_id : ticket_id,
                    valeur    : valeur
                },
                dataType: "json",
                success: function (response) {
                    window.location = "{{ redirect()->route('ticket.index')->getTargetUrl() }}";
                }
            });
        })


        $("#delete").on('click', function() {
            $("#myLoader").removeClass("d-none")
            var id = []
            $('.sub_chk:checked').each(function() {
                id.push($(this).attr('data-id'))
            });
            if (id.length <= 0) {
                toastr.error("veillez d'abord sélectionner");
            } else {
                var strIds = id.join(",");
                $.ajax({
                    type: "GET",
                    url: "/deleteTicketMultiple",
                    data: {
                        strIds: strIds
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data['status'] == true) {

                            window.location =
                                "{{ redirect()->route('ticket.index')->getTargetUrl() }}";
                        }
                    }
                });
            }
        })


        $("#archive_data").on('click', function() {
            $("#myLoader").removeClass("d-none")
            var id = []
            $('.sub_chk:checked').each(function() {
                id.push($(this).attr('data-id'))
            });
            if (id.length <= 0) {
                toastr.error("veillez d'abord sélectionner");
            } else {
                var strIds = id.join(",");
                $.ajax({
                    type: "GET",
                    url: "/archiveTicketMultiple",
                    data: {
                        strIds: strIds
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data['status'] == true) {

                            window.location =
                                "{{ redirect()->route('ticket.index')->getTargetUrl() }}";
                        }
                    }
                });
            }
        })
    });
</script>
