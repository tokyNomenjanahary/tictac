<form id="editFormLocatairesInfoComplementaire" enctype="multipart/form-data" method="POST">
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
                                   aria-describedby="helpId" @if(isset($locataire->LocatairesComplementaireInformations->NameSociete)) value="{{$locataire->LocatairesComplementaireInformations->NameSociete}} @endif">
                            <span class="text-danger" id="NameSocieteErrorMsg"></span>
                            <br>
                            <p>Si vous remplissez ce champs, le nom de la société figurera sur les documents.</p>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="" class="form-label">NO. TVA</label>
                            <input type="number" min="1" name="nTva" id="nTva" class="form-control" placeholder=""
                                   aria-describedby="helpId" @if(isset($locataire->LocatairesComplementaireInformations->nTva)) value="{{$locataire->LocatairesComplementaireInformations->nTva}}" @endif>
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
                                   aria-describedby="helpId" @if(isset($locataire->LocatairesComplementaireInformations->rcs)) value="{{$locataire->LocatairesComplementaireInformations->rcs}}" @endif>
                            <span class="text-danger" id="rcsErrorMsg"></span>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="" class="form-label">CAPITAL</label>
                            <input type="number" name="capital" id="capital" class="form-control" placeholder=""
                                   aria-describedby="helpId" @if(isset($locataire->LocatairesComplementaireInformations->capital)) value="{{$locataire->LocatairesComplementaireInformations->capital}}" @endif>
                            <span class="text-danger" id="capitalErrorMsg"></span>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="" class="form-label">DOMAINE D'ACTIVITÉ</label>
                            <input type="text" name="" id="domaineActiviter" class="form-control" placeholder=""
                                   aria-describedby="helpId" @if(isset($locataire->LocatairesComplementaireInformations->domaineActiviter)) value="{{$locataire->LocatairesComplementaireInformations->domaineActiviter}}" @endif>

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
                                   aria-describedby="helpId" @if(isset($locataire->LocatairesComplementaireInformations->employeur)) value="{{$locataire->LocatairesComplementaireInformations->employeur}}" @endif>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="" class="form-label">ADRESSE</label>
                            <input type="text" name="" id="adresseProfesionnel" class="form-control"
                                   placeholder="Indiquez un lieu"
                                   aria-describedby="helpId" @if(isset($locataire->LocatairesComplementaireInformations->adresseProfesionnel)) value="{{$locataire->LocatairesComplementaireInformations->adresseProfesionnel}}" @endif>
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
                                   aria-describedby="helpId" @if(isset($locataire->LocatairesComplementaireInformations->banque)) value="{{$locataire->LocatairesComplementaireInformations->banque}}" @endif>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="civilite" class="form-label">ADRESSE</label>
                            <input type="text" name="" id="adresseBanque" class="form-control"
                                   placeholder="Indiquez un lieu"
                                   aria-describedby="helpId" @if(isset($locataire->LocatairesComplementaireInformations->adresseBanque)) value="{{$locataire->LocatairesComplementaireInformations->adresseBanque}}" @endif>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="civilite" class="form-label">CODE POSTAL</label>
                            <input type="number" name="" id="codePostalBanque" class="form-control"
                                   placeholder="Indiquez un lieu"
                                   aria-describedby="helpId" @if(isset($locataire->LocatairesComplementaireInformations->codePostalBanque)) value="{{$locataire->LocatairesComplementaireInformations->codePostalBanque}}" @endif>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="civilite" class="form-label">IBAN</label>
                            <input type="number" name="" id="ibanBanque" class="form-control"
                                   placeholder="Indiquez un lieu"
                                   aria-describedby="helpId" @if(isset($locataire->LocatairesComplementaireInformations->ibanBanque)) value="{{$locataire->LocatairesComplementaireInformations->ibanBanque}}" @endif>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -40px">
                        <div class="">
                            <label for="civilite" class="form-label">SWIFT/BIC</label>
                            <input type="text" name="" id="swiftBicBanque" class="form-control"
                                   placeholder="Indiquez un lieu"
                                   aria-describedby="helpId" @if(isset($locataire->LocatairesComplementaireInformations->swiftBicBanque)) value="{{$locataire->LocatairesComplementaireInformations->swiftBicBanque}}" @endif>
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
                                   aria-describedby="helpId" @if(isset($locataire->LocatairesComplementaireInformations->nouvelleAdresse)) value="{{$locataire->LocatairesComplementaireInformations->nouvelleAdresse}}" @endif>
                            <br>
                            <p>Nouvelle adresse du locataire pour toute future correspondance après son départ</p>
                        </div>
                        <div class="">
                            <label for="" class="form-label">NOTE PRIVÉE</label>
                            {{-- <input type="text" name="" id="" class="form-control" placeholder="EX. Avenue de opera France"
                                aria-describedby="helpId"> --}}
                            <textarea name="" id="NotePrive" class="form-control" cols="20" rows="5"
                                      placeholder="EX. Le locataire parait qu'on rêve tous d'avoir..." >@if(isset($locataire->LocatairesComplementaireInformations->NotePrive)) {{$locataire->LocatairesComplementaireInformations->NotePrive}} @endif</textarea>
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
                    <button id="precedantInfoComplementaire" class="btn btn-secondary"
                       >Precedent</button>
                    <button type="submit" class="btn btn-primary"> Sauvegarder et Suivant</button>
                </div>
            </div>
        </div>
    </div>
</form>

@php
$idInfoComplementaire = null;
    $exist = isset($locataire->LocatairesComplementaireInformations->id);
    if ($exist == false) {
    $isNew = 1;
    }
    else {
        $isNew = 0;
    }
    if (isset($locataire->LocatairesComplementaireInformations->id)){
        $idInfoComplementaire = $locataire->LocatairesComplementaireInformations->id;
    }
@endphp

@push('js')
    <script>
        $(document).ready(function () {
            var url = window.location.href;
            var info_gen_id = url.split("/").pop();
            $('#precedantInfoComplementaire').click(function (e){
                e.preventDefault();
                $('#home-tab, #information_generale').addClass('active')
                $('#profile-tab, #complementaire').removeClass('active')
            })
            $('#editFormLocatairesInfoComplementaire').submit(function (e) {
                e.preventDefault();
                var complementaireId = "{{$isNew}}"
                var data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                var fields = $(this).find(':input, textarea').not(':button');
                fields.each(function () {
                    data.append(this.id, $(this).val());
                });
                if (complementaireId == '1') {
                    data.append("info_gen_id", info_gen_id);
                } else {
                    data.append("idInfoComplementaire", "{{$idInfoComplementaire}}");
                }
                    $.ajax({
                        url: "{{ route('locataire.info_complementaire.edit') }}",
                        type: "POST",
                        processData: false,
                        contentType: false,
                        data: data,
                        beforeSend: function () {
                            $('#myLoader').removeClass('d-none')
                        },
                        success: function (data) {
                            $('#myLoader').addClass('d-none')
                            $('#profile-tab, #complementaire').removeClass('active')
                            $('#messages-tab, #garants').addClass('active')
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
            });
        })
    </script>

@endpush
