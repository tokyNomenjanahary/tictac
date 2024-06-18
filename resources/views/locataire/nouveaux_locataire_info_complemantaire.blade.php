<form id="formLocatairesInfoComplementaire" enctype="multipart/form-data" method="POST">
    @csrf
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card" style="margin-top: 5px">
                    <div class="card-header"
                         style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                        Information société
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="civilite" class="form-label">SOCIÉTÉ</label>
                            <input type="text" name="NameSociete" id="NameSociete" class="form-control"
                                   placeholder="Nom Societe"
                                   aria-describedby="helpId">
                            <span class="text-danger" id="NameSocieteErrorMsg"></span>
                            <br>
                            <p>Si vous remplissez ce champs, le nom de la société figurera sur les documents.</p>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="" class="form-label">NO. TVA</label>
                            <input type="number" min="1" name="nTva" id="nTva" class="form-control" placeholder=""
                                   aria-describedby="helpId">
                            <span class="text-danger" id="nTvaErrorMsg"></span>
                            <br>
                            <p class>Si vous remplissez ce champs, cette information figurera sur certains documents
                                automatiques.</p>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="" class="form-label">RCS / SIREN</label>
                            <input type="text" name="rcs" id="rcs" class="form-control" placeholder=""
                                   aria-describedby="helpId">
                            <span class="text-danger" id="rcsErrorMsg"></span>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="" class="form-label">CAPITAL</label>
                            <input type="number" name="capital" id="capital" class="form-control" placeholder=""
                                   aria-describedby="helpId">
                            <span class="text-danger" id="capitalErrorMsg"></span>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="" class="form-label">DOMAINE D'ACTIVITÉ</label>
                            <input type="text" name="" id="domaineActiviter" class="form-control" placeholder=""
                                   aria-describedby="helpId">

                        </div>
                    </div>
                </div>
                <div class="card" style="margin-top: 5px">
                    <div class="card-header"
                         style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                        Adresse professionnelle
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="" class="form-label">EMPLOYEUR</label>
                            <input type="text" name="" id="employeur" class="form-control" placeholder=""
                                   aria-describedby="helpId">
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="" class="form-label">ADRESSE</label>
                            <input type="text" name="" id="adresseProfesionnel" class="form-control"
                                   placeholder="Indiquez un lieu"
                                   aria-describedby="helpId">
                        </div>
                    </div>
                </div>
                <div class="card" style="margin-top: 5px">
                    <div class="card-header"
                         style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                        Coordonnées bancaires
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="civilite" class="form-label">BANQUE</label>
                            <input type="text" name="" id="banque" class="form-control" placeholder="Indiquez un lieu"
                                   aria-describedby="helpId">
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="civilite" class="form-label">ADRESSE</label>
                            <input type="text" name="" id="adresseBanque" class="form-control"
                                   placeholder="Indiquez un lieu"
                                   aria-describedby="helpId">
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="civilite" class="form-label">CODE POSTAL</label>
                            <input type="number" name="" id="codePostalBanque" class="form-control"
                                   placeholder="Indiquez un lieu"
                                   aria-describedby="helpId">
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="civilite" class="form-label">IBAN</label>
                            <input type="number" name="" id="ibanBanque" class="form-control"
                                   placeholder="Indiquez un lieu"
                                   aria-describedby="helpId">
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="civilite" class="form-label">SWIFT/BIC</label>
                            <input type="text" name="" id="swiftBicBanque" class="form-control"
                                   placeholder="Indiquez un lieu"
                                   aria-describedby="helpId">
                        </div>
                    </div>
                </div>
                <div class="card" style="margin-top: 5px">
                    <div class="card-header"
                         style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                        Informations complémentaires
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="" class="form-label">NOUVELLE ADRESSE</label>
                            <input type="text" name="" id="nouvelleAdresse" class="form-control"
                                   placeholder="EX. Avenue de opera France"
                                   aria-describedby="helpId">
                            <br>
                            <p>Nouvelle adresse du locataire pour toute future correspondance après son départ</p>
                        </div>
                        <div class="">
                            <label for="" class="form-label">NOTE PRIVÉE</label>
                            {{-- <input type="text" name="" id="" class="form-control" placeholder="EX. Avenue de opera France"
                                aria-describedby="helpId"> --}}
                            <textarea name="" id="NotePrive" class="form-control" cols="20" rows="5"
                                      placeholder="EX. Le locataire parait qu'on rêve tous d'avoir..."></textarea>
                            <br>
                            <p>Cette note est visible uniquement pour vous.</p>
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
                       onclick="localStorage.clear();">Annuler</a>
                    <button type="submit" class="btn btn-primary"> Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" data-backdrop="false" id="basicModalC" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">{{__('Ajouts des garants')}}</h5>
                    {{--                    <button--}}
                    {{--                            type="button"--}}
                    {{--                            class="btn-close"--}}
                    {{--                            data-bs-dismiss="modal"--}}
                    {{--                            aria-label="Close"--}}
                    {{--                    ></button>--}}
                </div>
                <div class="modal-body text-success" role="alert" id="successMsg" style="display: none">
                    {{__('L\'ajout des informations complémentaires de votre locataire a bien été enregistré')}}
                </div>
                <div class="modal-body">{{__('Voulez-vous continuer à ajouter des garants pour le nouveau locataire ?')}}</div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" id="buttonModalNonC">
                        {{__('Non')}}
                    </button>
                    <button class="btn btn-primary" id="buttonModalOuiC">{{__('Oui')}}</button>
                </div>
            </div>
        </div>
    </div>
</form>
@push('js')
    <script>
        $(document).ready(function () {
            $('#formLocatairesInfoComplementaire').submit(function (e) {
                e.preventDefault();
                var data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                var fields = $(this).find(':input, textarea').not(':button');
                fields.each(function () {
                    data.append(this.id, $(this).val());
                });
                data.append("info_gen_id", localStorage.getItem("locataireInfoGeneraleId"));
                if (!localStorage.getItem("locataireComplementaire")) {
                    $.ajax({
                        url: "{{ route('locataire.info_complementaire') }}",
                        type: "POST",
                        processData: false,
                        contentType: false,
                        data: data,
                        beforeSend: function () {
                            $('#myLoader').removeClass('d-none')
                        },
                        success: function (data) {
                            localStorage.setItem("locataireComplementaire", 1);
                            $('#myLoader').addClass('d-none')
                            $('#successMsg').show();
                            $('html, body').animate({
                                scrollTop: $("#successMsg").offset().top
                            }, 300);
                            // $('#basicModal').modal('show');
                            $('#basicModalC').modal({
                                backdrop: 'static',
                                keyboard: false
                            }).modal('show');

                            $("#buttonModalNonC").click(function () {
                                $('#formLocatairesInfoComplementaire')[0].reset();
                                window.location = "{{route('locataire.locataire')}}";
                            });
                            $("#buttonModalOuiC").click(function () {
                                $('#basicModalC').modal('hide');
                                $('#profile-tab-li').attr("hidden", "hidden");
                                $('#messages-tab-li').removeAttr('hidden')
                                $('#profile-tab, #complementaire').removeClass('active')
                                $('#messages-tab, #garants').addClass('active')
                                $('#successMsg').hide();
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