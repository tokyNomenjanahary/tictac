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

<form id="formLocatairesUrgence" enctype="multipart/form-data" method="POST">
    @csrf
    <div class="col-md-6">
        <div class="card mb-4">
            <h5 class="card-header">{{__('Contact d\'urgence')}}</h5>
            <div class="card-body">
                <div>
                    <label for="defaultFormControlInput" class="form-label">PRÉNOM *</label>
                    <input
                            type="text"
                            {{--                            required--}}
                            class="form-control"
                            id="urgencePrenoms"
                            placeholder=""
                            aria-describedby="defaultFormControlHelp"
                    />
                    <div id="urgencePrenomsErrorMsg" class="form-text text-danger">
                    </div>
                </div>
                <div>
                    <label for="defaultFormControlInput" class="form-label">NOM *</label>
                    <input
                            type="text"
                            {{--                            required--}}
                            class="form-control"
                            id="urgenceNom"
                            placeholder=""
                            aria-describedby="defaultFormControlHelp"
                    />
                    <div id="urgenceNomErrorMsg" class="form-text text-danger">
                    </div>
                </div>
                <div>
                    <label for="defaultFormControlInput" class="form-label">DATE DE NAISSANCE</label>
                    <input
                            type="date"
                            class="form-control"
                            id="urgenceDateNaissance"
                            placeholder=""
                            aria-describedby="defaultFormControlHelp"
                    />
                    <div id="urgenceDateNaissanceErrorMsg" class="form-text text-danger">
                    </div>
                </div>
                <div>
                    <label for="defaultFormControlInput" class="form-label">LIEU DE NAISSANCE</label>
                    <input
                            type="text"
                            class="form-control"
                            id="urgenceLieuNaissance"
                            placeholder=""
                            aria-describedby="defaultFormControlHelp"
                    />
                    <div id="urgenceLieuNaissanceErrorMsg" class="form-text text-danger">
                    </div>
                </div>
                <div>
                    <label for="defaultFormControlInput" class="form-label">E-MAIL</label>
                    <input
                            type="text"
                            class="form-control"
                            id="urgenceEmail"
                            placeholder=""
                            aria-describedby="defaultFormControlHelp"
                    />
                    <div id="urgenceEmailErrorMsg" class="form-text text-danger">
                    </div>
                </div>
                <div>
                    <label for="defaultFormControlInput" class="form-label">MOBILE</label>
                    <input
                            type="text"
                            class="form-control"
                            id="urgenceMobile"
                            placeholder=""
                            aria-describedby="defaultFormControlHelp"
                    />
                    <div id="urgenceMobileErrorMsg" class="form-text text-danger">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <p style="margin-top: 10px;">{{__('Vous pouvez ajouter plusieurs contact d\'urgence si besoin en cliquant sur "oui" après avoir sauvegardé')}}</p>
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
    <!-- Modal ajout autre urgence -->
    <div class="modal fade" data-backdrop="false" id="basicModalUrgence" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabelGarant">{{__('Ajout de Contact d\'urgence')}}</h5>
                    {{--                    <button--}}
                    {{--                            type="button"--}}
                    {{--                            class="btn-close"--}}
                    {{--                            data-bs-dismiss="modal"--}}
                    {{--                            aria-label="Close"--}}
                    {{--                    ></button>--}}
                </div>
                <div class="modal-body text-success" role="alert" id="successMsg">
                    {{__('Le contact d\'urgence de votre nouveau locataire a bien été enregistré')}}
                </div>
                <div class="modal-body">{{__('Voulez-vous ajouter d\'autres contact d\'urgence ?')}}</div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" id="buttonModalNonUrgence">
                        {{__('Non')}}
                    </button>
                    <button class="btn btn-primary" id="buttonModalOuiUrgence">{{__('Oui')}}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal  -->
    <div class="modal fade" data-backdrop="false" id="basicModalUrgenceU" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">{{__('Ajout contact d\'urgence')}}</h5>
                    {{--                    <button--}}
                    {{--                            type="button"--}}
                    {{--                            class="btn-close"--}}
                    {{--                            data-bs-dismiss="modal"--}}
                    {{--                            aria-label="Close"--}}
                    {{--                    ></button>--}}
                </div>
                <div class="modal-body text-success" role="alert" id="successMsg" style="display: none">
                    {{__('La création de votre nouveau locataire a bien été enregistré')}}
                </div>
                <div class="modal-body">{{__('Voulez-vous continuer à ajouter des contacts d\'urgence pour le locataire ?')}}</div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" id="buttonModalNonUrgenceU">
                        {{__('Non')}}
                    </button>
                    <button class="btn btn-primary" id="buttonModalOuiUrgenceU">{{__('Oui')}}</button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('js')
    <script>
        $(document).ready(function () {
            $('#formLocatairesUrgence').submit(function (e) {
                e.preventDefault();
                var data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                var fields = $(this).find(':input, textarea').not(':button');
                fields.each(function () {
                    data.append(this.id, $(this).val());
                });
                data.append("info_gen_id", localStorage.getItem("locataireInfoGeneraleId"));
                if(!localStorage.getItem("locataireUrgence")) {
                    $.ajax({
                        url: "{{ route('locataire.info_urgence') }}",
                        type: "POST",
                        processData: false,
                        contentType: false,
                        data: data,
                        beforeSend: function () {
                            $('#myLoader').removeClass('d-none')
                        },
                        success: function (data) {
                            localStorage.setItem("locataireUrgence", 1);
                            $('#myLoader').addClass('d-none')
                            // $('#successMsg').show();
                            // $('html, body').animate({
                            //     scrollTop: $("#successMsg").offset().top
                            // }, 300);
                            $('#basicModalUrgence').modal({
                                backdrop: 'static',
                                keyboard: false
                            }).modal('show');
                            $("#buttonModalNonUrgence").click(function () {
                                window.location = "{{route('locataire.locataire')}}";
                                // $('#basicModalUrgence').modal('hide');
                                // $('#basicModalUrgenceU').modal({
                                //     backdrop: 'static',
                                //     keyboard: false
                                // }).modal('show');
                            });
                            $("#buttonModalOuiUrgence").off('submit').click(function (event) {
                                event.preventDefault();
                                localStorage.removeItem("locataireUrgence");
                                $('.card-header').text('Veiller remplir les champs ci-dessous pour ajouter un autre contact d\'urgence')
                                $('#formLocatairesUrgence')[0].reset();
                                $('#basicModalUrgence').modal('hide');
                                $('#urgencePrenoms').focus()
                                $('html, body').animate({
                                    scrollTop: $("#urgencePrenoms").offset().top
                                }, 200);
                            });

                            {{--$("#buttonModalNonUrgenceU").click(function () {--}}
                            {{--    window.location = "{{route('locataire.locataire')}}";--}}
                            {{--});--}}

                            {{--$("#buttonModalOuiUrgenceU").click(function () {--}}
                            {{--    $('#basicModalUrgenceU').modal('hide');--}}
                            {{--    $('#messages-tab-li').attr("hidden", "hidden");--}}
                            {{--    $('#contactUrgence-li').removeAttr('hidden')--}}
                            {{--    $('#messages-tab, #garants').removeClass('active')--}}
                            {{--    $('#contactUrgence, #urgence').addClass('active')--}}
                            {{--    // $('#successMsg').hide();--}}
                            {{--});--}}
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