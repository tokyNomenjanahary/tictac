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
        #location_locataire_filter{
            display: none;
        }
    </style>
    <?php
        $userId = auth()->user()->id; // Obtenez l'ID de l'utilisateur connecté
        $documentCafs = \App\Document_caf::where('etat', 2)
        ->where('cree_par', 2)
        ->with('location')
        ->whereHas('location', function ($query) use ($userId) {
            $query->whereHas('Locataire', function ($subQuery) use ($userId) {
                $subQuery->where('user_account_id', $userId);
            });
        })
        ->get();
    ?>
    <div class="container">
        <div class="row tete mt-4">
            <div class="col-lg-4 col-sm-4 col-md-4 titre">
                <h3 class="page-header page-header-top">{{__('location.location')}}</h3>
            </div>
        </div>
        @if(count($documentCafs) > 0)
            <div class="row p-3">
                <div class="alert m-b-0 m-l-10 m-r-10" style="background-color: #d9f7dc; border-left: 4px solid rgb(58,135,173);">
                    <p style="margin-top:10px;font-size:14px !important;">Votre document CAF à bien été replit par votre proprietaire</p>
                    @foreach ($documentCafs as $loc)
                        <a class="mt-3" href="{{route('location.documentCAF',$loc->location->id)}}">-&nbsp;&nbsp;{{$loc->location->identifiant}}</a> <br>
                    @endforeach
                </div>
            </div>
        @endif
        @if($locations == 'vide')
            <div class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7; border-left: 4px solid rgb(58,135,173);">
                {{-- <span class="label m-r-2"
                    style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">INFORMATION</span> --}}
                <span style="font-size:25px ; color:rgb(76,141,203)">Information</span>
                </p style="margin-top:50px;font-size:12px !important;"> Vous n'êtes pas encore un locataire d'une location.</p>
            </div>
        @else
            <div class="row" style="background: white;margin-top:0px;margin-left: var(--bs-gutter-x)\);margin-right: calc(-0.5 * var(---gutter-x));">
                <div class="table">
                    <table class="table table-hover" id="location_locataire">
                        <thead>
                            <tr>
                                <th>Bien</th>
                                <th>Proprietaire</th>
                                <th>Loyer</th>
                                <th>Durée</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($locations as $location)
                                <tr>
                                    <td class="@if(strtotime($location->fin) < time() || $location->etat == 2) disabled-row @else @endif">
                                        <div class="align-middle">
                                                <a href="" style="cursor: pointer" data-bs-toggle="tooltip" data-bs-html="true" title="<div class='tol'>
                                                    <p>Type :  {{$location->typelocation->description}}</p>
                                                    <p>Payement :  {{$location->typepayement->description}}</p>
                                                    {{-- <p>Renouvelement :  Oui</p> --}}
                                                    <p>loyer : {{$location->loyer_HC}} €</p>
                                                    <p>durée: {{\Carbon\Carbon::parse($location->debut)->format('d/m/Y').' - '.\Carbon\Carbon::parse($location->fin)->format('d/m/Y')}}</p>
                                                </div>"  data-id="{{$location->id}}" class="lolo">{{$location->Logement->identifiant}}</a>
                                        </div>
                                        <span>{{$location->typelocation->description}}</span><br>
                                        <span><i class="fa-solid fa-location-dot"></i>&nbsp;&nbsp;{{$location->Logement->adresse}}</span><br>
                                        @for($i = 0; $i < count($etat_finance); $i++)
                                            @if ($location->id == $etat_finance[$i][1])
                                                <span class="text-danger"><i class="fa fa-exclamation-circle text-danger m-r-5"></i>&nbsp;Loyer impayé</span>
                                            @endif
                                        @endfor
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-1">
                                                    <span class="badge badge-center bg-primary rounded-pill random-bg text-capitalize" style="width:40px;height:40px;">{{strtoupper(substr($location->user->first_name,0,2))}}</span>
                                            </div>
                                            <div>
                                                <a href="">{{$location->user->first_name . ' ' . $location->user->last_name}}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="@if(strtotime($location->fin) < time() || $location->etat == 2) disabled-row @else @endif">{{number_format($location->loyer_HC, 0, ',', '.')}} €</td>
                                    <td class="@if(strtotime($location->fin) < time() || $location->etat == 2) disabled-row @else @endif">{{\Carbon\Carbon::parse($location->debut)->format('d/m/Y').' - '.\Carbon\Carbon::parse($location->fin)->format('d/m/Y')}}</td>
                                    <td class="dropdown">
                                        <div class="dropdown dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow dropdown-toggle-split"
                                                data-bs-toggle="dropdown" id="but">
                                                <i class="bx bx-dots-horizontal-rounded" ></i>
                                            </button>
                                            <div class="dropdown-menu" style="font-size:14px">
                                                <a class="dropdown-item" href="{{route('location.detail_loc',$location->id)}}" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" title="<div class='tol' >
                                                    <p>afficher la location</p>
                                                    </div>"><i class="fa-solid fa-calendar-week"></i>&nbsp;&nbsp;
                                                    Afficher</a>
                                                <a class="dropdown-item" href="{{route('location.documentCAF',$location->id)}}" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" title="<div class='tol' >
                                                    <p>Document caf</p>
                                                    </div>"><i class="fa-solid fa-calendar-week"></i>&nbsp;&nbsp;
                                                    Document CAF</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
    integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function () {
            var location_locataire = $('#location_locataire').DataTable({
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
                "columnDefs": [
                    { "orderable": false, "targets":[4] } // Désactiver le tri sur la première colonne (index 0)
                ],
                "order": []
            });
            location_locataire.on( 'draw.dt', function () {
                function getRandomColor() {
                var letters = '0123456789ABCDEF';
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
                }

                var elements = document.getElementsByClassName("random-bg");
                var color = getRandomColor();

                for (var i = 0; i < elements.length; i++) {
                    elements[i].style.backgroundColor = color;
                }
            });
        });
    </script>
@endsection
