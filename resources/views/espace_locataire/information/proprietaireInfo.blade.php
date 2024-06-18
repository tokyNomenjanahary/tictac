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
        #mes_prop_filter{
            display: none;
        }
    </style>
    <div class="container">
        <div class="row tete mt-4">
            <div class="col-lg-4 col-sm-4 col-md-4 titre">
                <h3 class="page-header page-header-top">{{__("ticket.Proprietaires")}}</h3>
            </div>
        </div>
        @if($proprios->isNotEmpty())
            <div class="row" style="background: white;margin-top:10px;margin-left: var(--bs-gutter-x)\);margin-right: calc(-0.5 * var(---gutter-x));">
                <div class="table">
                    <table class="table table-hover" id="mes_prop">
                        <thead>
                        <tr>
                            <th>{{__('ticket.Nom')}}</th>
                            <th>{{__('echeance.Email')}}</th>
                            <th>{{__('echeance.Téléphone')}}</th>
                            <th>{{__('finance.Actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                           @foreach($proprios as $proprio)
                        <tr>
                            <td>{{	$proprio->first_name}}</td>
                            <td> {{	$proprio->email}}</td>
                            <td> {{	$proprio->mobile_no}}</td>
                            <td>
                              <div class="dropdown">
                                  <button type="button"
                                          class="btn p-0 dropdown-toggle hide-arrow"
                                          data-bs-toggle="dropdown">
                                      <i class="bx bx-dots-horizontal-rounded"></i>
                                  </button>
                                  <div class="dropdown-menu">
                                      <a href="{{route('espaceLocataire.infoplus',$proprio->loc_id)}}" class="dropdown-item"> <i class="fa-solid fa-eye"></i> {{__('ticket.Afficher')}}</a>
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
                <p style="margin-top:50px;font-size:12px !important;">{{__("ticket.alertinfo")}}</p>
            </div>
        @endif
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function () {
            var mes_prop = $('#mes_prop').DataTable({
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
        });
    </script>
@endsection

