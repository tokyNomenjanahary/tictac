@extends('proprietaire.index')
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
        .form-control[readonly] {
            background-color: #FFFFFF !important;
            opacity: 1;
        }
    </style>
@endsection
@section('contenue')
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- CARD FILTER -->
            <form action="{{ route('finance.sauver_Loyer') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <div class="card h-100">
                        <div class="card-header border-bottom py-2 px-3">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2 w-auto">{{__('revenu.Revenu')}}</h5>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-3 border-bottom">
                                <div class="mb-3 row">
                                    <label for="html5-text-input" class="col-md-2 col-form-label text-md-end">{{__('revenu.Location')}}</label>
                                    <div class="col-lg-5">
                                        <select name="location" id="location" disabled class="form-select">
                                            <option value="{{ $finance->location_id }}">
                                                {{ $finance->Location->identifiant }}</option>
                                            @foreach ($locations as $location)
                                                <option value="{{ $location->id }}">{{ $location->identifiant }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="html5-text-input" class="col-md-2 col-form-label text-md-end">{{__('revenu.Bien')}}</label>
                                    <div class="col-lg-5">
                                        <select name="bien" id="bien" disabled class="form-select">
                                            {{-- <option value="">Pas lié a un bien</option> --}}
                                                <option value="{{ $finance->logement_id }}">
                                                    {{ $finance->Logement->identifiant }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="html5-text-input" class="col-md-2 col-form-label text-md-end">{{__('finance.de')}}</label>
                                    <div class="col-lg-5">
                                        <select class="form-select" id="locataire_id" disabled name="locataire_id">
                                            <option value="{{ $finance->locataire_id }}">
                                                {{ $finance->locataire->TenantFirstName }}</option>
                                            @foreach ($locataires as $locataire)
                                                <option value="{{ $locataire->id }}">{{ $locataire->TenantFirstName }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="html5-text-input" class="col-md-2 col-form-label text-md-end">{{__('revenu.Type')}}</label>
                                    <div class="col-lg-5">
                                        <select class="form-select" id="Type" name="Type">
                                                <option value="{{ $finance->Description }}">{{ $finance->Description }}
                                                </option>
                                            <option value="{{__('revenu.apport')}}">{{__('revenu.apport')}}</option>
                                            <option value="{{__('revenu.depot')}}">{{__('revenu.depot')}}</option>
                                            <option value="{{__('revenu.droit')}}">{{__('revenu.droit')}}</option>
                                            <option value="{{__('revenu.assurance')}}">{{__('revenu.assurance')}}</option>
                                            <option value="{{__('revenu.regularisation')}}">{{__('revenu.regularisation')}}</option>
                                            <option value="{{__('revenu.Remboursement')}}">{{__('revenu.Remboursement')}}</option>
                                            <option value="{{__('revenu.Remboursement_du_locataire')}}">{{__('revenu.Remboursement_du_locataire')}}</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="mb-3 row">
                                    <label for="html5-text-input" class="col-md-2 col-form-label text-md-end">Fréquence</label>
                                    <div class="col-lg-5">
                                        <select class="form-select" id="frequence" name="frequence">
                                            <option value="Une fois">Une fois</option>
                                            <option value="Réccurent">Réccurent</option>
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="mb-3 row">
                                    <label for="html5-date-input" class="col-md-2 col-form-label text-md-end">{{__('finance.Date')}}</label>
                                    <div class="col-lg-5">
                                            <input type="date" class="form-control" readonly id="date" name="date"
                                                value="{{ $finance->debut }}">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="html5-date-input" class="col-md-2 col-form-label text-md-end"> </label>
                                    <div class="col-lg-5">
                                            <input type="date" class="form-control" readonly id="fin" name="fin"
                                                value="{{ $finance->fin }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-header border-bottom py-2 px-3">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2 w-auto">{{__('finance.Montant')}}</h5>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="p-3 border-bottom">
                                <div class="mt-3 row">
                                    <label for="Loyer" class="mt-3 col-md-2 col-form-label text-md-end">{{__('finance.Loyer')}}</label>
                                    <div class="col-lg-2">
                                        <div class="mt-3 input-group">
                                                <input type="number" class="form-control" id="Loyer"  name="Loyer" value="{{$finance->loyer_HC}}">
                                        </div>
                                    </div>
                                    <div class="mt-3  col-lg-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Loyer" readonly  >
                                        </div>
                                    </div>
                                    <div class="mt-3  col-lg-5">
                                        <input class="form-control" type="text" placeholder="description">
                                    </div>
                                </div>
                                <div class="mb-3  row">
                                    <label for="charge" class="mt-3 col-md-2 col-form-label text-md-end">{{__('finance.Charges')}}</label>
                                    <div class="col-lg-2">
                                        <div class="mt-3 input-group">
                                                <input type="number" class="form-control" id="charge"  placeholder="Montant" name="charge" value="{{$finance->charge}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 mt-3">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Loyer" readonly>
                                        </div>
                                    </div>
                                    <div class="mt-3 col-lg-5">
                                        <input class="form-control" type="text"  placeholder="Description">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 mt-3 row text-center" style="margin-top: 16px;">
                                <label for="html5-text-input" class="col-md-2 col-form-label text-md-end"></label>
                                <div class="col-lg-8">
                                    <a class="btn btn-dark" href="{{route('finance.annuler')}}" style="color:white;">{{__('depense.Annuler')}}</a>
                                    <button class="btn btn-primary" id="modifier-Loyer">{{__('depense.Sauvegarder')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END CARD FILTER -->
        </div>
        <!-- / Content -->
        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->
@endsection
@push('script')
@endpush

@push('js')
    <!-- buffer.min.js and filetype.min.js are necessary in the order listed for advanced mime type parsing and more correct
                                 preview. This is a feature available since v5.5.0 and is needed if you want to ensure file mime type is parsed
                                 correctly even if the local file's extension is named incorrectly. This will ensure more correct preview of the
                                 selected file (note: this will involve a small processing overhead in scanning of file contents locally). If you
                                 do not load these scripts then the mime type parsing will largely be derived using the extension in the filename
                                 and some basic file content parsing signatures. -->
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/buffer.min.js"
        type="text/javascript"></script>
    {{-- <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/filetype.min.js" type="text/javascript"></script> --}}

    <!-- piexif.min.js is needed for auto orienting image files OR when restoring exif data in resized images and when you
                                     wish to resize images before upload. This must be loaded before fileinput.min.js -->
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/piexif.min.js"
        type="text/javascript"></script>

    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview.
                                     This must be loaded before fileinput.min.js -->
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/sortable.min.js"
        type="text/javascript"></script>

    <!-- bootstrap.bundle.min.js below is needed if you wish to zoom and preview file content in a detail modal
                                     dialog. bootstrap 5.x or 4.x is supported. You can also use the bootstrap js 3.3.x versions. -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>

    <!-- the main fileinput plugin script JS file -->
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/fileinput.min.js"></script>

    <!-- following theme script is needed to use the Font Awesome 5.x theme (`fa5`). Uncomment if needed. -->
    <!-- script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/themes/fa5/theme.min.js"></script -->

    <!-- optionally if you need translation for your language then include the locale file as mentioned below (replace LANG.js with your language locale) -->
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/locales/LANG.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.5.0/js/locales/fr.js"
        integrity="sha512-ncBK/c/2Y7C/dQ94Ye0QDDBeMrFY7yCb3KGod1KVRQR4nSlXKAXoCzpwHysH5BPMS/hXAe5xaw4/bYyuMjTY4A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[type="hidden"]').attr('value')
                }
            });
        })

        $("#modifier-Loyer").on("click", function(e) {
            e.preventDefault()
            var bien = $("#bien").val()
            var location = $("#location").val()
            var Type = $("#Type").val()
            var date = $("#date").val()
            var locataire = $("#locataire_id").val()
            var charge = $("#charge").val()
            var Loyer = $("#Loyer").val()
            var fin = $("#fin").val()

            $("#myLoader").removeClass("d-none")


            $.ajax({
                type: 'POST',
                url: '{{ route('finance.sauver_Loyer') }}',
                data: {
                    bien: bien,
                    location: location,
                    date: date,
                    locataire: locataire,
                    Loyer : Loyer,
                    charge : charge,
                    Type: Type,
                    fin : fin
                },
                dataType: "json",
                success: function(data) {
                    console.log(data)
                    if ($.isEmptyObject(data.errors)) {
                        window.location = "{{ route('proprietaire.finance') }}"
                    } else {
                        ErrorMsge(data.errors)
                        $("#myLoader").addClass("d-none")
                    }
                }

            });

            function ErrorMsge(msg) {
                var paris = []
                $.each(msg, function(i, value) {
                    $('.' + i + '_err').text("ce champ est obligatoire")
                    $('#' + i).addClass('is-invalid')
                });

            }
        })
    </script>
@endPush
