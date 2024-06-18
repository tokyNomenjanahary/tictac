@extends('proprietaire.index')
<style>
    .nav-link.active{
        border-bottom: 3px solid #4C8DCB !important;
    }
    hr{
      color : blue;
      width: 2px;
    }

    .notification{
        color: red;
        display: none;
    }
</style>
@section('contenue')


<style>
  #active_records {
      background-color: rgba(114, 163, 51, 0.14) !important;
      border: 1px solid rgba(114, 163, 51, 0.6);
      border-right: 1px solid rgba(114, 163, 51, 0.4);
  }

  #archived_records {
      /* background-color: rgba(114, 163, 51, 0.14) !important; */
      border: 1px solid rgba(114, 163, 51, 0.6);
      border-right: 1px solid rgba(114, 163, 51, 0.4);
  }

  .extension-contrat{
      display: flex;
      align-items: center;
      justify-content: center;
      width: 3rem;
      height: 3rem;
      color: black;
  }
</style>
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css"> --}}
    @endpush

<div class="p-12">
   <header class="bg-white shadow" style="margin:25px auto;margin-left:25px;margin-right: 25px">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="row">
            <div class="col-md-12 p-3">
               <div class="float-start">
                @if(isset($detailLogement))
                    <h3 class="page-header page-header-top m-0"><a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>Modification de logement</h3>

                @else
                    <h3 class="page-header page-header-top m-0"><a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>Nouveau logement</h3>
                @endif
               </div>

            </div>
          </div>
        <div class="row">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#information_generale" type="button" role="tab" aria-controls="home" aria-selected="true">INFORMATION GÉNÉRALE</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link " id="profile-tab" data-bs-toggle="tab" data-bs-target="#complementaire" type="button" role="tab" aria-controls="profile" aria-selected="false">INFORMATION COMPLÉMENTAIRE</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link " id="photo-tab" data-bs-toggle="tab" data-bs-target="#photo_logement" type="button" role="tab" aria-controls="profile" aria-selected="false">PHOTO</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="contrat-tab" data-bs-toggle="tab" data-bs-target="#contrat" type="button" role="tab" aria-controls="contrat" aria-selected="false">CONTRAT ET DIAGNOSTIC</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">CONTACT</button>
              </li>
            </ul>

            <!-- Tab panes -->
            <form id="formLogement" class="" method="POST" @if(isset($detailLogement)) action="{{ route('proprietaire.saveEditLogement',$detailLogement->id) }}" @else action="{{ route('proprietaire.save_logement') }}" @endif enctype="multipart/form-data">
                @csrf
                <div class="tab-content mt-3" style="padding: 0;">
              {{-- information géneral --}}

                    <div class="tab-pane active" id="information_generale" role="tabpanel" aria-labelledby="home-tab">
                        @include('proprietaire.nouveaux_logement_info_generale')
                    </div>

                    <div class="tab-pane " id="complementaire" role="tabpanel" aria-labelledby="profile-tab">
                        @include('proprietaire.nouveaux_logement_infocomplementaire')
                    </div>
                    <div class="tab-pane " id="photo_logement" role="tabpanel" aria-labelledby="profile-tab">
                        @include('proprietaire.nouveaux_logement_photo')
                    </div>
                    <div class="tab-pane" id="contrat" role="tabpanel" aria-labelledby="messages-tab">
                        @include('proprietaire.nouveaux_logement_contrat')
                    </div>
                    <div class="tab-pane" id="contact" role="tabpanel" aria-labelledby="messages-tab">
                        @include('proprietaire.nouveaux_logement_contact')
                    </div>
                    <div class="card" style="margin-top: 5px">
                        <div class="card-body" style="margin-top: -5px">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="float-start">

                                    </div>
                                    <div class="float-end">
                                        <a href="" class="btn btn-secondary">Annuler</a>
                                        <button type="button" class="btn btn-success" id="saveLogement"> Sauvegarder </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
      </div>
   </header>
</div>

@push('script')
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/buffer.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/piexif.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/sortable.min.js" type="text/javascript"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script> --}}
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/fileinput.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/locales/LANG.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.5.0/js/locales/fr.js"
integrity="sha512-ncBK/c/2Y7C/dQ94Ye0QDDBeMrFY7yCb3KGod1KVRQR4nSlXKAXoCzpwHysH5BPMS/hXAe5xaw4/bYyuMjTY4A==" crossorigin="anonymous"
referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
    $(document).ready(function() {

        //function pour remettre à 0 les chiffre netave
        function valeurMinInput(idInput) {
            $('#'+idInput).on('input', function() {
                if ($(this).val() < 0) {
                    $(this).val(0);
                }
                var number = $(this).val();
                number = number.replace(/[^0-9+\s]/g, ''); // Supprimer les caractères non numériques et non "+" ou espaces
                $(this).val(number);
            });
        }

        /*** on ne peut pas mettre d'autre caractere que le numero ***/
        function valeurNumerique(id){
            $('#'+id).on('keydown', function(e) {
                var key = e.keyCode || e.which;
                var spanNotification = $('#'+id).next('.notification');
                if (key === 189 || key === 109 || key === 107 || key === 190 || key === 110) {
                    e.preventDefault();
                    if (spanNotification.length === 0) {
                        spanNotification = $('<span>', { class: 'notification' }).appendTo($('#'+id).parent());
                    }
                    spanNotification.text('La valeur ne peut pas être négative');
                    spanNotification.show();
                    $('#'+id).addClass('border-danger');
                }else{
                    spanNotification.hide();
                    $(this).removeClass('border-danger')
                }
            });
        }
        valeurNumerique('escalier');
        valeurNumerique('etage');
        valeurNumerique('numero');
        valeurNumerique('loyer');
        valeurNumerique('charge');
        valeurNumerique('superficie');
        valeurNumerique('nbr_chambre');
        valeurNumerique('nbr_piece');
        valeurNumerique('salle_bain');
        valeurNumerique('annee_construction');
        valeurNumerique('taxe_habitation');
        valeurNumerique('taxe_fonciere');
        valeurNumerique('prix_acquisition');
        valeurNumerique('frais_acquisition');
        valeurNumerique('valeur_actuel');

        $("#return_back").on('click', function(){
            window.history.back();
        });

        $('#saveLogement').on('click', function (e) {
            e.preventDefault();

            var fields = $('.info-general');
            let isValid = [];

            for (let i = 0; i < fields.length; i++) {
                if (!fields[i].value) {
                    isValid.push(false);
                    $('#home-tab').tab('show');

                    fields[i].classList.add('border');
                    fields[i].classList.add('border-danger');
                    $('#'+fields[i].id).focus();
                    $("#err_" + i).text("veuillez remplir ce champ!");
                } else {
                    isValid.push(true);
                    fields[i].classList.remove('border');
                    fields[i].classList.remove('border-danger');
                    $("#err_" + i).text("");
                }
            }

            // Vérifier si fields[1] et fields[2] sont vides, et si oui, colorer fields[3]
            if (fields[1].value === "" && fields[2].value === "") {
                fields[3].classList.add('border');
                fields[3].classList.add('border-danger');
                $('#'+fields[3].id).focus();
                $("#err_3").text("veuillez sélectionner le lieu suggéré");
                isValid.push(false);
            } else {
                fields[3].classList.remove('border');
                fields[3].classList.remove('border-danger');
                $("#err_3").text("");
                isValid.push(true);
            }

            if (!isValid.includes(false)) {
                $('#formLogement').submit();
            }
        });

        });


        var initialPreviewArray = [];
        var initialPreviewConfigArray = [];


        @if(isset($detailLogement))
            let detailLogement = {!! json_encode($detailLogement) !!};
            if (detailLogement.files.length)
            {
                for (let index = 0; index < detailLogement.files.length; index++) {
                    let url = "/uploads/images_annonces/" + detailLogement.files[index].file_name
                    initialPreviewArray.push(url)
                    initialPreviewConfigArray.push({key: detailLogement.files[index].id, extra: {id: detailLogement.files[index].id, type:"deleted", edit: 1}})
                }
            }
        @endif

        let file_photos = $("#file-photos").fileinput({
            language: "fr",
            initialPreview: initialPreviewArray,
            initialPreviewAsData: true,
            initialPreviewConfig: initialPreviewConfigArray,
            uploadUrl: "{{ route('proprietaire.uploadImageLogement') }}",
            deleteUrl: "{{ route('proprietaire.deleleteImageLogement')}}",
            altId: "doc-files-update",
            overwriteInitial: false,
            allowedFileExtensions: ["jpg", "jpeg", "png", 'gif'],
            maxFileCount: 8,
            showUpload: false,
            uploadAsync: true,
            showRemove: false,
            showCancel: false,
            showDrag: false,
            maxFileSize: 5120,
            ajaxSettings: { headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            ajaxDeleteSettings : { headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
        });

        file_photos.on("filebatchselected", function (event, files) {
            $("#file-photos").fileinput("upload");
        });

        file_photos.on("filebatchuploadcomplete", function (event) {
            var tab = []
            plugin = $('#file-photos').data('fileinput');
            for (let index = 0; index < plugin.initialPreviewConfig.length; index++) {
                tab.push(plugin.initialPreviewConfig[index].extra.id)
            }
            $('#file_ids').val(tab);
        });

        $("#suivantInfoGeneral").click(function() {
            $('#profile-tab').tab('show');
        });


    </script>
@endpush
@endsection
