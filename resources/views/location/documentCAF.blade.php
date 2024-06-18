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

@section($contenue)
<style>
    .dataTables_length{
            display: none;
        }

    .disabled-row{
        opacity: 0.5;
    }
    th {
        color: blue !important;
        font-size: 10px !important;
    }
    td{
        font-size:13px;
    }
    p{
        font-size: 10px;

    }
    #location_locataire_filter{
        display: none !important;
    }
</style>
<div class="container">
    <div class="row tete mt-4">
        <div class="col-lg-6 col-sm-6 col-md-6 titre">
            <h3 class="page-header page-header-top"><a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>{{__('location.documentCaf')}} - {{$location->identifiant}}</h3>
        </div>
        <div class="col-lg-6 text-end">
            <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample"
                data-bs-toggle="dropdown">{{__('location.ajouterCaf')}}</button>
        </div>
    </div>
    <div class="row" style="background: white;margin-top:10px;margin-left: var(--bs-gutter-x)\);margin-right: calc(-0.5 * var(---gutter-x));">
        <div class="table">
            <table class="table table-hover" id="location_locataire">
                <thead>
                    <tr>
                        <th>{{__('location.locataire')}}</th>
                        <th>Bien</th>
                        <th>{{__('location.etat')}}</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($document_caf as $doc)
                        <tr>
                            <td>{{$doc->location->Locataire->TenantFirstName . ' ' . $doc->location->Locataire->TenantLastName}}</td>
                            <td>{{$doc->location->Logement->identifiant}}</td>
                            <td>
                                @if ($doc->Etat == 1)
                                    <span class="btn btn-warning btn-sm" style="font-size: 10px">{{__('location.enAttente')}}</span>
                                @else
                                    <span class="btn btn-success btn-sm" style="font-size: 10px">{{__('location.remplit')}}</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow dropdown-toggle-split"
                                        data-bs-toggle="dropdown" id="but">
                                        <i class="bx bx-dots-horizontal-rounded" ></i>
                                    </button>
                                    <div class="dropdown-menu" style="font-size:14px">
                                        @if ($doc->Etat == 1)
                                            @if (!isTenant())
                                                <a class="dropdown-item" href="{{route('location.attestationLoyer',$doc->id)}}" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" title="<div class='tol' >
                                                <p>{{__('location.textRemplit')}}</p>
                                                </div>"><i class="fa fa-pencil me-1"></i>
                                                {{__('location.actionremplit')}}</a>
                                            @endif
                                            <a class="dropdown-item" href="{{route('document_caf.telechargement',$doc->id)}}" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" title="<div class='tol align-middle' >
                                                <p style='margin-top:8px'>{{__('location.telechargeCAF')}}</p>
                                                </div>"><i class="fas fa-download"></i>
                                                {{__('location.telechargeCAF')}}</a>
                                        @else
                                        <a class="dropdown-item" href="{{route('document_caf.telechargement',$doc->id)}}" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" title="<div class='tol align-middle' >
                                            <p style='margin-top:8px'>{{__('location.textTelecharger')}}</p>
                                            </div>"><i class="fas fa-download"></i>
                                            {{__('location.telechargeCAF')}}</a>
                                        @endif

                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel"></h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <h3>{{__('location.ajouterCaf')}}</h3>
                <div class="row p-3">
                    <div class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7; border-left: 4px solid rgb(58,135,173);">
                        </p style="margin-top:50px;font-size:12px !important;">{{__('location.textNormeCAF')}}</p>
                        @if(isTenant())
                            <p style="margin-top:0px;font-size:15px !important;">{{__('location.textAjout')}}</p>
                        @endif
                    </div>
                </div>
                <form action="{{route('documenCAF.enregister')}}" method="post" enctype="multipart/form-data">
                @csrf
                    <div class="row p-3">
                        <label for="form-label" style="margin-left:-10px">{{__('location.location')}} :</label>
                        <input type="hidden" name="location_id" value="{{$location->id}}">
                        <input type="text" class="form-control mt-2" disabled value="{{$location->identifiant}}">
                    </div>
                    <div class="row p-3">
                        <label for="form-label" style="margin-left:-10px">{{__('location.locataire')}} :</label>
                        <input type="text" class="form-control mt-2" disabled value="{{$location->Locataire->TenantFirstName . ' ' . $location->Locataire->TenantLastName}}">
                    </div>
                    <div class="row p-3">
                        <label for="form-label" style="margin-left:-10px">{{__('location.fichier')}} :</label>
                        <input type="file" name="fichier_pdf" class="form-control mt-2">
                        <span style="margin-left:-10px">Format accepter : .pdf, taille max : 1Mo</span>
                    </div>
                    <div class="row p-3">
                        <button type="submit" class="btn btn-primary">{{__('location.enregistrer')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                    { "orderable": false, "targets":[3] } // Désactiver le tri sur la première colonne (index 0)
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
