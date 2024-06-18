@extends('espace_locataire.index')

@section('locataire-contenue')
    <style>
        th {
            color: blue !important;
            font-size: 10px !important;
        }
        td{
            font-size:13px;
        }
        p{
            line-height: 8px;
            font-size: 10px;
        }
        .tol{
            padding-top: 10px;
        }
        .dataTables_length{
            display: none;
        }

        .disabled-row{
            opacity: 0.5;
        }
        .dataTables_info{
            display: none;
        }
        .dataTables_filter{
            display: none;
        }
        #mes_prop_filter{
            display: none;
        }
    </style>
    <div class="container">
        <div class="row tete mt-4">
            <div class="col-lg-4 col-sm-4 col-md-4 titre">
                <h4 class="page-header page-header-top">{{__('finance.Quittance_de_loyer')}}</h4>
            </div>
        </div>
        @if($quittances->isNotEmpty())
            <div class="row">
{{--                <div class="col-lg-2 col-md-12 mb-3">--}}
{{--                    <div class="form-group">--}}
{{--                        <div>--}}
{{--                            <select id="filter-select-date" class="form-select form-select-sm">--}}
{{--                                <option value="All">toute la periode</option>--}}
{{--                                <option value="{{ \Carbon\Carbon::now()->subMonth(1)->format('m/Y') }}">{{__('finance.mois_dernier')}}--}}
{{--                                </option>--}}
{{--                                <option value="{{ \Carbon\Carbon::now()->subMonth(3)->format('m/Y') }}">{{__('finance.trois_mois_dernier')}}--}}
{{--                                </option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="col-lg-3 col-md-12 mb-3">
                    <div class="form-group">
                        <div>
                            <select id="filter-select-bien" class="form-select form-select-sm">
                                <option value="All">{{__('finance.Tous_les_biens')}}</option>
                                @foreach($quittances->unique('bien') as $quittance)
                                <option>{{$quittance->bien}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-12 mb-3">
                    <div class="form-group">
                        <div>
                            <input id="recherche" class="form-control form-control-sm" type="text"  placeholder="mot cle">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="background: white;margin-top:10px;margin-left: var(--bs-gutter-x)\);margin-right: calc(-0.5 * var(---gutter-x));">
                <div class="table">
                    <table class="table table-hover" id="quittance">
                        <thead>
                        <tr>
                            <th>{{__('finance.Date')}}</th>
                            <th>{{__('finance.Bien')}}</th>
                            <th>{{__('finance.Montant')}}</th>
                            <th>{{__('finance.Description')}}</th>
                            <th>{{__('finance.Etat')}}</th>
                            <th>{{__('finance.Actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($quittances as $quittance)
                            <tr>
                                <td>{{\Carbon\Carbon::parse($quittance->created_at)->format('d/m/Y')}}</td>
                                <td>{{$quittance->bien}}</td>
                                <td>{{$quittance->montant}}</td>
                                <td>{{$quittance->description}}</td>
                                <td>
                                    <span class="p-1 text-info" style="background-color: #F3F5F6;">PAYE</span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button"
                                                class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-horizontal-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                             @if($quittance->description=="Loyer") <a class="dropdown-item"  href="{{route('quittance.download', $quittance->id)}}"><i class="fas fa-download"></i>  {{__('echeance.Télécharger')}}</a>@endif
                                             @if($quittance->description=="Depot de garantie")<a class="dropdown-item" href="{{route('quittance.download', $quittance->id)}}"><i class="fas fa-download"></i>  {{__('echeance.Télécharger')}}</a>@endif
                                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deletequittance{{$quittance->id}}"><i class="fa-solid fa-trash" style="color:red;"></i> {{__('finance.Supprimer')}}</a>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="deletequittance{{$quittance->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
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
                                                    <a href="{{route('quittance.supprimerquittance', $quittance->id)}}" type="button" class="btn btn-danger ">{{__('finance.Supprimer')}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="alert  m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7; border-left: 4px solid rgb(58,135,173);">
                       <span class="label m-r-2"
                             style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">{{__("ticket.INFORMATION")}}</span>
                </p style="margin-top:50px;font-size:12px !important;">{{__("quittance.alertinfo")}}</p>
            </div>
        @endif
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function () {
            var quittance = $('#quittance').DataTable({
                "pageLength": 10,
                "language": {
                    "lengthMenu": "Filtre _MENU_ ",
                    "zeroRecords": "Pas de recherche corespondant",
                    "info": "Affichage _PAGE_ sur _PAGES_",
                    "infoEmpty": "Pas de recherche corespondant",
                    "infoFiltered": "(filtered from _MAX_ total records)",
                    "paginate": {
                        "previous": "&lt;", // Remplacer "previous" par "<"
                        "next": "&gt;" // Remplacer "next" par ">"
                    },
                },
            });
            $('#recherche').on('keyup', function() {
                quittance.search(this.value).draw();
            });
            $('#filter-select-bien').on('change', function() {
                var selectedValue = this.value;
                if (selectedValue === 'All') {
                    quittance.search('').columns().search('').draw();
                } else {
                    quittance.column(1).search(selectedValue).draw();
                }
            });
            $('#filter-select-date').on('change', function() {
                var selectedValue = this.value;
                if (selectedValue === 'All') {
                    quittance.search('').columns().search('').draw();
                } else {
                    quittance.column(0).search(selectedValue).draw();
                }
            });
        });

    </script>
@endsection

