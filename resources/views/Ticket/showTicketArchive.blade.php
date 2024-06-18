<style>
    #showLocataire_length {
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
<div id="navs-top-archive">
    <div class="table-responsive text-nowrap">
        <table id="showLocataireArchiver" class="table">
            <thead>
                <tr>
                    <th><input type="checkbox" class="checkbox_input align-middle select_all_state"
                            style="height: 20px;width:20px;"></th>
                    <th>{{ __('revenu.Location') }}</th>
                    <th>{{ __('ticket.sujet') }}</th>
                    <th>{{ __('ticket.etat') }}</th>
                    <th>{{ __('ticket.createur') }}</th>
                    <th>{{ __('ticket.mis_a_jour') }}</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($tickets_archive as $ticket)
                    <tr>
                        <td style="width: 5px" class="check">
                            <input type="checkbox" id="selectionner"
                                class="checkbox_input checkbox align-middle sub_chk" style="height: 15px;width:15px;"
                                data-id="{{ $ticket->id }}">
                        </td>
                        <td>{{ $ticket->locations->identifiant }}</td>
                        <td>{{ $ticket->Subject }}</td>
                        <td>{{ $ticket->Status }}</td>
                        <td>{{ $ticket->Date_dernier_modif }}</td>
                        <td>
                            <div class="dropdown" style="position: static !important;">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @if ($ticket->Status ==__('ticket.termine'))
                                        <a class="dropdown-item" href="{{ route('ticket.reouvrir', $ticket->id) }}"><i
                                                class="fas fa-folder-open me-1"></i>Réouvrir</a>
                                    @endif
                                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"
                                        data-bs-target="#largeModal"><i class="fa-solid fa-info-circle"></i>{{  __('ticket.detail') }}</a>
                                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"
                                        data-bs-target="#deleteticket{{ $ticket->id }}"><i class="fa-solid fa-trash"
                                            style="color:red;"></i> {{ __('finance.Supprimer') }}</a>
                                    <a class="dropdown-item" href="{{ route('ticket.archive', $ticket->id) }}"><i
                                            class="fas fa-archive me-1"></i>{{ __('texte_global.desarchiver') }}</a>

                                </div>
                            </div>
                            <div class="modal fade" id="deleteticket{{ $ticket->id }}" tabindex="-1"
                                data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
                                aria-labelledby="modalTitleId" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm"
                                    role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalTitleId">Suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Voulez-vous vraiment supprimer?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary "
                                                data-bs-dismiss="modal">{{ __('depense.Annuler') }}</button>
                                            <a href="{{ route('ticket.suppression', ['id' => $ticket->id]) }}"
                                                type="button"
                                                class="btn btn-danger ">{{ __('finance.Supprimer') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>


                  
                    </tr>
                @endforeach
            </tbody>

        </table>
        @if (count($tickets_archive) < 1)
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
