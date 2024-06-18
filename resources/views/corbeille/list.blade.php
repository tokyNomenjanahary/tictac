@extends('proprietaire.index')
<style>
    th {
        color: blue !important;
        font-size: 10px !important;
    }

    td {
        font-size: 13px;
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

    div.dataTables_wrapper div.dt-row {
        position: static !important;
    }
</style>
@section('contenue')
    <div class="content-wrapper"
        style="font-family: Manrope, -apple-system,BlinkMacSystemFont,segoe ui,Roboto,Oxygen,Ubuntu,Cantarell,open sans,helvetica neue,sans-serif;">
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row tete mt-4">
                <div class="row tete ">
                    <div class="col-lg-6 col-sm-4 col-md-4 titre">
                        <h3 class="page-header page-header-top">Corbeille</h3>
                    </div>
                    <div class="col-lg-6 col-sm-4 col-md-4 nouv text-end">
                        @if (count($corbeilles) !== 0)
                            <a class="btn btn-danger text-white" data-bs-toggle="modal" data-bs-target="#modalId">
                                Vider la corbeille
                            </a>
                        @endif
                    </div>
                </div>


            </div>

            <div class="alert m-t-15 m-b-0 m-l-10 m-r-10"
                style="background-color: #D9EDF7; border-left: 4px solid rgb(58,135,173);">
                <span style="font-size:25px ; color:rgb(76,141,203)">Information</span>
                </p style="margin-top:40px;font-size:12px !important;">Vous avez jusqu'à 7 jours pour restaurer les fichiers
                supprimés. Après cette période, les fichiers sont supprimés définitivement.</p>
            </div>
            <div class="modal fade" id="modalId" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #F5F5F9">
                            <h5 class="modal-title" id="exampleModalLabel" style="margin-top:-15px;">Avertissement</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h6>Vous allez vider votre corbeille!</h6>
                        </div>
                        <div class="modal-footer" style="background: #F5F5F9;height: 50px;">
                            <button type="button" class="btn  btn-sm btn-dark" data-bs-dismiss="modal">Annuler</button>
                            <a href="{{ route('corbeille.vider') }}" class="btn btn-primary btn-sm">Confirmer</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- filter --}}
            <div class="row" style="margin-top: 25px">
                <div class="col">
                    <div class="card">
                        <div class="card-body" style="padding-top: 15px; padding-bottom: 0px;">
                            <div>
                                <p><span>{{ __('location.textFiltre') }}</span></p>
                            </div>
                            <div class="row">
                                {{-- filtre epic  --}}
                                <div class="col-lg-3 col-md-12 mb-3">
                                    <div class="form-group">
                                        <div>
                                            <select id="filter-epic" class="form-select form-select-sm">
                                                <option value="All">Tout type</option>
                                                @foreach ($filtre_epic as $epic)
                                                    <option value="{{ $epic }}">{{ $epic }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="col-lg-2 col-md-12 mb-3">

                                    <div>
                                        <input id="recherche" class="form-control form-control-sm" type="text"
                                            placeholder={{ __('finance.Recherche') }}>
                                    </div>
                                </div> --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- end filter --}}

            {{-- list --}}
            <div class="row" style="margin-top: 30px">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="row" style="margin-top: 15px;padding:15px;">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="corbeilleTable"
                                        style="margin-bottom:0px;border:2px solid #F3F5F6;">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="master"
                                                        class="checkbox_input align-middle "style="height: 20px;width:20px;">
                                                </th>
                                                <th style="width:65%">Titre</th>
                                                <th class="d-none"></th>
                                                <th style="width: 20%">Date</th>
                                                <th>{{ __('finance.Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($corbeilles as $corbeille)
                                                <tr>
                                                    <td style="width: 5px" class="check">
                                                        <input type="checkbox" class="checkbox_input align-middle c_check"
                                                            value="{{ $corbeille->id }}" style="height: 15px;width:15px;">
                                                    </td>
                                                    <td>
                                                        <h6>{{ $corbeille->information }}</h6>
                                                        <p>{{ $corbeille->epic }}</p>
                                                    </td>
                                                    <td class="d-none">{{ $corbeille->epic }}</td>
                                                    <td class=" align-items-center text center">
                                                        <h6>{{ \Carbon\Carbon::parse($corbeille->deleted_at)->format('d/m/Y') }}
                                                        </h6>
                                                    </td>
                                                    <td class=" align-items-center text center">
                                                        <div class="dropdown" style="position: static !important;">
                                                            <button type="button"
                                                                class="btn p-0 dropdown-toggle hide-arrow"
                                                                data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-horizontal-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu" style="z-index: 2000">
                                                                <a class="dropdown-item" href="javascript:void(0);"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#delete-corbeille-{{ $corbeille->id }}"><i
                                                                        class="fa-solid fa-check"
                                                                        style="margin-right: 6px"></i>
                                                                    Réstaurer</a>
                                                                <a data-id="{{ $corbeille->id }}" data-bs-toggle="modal"
                                                                    data-bs-target="#deletePermanent"
                                                                    class="dropdown-item load-sc">
                                                                    <i class="fa-solid fa-trash" style="color: red"></i>
                                                                    {{ __('finance.Supprimer') }}
                                                                </a>
                                                            </div>

                                                        </div>
                                                    </td>
                                                    <div class="modal fade" id="delete-corbeille-{{ $corbeille->id }}"
                                                        tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
                                                        role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm"
                                                            role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalTitleId">Confirmation
                                                                    </h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Veuillez confirmer
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary "
                                                                        data-bs-dismiss="modal">{{ __('depense.Annuler') }}</button>
                                                                    <a href="{{ route('corbeille.restaurer', ['id' => $corbeille->id]) }}"
                                                                        type="button"
                                                                        class="btn btn-danger ">Confirmer</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                        @if (isset($corbeilles) && count($corbeilles) < 1)
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
                                                        <p class="text-center">Cette page permet de gérer les éléments
                                                            supprimés.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                    <div class="d-flex ps-3 mb-3 d-none" id="action_btn">
                                        <button class="ml-3 btn btn-danger btn-sm handle-click-arc btn-action"
                                            data-bs-toggle="modal" data-bs-target="#deletePermanent">
                                            <i class="fa-solid fa-trash"></i>&nbsp;SUPPRIMER
                                        </button>
                                        <button class="ms-2 btn btn-info btn-sm handle-click-arc btn-action"
                                            data-bs-toggle="modal" data-bs-target="#restorePermanent">
                                            <i class="fa-solid fa-trash"></i>&nbsp;RESTAURER
                                        </button>
                                    </div>
                                    <button class="handle-click d-none">
                                        handle click
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- end list --}}
        </div>
    </div>
    <div class="modal fade" id="deletePermanent" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Veuillez confirmer
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" id="canceled"
                        data-bs-dismiss="modal">{{ __('depense.Annuler') }}</button>
                    <button class="btn btn-danger" id="confirmed">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="restorePermanent" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Veuillez confirmer
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" id="canceled"
                        data-bs-dismiss="modal">{{ __('depense.Annuler') }}</button>
                    <button class="btn btn-info" id="confirmedRestore">Restaurer</button>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            $(document).ready(function() {
                var inv_ids
                var corbeille = $('#corbeilleTable').DataTable({
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

                $('#filter-epic').on('change', function() {
                    var selectedValue = this.value;
                    if (selectedValue === 'All') {
                        corbeille.search('').columns().search('').draw();
                        corbeille.search('').columns().search('').draw();
                    } else {
                        corbeille.column(2).search(selectedValue).draw();
                        corbeille.column(2).search(selectedValue).draw();
                    }
                });

                $("#master").on("change", function() {
                    corbeille.draw()
                    for (let i = 0; i < $(".c_check").length; i++) {
                        if (this.checked) {
                            $(".c_check")[i].checked = true
                            $("#action_btn").removeClass("d-none")
                        } else {
                            $(".c_check")[i].checked = false
                            $("#action_btn").addClass("d-none")
                        }
                    }
                })

                $(".c_check").on("change", function(e) {
                    e.stopPropagation()
                    if ($(".c_check:checked").length > 0) {
                        $("#action_btn").removeClass("d-none")
                    } else {
                        $("#action_btn").addClass("d-none")
                    }
                    if ($(".c_check:checked").length == $(".c_check").length) {
                        $("#master").prop("checked", true);
                    } else {
                        $("#master").prop("checked", false);
                    }
                });

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('input[type="hidden"]').attr('value')
                    }
                });

                $(".handle-click").on('click', function(e) {
                    e.preventDefault()
                    inv_ids = []
                    inv_ids = $(".c_check:checked").map(function() {
                        return this.value;
                    }).get();
                    return inv_ids
                })

                $('.btn-action, .load-sc').on('click', function(e) {
                    e.preventDefault()
                    if ($(this).attr("data-id")) {
                        inv_ids = []
                        inv_ids.push($(this).attr("data-id"))
                    } else {
                        $(".handle-click").trigger("click");
                    }
                    return inv_ids
                })

                $('#confirmed').on('click', function(e) {
                    e.preventDefault()
                    sendR(inv_ids)
                    $("#myLoader").removeClass("d-none")
                    $("#deletePermanent").modal('hide')
                })
                $('#confirmedRestore').on('click', function(e) {
                    e.preventDefault()
                    sendRestore(inv_ids)
                    $("#myLoader").removeClass("d-none")
                    $("#restorePermanent").modal('hide')
                })

                let sendR = function(inv_ids) {
                    $.ajax({
                        type: 'GET',
                        url: "/corbeille/permanent-delete",
                        data: {
                            ids: inv_ids,
                        },
                        success: function(data) {
                            $('script').remove();
                            location.reload(true);
                        }
                    });
                }

                let sendRestore = function(inv_ids) {
                    $.ajax({
                        type: 'GET',
                        url: "/corbeille/permanent-restore",
                        data: {
                            ids: inv_ids,
                        },
                        success: function(data) {
                            $('script').remove();
                            location.reload(true);
                        }
                    });
                }
            })
        </script>
    @endpush
@endsection

