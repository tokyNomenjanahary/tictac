@extends('proprietaire.index')
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
        .cust-d-none {
            display: none !important;
        }
    }
</style>
@section('contenue')
    <div class="content-wrapper"
        style="font-family: Manrope, -apple-system,BlinkMacSystemFont,segoe ui,Roboto,Oxygen,Ubuntu,Cantarell,open sans,helvetica neue,sans-serif;">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row tete mt-4">
                <div class="col-lg-4 col-sm-4 col-12 col-md-4 titre">
                    <h3 class="page-header page-header-top">{{ __('inventaire.Inventaires') }}</h3>
                </div>
                <div class="col-lg-4 col-sm-4 col-md-4 arh">
                    <ul class="nav nav-tabs" id="myTab" role="tablist" style="border: none;">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" style="border:1px solid #EBF2E2;color:blue;" id="navs-top-actif-tab"
                                data-bs-toggle="tab" data-bs-target="#navs-top-actif" type="button" role="tab"
                                aria-controls="navs-top-actif" aria-selected="true"><i class="fa fa-check m-r-5"></i>
                                {{ __('documents.Actifs') }} <span class="badge bg-primary">{{ count($inventaires) }}</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" style="border:1px solid #f9f9f9;color:blue;"
                                id="navs-top-archive-tab" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-archive"
                                aria-controls="navs-top-archive" aria-selected="false">
                                <i class="fa fa-folder-open m-r-5"></i>
                                {{ __('documents.Archives') }}
                                <span class="badge bg-primary">{{ count($inventairesArchive) }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 col-sm-4 col-md-4 nouv text-end">
                    <div>
                        <button type="button" class="btn btn-primary">
                            <a href="{{route("inventaire.nouveau")}}" style="color: white;"><i class="fa fa-plus-circle"></i>
                                {{ __('inventaire.Nouveau_inventaire') }}</a>
                        </button>

                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 30px">
                <div class="col-2">
                    <div>
                        <select id="filter-select-date" class="form-select form-select-sm">
                            <option value="All">{{ __('inventaire.Tous_les_prop') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div>
                        <select id="filter-select-bien" class="form-select form-select-sm">
                            <option value="All">{{ __('finance.Tous_les_biens') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top:15px">
                <div class="col">
                    <div class="" style="padding-left: 12px;margin-top:-38px;padding-right: 12px;">
                        <div class="row"
                            style="background-color:white;margin-top: 30px;border:1px solid #eeeeee;padding:25px;">
                            <div class="tab-content p-0">
                                <div class="tab-pane fade active show" id="navs-top-actif" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-responsive" id="inventaire"
                                            style="margin-bottom:0px;border:2px solid #F3F5F6;">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox"
                                                            class="checkbox_input align-middle select_all_state" style="height: 20px;width:20px;">
                                                    </th>
                                                    <th>{{ __('inventaire.identifiant') }}</th>
                                                    <th class="cust-d-none">{{ __('finance.Bien') }}</th>
                                                    <th class="cust-d-none">{{ __('inventaire.valeur') }}</th>
                                                    <th class="cust-d-none">{{ __('inventaire.locataire') }}</th>
                                                    <th class="cust-d-none">{{__('finance.Date')}}</th>
                                                    <th class="cust-d-none">{{ __('finance.Actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($inventaires as $inventaire )
                                                <tr>
                                                    <td style="width: 5px" class="check">
                                                        <input type="checkbox" value="{{ $inventaire->id }}"
                                                            class="checkbox_input align-middle sub_chk check-inventaire"
                                                            style="height: 15px;width:15px;" >
                                                    </td>
                                                    <td>{{$inventaire ->identifiant}}</td>
                                                    <td class="cust-d-none">
                                                        @if ($inventaire->Logement)
                                                        {{$inventaire->Logement->identifiant}}</td>
                                                        @endif
                                                    <td class="cust-d-none">0.00 €</td>
                                                    <td class="cust-d-none">
                                                        @if ($inventaire->location)
                                                        {{$inventaire->location->identifiant}}</td>
                                                        @endif
                                                    </td>
                                                    <td class="cust-d-none">
                                                      {{ \Carbon\Carbon::parse($inventaire->created_at)->format('d/m/Y') }}
                                                    </td>
                                                    <td>
                                                        <div class="dropdown"  style="position: static !important;">
                                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                                data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-horizontal-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu" style="z-index: 2000">
                                                                <a href="{{ route('inventaire.edit', $inventaire ->id) }}" class="dropdown-item" id="modification"> <i class="fa fa-pencil"></i>  {{__('finance.Modifier')}}</a>
                                                                <a class="dropdown-item load-sc" data-inv-url="{{ route('archive-inventaire') }}" data-id="{{$inventaire ->id}}"> <i class="fas fa-archive me-1"></i> {{ __('documents.Archiver') }}</a>
                                                                <a class="dropdown-item d-none"> <i class="fas fa-file-export"></i> {{__('inventaire.etat_de_lieux')}}</a>
                                                                <a href="{{ route('proprietaire.ajout-etat-inventaire',$inventaire->id) }}" class="dropdown-item"> <i class="fas fa-file-export"></i> Transferer</a>
                                                                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deleteinventaire{{$inventaire ->id}}"><i class="fa-solid fa-trash" style="color:red;"></i> {{__('finance.Supprimer')}}</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <div class="modal fade" id="deleteinventaire{{$inventaire ->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
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
                                                                     <a href="{{ route('delete-inventaire',$inventaire ->id) }}" type="button" class="btn btn-danger ">{{__('finance.Supprimer')}}</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            @if ($inventaires->isEmpty())
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
                                                        <p class="text-center">{{ __('inventaire.text_rien') }}</p>
                                                        <center>
                                                            <button class="btn btn-outline-warning"
                                                                style="margin-bottom: 10px;"><a
                                                                    href="{{route("inventaire.nouveau")}}">{{ __('inventaire.Nouveau_inventaire') }}</a>
                                                            </button>
                                                        </center>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="navs-top-archive" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-responsive" id="inventaireArch"
                                            style="margin-bottom:0px;border:2px solid #F3F5F6;">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox"
                                                            class="checkbox_input align-middle select_all_state" style="height: 20px;width:20px;">
                                                    </th>
                                                    <th>{{ __('inventaire.identifiant') }}</th>
                                                    <th class="cust-d-none">{{ __('finance.Bien') }}</th>
                                                    <th class="cust-d-none">{{ __('inventaire.valeur') }}</th>
                                                    <th class="cust-d-none">{{ __('inventaire.locataire') }}</th>
                                                    <th class="cust-d-none">{{__('finance.Date')}}</th>
                                                    <th>{{ __('finance.Actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($inventairesArchive as $inventaire )
                                                <tr>
                                                    <td style="width: 5px" class="check">
                                                        <input type="checkbox" value="{{ $inventaire->id }}"
                                                            class="checkbox_input align-middle sub_chk check-inventaire"
                                                            style="height: 15px;width:15px;" >
                                                    </td>
                                                    <td>{{$inventaire ->identifiant}}</td>
                                                    <td class="cust-d-none">
                                                        @if ($inventaire->Logement)
                                                        {{$inventaire->Logement->identifiant}}</td>
                                                        @endif
                                                    <td class="cust-d-none">0.00 €</td>
                                                    <td class="cust-d-none">
                                                        @if ($inventaire->location)
                                                        {{$inventaire->location->identifiant}}</td>
                                                        @endif
                                                    </td>
                                                    <td class="cust-d-none">
                                                        {{ \Carbon\Carbon::parse($inventaire->created_at)->format('d/m/Y') }}
                                                    </td>
                                                    <td>
                                                        <div class="dropdown"  style="position: static !important;">
                                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                                data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-horizontal-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu" style="z-index: 2000">
                                                                <a href="{{ route('inventaire.edit', $inventaire ->id) }}" class="dropdown-item" id="modification"> <i class="fa fa-pencil"></i>  {{__('finance.Modifier')}}</a>
                                                                <a href="{{ route('delete-inventaire',$inventaire ->id) }}" class="dropdown-item" > <i class="fa-solid fa-trash" style="color: red"></i> {{__('finance.Supprimer')}}</a>
                                                                <a class="dropdown-item load-sc" data-inv-url="{{ route('archive-inventaire') }}" data-id="{{$inventaire ->id}}"> <i class="fas fa-archive me-1"></i> Désarchiver</a>
                                                                <a class="dropdown-item d-none"> <i class="fas fa-file-export"></i> {{__('inventaire.etat_de_lieux')}}</a>
                                                                <a href="{{ route('proprietaire.ajout-etat-inventaire',$inventaire->id) }}" class="dropdown-item"> <i class="fas fa-file-export"></i> Transferer</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <div class="modal fade" id="deleteinventaire{{$inventaire ->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
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
                                                                     <a href="{{ route('delete-inventaire',$inventaire ->id) }}" type="button" class="btn btn-danger ">{{__('finance.Supprimer')}}</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            @if ($inventairesArchive->isEmpty())
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
                                                        <p class="text-center">{{ __('inventaire.text_rien') }}</p>
                                                        <center>
                                                            <button class="btn btn-outline-warning"
                                                                style="margin-bottom: 10px;"><a
                                                                    href="{{route("inventaire.nouveau")}}">{{ __('inventaire.Nouveau_inventaire') }}</a>
                                                            </button>
                                                        </center>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex mb-3 g-0 d-none" id="action_btn">
                                <button class="btn btn-danger btn-sm me-2 btn-action" data-inv-url="{{ route('deletedeleteMultiple-inventaire') }}" data-action="delete">
                                <i class="fa-solid fa-trash"></i>&nbsp;SUPPRIMER
                                </button>
                                <button class="btn btn-secondary btn-sm btn-action" data-inv-url="{{ route('archive-inventaire') }}" data-action="archive">
                                <i class="fa-solid fa-trash"></i>&nbsp;ARCHIVER
                                </button>
                            </div>
                        </div>
                        <button class="handle-click" hidden>handle-click</button>
                        <button class="action-submit" hidden>handle-click</button>
                </div>
            </div>
            @include('proprietaire.suggestion')
        </div>
    </div>
    <div class="content-backdrop fade"></div>
    </div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
    integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
     $(document).ready(function() {
        var finance = $('#inventaire').DataTable({
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

        var inventaireArch = $('#inventaireArch').DataTable({
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
        var inv_ids
        var loadSc = function () {
          $("#myLoader").removeClass("d-none")
        }
        let ids = ["#navs-top-actif", "#navs-top-archive"]
        $.each(ids, function(key, value) {
            $(value + " .select_all_state").on("change", function () {
                if (value == "#navs-top-actif") {
                    finance.draw()
                } else {
                    inventaireArch.draw()
                }
                for (let i = 0; i < $(value + " .check-inventaire").length; i++) {
                    if (this.checked) {
                    $(value + " .check-inventaire")[i].checked = true
                    $("#action_btn").removeClass("d-none")
                    } else {
                    $(value + " .check-inventaire")[i].checked = false
                    $("#action_btn").addClass("d-none")
                    }
                }
            })

            $(value + " .check-inventaire").on("change", function (e) {
                e.stopPropagation()
                if ($(value + " .check-inventaire:checked").length > 0) {
                    $("#action_btn").removeClass("d-none")
                } else {
                    $("#action_btn").addClass("d-none")
                }
                if ($(value + " .check-inventaire:checked").length == $(value + " .check-inventaire").length) {
                    $(value + " .select_all_state").prop("checked", true);
                } else {
                    $(value + " .select_all_state").prop("checked", false);
                }
            });
        });

        $('#navs-top-actif-tab, #navs-top-archive-tab').on('click', function () {
            for (let i = 0; i < $(".check-inventaire").length; i++) {
                $(".check-inventaire")[i].checked = false
            }
            $(".select_all_state").prop("checked", false);
            $("#action_btn").addClass("d-none")
        })

        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('input[type="hidden"]').attr('value')
            }
        });

        $(".handle-click").on('click', function (e) {
          e.preventDefault()
          inv_ids = []
          inv_ids = $(".check-inventaire:checked").map(function () {
            return this.value;
          }).get();
          return inv_ids
        })

        $('.btn-action, .load-sc').on('click', function (e) {
            e.preventDefault()
            let action = $(this).attr('data-action')
            let url = $(this).attr('data-inv-url')
            if ($(this).attr("data-id")) {
                inv_ids = []
                inv_ids.push($(this).attr("data-id"))
            } else {
                $(".handle-click").trigger("click");
            }
            sendR(url, action, inv_ids)
        })

        let sendR = function (url, action, inv_ids) {
            loadSc()
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    inv_ids: inv_ids,
                },
                success: function (data) {
                location.href = "{{ route('inventaire.index') }}"
                }
            });
        }
    });
</script>
