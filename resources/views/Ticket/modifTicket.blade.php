<?php
if (!isTenant()) {
    $entete = 'proprietaire.index';
    $contenue = 'contenue';
} else {
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

        .bg-check {
            background: #d0d0f1;
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
                    <h3 class="page-header page-header-top m-0"><a href="{{ route('ticket.index') }}"> <i
                                class="fas fa-chevron-left"></i> </a>{{ __('ticket.modifier_ticket') }}</h3>

                </div>
            </div>
            <!-- END HEADER -->

            <!-- CARD FILTER -->
            <div class="row">
                <div class="card mb-4 p-3">
                    <div class="row">
                        <div class="col">
                            <form action="{{ route('ticket.sauvermodif') }}" method="post" enctype="multipart/form-data">
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

                                                <div class="mb-3 row">
                                                    <label for="html5-text-input"
                                                        class="col-md-2 col-form-label text-md-end">{{ __('ticket.sujet') }}</label>
                                                    <div class="col-lg-5">
                                                        <input type="text" name="sujet" class="form-control"
                                                            @if ($ticket->Subject) value="{{ $ticket->Subject }}" @endif>
                                                        @error('sujet')
                                                            <span
                                                                class="text-danger">{{ str_replace('sujet', 'subject', $message) }}</span>
                                                        @enderror
                                                    </div>

                                                </div>
                                                <div class="mb-3 row">
                                                    <label for="html5-text-input"
                                                        class="col-md-2 col-form-label text-md-end">Type</label>
                                                    <div class="col-lg-10">
                                                        @foreach ($types_tickets as $type)
                                                            <input hidden name="type_ticket" class="form-check-input"
                                                                @if ($type->id == $ticket->type_ticket_id) @checked(true) @endif
                                                                type="radio" value="{{ $type->id }}"
                                                                id="ticket_{{ $type->id }}">
                                                            <label
                                                                class="p-2 handle-radio-type col-lg-2 m-2 @if ($type->id == $ticket->type_ticket_id) bg-check @endif "
                                                                for="ticket_{{ $type->id }}" id="test"
                                                                style="border: 2px solid; border-radius: 10px; padding-top: 1rem;">
                                                                <center>
                                                                    @switch($type->id)
                                                                        @case(1)
                                                                            <i class="fa-solid fa-screwdriver-wrench"
                                                                                style="font-size: 2rem;"></i><br>
                                                                            {{ $type->Name }}
                                                                        @break

                                                                        @case(2)
                                                                            <i class="fa-solid fa-temperature-arrow-up"
                                                                                style="font-size: 2rem;"></i></i><br>
                                                                            {{ $type->Name }}
                                                                        @break

                                                                        @case(3)
                                                                            <i class='bx bx-wind' style="font-size: 2rem;"></i><br>
                                                                            {{ $type->Name }}
                                                                        @break

                                                                        @case(4)
                                                                            <i class="fa-solid fa-lightbulb"
                                                                                style="font-size: 2rem;"></i><br>
                                                                            {{ $type->Name }}
                                                                        @break

                                                                        @case(5)
                                                                            <i class='bx bxs-washer'
                                                                                style="font-size: 2rem;"></i><br>
                                                                            {{ $type->Name }}
                                                                        @break

                                                                        @case(6)
                                                                            <i class="fa-solid fa-tree"
                                                                                style="font-size: 2rem;"></i><br>
                                                                            {{ $type->Name }}
                                                                        @break

                                                                        @case(7)
                                                                            <i class="fa-solid fa-broom"
                                                                                style="font-size: 2rem;"></i><br>
                                                                            {{ $type->Name }}
                                                                        @break

                                                                        @case(8)
                                                                            <i class="fa-solid fa-bug" style="font-size: 2rem;">
                                                                            </i><br>
                                                                            {{ $type->Name }}
                                                                        @break

                                                                        @case(9)
                                                                            <i class="fa-solid fa-paint-roller"
                                                                                style="font-size: 2rem;"></i><br>
                                                                            {{ $type->Name }}
                                                                        @break

                                                                        @case(10)
                                                                            <i class="fa-solid fa-faucet-drip"
                                                                                style="font-size: 2rem;"> </i><br>
                                                                            {{ $type->Name }}
                                                                        @break

                                                                        @case(11)
                                                                            <i class="fa-solid fa-key"
                                                                                style="font-size: 2rem;"></i><br>
                                                                            {{ $type->Name }}
                                                                        @break

                                                                        @case(12)
                                                                            <i class="fa-solid fa-ellipsis"
                                                                                style="font-size: 2rem;"></i><br>
                                                                            {{ $type->Name }}
                                                                        @break
                                                                    @endswitch
                                                                </center>
                                                            </label>
                                                        @endforeach
                                                        {{-- <select name="type_ticket" id="type_ticket" class="form-control" required>

                                                             <option value={{$ticket->type_ticket_id}}>{{$ticket->gettickets->Name}}</option>
                                                            @endforeach
                                                            @foreach ($types_tickets as $type)
                                                                @if (old('type_ticket') == $type->id)
                                                                    <option value="{{ $type->id }}" selected>
                                                                        {{ $type->Name }}</option>
                                                                @else
                                                                    <option value="{{ $type->id }}">
                                                                        {{ $type->Name }}</option>
                                                                @endif


                                                        </select> --}}
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label for="html5-text-input"
                                                        class="col-md-2 col-form-label text-md-end">{{ __('ticket.priorite') }}</label>
                                                    <div class="col-lg-10">
                                                        <div class="row">
                                                            @for ($i = 1; $i <= 3; $i++)
                                                                <div class="col-lg-3 form-check p-2">
                                                                    <input hidden name="priorite" class="form-check-input"
                                                                        @if ($i == $ticket->Priority[0]) @checked(true) @endif
                                                                        type="radio" value="{{ $i }}"
                                                                        id="priorite_{{ $i }}">
                                                                    <label
                                                                        class="p-2 w-50 handle-radio @if ($i == $ticket->Priority[0]) bg-check @endif"
                                                                        for="priorite_{{ $i }}"
                                                                        id="p_{{ $i }}"
                                                                        style="border: 2px solid; border-radius: 10px; padding-top: 1rem;">
                                                                        <center>
                                                                            @if ($i == 1)
                                                                                <i class="fa-regular fa-face-smile"
                                                                                    style="font-size: 2rem;"></i><br>
                                                                                {{ __('ticket.basse') }}
                                                                            @endif
                                                                            @if ($i == 2)
                                                                                <i class="fa-regular fa-face-meh"
                                                                                    style="font-size: 2rem;"></i><br>
                                                                                {{ __('ticket.normale') }}
                                                                            @endif
                                                                            @if ($i == 3)
                                                                                <i class="fa-regular fa-face-frown"
                                                                                    style="font-size: 2rem;"></i><br>
                                                                                {{ __('ticket.urgence') }}
                                                                            @endif
                                                                        </center>
                                                                    </label>
                                                                </div>
                                                            @endfor
                                                            @error('priorite')
                                                                <span
                                                                    class="text-danger">{{ str_replace('priorite', 'priorite', $message) }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                @if (isTenant())
                                                    <div class="mb-3 row">
                                                        <label for="html5-text-input"
                                                            class="col-md-2 col-form-label text-md-end">Proprietaire</label>
                                                        <div class="col-lg-10">
                                                            <input type="text" name="" class="form-control"
                                                                disabled
                                                                value="{{ $ticket->locations->user->first_name }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 row">
                                                        <label for="html5-text-input"
                                                            class="col-md-2 col-form-label text-md-end">{{ __('revenu.Location') }}</label>
                                                        <div class="col-lg-10">
                                                            <input type="text" class="form-control" disabled
                                                                value="{{ $ticket->locations->identifiant }}">
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="mb-3 row">
                                                        <label for="html5-text-input"
                                                            class="col-md-2 col-form-label text-md-end">{{ __('revenu.Location') }}</label>
                                                        <div class="col-lg-5">
                                                            <select disabled name="location" id="location"
                                                                class="form-control" required>
                                                                <option value={{ $ticket->Location_id }}>
                                                                    {{ $ticket->locations->identifiant }} </option>
                                                                @foreach ($locations as $location)
                                                                    @if (old('location') == $location->id)
                                                                        <option value="{{ $location->id }}" selected>
                                                                            {{ $location->identifiant }}</option>
                                                                    @else
                                                                        <option value="{{ $location->id }}">
                                                                            {{ $location->identifiant }}</option>
                                                                    @endif
                                                                @endforeach

                                                            </select>
                                                            @if (count($locations) == 0)
                                                                <span class="text-danger">Vous n'avez pas de location
                                                                    active</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="mb-3 row">
                                                        <label for="html5-text-input"
                                                            class="col-md-2 col-form-label text-md-end">{{ __('acceuil.locataire') }}</label>
                                                        <div class="col-lg-5">
                                                            <input type="text" name="locataire" id="locataire"
                                                                class="form-control" disabled>

                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="mb-3 row">
                                                    <label for="html5-text-input"
                                                        class="col-md-2 col-form-label text-md-end">{{ __('Document') }}</label>
                                                    <div class="col-lg-10">
                                                        <input id="input-file" name="input-file[]" type="file"
                                                            data-browse-on-zone-click="true" multiple>
                                                        @error('input-file')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                        <input type="hidden" id="plugin" name="plugin">
                                                    </div>
                                                </div>

                                                <div class="mb-3 row">
                                                    <label for="html5-text-input"
                                                        class="col-md-2 col-form-label text-md-end"></label>
                                                    <div class="col-12 col-lg-6">
                                                        <hr>
                                                        <label class="mb-2">{{ __('ticket.doc_existant') }}</label>
                                                        <div class="input-group mb-3 col-12 col-md-7">
                                                            <input type="hidden" name="compteur-doc" id="compteur-doc">
                                                            <select class="custom-select form-control"
                                                                id="fichier_existant">
                                                                <option value="" selected>{{__('texte_global.choisir')}}</option>
                                                                @foreach ($documents as $doc)
                                                                    <option value="{{ $doc->id }}">
                                                                        {{ $doc->nomFichier }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <label class="input-group-text btn-primary"
                                                                    style="text-decoration:none" for="fichier_existant"
                                                                    onclick="addDocument()">{{__('texte_global.ajouter')}}</label>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="mb-3 row">
                                                    <label for="html5-text-input"
                                                        class="col-md-2 col-form-label text-md-end"></label>
                                                    <div class="col-lg-6" id="list_fichier_exisant">
                                                        @if (isset($document_existant))
                                                            @foreach ($document_existant as $key => $doc)
                                                                <div class="input-group mb-2"
                                                                    id="item-{{ $doc->id }}">
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $doc->nomFichier }}" disabled>
                                                                    <input type="text" value="{{ $doc->id }}"
                                                                        name="doc-{{ $key }}" hidden>
                                                                    <span class="input-group-text"
                                                                        onclick="deleteDocumentExistant({{ $doc->id }})"
                                                                        style="background-color: red"><i
                                                                            class="fa-solid fa-trash"
                                                                            style="color:white"></i></span>
                                                                </div>
                                                            @endforeach
                                                        @endif

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
                                            <a class="btn btn-dark" style="color:white;"
                                                href="{{ route('ticket.index') }}">{{ __('depense.Annuler') }}</a>
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('depense.Sauvegarder') }} </button>
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
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/buffer.min.js"
        type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/piexif.min.js"
        type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/sortable.min.js"
        type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/fileinput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/locales/LANG.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.5.0/js/locales/fr.min.js"
        integrity="sha512-IzzZlYpScPi/cBy0PyW7EIyFeZr6Uwxl7M3UCu2oDvI00xbBC2Qc+S/lwtE3hlKxXNxd7owqwIuIvz6g9rGVeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[type="hidden"]').attr('value')
                }
            });

            var plugin
            let initialPreviewArray = []
            let initialPreviewConfigArray = []
            @if (isset($ticketDocument))
                var etatFiles = {!! json_encode($ticketDocument) !!};
                var img_file = ["docx", "doc", "pdf", "txt", "odt", "rtf", "pptx", "ppt", "xlsx", "xls"]
                for (let i = 0; i < etatFiles.length; i++) {
                    var extension = etatFiles[i].type
                    if (img_file.includes(extension)) {
                        initialPreviewArray.push("<i class='bi-file-earmark-fill'></i>")
                        initialPreviewConfigArray.push({
                            type: "file",
                            caption: etatFiles[i].name,
                            width: '120px',
                            url: "{{ route('ticket.DeleteTicketFiles') }}",
                            extra: {
                                id: etatFiles[i].id,
                                type: "deleted",
                                file_name: etatFiles[i].name,
                                etat: etatFiles[i].ticket_id
                            }
                        });

                    } else {
                        initialPreviewArray.push('/uploads/ticket/document/' + etatFiles[i].name)
                        initialPreviewConfigArray.push({
                            type: "image",
                            caption: etatFiles[i].name,
                            width: '120px',
                            url: "{{ route('ticket.DeleteTicketFiles') }}",
                            extra: {
                                id: etatFiles[i].id,
                                type: "deleted",
                                file_name: etatFiles[i].name,
                                etat: etatFiles[i].ticket_id
                            }
                        });
                    }
                }
            @endif

            let test = $("#input-file").fileinput({
                language: "fr",
                initialPreview: initialPreviewArray,
                initialPreviewAsData: true,
                initialPreviewConfig: initialPreviewConfigArray,
                uploadUrl: "{{ route('ticket.uploadTicketFiles') }}",
                deleteUrl: "{{ route('ticket.DeleteTicketFiles') }}",
                overwriteInitial: false,
                allowedFileExtensions: ["pdf", "jpeg", "png", 'gif', 'jpg', 'docx'],
                maxFileCount: 8,
                showUpload: false,
                uploadAsync: true,
                showRemove: false,
                showCancel: false,
                showDrag: false,
                maxFileSize: 5120,
            })

            test.on("filebatchselected", function(event, files) {
                $("#input-file").fileinput("upload");
            });
            test.on("filebatchuploadcomplete", function(event) {
                var tab = []
                plugin = $('#input-file').data('fileinput');
                for (let index = 0; index < plugin.initialPreviewConfig.length; index++) {
                    tab.push(plugin.initialPreviewConfig[index].id)
                }
                $('#plugin').val(tab);
            });
        });
    </script>


    <script>
        const locations = @json($locations);

        var compteur = @json(count($document_existant));
        var list_doc_ids = [];

        function addDocument() {

            let id_doc = $('#fichier_existant').val();
            let text = jQuery.trim($('#fichier_existant').find(":selected").text());
            if (!id_doc) {
                return;
            }
            if (list_doc_ids.includes(id_doc)) {
                return;
            }
            let row = `
                <div class="input-group mb-2" id="item-${ id_doc }">
                    <input type="text" class="form-control"
                        value="${ text }" disabled>
                    <input type="text" value="${ id_doc }" name="doc-${ compteur }"
                        hidden>
                    <span class="input-group-text" onclick="deleteDocumentExistant(${  id_doc })"
                        style="background-color: red"><i
                            class="fa-solid fa-trash"
                            style="color:white"></i></span>
                </div>
            `;
            list_doc_ids.push(id_doc);
            list_doc_ids = [...new Set(list_doc_ids)];
            compteur++;
            $('#compteur-doc').val(compteur);
            $('#list_fichier_exisant').append(row);

        }

        function deleteDocumentExistant(id) {
            list_doc_ids = list_doc_ids.filter(function(number) {
                return number != id;
            });
            $('#item-' + id).remove();
        }

        $(document).ready(function() {



            $('.handle-radio').on('click', function() {
                $('.handle-radio').removeClass('bg-check')
                $(this).toggleClass('bg-check')
            })
            $('.handle-radio-type').on('click', function() {
                $('.handle-radio-type').removeClass('bg-check')
                $(this).toggleClass('bg-check')
            })

            let id_location = $('#location').val();
            locations.forEach(element => {
                $('#locataire').val(element.locataire.TenantFirstName + ' ' + element
                    .locataire.TenantLastName);
            })

            $('#location').on('change', function() {
                let id_location = $('#location').val();
                if (!id_location) {
                    $('#id_locataire').val(element.locataire.id);
                    $('#locataire_name').val('');
                    $('#locataire').prop('disabled', false);
                    $('#locataire').val('');
                    $('#locataire').prop('disabled', true);
                } else {
                    locations.forEach(element => {
                        if (id_location == element.id) {
                            $('#id_locataire').val(element.locataire.id);
                            $('#locataire').prop('disabled', false);
                            $('#locataire').val(element.locataire.TenantFirstName + ' ' + element
                                .locataire.TenantLastName);
                            $('#locataire_name').val($('#locataire').val());
                            $('#locataire').prop('disabled', true);
                            $('#emailLocataire').val(element.locataire.TenantEmail);
                        }
                    });
                }


            })
        });
    </script>
@endpush
