
@push('scripts')

<script>
    var messages = {"phone_error" : "{{__('validator.phone_error')}}", "error_form" : "{{__('profile.error_form')}}", "error_contact" : "{{__('backend_messages.error_contact')}}"}
    var appSettings = {};

        filesize = {{filesize(storage_path('uploads/cover_pics/' . getConfig('photo_couverture')))}}
        appSettings['profile_pic'] = ["{{getConfig('photo_couverture')}}", filesize];

    var messagess = {"browse" : "{{__('profile.browse')}}","cancel" : "{{__('profile.cancel')}}","remove" : "{{__('profile.remove')}}","upload" : "{{__('profile.upload')}}"}


</script>
@endpush

@push('scripts')
<script src="{{ asset('bootstrap-fileinput/js/fileinput.min.js') }}"></script>
@endpush


@extends('layouts.adminappinner')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Config
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        @if ($message = Session::get('error'))
        <div class="alert alert-danger fade in alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            {{ $message }}
        </div>

        @endif
        @if ($message = Session::get('status'))
        <div class="alert alert-success fade in alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            {{ $message }}
        </div>
        @endif
        <div class="row">
            <div class="col-md-12 show-message">
                <!-- general form elements -->
                <form id="edit-package" method="POST" action="{{ route('admin.save-config')}}" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}

                    <div class="box box-primary ">
                        <div class="box-header with-border">
                            <h3 class="box-title">Config</h3>
                        </div>
                        <div class="box-body" id="content-list-phrase">

                            <div id="" class="row row-phrase">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Nb annonce colocataire ville :</label>
                                            <input type="text" class="form-control" placeholder="Nb annonce colocataire ville" name="nombre_colocataire_villes" value="{{getConfig('nombre_colocataire_villes')}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Nb annonce colocation ville :</label>
                                            <input type="text" class="form-control" placeholder="Nb annonce colocation ville" name="nombre_colocation_villes" value="{{getConfig('nombre_colocation_villes')}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Nb profils sponsorisés acceuil :</label>
                                            <input type="text" class="form-control" placeholder="Nb profils sponsorisés acceuil" name="nombre_profils_sponsorises_acceuil" value="{{getConfig('nombre_profils_sponsorises_acceuil')}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Nb annonce sponsorisés acceuil :</label>
                                            <input type="text" class="form-control" placeholder="Nb annonce sponsorisés acceuil" name="nombre_annonce_sponsorises_acceuil" value="{{getConfig('nombre_annonce_sponsorises_acceuil')}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Nb annonce par page dans resultat de recherche :</label>
                                            <input type="text" class="form-control" placeholder="Nb annonce par page dans resultat de recherche" name="nb_per_page_search" value="{{getConfig('nb_per_page_search')}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Bitly token :</label>
                                            <input type="text" class="form-control" placeholder="Nb annonce par page dans resultat de recherche" name="bitly_token" value="{{getConfig('bitly_token')}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Bl.ink email :</label>
                                            <input type="text" class="form-control" placeholder="Nb annonce par page dans resultat de recherche" name="email_blink" value="{{getConfig('email_blink')}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Bl.ink mot de passe :</label>
                                            <input type="text" class="form-control" placeholder="Nb annonce par page dans resultat de recherche" name="mdp_blink" value="{{getConfig('mdp_blink')}}"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Nb max d'affichage de contact :</label>
                                            <input type="text" class="form-control" placeholder="Nombre d'affihage de contact pour les premium" name="nb_max_contact" value="{{getConfig('nb_max_contact')}}"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Nombre de ligne publication Community :</label>
                                            <input type="text" class="form-control" placeholder="Nombre de ligne publication Community" name="nb_publication" value="{{getConfig('nb_publication')}}"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Temps de requête des fichiers js :</label>
                                            <input type="text" class="form-control" placeholder="Temps de requête des fichiers js (en ms) " name="temps_requete_js" value="{{getConfig('temps_requete_js')}}"/>
                                        </div>
                                    </div>
                                </div>
                                <!-- toc toc -->
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Nombre maximun de toc toc utilisateur :</label>
                                            <input type="text" class="form-control" placeholder="Nombre de toc toc utilisateur" name="free_message_flash" value="{{getConfig('free_message_flash')}}"/>
                                        </div>
                                    </div>
                                </div>
                                <!-- toc toc -->

                                <!-- nbr email -->
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Nombre maximum d'email :</label>
                                            <input type="text" class="form-control" placeholder="Nombre maximum d'email envoyé par jour" name="nbr_email" value="{{getConfig('nbr_email')}}"/>
                                        </div>
                                    </div>
                                </div>
                                <!-- nbr email -->

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Reste de nombre d'email actuel :</label>
                                            <input type="text" class="form-control" placeholder="Reste de nombre d'email actuel :" name="nbr_email_moin" value="{{getConfig('nbr_email_moin')}}"/>
                                        </div>
                                    </div>
                                </div>
                                <!-- Nombre de jour avant expiration d'annonce -->
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group {{ $errors->has('delay') ? ' has-error' : '' }}">
                                            <label for="delay">Nombre de jour avant expiration d'annonce :</label>
                                            <input type="text" class="form-control" id="delay"
                                                   name="delay"
                                                   value="{{ old('delay', getConfig('day_delay')) }}"/>
                                        </div>
                                    </div>
                                </div>
                                <!-- nbr affichage annonce -->
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Nombre de jour d'affichage d'annonce :</label>
                                            <input type="text" class="form-control" placeholder="Nombre de jour d'affichage d'annonce " name="nbr_annonce" value="{{getConfig('nbr_annonce')}}"/>
                                        </div>
                                    </div>
                                </div>
                                <!--  nbr affichage annonce  -->

                                <!-- Id googletag manager -->
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Id googletag manager :</label>
                                            <input type="text" class="form-control" placeholder="Id googletag manager " name="googletagmanager" value="{{getConfig('googletagmanager')}}"/>
                                        </div>
                                    </div>
                                </div>
                                <!--  Id googletag manager  -->

                            <!-- Id google_analytic -->
                            <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Id google analytic :</label>
                                            <input type="text" class="form-control" placeholder="Id google analytic " name="google_analytic" value="{{getConfig('google_analytic')}}"/>
                                        </div>
                                    </div>
                                </div>
                            <!--  Id google_analytic -->


                            <!-- Id adsense -->
                            <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Id adsense :</label>
                                            <input type="text" class="form-control" placeholder="Id adsense" name="adsense" value="{{getConfig('adsense')}}"/>
                                        </div>
                                    </div>
                                </div>
                            <!--  Id adsense  -->

                             <!-- Id pixel fb -->
                             <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Id pixel fb :</label>
                                            <input type="text" class="form-control" placeholder="Id pixel fb" name="pixel_id" value="{{getConfig('pixel_id')}}"/>
                                        </div>
                                    </div>
                                </div>
                            <!--  Id pixel fb   -->    

                             <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Mot clé Annonce :</label>
                                            <input type="text" class="form-control" placeholder="Mot clé dans annonce" name="annonce_ads" value="{{getConfig('annonce_ads')}}"/>
                                        </div>
                                    </div>
                                </div>

                            
                            </div>

                        </div>



                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-info" id="edit-profile-step-3">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>


                <form id="editProfile2" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}

                    <h5><b>CHANGER LA PHOTO DE COUVERTURE :</b></h5>
                    <div class="form-group">
                        <label class="control-label">{{ __('profile.upload_photo') }} (Idéal: 1900x820 pixels)</label>
                        <div class="upload-photo-outer">
                            <div class="file-loading">
                                <input id="file-profile-photo" type="file" class="file" data-overwrite-initial="true" data-min-file-count="1" name="file_profile_photos" accept="image/*">
                            </div>
                        </div>
                        <div class="upload-photo-listing">
                            <p>Téléchargez une photo de couverture (Image prise en charge - .jpg, .jpeg, .png, .gif).</p>
                        </div>
                    </div>
                    <div class="text-right">
                                <div class="submit-btn-1 save-nxt-btn"><a href="javascript:void(0);" id="edit-profile-step-2" style="background-color: #229ce2;margin: 8px;padding: 13px;color: #fff; border-radius: 9px;box-shadow: 74 15 black;">Enregistrer</a></div>
                    </div>
                </form>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div>

<script>
         var initialPreviewArray = [];
         var initialPreviewConfigArray = [];
         var initialPreviewCount = 0;
         var initialCaption = '';

        initialPreviewCount = 1;
        initialPreviewArray.push("<img src='/uploads/cover_pics/" + appSettings.profile_pic[0] + "' class='file-preview-image' alt='" + appSettings.profile_pic[0] + "' title='" + appSettings.profile_pic[0] + "'>");
        initialPreviewConfigArray.push({caption:appSettings.profile_pic[0], size:appSettings.profile_pic[1]});
        initialCaption = appSettings.profile_pic[0];




    $("#file-profile-photo").fileinput({
        language: $("#changeLang").val(),
        theme: 'fa',
        showUpload: false,
        showCancel: false,
        uploadAsync: false,
        allowedFileExtensions: ['jpg', 'jpeg', 'png', 'gif'],
        overwriteInitial: true,
        initialPreviewShowDelete: false,
        initialPreview: initialPreviewArray,
        initialPreviewConfig: initialPreviewConfigArray,
        initialPreviewCount: initialPreviewCount,
        initialCaption: initialCaption,
        maxFilesNum: 1,
        allowedFileTypes: ['image'],
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#add-regex-btn').on('click', function(){
            $('#regex-modal').modal("show");
        });
        $(".btn-remove-phrase").on('click', function(){
            $('#delete_regex').val($(this).attr('data-id'));
            $('#regex-delete-modal').modal('show');
        });


        $('body').on('click', "#edit-profile-step-2", function() {
        submit_form_2();
         });





         function submit_form_2(){


        var params = $("#editProfile2").serializeArray();
            var files = $("#file-profile-photo")[0].files;
            $("#about_me").closest(".form-group").removeClass('has-error');
            $("#school_name").closest(".form-group").removeClass('has-error');

            $("#editProfile2").find(".alert").remove();
            var formData = new FormData();
            for (var i = 0; i < files.length; i++) {
                formData.append("file_profile_photos", files[i]);
            }
            //Now Looping the parameters for all form input fields and assigning them as Name Value pairs.
            $(params).each(function (index, element) {
                formData.append(element.name, element.value);
            });

            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(".loader-icon").show();

        $.ajax({
            type: "POST",
            url: '/admin2021/ajax_edit_photo_couverture',
            data : formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data) {
                $(".loader-icon").hide();
                    if(data.error == 'yes') {
                        $('#editProfile2').prepend('<div class="alert alert-success fade in alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + data.message + '</div>');
                        actualiser();

                    } else {

                        $('#editProfile2').prepend('<div class="alert alert-danger fade in alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>'+ data.message+'</div>');

                    }

            }
        });



    }


    function actualiser() {
        /*$("#edit_step_1, #edit_step_2").css('display', 'none');
        $("#edit_step_3").show();
        $(".edit-pro-content-1 li a").parent("li").removeClass("active");
        $(".edit-pro-content-1 .visiting-menu").addClass("active");*/
        location.href ="#"
    }




    });
</script>
<style type="text/css">
    .row-phrase
    {
        padding : 15px;
    }

    .row-phrase input
    {
        max-width : 400px;
    }
</style>
@endsection
