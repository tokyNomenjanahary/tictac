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
@section('file-input')
    <!-- if using RTL (Right-To-Left) orientation, load the RTL CSS file after fileinput.css by uncommenting below -->
    {{-- <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/css/fileinput-rtl.min.css" media="all" rel="stylesheet" type="text/css"> --}}

    <!-- bootstrap 5.x or 4.x is supported. You can also use the bootstrap css 3.3.x versions -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        crossorigin="anonymous">

    <!-- default icons used in the plugin are from Bootstrap 5.x icon library (which can be enabled by loading CSS below) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css"
        crossorigin="anonymous">

    <!-- alternatively you can use the font awesome icon library if using with `fas` theme (or Bootstrap 4.x) by uncommenting below. -->
    <!-- link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" crossorigin="anonymous" -->

    <!-- the fileinput plugin styling CSS file -->
    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/css/fileinput.min.css" media="all"
        rel="stylesheet" type="text/css" />
    <style>
        .kv-file-rotate,
        .kv-file-upload {
            display: none !important;
        }
    </style>
@endsection
@section($contenue)
    <style>
        .input-group-prepend {
            position: relative;
            /* Add relative positioning */
            z-index: 1;
            /* Set a z-index value to ensure it's positioned behind the input field */
        }
    </style>
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- HEADER -->
            <div class="mb-4">
                <div>
                    <h3 class="page-header page-header-top m-0">Document</h3>
                </div>
            </div>
            <!-- END HEADER -->

            <!-- CARD FILTER -->
            @if(!isTenant())
            <div class="row">
                <div class="col">
                    <div class="card mb-4 p-3">
                        <div class="card-header py-2 px-3" style="background-color: rgb(249,249,249)">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2 w-auto">Rechercher</h5>
                                <hr>
                            </div>
                        </div>
                        <div class="card-body container">

                            <div class="row justify-content-center">
                                <div class="col-md-5 col-12">
                                    <form class="form-inline">
                                        <div class="input-group mb-3">
                                            <button
                                                style="border-radius:7px;background-color:rgb(76,141,203);color:white" disabled><i
                                                    class="fas fa-search"></i></button>
                                            <input type="text" class="form-control" placeholder="document"
                                                aria-label="document_name" id="document_name" onkeyup="myFunction()">
                                        </div>


                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col">
                    <div class="card mb-4 p-3">
                        <div class="card-header py-2 px-3" style="background-color: rgb(249,249,249)">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2 w-auto">Modèles de document</h5>
                                <hr>
                            </div>
                        </div>
                        @if(isTenant())
                        <div class="card-body" id="liste_generale">
                            <div class="row">
                                <div class="col" id="liste_modele">
                                    @foreach ($type_document as $type)
                                        @if($type->id == 3)
                                        <h6>{{ $type->intitule }}</h6>
                                        <ul id="{{ 'type-UL-' . $loop->index }}">
                                            @if (count($type->listeModele) == 0)
                                                <li><a href="#" class="disabled">Vide</a></li>
                                            @else
                                                @foreach ($type->listeModele as $modele)
                                                    <li><a
                                                            href="{{ route('documents.telecharger_modele', ['id' => $modele->id]) }}">{{ $modele->intitule }}</a>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @else

                        <div class="card-body" id="liste_generale">
                            <div class="row">
                                <div class="col" id="liste_modele">
                                    @foreach ($type_document as $type)
                                        <h6>{{ $type->intitule }}</h6>
                                        <ul id="{{ 'type-UL-' . $loop->index }}">
                                            @if (count($type->listeModele) == 0)
                                                <li><a href="#" class="disabled">Vide</a></li>
                                            @else
                                                @foreach ($type->listeModele as $modele)
                                                    <li><a
                                                            href="{{ route('documents.telecharger_modele', ['id' => $modele->id]) }}">{{ $modele->intitule }}</a>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="card-body d-none" id="resultat_recherche">
                            <div class="row">
                                <div class="col">
                                    <p><button type="button" class="btn btn-primary" onclick="listePrincipale()">Liste
                                            principale</button></p>
                                    <h6>Resultat</h6>
                                    <ul id="liste_resultat">

                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- END CARD FILTER -->
    </div>
    <!-- / Content -->
    <div class="content-backdrop fade"></div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        let nombre_type = @json(count($type_document));

        function myFunction() {
            let filter, ul, li, a, i, txtValue;
            filter = $('#document_name').val().toUpperCase();
            // console.log(filter);
            for (let index = 0; index < nombre_type; index++) {
                ul = document.getElementById("type-UL-" + index);
                li = ul.getElementsByTagName("li");
                for (i = 0; i < li.length; i++) {
                    a = li[i].getElementsByTagName("a")[0];
                    txtValue = a.textContent || a.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) !== -1) {
                        li[i].style.display = "";
                    } else {
                        li[i].style.display = "none";
                    }
                }
            }

        }
    </script>
@endsection
