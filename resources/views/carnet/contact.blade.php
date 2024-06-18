<?php
if (!isTenant()) {
    $entete = 'proprietaire.index';
    $contenue = 'contenue';
}else{
    $entete = 'espace_locataire.index';
    $contenue = 'locataire-contenue';
}
?>

@extends($entete)
<style>
    th {
        color: blue !important;
        font-size: 10px !important;
    }

    td {
        font-size: 13px;
    }

    div.dataTables_wrapper div.dt-row {
        position: static !important;
    }

    .dataTables_length {
        display: none !important;
    }

    .dataTables_empty {
        display: none !important;
    }

    .dataTables_info {
        display: none !important;
    }

    @media screen and (max-width: 576px) {
        .t-sm {
            text-align: center !important;
        }
        .arh {
            margin-bottom: 1.5rem;
        }
    }
</style>
@section($contenue)
    <div class="content-wrapper"
        style="font-family: Manrope, -apple-system,BlinkMacSystemFont,segoe ui,Roboto,Oxygen,Ubuntu,Cantarell,open sans,helvetica neue,sans-serif;">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row tete mt-4">
                <div class="col-lg-4 col-sm-4 col-12 col-md-4 titre t-sm">
                    <h3 class="page-header page-header-top">{{ __('carnet.carnets') }}</h3>
                </div>
                <div class="col-lg-4 col-sm-4 col-md-4 arh">
                    <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist" style="border: none;">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" style="border:1px solid #EBF2E2;color:blue;" id="home-tab"
                                data-bs-toggle="tab" data-bs-target="#coloc_acif" type="button" role="tab"
                                aria-controls="home" aria-selected="true"><i class="fa fa-check m-r-5"></i>
                                {{ __('documents.Actifs') }} <span class="badge bg-primary" id="ActifsCounts">{{$count}}</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation" hidden>
                            <a class="nav-link" style="border:1px solid #f9f9f9;color:blue;" id="profile-tab"
                                data-bs-toggle="tab" data-bs-target="#coloc_archi" type="button" role="tab"
                                aria-controls="profile" aria-selected="false"> <i class="fa fa-folder-open m-r-5"></i>
                                {{ __('documents.Archives') }}
                                <span class="badge bg-primary" id="ArchiveCounts">0</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 t-sm" style="text-align: right;">
                    <div class="dropdown">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                            {{ __('carnet.Nouveau_carnet') }}
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{route('carnet.newContact')}}"><i
                                    class="fa fa-plus-circle" ></i> {{ __('carnet.Nouveau_carnet') }}</a>
                            <a class="dropdown-item" href="{{route("carnet.importercontact")}}"><i class="fa fa-cloud-upload me-1"></i> Importer</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 30px">
                <div class="col-2 d-none">
                    <div>
                        <select id="filter-select-date" class="form-select form-select-sm">
                            <option value="All">{{ __('inventaire.Tous_les_prop') }}</option>
                        </select>
                    </div>
                </div>
                @if($entete == 'proprietaire.index')
                <div class="col-sm-4 col-12">
                    <div>
                        <select id="filter-select-bien" class="form-select form-select-sm">
                            <option value="All" selected>{{ __('finance.Tous_les_biens') }}</option>
                            @foreach ($logements as $logement)
                                <option value="{{ $logement->identifiant }}">{{ $logement->identifiant }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
            </div>

            <div class="row" style="margin-top:15px">
                <div class="col">
                    <div class="" style="padding-left: 12px;margin-top:-38px;padding-right: 12px;">
                        <div class="tab-pane active " id="coloc_acif" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row"
                                style="background-color:white;margin-top: 30px;border:1px solid #eeeeee;padding:25px;">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-responsive" id="contact"
                                        style="margin-bottom:0px;border:2px solid #F3F5F6;">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="master"
                                                        class="checkbox_input align-middle select_all_state"
                                                        style="height: 20px;width:20px;">
                                                </th>
                                                <th>{{ __('carnet.nom') }}</th>
                                                <th>{{ __('carnet.email') }}</th>
                                                <th>{{ __('carnet.telephone') }}</th>
                                                <th>{{ __('carnet.bien') }}</th>
                                                <th class="d-none">{{ __('carnet.locataire') }}</th>
                                                <th>{{ __('carnet.partage') }}</th>
                                                <th>{{ __('carnet.Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($contacts as $contact)
                                                <tr>
                                                    <td style="width: 5px" class="check">
                                                        <input type="checkbox" value="{{ $contact->id }}"
                                                            class="checkbox_input align-middle sub_chk check-contact"
                                                            style="height: 15px;width:15px;">
                                                    </td>
                                                    <td>{{ $contact->name }}</td>
                                                    <td>{{ $contact->email }}</td>
                                                    <td>{{ $contact->mobile }}</td>
                                                    <td>
                                                        @if ($contact->logement)
                                                            {{ $contact->logement->identifiant}}
                                                        @else
                                                            <span>Pas lié</span>
                                                        @endif
                                                    </td>
                                                    <td class="d-none">
                                                        <span>Pas lié</span>
                                                    </td>
                                                    <td><span title="Le document n'est pas partagé"
                                                    style=" padding:2px 2px 2px 2px;color:#EBF2E2;font-size:12px;border-radius:5px" class="bg-success">{{ $contact->partage }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown" style="position: static !important;">
                                                            <button type="button"
                                                                class="btn p-0 dropdown-toggle hide-arrow"
                                                                data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-horizontal-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu" style="z-index: 2000">
                                                                <a href="{{ route('carnet.editContact', $contact->id) }}"
                                                                    class="dropdown-item"> <i class="fa fa-pencil"></i>
                                                                    {{ __('finance.Modifier') }}</a>
                                                                    @if($contact->for_tenant===0)
                                                                    <a class="dropdown-item"
                                                                    href="{{ route('carnet.show', ['contact_id' => $contact->id]) }}">
                                                                    <i class="fa fa-eye me-1"></i>Fiche contact</a>
                                                                    @endif
                                                                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deleteloyer{{$contact->id}}"><i class="fa-solid fa-trash" style="color:red;"></i> {{__('finance.Supprimer')}}</a>
                                                                {{-- <a class="dropdown-item"
                                                                    href="{{ route('carnet.delete', $contact->id) }}"> <i
                                                                        class="fa-solid fa-trash"
                                                                        style="color:red;"></i>&nbsp;Supprimer</a> --}}

                                                            </div>
                                                        </div>
                                                        <div class="modal fade" id="deleteloyer{{$contact->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="modalTitleId">{{ __('quittance.suppression') }}</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                    {{ __('quittance.Voulez-vous') }}
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">{{ __('depense.Annuler') }}</button>
                                                                         <a href="{{ route('carnet.delete', $contact->id) }}" type="button" class="btn btn-danger ">{{__('finance.Supprimer')}}</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        @if ($contacts->isEmpty())
                                            <tr>
                                                <td colspan="10">
                                                    <div class="card" style="margin-top: 5px">
                                                        <center>
                                                            <img src="https://www.lexpressproperty.com/local/cache-gd2/5c/bdc26a1bd667d2212c86fd1c86c3a7.jpg?1647583702"
                                                                alt="" style="width:300px;height:200px;"
                                                                class="img-responsive">
                                                        </center>
                                                        <br>
                                                        <h4 class="text-center">{{ __('finance.rien') }}...</h4>
                                                        <p class="text-center">{{ __('carnet.text_rien') }}</p>
                                                        <center>
                                                            <button class="btn btn-outline-warning"
                                                                style="margin-bottom: 10px;"><a
                                                                    href="{{ route('carnet.newContact') }}">{{ __('carnet.Nouveau_carnet') }}</a>
                                                            </button>
                                                        </center>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                                <div class="d-flex ps-3 mb-3 d-none" id="action_btn">
                                    <button class="btn btn-danger d-none btn-sm delete-contact me-2">
                                        <i class="fa-solid fa-trash"></i>&nbsp;SUPPRIMER
                                    </button>
                                    <button class="btn btn-secondary btn-sm archive-etat handle-click-arc d-none">
                                        <i class="fa-solid fa-trash"></i>&nbsp;ARCHIVER
                                    </button>
                                    <div class="dropdown" style="margin-left:10px" id="export">
                                        <button type="button" class="btn btn-success btn-sm dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-download"></i>&nbsp;EXPORTER
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item " href="#" style="font-size: 14px"
                                                onclick="exportContact('excel')"><i
                                                    class="fa-regular fa-file-excel"></i>&nbsp;Export format Excel</a>
                                            <a class="dropdown-item " href="#"
                                                style="cursor:pointer;font-size: 14px"
                                                onclick="exportContact('open_office')"><i
                                                    class="fa-solid fa-file-excel"></i>&nbsp;Export format Open Office</a>
                                        </div>
                                    </div>
                                </div>
                                <button class="handle-click" hidden>handle-click</button>

                                <form id="form_export" action="{{ route('carnet.export_contact') }}" class="d-none">
                                    <input type="hidden" name="ids_liste" id="ids_liste">
                                    <input type="hidden" name="type" id="type">
                                    <button type="submit" id='submit_export'></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @include('proprietaire.suggestion')
                </div>
            </div>
            <div class="content-backdrop fade"></div>
        </div>

    @endsection
    @push('script')
        <script>
            var contact_table = $('#contact').DataTable({
                "pageLength": 10,
                "language": {
                    "paginate": {
                        "previous": "&lt;", // Remplacer "previous" par "<"
                        "next": "&gt;" // Remplacer "next" par ">"
                    },
                    "lengthMenu": "Filtre _MENU_ ",
                    "zeroRecords": "Pas de recherche corespondant",
                    "info": "Affichage _PAGE_ sur _PAGES_",
                    "infoEmpty": "Pas de recherche corespondant",
                    "infoFiltered": "(filtered from _MAX_ total records)"
                }
            });
            var contact_ids
            var loadSc = function() {
                $("#myLoader").removeClass("d-none")
            }



            $(document).ready(function() {
                $(".select_all_state").on("change", function() {
                    contact_table.draw()
                    for (let i = 0; i < $(".check-contact").length; i++) {
                        if (this.checked) {
                            $(".check-contact")[i].checked = true
                            $("#action_btn").removeClass("d-none")
                        } else {
                            $(".check-contact")[i].checked = false
                            $("#action_btn").addClass("d-none")
                        }
                    }
                })

                $(".check-contact").on("change", function(e) {
                    e.stopPropagation()
                    if ($(".check-contact:checked").length > 0) {
                        $("#action_btn").removeClass("d-none")
                    } else {
                        $("#action_btn").addClass("d-none")
                    }
                    if ($(".check-contact:checked").length == $(".check-contact").length) {
                        $(".select_all_state").prop("checked", true);
                    } else {
                        $(".select_all_state").prop("checked", false);
                    }
                });

                $(".handle-click").on('click', function(e) {
                    e.preventDefault()
                    contact_ids = []
                    contact_ids = $(".check-contact:checked").map(function() {
                        return this.value;
                    }).get();
                    return contact_ids
                })

                $('#filter-select-bien').on('change', function() {
                    var selectedValue = this.value;
                    if (selectedValue === 'All') {
                        contact_table.search('').columns().search('').draw();
                    } else {
                        contact_table.column(4).search(selectedValue).draw();
                    }
                });

            })

            function exportContact(type) {
                event.preventDefault();
                $(".handle-click").trigger("click");
                $('#ids_liste').val(JSON.stringify(contact_ids));
                $('#type').val(type);
                $('#form_export').submit();
            }
        </script>
    @endpush
