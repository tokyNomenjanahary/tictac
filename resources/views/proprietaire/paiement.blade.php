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
            <!-- HEADER -->
            <div class="mb-4">
                <div>
                    <h3 class="page-header page-header-top m-0">{{ __('paye.Enregistrer_un_paiement') }}</h3>
                </div>
            </div>
            <!-- END HEADER -->

            <!-- CARD FILTER -->
                <form action="{{ route('paiement.sauver') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <div class="card h-100">
                            <div class="card-header border-bottom py-2 px-3">
                                <div class="card-title mb-0">
                                    <h5 class="m-0 me-2 w-auto">{{ __('revenu.Revenu') }}</h5>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="p-3 border-bottom">
                                    <div class="mb-3 row">
                                        <label for="html5-text-input"
                                            class="col-md-2 col-form-label text-md-end">{{ __('revenu.Bien') }}</label>
                                        <div class="col-lg-5">
                                            <input type="text" readonly class="form-control"
                                                value="{{ $finance->Logement->identifiant }}">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="html5-text-input"
                                            class="col-md-2 col-form-label text-md-end">{{ __('finance.de') }}</label>
                                        <div class="col-lg-5">
                                            <input type="text" value="{{ $finance->Locataire->TenantFirstName }}"
                                                readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="html5-text-input"
                                            class="col-md-2 col-form-label text-md-end">{{ __('revenu.Type') }} </label>
                                        <div class="col-lg-5">
                                            <input type="text" readonly value="{{ $finance->Description }}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="html5-text-input"
                                            class="col-md-2 col-form-label text-md-end">{{ __('finance.Date') }}</label>
                                        <div class="col-lg-5">
                                            <input type="date" readonly value="{{ $finance->debut }}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="html5-text-input"
                                            class="col-md-2 col-form-label text-md-end">{{ __('finance.Montant') }}</label>
                                        <div class="col-lg-5">
                                            <input type="text" readonly value="{{ $finance->montant }} €"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-header border-bottom py-2 px-3">
                                <div class="card-title mb-0">
                                    <h5 class="m-0 me-2 w-auto">{{ __('finance.Montant') }}</h5>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="p-3 border-bottom">
                                    <div class="mb-3 row" id="test">
                                        <label for="montant" class=" mt-3 col-md-2 col-form-label text-md-end">{{ __('paye.recu') }}</label>
                                        <div class="col-lg-2">
                                            <input class="form-control mt-3" readonly type="text" value="{{ $finance->montant }} €"
                                                placeholder={{ __('finance.Montant') }}>
                                        </div>
                                        <div class="col-lg-2">
                                            <input class="form-control readonly   mt-3" type="text" readonly
                                                value="{{ $finance->Locataire->TenantFirstName }}">
                                        </div>
                                        <div class="col-lg-2">
                                            <input class="form-control mt-3" readonly  type="text"
                                                placeholder={{ __('paye.mode_de_payement') }}>
                                        </div>
                                        <div class="col-lg-2">
                                            <input class="form-control mt-3" readonly  type="date" value="{{ $finance->debut }}"
                                                placeholder={{ __('finance.Date') }}>
                                        </div>
                                    </div>
                                    <p>{{__('paye.regle_montant')}}</p>
                                    <a id="ajouter"
                                        style="border: 1px solid gray;background-color:#F3F5F6;margin-left:17%;border:1px solid red;"
                                        class="btn push m-t-10 text-info"> {{ __('paye.Enregistrer_un_paiement') }}</a>
                                </div>
                                <div class="card-header border-bottom py-2 px-3 mb-3">
                                    <div class="card-title mb-0">
                                        <h5 class="m-0 me-2 w-auto">{{__('depense.Autres_informations')}}</h5>
                                    </div>
                                </div>
                                <div class="p-3 border-bottom">
                                <div class="mb-3 row">
                                    <label for="html5-text-input"
                                        class="col-md-2 col-form-label text-md-end">{{__('revenu.description')}}</label>
                                    <div class="col-lg-5">
                                        <textarea class="form-control" id="Autres_informations" rows="2" readonly  name="Autres_informations">
                                         @if($Autres_information){{ $Autres_information->Autres_informations }} @endif
                                        </textarea>
                                        <p>{{__('paye.notes')}}</p>
                                    </div>
                                </div>
                        </div>
                        <div class="p-3 border-bottom">
                            <div class="mb-3 row text-center">
                                <label for="html5-text-input" class="col-md-2 col-form-label text-md-end"></label>
                                <div class="col-lg-8">
                                    <a class="btn btn-dark" href="{{route('finance.annuler')}}" style="color:white;">{{__('depense.Annuler')}}</a>
                                    <button class="btn btn-primary" type="submit"
                                        id="enregistrer-etat">{{__('depense.Sauvegarder')}}</button>
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

            $('#ajouter').on('click', function() {
                let child = `<div class="row p-3 align-items-end">
                                <div class="col-md-2 text-md-end"><span class="btn btn-danger btn-remove"><i class="fa-solid fa-trash-can"></i></span></div>
                                        <div class="col-lg-2">
                                            <input class="form-control mt-3" type="text" name="montant[]" id="montant"
                                                placeholder={{__('finance.Montant')}}>
                                        </div>
                                        <div class="col-lg-2">
                                            <input class="form-control mt-3" type="text" readonly name="locataire[]" value="{{ $finance->Locataire->TenantFirstName }} "id="locataire">
                                        </div>
                                        <div class="col-lg-2">
                                            <select class="form-select mt-3" name="mode[]" id="mode">
                                                <option value="{{__('paye.carte')}}">{{__('paye.carte')}}</option>
                                                <option value="{{__('paye.Chèque')}}">{{__('paye.Chèque')}}</option>
                                                <option value="{{__('paye.Espèces')}}">{{__('paye.Espèces')}}</option>
                                                <option value="{{__('paye.prelevement')}}">{{__('paye.prelevement')}}</option>
                                                <option value="{{__('paye.Virement')}}">{{__('paye.Virement')}}</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2">
                                            <input class="form-control mt-3" readonly type="date" name="date[]" id="date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                placeholder={{__('finance.Date')}}>
                                        </div>
                                </div>`
                $("#test").append(child)
                $("span.btn-remove").on('click', function(e) {
                    $(this).closest('.row.p-3.align-items-end').remove()
                })
            })
        })
    </script>
@endPush
