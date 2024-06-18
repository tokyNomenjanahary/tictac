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
</style>

<form id="formLocatairesGarants" enctype="multipart/form-data" method="POST">
    @csrf
    <div class="col-md-6">
        <div class="card mb-4">
            <h5 class="card-header">Garants</h5>
            <div class="card-body">
                <div>
                    <label for="defaultFormControlInput" class="form-label">PRÉNOM GARANT *</label>
                    <input
                            type="text"
{{--                            required--}}
                            class="form-control"
                            id="garantPrenoms"
                            placeholder=""
                            aria-describedby="defaultFormControlHelp"
                    />
                    <div id="garantPrenomsErrorMsg" class="form-text text-danger">
                    </div>
                </div>
                <div>
                    <label for="defaultFormControlInput" class="form-label">NOM GARANTS *</label>
                    <input
                            type="text"
{{--                            required--}}
                            class="form-control"
                            id="garantNom"
                            placeholder=""
                            aria-describedby="defaultFormControlHelp"
                    />
                    <div id="garantNomErrorMsg" class="form-text text-danger">
                    </div>
                </div>
                <div>
                    <label for="defaultFormControlInput" class="form-label">DATE DE NAISSANCE GARANTS</label>
                    <input
                            type="date"
                            class="form-control"
                            id="garantDateNaissance"
                            placeholder=""
                            aria-describedby="defaultFormControlHelp"
                    />
                    <div id="garantDateNaissanceErrorMsg" class="form-text text-danger">
                    </div>
                </div>
                <div>
                    <label for="defaultFormControlInput" class="form-label">LIEU DE NAISSANCE GARANTS</label>
                    <input
                            type="text"
                            class="form-control"
                            id="garantLieuNaissance"
                            placeholder=""
                            aria-describedby="defaultFormControlHelp"
                    />
                    <div id="garantLieuNaissanceErrorMsg" class="form-text text-danger">
                    </div>
                </div>
                <div>
                    <label for="defaultFormControlInput" class="form-label">E-MAIL GARANTS</label>
                    <input
                            type="text"
                            class="form-control"
                            id="garantEmail"
                            placeholder=""
                            aria-describedby="defaultFormControlHelp"
                    />
                    <div id="garantEmailErrorMsg" class="form-text text-danger">
                    </div>
                </div>
                <div>
                    <label for="defaultFormControlInput" class="form-label">MOBILE GARANTS</label>
                    <input
                            type="number"
                            class="form-control"
                            id="garantMobile"
                            placeholder=""
                            aria-describedby="defaultFormControlHelp"
                    />
                    <div id="garantMobileErrorMsg" class="form-text text-danger">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <p style="margin-top: 10px;">{{__('Vous pouvez ajouter plusieurs garants si besoin en cliquant sur "oui" après avoir
        sauvegardé')}}</p>
    <div class="card" style="margin-top: 5px">
        <div class="row">
            <div class="col-md-12" style="padding: 15px;">
                <div class="float-end">
                    <a href="{{ route('locataire.locataire') }}" class="btn btn-secondary"
                       onclick="localStorage.clear();">{{__('Annuler')}}</a>
                    <button type="submit" class="btn btn-primary">{{__('Sauvegarder')}}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal ajout autre garant -->
    <div class="modal fade" data-backdrop="false" id="basicModalGarant" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabelGarant">{{__('Ajout  d\'autres garants')}}</h5>
                    {{--                    <button--}}
                    {{--                            type="button"--}}
                    {{--                            class="btn-close"--}}
                    {{--                            data-bs-dismiss="modal"--}}
                    {{--                            aria-label="Close"--}}
                    {{--                    ></button>--}}
                </div>
                <div class="modal-body text-success" role="alert" id="successMsg" >
                    {{__('Le garant de votre nouveau locataire a bien été enregistré')}}
                </div>
                <div class="modal-body">{{__('Voulez-vous ajouter d\'autres garants ?')}}</div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" id="buttonModalNonGarant">
                        {{__('Non')}}
                    </button>
                    <button class="btn btn-primary" id="buttonModalOuiGarant">{{__('Oui')}}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal  -->
    <div class="modal fade" data-backdrop="false" id="basicModalGarantU" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">{{__('Ajouts des contacts d\'urgence')}}</h5>
                    {{--                    <button--}}
                    {{--                            type="button"--}}
                    {{--                            class="btn-close"--}}
                    {{--                            data-bs-dismiss="modal"--}}
                    {{--                            aria-label="Close"--}}
                    {{--                    ></button>--}}
                </div>
                <div class="modal-body">{{__('Voulez-vous continuer à ajouter des contacts d\'urgence pour le locataire ?')}}</div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" id="buttonModalNonGarantU">
                        {{__('Non')}}
                    </button>
                    <button class="btn btn-primary" id="buttonModalOuiGarantU">{{__('Oui')}}</button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('js')
    <script>
        $(document).ready(function () {
            $('#formLocatairesGarants').submit(function (e) {
                e.preventDefault();
                var data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                var fields = $(this).find(':input, textarea').not(':button');
                fields.each(function () {
                    data.append(this.id, $(this).val());
                });
                data.append("info_gen_id", localStorage.getItem("locataireInfoGeneraleId"));
                if(!localStorage.getItem("locataireGarants")) {
                    $.ajax({
                        url: "{{ route('locataire.info_garants') }}",
                        type: "POST",
                        processData: false,
                        contentType: false,
                        data: data,
                        beforeSend: function () {
                            $('#myLoader').removeClass('d-none')
                        },
                        success: function (data) {
                            localStorage.setItem("locataireGarants", 1);
                            $('#myLoader').addClass('d-none')
                            // $('#successMsg').show();
                            // $('html, body').animate({
                            //     scrollTop: $("#successMsg").offset().top
                            // }, 300);
                            $('#basicModalGarant').modal({
                                backdrop: 'static',
                                keyboard: false
                            }).modal('show');
                            $("#buttonModalNonGarant").click(function () {
                                $('#basicModalGarant').modal('hide');
                                $('#basicModalGarantU').modal({
                                    backdrop: 'static',
                                    keyboard: false
                                }).modal('show');
                            });
                            $("#buttonModalOuiGarant").off('submit').click(function (event) {
                                event.preventDefault();
                                localStorage.removeItem("locataireGarants");
                                $('.card-header').text('Veiller remplir les champs ci-dessous pour ajouter un autre garant')
                                $('#formLocatairesGarants')[0].reset();
                                $('#basicModalGarant').modal('hide');
                                $('#garantPrenoms').focus()
                                $('html, body').animate({
                                    scrollTop: $("#garantPrenoms").offset().top
                                }, 200);
                            });

                            $("#buttonModalNonGarantU").click(function () {
                                window.location = "{{route('locataire.locataire')}}";
                            });

                            $("#buttonModalOuiGarantU").click(function () {
                                $('#basicModalGarantU').modal('hide');
                                $('#messages-tab-li').attr("hidden", "hidden");
                                $('#contactUrgence-li').removeAttr('hidden')
                                $('#messages-tab, #garants').removeClass('active')
                                $('#contactUrgence, #urgence').addClass('active')
                                // $('#successMsg').hide();
                            });
                                },
                        error: function (xhr) {
                            $('#myLoader').addClass('d-none')
                            var err = JSON.parse(xhr.responseText);
                            var champs = Object.keys(err.error);
                            for (var i = 0; i < champs.length; i++) {
                                var champ = champs[i];
                                $('#' + champ + 'ErrorMsg').text(err.error[champ]);
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
                                    (function (localChamp) {
                                        $(document).on('input', '#' + localChamp, function () {
                                            $('#' + localChamp + 'ErrorMsg').text("");
                                        });
                                        //fonction callback
                                    })(champ);
                                }
                            }
                        }
                    });
                }
            });
        })
    </script>
@endpush
