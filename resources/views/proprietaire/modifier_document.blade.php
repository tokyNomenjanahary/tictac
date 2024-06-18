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
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- HEADER -->
            <div class="mb-4">
                <div>
                   <h3 class="page-header page-header-top m-0"><a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>Modifier document</h3>

                </div>
            </div>
            <!-- END HEADER -->

            <!-- CARD FILTER -->
            <div class="row">
                <div class="card mb-4 p-3">
                    <div class="row">
                        <div class="col">
                            <form action="{{ route('documents.modification_document') }}" method="POST"
                                enctype="multipart/form-data">
                                <input type="hidden" name="id_document" value="{{ $document->id }}">
                                @csrf
                                <div class="mb-4">
                                    <div class="card h-100">
                                        <div class="card-header border-bottom py-2 px-3"
                                            style="background-color: rgb(249,249,249)">
                                            <div class="card-title mb-0">
                                                <h5 class="m-0 me-2 w-auto">Information</h5>
                                            </div>
                                        </div>

                                       


                                        <div class="card-body p-0">

                                            <div class="p-3 border-bottom">
                                                @if (isTenant())
                                                    <div class="mb-3">
                                                        <div class="row align-items-end" >
                                                                <label class="col-md-2 col-form-label text-md-end">Location</label>
                                                            <div class="col-md-8 col-sm-10" >
                                                                <select name="location_id" class="form-control" id="location_id_select">
                                                                    @foreach ($locations as $location)
                                                                        <option value="{{$location->id}}" {{ old('location_id') == $location->id || $document->location_id == $location->id ? 'selected' : '' }}>{{$location->identifiant}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="row align-items-end" >
                                                            <label class="col-md-2 col-form-label text-md-end">Bien</label>
                                                            <div class="col-md-8 col-sm-10" >
                                                                @if ($document->logement_id)
                                                                    @foreach ($locations as $location)
                                                                        @if ($document->location_id == $location->id)
                                                                            <input type="text" disabled class="form-control" value="{{$location->logement->identifiant}}" id="logement_id_field_value">
                                                                            <input type="text" hidden class="form-control" name="logement_id" id="logement_id_field" value="{{$location->logement->id}}">
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <input type="text" disabled class="form-control" value="Pas lié" id="logement_id_field_value">
                                                                    <input type="text" hidden class="form-control" name="logement_id" id="logement_id_field">
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="mb-3 row">
                                                        <label for="html5-text-input"
                                                            class="col-md-2 col-form-label text-md-end">Bien</label>
                                                        <div class="col-lg-5">
                                                            <select name="logement_id" id="" class="form-control">
                                                                <option value="0" selected>Pas lié</option>
                                                                @foreach ($biens as $bien)
                                                                    @if ($bien->id == $document->logement_id)
                                                                        <option value="{{ $bien->id }}" selected>
                                                                            {{ $bien->identifiant }}</option>
                                                                    @else
                                                                        <option value="{{ $bien->id }}">
                                                                            {{ $bien->identifiant }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3 row">
                                                        <label for="html5-text-input"
                                                            class="col-md-2 col-form-label text-md-end">Location</label>
                                                        <div class="col-lg-5">
                                                            <select name="location_id" id="" class="form-control">
                                                                <option value="0" selected>Pas lié</option>
                                                                @foreach ($locations as $location)
                                                                    @if ($location->id == $document->location_id)
                                                                        <option value="{{ $location->id }}" selected>
                                                                            {{ $location->identifiant }}</option>
                                                                    @else
                                                                        <option value="{{ $location->id }}">
                                                                            {{ $location->identifiant }}</option>
                                                                    @endif
                                                                @endforeach

                                                            </select>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="mb-3 row">
                                                    <label for="html5-text-input"
                                                        class="col-md-2 col-form-label text-md-end">Déscription</label>
                                                    <div class="col-lg-5">
                                                        <textarea name="description" id="" class="form-control" style="width: 100%" rows="5">{{ $document->description }}</textarea>
                                                    </div>
                                                </div>


                                                <div class="mb-3 row">
                                                    <label for="html5-text-input"
                                                        class="col-md-2 col-form-label text-md-end">Fichier</label>
                                                    <div class="col-lg-5">
                                                        <input type="text" name=""
                                                            value="{{ $document->nomFichier }}" class="form-control"
                                                            disabled>
                                                    </div>
                                                </div>

                                                <div class="mb-3 row">
                                                    <label for="html5-text-input"
                                                        class="col-md-2 col-form-label text-md-end"></label>
                                                    <div class="col-lg-5">
                                                        @if ($errors->any())
                                                            @foreach ($errors->all() as $error)
                                                                <div class="alert alert-danger">
                                                                    Le fichier ne doit pas dépasser 2Mo
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="mb-3 row">
                                                    <label for="html5-text-input"
                                                        class="col-md-2 col-form-label text-md-end">Nouveau Fichier</label>
                                                    <div class="col-md-5 col-9" id="div_file">
                                                        <input type="file" name="fichier" id="new_file"
                                                            class="form-control file @error('fichier') is-invalid @enderror"
                                                            value="">
                                                    </div>
                                                    <div style="display:none" id="div_delete_file" class="col-auto justify-content-end">
                                                        <span id="deleteButton" class="btn btn-danger"
                                                            onclick="handleFileDelete()"> <i class="fa-solid fa-trash"
                                                                style="color:white;"></i></span>

                                                    </div>
                                                </div>


                                                <div class="mb-3 row">
                                                    <label for="html5-date-input"
                                                        class="col-md-2 col-form-label text-md-end">Date</label>
                                                    <div class="col-lg-5">
                                                        <input type="date" name="date" value="{{ $document->date }}"
                                                            class="form-control">

                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label for="html5-date-input"
                                                        class="col-md-2 col-form-label text-md-end">Partage</label>
                                                    <div class="col-lg-5">
                                                        <input type="checkbox" name="partage"  class="mt-2"  {{ $document->partage == 1 ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card text-center" style="margin-top: 5px">
                                    <div class="row">
                                        <div class="col-md-2">
                                        </div>
                                        <div class="col-lg-5" style="padding: 15px;">
                                            <button type="submit" class="btn btn-primary">
                                                Sauvegarder </button>
                                            <a class="btn btn-dark" style="color:white;"
                                                href="{{ route('documents.index') }}">Annuler</a>
                                            {{-- <a role="button" class="btn btn-dark float-left"
                                                href="{{ redirect()->route('documents.index') }}">Annuler</a> --}}

                                        </div>
                                    </div>
                                </div>
                            </form>
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
@endsection
@push('script')
    <script>
        const fileInput = document.querySelector('input[type="file"]');

        $(document).ready(function() {
            fileInput.addEventListener('change', handleFileSelect);
        })

        function handleFileSelect(event) {
            const fileList = event.target.files;
            if (fileList.length > 0) {
               $('#div_delete_file').show();
            }
        }

        function handleFileDelete() {
            $('#new_file').val('');
            $('#div_delete_file').hide();
        }

        const locations = @json($locations);
        $('#location_id_select').on('change', function() {
            let id_location = $('#location_id_select').val();
            if (!id_location) {
                $('#logement_id_field_value').val('Pas lié');
            } else {
                locations.forEach(element => {
                    if (id_location == element.id) {
                        $('#logement_id_field').val(element.logement.id);
                        $('#logement_id_field_value').val(element.logement.identifiant);
                    }
                });
            }
        })
    </script>
@endpush
