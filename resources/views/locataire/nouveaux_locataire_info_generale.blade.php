<style>
    /*label {*/
    /*    color: black !important;*/
    /*    margin-top: 12px;*/
    /*}*/

    /*input {*/
    /*    border-radius: none !important;*/
    /*}*/

    /*.card {*/
    /*    border-style: none;*/
    /*    border-radius: 0px;*/
    /*}*/

    canvas {
        height: 175px;
        border-style: solid;
        border-width: 1px;
        border-color: black;
        margin-bottom: 20px;
        margin-left: -65px;
    }
</style>

@push('css')
    <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648722849/css/countrySelect_luefw2.css" rel="stylesheet">
    <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648722693/css/intlTelInput.min_ft4ncf.css" rel="stylesheet">
@endpush
<form id="formLocatairesGeneralInformation" enctype="multipart/form-data" method="POST">
    @csrf
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <div>
                    <label for="type" class="form-label">{{ __('Type') }} </label>
                    <select id="locataireType" class="form-control"><span class="caret"></span>
                        <option value="Particulier">{{ __('Particulier') }}</option>
                        <option value="Société/autre">{{ __('Société/autre') }}</option>
                    </select>
                    <span class="text-danger" id="locataireTypeErrorMsg"></span>
                </div>

                <div>
                    <div class="card-header" style="color: #4C8DCB">
                        <label for="photo" class="form-label">{{ __('Photo') }} </label>
                    </div>
                    <div class="controls">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div>
                                <div class="fileupload-preview thumbnail mx-auto" style="width: min-content;">
                                    <img id="default-image" style="height:142;width:151;padding-bottom:10px;"
                                        src="https://previews.123rf.com/images/thesomeday123/thesomeday1231712/thesomeday123171200009/91087331-ic%C3%B4ne-de-profil-d-avatar-par-d%C3%A9faut-pour-le-m%C3%A2le-espace-r%C3%A9serv%C3%A9-photo-gris-vecteur-d.jpg"
                                        alt="">
                                </div>
                                <p class="text-center">
                                <span class="btn btn-file">
                                    <canvas hidden id="canv1"></canvas>
                                    <input class="form-control" type="file" name="TenantPhoto" id="TenantPhoto"
                                        onchange="upload()">
                                    <span class="text-danger" id="TenantPhotoErrorMsg"></span>
                                </span>&nbsp;

                                    <a class="btn btn-danger fileupload-exists" data-dismiss="fileupload"
                                       style="display:none";>
                                        <i class="fa-solid fa-trash" style="color:white;"></i>
                                    </a>
                                </p>

                                <span class="fileupload-preview text" style="padding-left: 10px; display:none;"></span>
                            </div>
                        </div>
                        <span class="help-block"> Formats acceptés: GIF, JPG, PNG. Taille maximale: 5 Mo</span>
                    </div>
                </div>
                <div class="card" style="margin-top: 5px">
                    <div class="card-header"
                        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                        {{ __('Informations personnelles') }}
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="civilite" class="form-label">{{ __('CIVILITÉ') }}</label>
                            <select name="" id="civilite" class="form-control"><span class="caret"></span>
                                <option value="">Choisir</option>
                                <option value="Miss">Mlle</option>
                                <option value="Mrs">Mme</option>
                                <option value="Mr">M.</option>
                                <option value="M. et Mme">M. et Mme</option>
                            </select>
                            <span class="text-danger" id="civiliteErrorMsg"></span>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="form-group">
                            <label for="" class="form-label">{{ __('PRÉNOM *') }}</label>
                            <input id="TenantFirstName" type="text" class="form-control" placeholder=""
                                aria-describedby="helpId">
                            <span class="text-danger" id="TenantFirstNameErrorMsg"></span>

                            <div class="invalid-feedback">Veuillez renseigner ce champ</div>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="" class="form-label">{{ __('NOM *') }}</label>
                            <input id="TenantLastName" type="text" name="" class="form-control" placeholder=""
                                aria-describedby="helpId">
                            <span class="text-danger" id="TenantLastNameErrorMsg"></span>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="" class="form-label">{{ __('DATE DE NAISSANCE') }}</label>
                            <input type="date" name="" id="TenantBirthDate" class="form-control"
                                placeholder="" aria-describedby="helpId">
                            <span class="text-danger" id="TenantBirthDateErrorMsg"></span>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="" class="form-label">{{ __('LIEU DE NAISSANCE') }}</label>
                            <input type="text" name="" id="TenantBirthPlace" class="form-control"
                                placeholder="" aria-describedby="helpId">
                            <span class="text-danger" id="TenantBirthPlaceErrorMsg"></span>
                        </div>
                    </div>
                </div>
                <div class="card" style="margin-top: 5px">
                    <div class="card-header"
                        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                        {{ __(' Situation professionnelle') }}
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="" class="form-label">{{ __('PROFESSION') }}</label>
                            <input type="text" name="" id="TenantProfession" class="form-control"
                                placeholder="" aria-describedby="helpId">
                            <span class="text-danger" id="TenantProfessionErrorMsg"></span>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="" class="form-label">{{ __('REVENUS MENSUELS') }}</label>
                            <input type="number" name="" id="TenantRevenus" class="form-control"
                                placeholder="" aria-describedby="helpId">
                            <span class="text-danger" id="TenantRevenusErrorMsg"></span>
                        </div>
                    </div>
                </div>
                <div class="card" style="margin-top: 5px">
                    <div class="card-header"
                        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                        {{ __('Pièce d\'identité') }}
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="civilite" class="form-label">{{ __('TYPE') }}</label>
                            <select id="LandlordIDType" class="form-control"><span class="caret"></span>
                                <option value="">Choisir</option>
                                <option value="ID">Carte d'identité</option>
                                <option value="passport">Passeport</option>
                            </select>
                            <span class="text-danger" id="LandlordIDTypeErrorMsg"></span>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="" class="form-label">{{ __('NUMÉRO') }}</label>
                            <input type="number" name="" id="TenantIDNumber" class="form-control"
                                placeholder="" aria-describedby="helpId">
                            <span class="text-danger" id="TenantIDNumberErrorMsg"></span>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="" class="form-label">{{ __('EXPIRATION') }}</label>
                            <input type="date" name="" id="TenantIDExpiry" class="form-control"
                                placeholder="" aria-describedby="helpId">
                            <span class="text-danger" id="TenantIDExpiryErrorMsg"></span>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="" class="form-label">{{ __('FICHIER') }}</label>
                            <div class="controls">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div>
                                        <div class="fileupload-preview thumbnail mx-auto" style="width: min-content;">
                                            <img id="default-image1" style="height:142;width:151;padding-bottom:10px;"
                                                src="https://previews.123rf.com/images/thesomeday123/thesomeday1231712/thesomeday123171200009/91087331-ic%C3%B4ne-de-profil-d-avatar-par-d%C3%A9faut-pour-le-m%C3%A2le-espace-r%C3%A9serv%C3%A9-photo-gris-vecteur-d.jpg"
                                                alt="">
                                        </div>
                                        <p class="text-center">
                                        <span class="btn btn-file">
                                            <canvas hidden id="canv2"></canvas>
                                            <input class="form-control" type="file" id="TenantIDCard"
                                                onchange="uploadCin()">
                                            <span class="text-danger" id="TenantIDCardErrorMsg"></span>
                                        </span>&nbsp;<a href="#" class="btn btn-danger fileupload-exist"
                                            data-dismiss="fileupload" style="display:none">
                                            <i class="fa-solid fa-trash" style="color:white;"></i></a>
                                        </p>
                                        <span class="fileupload-preview text"
                                            style="padding-left: 10px; display:none;"></span>
                                    </div>
                                </div>
                                <span
                                    class="help-block">{{ __('Formats acceptés: GIF, JPG, PNG. Taille maximale: 5 Mo') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" style="margin-top: 5px">
                    <div class="card-header"
                        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                        {{ __('Information de contact') }}
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="" class="form-label">{{ __('Email') }}</label>
                            <input type="mail" name="" id="TenantEmail" class="form-control"
                                placeholder="" aria-describedby="helpId">
                            <span class="text-danger" id="TenantEmailErrorMsg"></span>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top:2px">
                        <div class="row align-middle">
                            <div class="col-md-2  align-middle">
                                <h6 style="color: black;">{{ __('Invitation') }}</h6>
                            </div>
                            <div class="form-check form-switch col-md-10 align-left">
                                <input type="checkbox" name="" id="send_invite"
                                    class="form-check-input form-control" placeholder="" aria-describedby="helpId"
                                    value="1">
                                <span class="text-danger" id="send_inviteErrorMsg"></span>
                                <br>
                            </div>
                            <p class="form-control">
                                {{ __('Si vous voulez inviter et donner accès au site à votre locataire, veuillez
                                                                                                                    saisir
                                                                                                                    son adresse e-mail
                                                                                                                    (un email UNIQUE par locataire). Le locataire aura accès uniquement aux informations liées à sa
                                                                                                                    location, ses quittances de loyer et il pourra vous envoyer des messages via son interface.') }}
                            </p>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="" class="form-label">{{ __('MOBILE') }}</label>
                            <input type="number" name="" id="TenantMobilePhone" class="form-control"
                                placeholder="{{ __('register.enter_phone') }}" aria-describedby="helpId"
                                value="+1">
                            <span class="text-danger" id="TenantMobilePhoneErrorMsg"></span>
                            {{--                <input type="hidden" name="valid_number" id="valid_number" /> --}}
                            {{--                <input type="hidden" name="iso_code" id="iso_code" /> --}}
                            {{--                <input type="hidden" name="dial_code" id="dial_code" /> --}}
                        </div>
                    </div>
                </div>
                <div class="card" style="margin-top: 5px">
                    <div class="card-header"
                        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                        Adresse
                    </div>
                    <div class="card-body">
                        <div class="autocomplete-container mb-1" id="autocompletess">
                            <label for="" class="form-label">{{ __('ADRESSE') }}</label>
                            <input type="text" name="" id="TenantAddress" class="form-control"
                                placeholder="Indiquer un lieu" aria-describedby="helpId">

                            <input type="hidden" id="latitude" class="info-general" name="latitude">
                            <input type="hidden" id="longitude" class="info-general" name="longitude">
                        </div>
                        <span class="text-danger" id="TenantAddressErrorMsg"></span>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="" class="form-label">{{ __('VILLE') }}</label>
                            <input type="text" name="" id="TenantCity" class="form-control"
                                placeholder="" aria-describedby="helpId">
                            <span class="text-danger" id="TenantCityErrorMsg"></span>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="" class="form-label">{{ __('CODE POSTAL') }}</label>
                            <input type="number" name="" id="TenantZip" class="form-control" placeholder=""
                                aria-describedby="helpId">
                            <span class="text-danger" id="TenantZipErrorMsg"></span>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="" class="form-label">{{ __('REGION') }}</label>
                            <input type="text" name="" id="TenantState" class="form-control"
                                placeholder="" aria-describedby="helpId">
                            <span class="text-danger" id="TenantStateErrorMsg"></span>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="country-select inside">
                            <label for="" class="form-label">{{ __('PAYS') }}</label>
                            <input type="text" class="form-control"
                                placeholder="{{ __('register.origin_country') }}" name="origin_country"
                                id="country_selector" />
                                <input type="hidden" name="origin_country_code" id="country_selector" value="fr" />
                            <input type="hidden" name="country_selector" id="country_selector_reference" value="fr" />

                            <span class="text-danger" id="country_selectorErrorMsg"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card" style="margin-top: 5px">
        <div class="row">
            <div class="col-md-12" style="padding: 15px;">
                <div class="float-end">
                    <a href="{{ route('locataire.locataire') }}" class="btn btn-secondary"
                        onclick="localStorage.clear();" style="margin-top: 5px">Annuler</a>
                    <button type="submit" class="btn btn-primary" style="margin-top: 5px"> Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" data-backdrop="false" id="basicModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">
                        {{ __('Ajouts des informations complémentaires') }}</h5>
                    {{--                    <button --}}
                    {{--                            type="button" --}}
                    {{--                            class="btn-close" --}}
                    {{--                            data-bs-dismiss="modal" --}}
                    {{--                            aria-label="Close" --}}
                    {{--                    ></button> --}}
                </div>
                <div class="modal-body text-success" role="alert" id="successMsg" style="display: none">
                    {{ __('La création de votre nouveau locataire a bien été enregistré') }}
                </div>
                <div class="modal-body">
                    {{ __('Voulez-vous continuer à ajouter des informations complémentaires pour le nouveau locataire ?') }}
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" id="buttonModalNon">
                        {{ __('Non') }}
                    </button>
                    <button class="btn btn-primary" id="buttonModalOui">{{ __('Oui') }}</button>
                </div>
            </div>
        </div>
    </div>

</form>
@push('js')
    <script src="https://www.dukelearntoprogram.com/course1/common/js/image/SimpleImage.js"></script>
    <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650610706/Bailti/js/countrySelect_qmrjhm.js"></script>
    <script src="/js/intlTelInput/intlTelInput.min.js"></script>
    <script>

        $(document).ready(function () {
            // $('input[type="number"]').on('input', function() {
            // var value = parseFloat($(this).val());
            // console.log(value)
            // if (value === '') {
            //     $(this).removeClass('is-invalid');
            //     $(this).next('.error-message').remove();
            // } else if (isNaN(value) || parseFloat(value) < 0) {
            //     $(this).addClass('is-invalid');
            //     $(this).next('.error-message').remove();
            //     $(this).after('<span class="error-message text-danger">Les valeurs négatives ne sont pas autorisées</span>');
            // } else {
            //     $(this).removeClass('is-invalid');
            //     $(this).next('.error-message').remove();
            // }
            // });
        });
        //afichage de boutton suppression dans photo de locataire
        $('#TenantPhoto').change(function() {
            // si un fichier est choisi
            if ($(this).val()) {
                $('.fileupload-exists').show(); // montrer le bouton de suppression
            } else {
                $('.fileupload-exists').hide(); // cacher le bouton de suppression
            }
        });

        //afichage de boutton suppression dans cin de locataire
        $('#TenantIDCard').change(function() {
            // si un fichier est choisi
            if ($(this).val()) {
                $('.fileupload-exist').show(); // montrer le bouton de suppression
            } else {
                $('.fileupload-exist').hide(); // cacher le bouton de suppression
            }
        });

        //evenement lorque on clique l'icone supprime le photo de locataire
        $('.fileupload-exists').click(function() {
            // supprimer l'image et réinitialiser l'input de fichier
            $('#TenantPhoto').val('');
            // $('.fileupload-preview').hide();
            $(this).hide();
            const canv1Div = document.getElementById('canv1');
            const defaultImage = document.getElementById('default-image');
            defaultImage.style.display = '';
            canv1Div.style.display = 'none';
        });

        $("#country_selector").countrySelect({
            defaultCountry: "fr"
        });
        $("#TenantMobilePhone").intlTelInput({
            // defaultCountry: "fr"
        });

        function upload() {
            const canv1Div = document.getElementById('canv1');
            const defaultImage = document.getElementById('default-image');
            var imgcanvas = document.getElementById("canv1");
            var fileinput = document.getElementById("TenantPhoto");

            if (fileinput.files.length === 0) {
                // Aucun fichier sélectionné, cacher le canvas et afficher l'image par défaut
                canv1Div.style.display = 'none';
                defaultImage.style.display = '';
                return;
            }
            // Afficher le canvas et cacher l'image par défaut
            canv1Div.style.display = '';
            defaultImage.style.display = 'none';

            var image = new SimpleImage(fileinput);
            image.drawTo(imgcanvas);
        }

        //evenement lorque on clique l'icone supprime le photo de cin locataire
        $('.fileupload-exist').click(function() {
            // supprimer l'image et réinitialiser l'input de fichier
            $('#TenantIDCard').val('');
            // $('.fileupload-preview').hide();
            $(this).hide();
            const canv1Div = document.getElementById('canv2');
            const defaultImage1 = document.getElementById('default-image1');
            defaultImage1.style.display = '';
            canv1Div.style.display = 'none';
        });

        function uploadCin() {
            const canv1Div = document.getElementById('canv2');
            const defaultImage1 = document.getElementById('default-image1');
            canv1Div.style.display = '';
            var imgcanvasCin = document.getElementById("canv2");
            var fileinputCin = document.getElementById("TenantIDCard");

            if (fileinputCin.files.length === 0) {
                // Aucun fichier sélectionné, cacher le canvas et afficher l'image par défaut
                canv2Div.style.display = 'none';
                defaultImage1.style.display = '';
                return;
            }
            // Afficher le canvas et cacher l'image par défaut
            canv1Div.style.display = '';
            defaultImage1.style.display = 'none';

            var image = new SimpleImage(fileinputCin);
            image.drawTo(imgcanvasCin);
        }
    </script>
    <script>
        $(document).ready(function() {
            /* auto completion (adresse)  */
            getKey(page)

            addressAutocomplete(document.getElementById("autocompletess"), (data) => {

                console.log(data);

                /* changer les adresses,ville,region,code postal */
                $('#latitude').val(data['lat']);
                $('#longitude').val(data['lon']);
                $('#TenantCity').val(data['city']);
                $('#TenantState').val(data['state']);
                $('#TenantZip').val(data['postcode']);

                /*changer contry_selector */
                $("#country_selector").countrySelect("selectCountry", data['country_code']);
                $("#country_selector").countrySelect("setCountry", data['country']);

                $('#country_selector_reference').val(data['country_code']);

            }, {
                placeholder: "Enter an address here",
            });
            /* fin auto completion (adresse)  */


            localStorage.clear()
            $('#TenantPhoto').on('input', (function(e) {
                $("#canv1").removeAttr("hidden");
            }));
            $('#TenantIDCard').on('input', (function(e) {
                $("#canv2").removeAttr("hidden");
            }));

            $('#formLocatairesGeneralInformation').submit(function(e) {
                e.preventDefault();
                var data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                var fields = $(this).find(':input').not(':button');
                fields.each(function() {
                    data.append(this.id, $(this).val());
                });
                data.append("TenantPhoto", $('#TenantPhoto')[0].files[0]);
                data.append("TenantIDCard", $('#TenantIDCard')[0].files[0]);
                if (!localStorage.getItem("locataireInfoGenerale")) {
                    $.ajax({
                        url: "{{ route('locataire.info_generale') }}",
                        type: "POST",
                        processData: false,
                        contentType: false,
                        data: data,
                        beforeSend: function() {
                            $('#myLoader').removeClass('d-none')
                        },
                        success: function(data) {
                            $('#myLoader').addClass('d-none')
                            localStorage.setItem("locataireInfoGenerale", 1);
                            localStorage.setItem("user_id", data.locataireInfoGenerale.user_id);
                            localStorage.setItem("locataireInfoGeneraleId", data
                                .locataireInfoGenerale.id);
                            $('#successMsg').show();
                            $('html, body').animate({
                                scrollTop: $("#successMsg").offset().top
                            }, 300);
                            // $('#basicModal').modal('show');
                            $('#basicModal').modal({
                                backdrop: 'static',
                                keyboard: false
                            }).modal('show');
                        },
                        error: function(xhr) {
                            $('#myLoader').addClass('d-none')
                            var err = JSON.parse(xhr.responseText);
                            var champs = Object.keys(err.error);
                            for (var i = 0; i < champs.length; i++) {
                                var champ = champs[i];
                                if(champ =='latitude'){
                                    $('#TenantAddressErrorMsg').text("Adresse invalide");
                                }
                                else{
                                    $('#' + champ + 'ErrorMsg').text(err.error[champ]);
                                }
                            }
                            for (var i = 0; i < champs.length; i++) {
                                var champ = champs[i];
                                if (!$('#' + champ + 'ErrorMsg').is(':empty')) {
                                    $('#' + champ).focus()
                                    $('html, body').animate({
                                        scrollTop: $("#" + champ).offset().top
                                    }, 200);
                                    break;
                                }
                            }
                            for (var i = 0; i < champs.length; i++) {
                                var champ = champs[i];
                                if (!$('#' + champ + 'ErrorMsg').is(':empty')) {
                                    (function(localChamp) {
                                        $(document).on('input', '#' + localChamp,
                                            function() {
                                                $('#' + localChamp + 'ErrorMsg').text(
                                                    "");
                                            });
                                        //fonction callback
                                    })(champ);
                                }
                            }
                        }
                    });
                }
            });

            $("#buttonModalNon").off("click").click(function() {
                localStorage.clear();
                $('#formLocatairesGeneralInformation')[0].reset();
                @if (Auth::user()->need_guide)
                window.location = "{{ route('proprietaire.bureau') }}";
                @else
                window.location = "{{ route('locataire.locataire') }}";
                @endif
            });

            $("#buttonModalOui").off("click").click(function() {
                $('#basicModal').modal('hide');
                // localStorage.getItem("user_id");
                $('#home-tab-li').attr("hidden", "hidden");
                $('#profile-tab-li').removeAttr('hidden')
                $('#home-tab, #information_generale').removeClass('active')
                $('#profile-tab, #complementaire').addClass('active')
                $('#successMsg').hide()
            });
        });
    </script>
    <script>
        var key = null
        var url_page = window.location.pathname;
        var page = url_page.slice(1)
        var position = page.search("/");

        function getKey(page) {
            //fixer le format de l'url
            if (position > 0) {
                key = 'bd5911089b114d6fa4fd3b0d17b1ac50'
                return
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.get(`/geoapify_key/${page}`)
                .then((response) => {
                    key = response

                })
                .catch((error) => {
                    key = 'bd5911089b114d6fa4fd3b0d17b1ac50'
                    //send error ici
                })

        }

        function addressAutocomplete(containerElement, callback, options) {

            const MIN_ADDRESS_LENGTH = 3;
            const DEBOUNCE_DELAY = 100;

            // create container for input element
            const inputContainerElement = document.createElement("div");
            inputContainerElement.setAttribute("class", "input-container");
            containerElement.appendChild(inputContainerElement);

            // create input element
            const inputElement = document.getElementById("TenantAddress");
            inputContainerElement.appendChild(inputElement);

            // add input field clear button
            const clearButton = document.createElement("div");
            clearButton.classList.add("clear-button");
            addIcon(clearButton);
            clearButton.addEventListener("click", (e) => {
                e.stopPropagation();
                inputElement.value = '';
                callback(null);
                clearButton.classList.remove("visible");
                closeDropDownList();
            });
            inputContainerElement.appendChild(clearButton);

            /* We will call the API with a timeout to prevent unneccessary API activity.*/
            let currentTimeout;

            /* Save the current request promise reject function. To be able to cancel the promise when a new request comes */
            let currentPromiseReject;

            /* Focused item in the autocomplete list. This variable is used to navigate with buttons */
            let focusedItemIndex;

            /* Process a user input: */
            inputElement.addEventListener("input", function(e) {
                const currentValue = this.value;

                /* Close any already open dropdown list */
                closeDropDownList();


                // Cancel previous timeout
                if (currentTimeout) {
                    clearTimeout(currentTimeout);
                }

                // Cancel previous request promise
                if (currentPromiseReject) {
                    currentPromiseReject({
                        canceled: true
                    });
                }

                if (!currentValue) {
                    clearButton.classList.remove("visible");
                }

                // Show clearButton when there is a text
                clearButton.classList.add("visible");

                // Skip empty or short address strings
                if (!currentValue || currentValue.length < MIN_ADDRESS_LENGTH) {
                    return false;
                }

                /* Call the Address Autocomplete API with a delay */
                currentTimeout = setTimeout(() => {
                    currentTimeout = null;

                    /* Create a new promise and send geocoding request */
                    const promise = new Promise((resolve, reject) => {
                        currentPromiseReject = reject;
                        var pathname = window.location.pathname;

                        // The API Key provided is restricted to JSFiddle website
                        // Get your own API Key on https://myprojects.geoapify.com
                        apiKey = key

                        var url =
                            `https://api.geoapify.com/v1/geocode/autocomplete?text=${encodeURIComponent(currentValue)}&format=json&limit=5&apiKey=${apiKey}`;

                        fetch(url)
                            .then(response => {
                                currentPromiseReject = null;
                                $.get(`/update_gestion_geoapify/${page}`,
                                    `url=${window.location.href}`)
                                // check if the call was successful
                                if (response.ok) {
                                    response.json().then(data => resolve(data));
                                } else {
                                    response.json().then(data => reject(data));
                                }
                            });
                    });

                    promise.then((data) => {
                        // here we get address suggestions
                        currentItems = data.results;

                        /*create a DIV element that will contain the items (values):*/
                        const autocompleteItemsElement = document.createElement("div");
                        autocompleteItemsElement.setAttribute("class", "autocomplete-items");
                        inputContainerElement.appendChild(autocompleteItemsElement);

                        /* For each item in the results */
                        if( data.results.length == 0 ){
                            $('#latitude').val('');
                            $('#longitude').val('');
                        }
                        data.results.forEach((result, index) => {
                            /* Create a DIV element for each element: */
                            const itemElement = document.createElement("div");
                            /* Set formatted address as item value */
                            itemElement.innerHTML = result.formatted;
                            autocompleteItemsElement.appendChild(itemElement);

                            /* Set the value for the autocomplete text field and notify: */
                            itemElement.addEventListener("click", function(e) {
                                inputElement.value = currentItems[index].formatted;
                                callback(currentItems[index]);
                                /* Close the list of autocompleted values: */
                                closeDropDownList();
                            });
                        });

                    }, (err) => {
                        if (!err.canceled) {
                            console.log(err);
                            // $.ajaxSetup({
                            //     headers: {
                            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            //     }
                            // });
                            // $.ajax({
                            //     type: "POST",
                            //     url: '/email-error-autocomplete-geoapify',
                            //     //data: err,
                            //     //dataType: 'json',
                            //     success: function (data) {
                            //         console.log('email envoyé');
                            //     }
                            // });
                        }
                    });
                }, DEBOUNCE_DELAY);
            });

            /* Add support for keyboard navigation */
            inputElement.addEventListener("keydown", function(e) {
                var autocompleteItemsElement = containerElement.querySelector(".autocomplete-items");
                if (autocompleteItemsElement) {
                    var itemElements = autocompleteItemsElement.getElementsByTagName("div");
                    if (e.keyCode == 40) {
                        e.preventDefault();
                        /*If the arrow DOWN key is pressed, increase the focusedItemIndex variable:*/
                        focusedItemIndex = focusedItemIndex !== itemElements.length - 1 ? focusedItemIndex + 1 : 0;
                        /*and and make the current item more visible:*/
                        setActive(itemElements, focusedItemIndex);
                    } else if (e.keyCode == 38) {
                        e.preventDefault();

                        /*If the arrow UP key is pressed, decrease the focusedItemIndex variable:*/
                        focusedItemIndex = focusedItemIndex !== 0 ? focusedItemIndex - 1 : focusedItemIndex = (
                            itemElements.length - 1);
                        /*and and make the current item more visible:*/
                        setActive(itemElements, focusedItemIndex);
                    } else if (e.keyCode == 13) {
                        /* If the ENTER key is pressed and value as selected, close the list*/
                        e.preventDefault();
                        if (focusedItemIndex > -1) {
                            closeDropDownList();
                        }
                    }
                } else {
                    if (e.keyCode == 40) {
                        /* Open dropdown list again */
                        var event = document.createEvent('Event');
                        event.initEvent('input', true, true);
                        inputElement.dispatchEvent(event);
                    }
                }
            });

            function setActive(items, index) {
                if (!items || !items.length) return false;

                for (var i = 0; i < items.length; i++) {
                    items[i].classList.remove("autocomplete-active");
                }

                /* Add class "autocomplete-active" to the active element*/
                items[index].classList.add("autocomplete-active");

                // Change input value and notify
                inputElement.value = currentItems[index].formatted;
                callback(currentItems[index]);
            }

            function closeDropDownList() {
                const autocompleteItemsElement = inputContainerElement.querySelector(".autocomplete-items");
                if (autocompleteItemsElement) {
                    inputContainerElement.removeChild(autocompleteItemsElement);
                }

                focusedItemIndex = -1;
            }

            function addIcon(buttonElement) {
                const svgElement = document.createElementNS("http://www.w3.org/2000/svg", 'svg');
                svgElement.setAttribute('viewBox', "0 0 24 24");
                svgElement.setAttribute('height', "24");

                const iconElement = document.createElementNS("http://www.w3.org/2000/svg", 'path');
                iconElement.setAttribute("d",
                    "M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"
                );
                iconElement.setAttribute('fill', 'currentColor');
                svgElement.appendChild(iconElement);
                buttonElement.appendChild(svgElement);
            }

            /* Close the autocomplete dropdown when the document is clicked.
            Skip, when a user clicks on the input field */
            document.addEventListener("click", function(e) {
                if (e.target !== inputElement) {
                    closeDropDownList();
                } else if (!containerElement.querySelector(".autocomplete-items")) {
                    // open dropdown list again
                    var event = document.createEvent('Event');
                    event.initEvent('input', true, true);
                    inputElement.dispatchEvent(event);
                }
            });
        }
    </script>
@endpush
