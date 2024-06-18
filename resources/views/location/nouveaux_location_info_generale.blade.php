<style>
    label {
        color: black !important;
        margin-top: 12px;
    }

    .form-control:disabled,
    .form-control[readonly] {
        background-color: #FFFFFF !important;
        opacity: 1;
    }

    input {
        border-radius: none !important;
    }

    .card {
        border-style: none;
        border-radius: 0px;
    }

    .cacher {
        display: none !important;
    }
    @media only screen and (max-width: 600px) {
        header{
            margin:25px auto;
            padding-bottom:50px !important;
            margin-left: 18px !important;
            margin-right: 18px !important;
        }
    }

</style>
{{-- <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648722693/css/intlTelInput.min_ft4ncf.css" rel="stylesheet"> --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/css/intlTelInput.css">
<div class="card">
    <div class="card-header"
        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
        {{__('location.BienLoue')}}
    </div>
    <div class="card-body">
        <div class="">
            <label for="logement_id" class="form-label">BIEN </label>
            <select name="logement_id" id="logement_id" page="loca_information_generale"
                onchange='suc("logement_id","logement_id_err");'
                class="form-select @if ($errors->has('logement_id')) is-invalid @endif">
                <option selected hidden value="null" class="form-select"> {{__('location.choisirBien')}}</option>
                @foreach ($logements as $logement)
                    @if (isset($logement_id))
                        <option value="{{ $logement->id }}" @if ($logement_id == $logement->id) selected @endif>{{ $logement->identifiant }}</option>
                    @else
                    <option value="{{ $logement->id }}">{{ $logement->identifiant }}</option>
                    @endif
                @endforeach
            </select>
            @if ($errors->has('logement_id'))
                <p class="text-danger">{{__('location.errBien')}}</p>
            @endif
            <p class="text-danger logement_id_err"></p>
        </div>

    </div>
</div>
<div class="card" style="margin-top: 5px">
    <div class="card-header"
        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
        {{__('location.detaile')}}
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <label for="civilite" class="form-label">{{__('location.type')}} *</label>
                <select name="type_location_id" id="type_location_id" page="loca_information_generale"
                    onchange='suc("type_location_id","type_location_id_err");'
                    class="form-control @if ($errors->has('type_location_id')) is-invalid @endif"><span
                        class="caret"></span>
                    {{-- <option selected hidden value="null"> Choisir la type de location</option> --}}
                    @foreach ($type_locations as $type_location)
                        <option value="{{ $type_location->id }}">{{ __('location.'.$type_location->description)}}</option>
                    @endforeach
                </select>
                @if ($errors->has('type_location_id'))
                    <p class="text-danger">{{__('location.errType')}}</p>
                @endif
                <p class="text-danger type_location_id_err"></p>
            </div>
            <div class="col-md-6">
                <label for="" class="form-label">{{__('location.identifiant')}}</label>
                <input type="text" name="identifiant" id="identifiant" page="loca_information_generale"
                    onchange='suc("identifiant","identifiant_err");'
                    class="form-control @if ($errors->has('identifiant')) is-invalid @endif " placeholder=""
                    aria-describedby="helpId">
                @if ($errors->has('identifiant'))
                    <p class="text-danger">{{ $errors->first('identifiant') }}</p>
                @endif
                <p class="text-danger identifiant_err"></p>
                <p style="margin-top:-8px;">{{__('location.errIdentifiant')}}</p>
            </div>
        </div>
    </div>

    <div class="card-body" style="margin-top: -40px">
        <div class="row">
            <div class="col-md-6">
                <label for="" class="form-label">{{__('location.debut')}}*</label>
                <input type="date" name="debut" id="debut" page="loca_information_generale"
                    onchange='suc("debut","debut_err");'
                    class="form-control @if ($errors->has('debut')) is-invalid @endif" placeholder=""
                    aria-describedby="helpId">
                @if ($errors->has('debut'))
                    <p class="text-danger">{{ $errors->first('debut') }}</p>
                @endif
                <p class="text-danger debut_err"></p>
            </div>
            <div class="col-md-6">
                <label for="" class="form-label">{{__('location.fin')}}</label>
                <input type="date" name="fin" id="fin" onchange="calculateDifference();"
                    page="loca_information_generale"
                    class="form-control @if ($errors->has('fin')) is-invalid @endif" placeholder=""
                    aria-describedby="helpId">
                <p class="text-danger fin_err" id="finerr"></p>
                <p class="text-danger" id="errorD"></p>
            </div>
        </div>
    </div>
    <div class="card-body" style="margin-top: -40px;display:none">
        <div class="">
            <label for="" class="form-label">DURÉE DU BAIL</label>
            <input type="text" name="dure" id="dure" class="form-control" placeholder=""
                aria-describedby="helpId" page="loca_information_generale" readonly>
        </div>
    </div>
    <div class="card-body" style="margin-top: -40px">
        <div class="row">
            <div class="col-md-6">
                <label for="civilite" class="form-label">{{__('location.payment')}} *</label>
                <select name="type_payment_id" id="type_payment_id" class="form-control"
                    page="loca_information_generale"><span class="caret"></span>
                    {{-- <option  selected hidden> Choisir la type de payment</option> --}}
                    @foreach ($type_payments as $type_payment)
                        <option value="{{ $type_payment->id }}">{{ $type_payment->description }}</option>
                    @endforeach
                </select>
                <p class="text-danger type_payment_id_err"></p>
            </div>
            <div class="col-md-6">
                <label for="mode_payement" class="form-label">{{__('location.modePayment')}}</label>
                <select name="mode_payment_id" id="mode_payment_id" class="form-control"
                    page="loca_information_generale"><span class="caret"></span>
                    {{-- <option  selected hidden> Choisir la mode de payment</option> --}}
                    @foreach ($mode_payments as $mode_payment)
                        <option value="{{ $mode_payment->id }}">{{ $mode_payment->description }}</option>
                    @endforeach
                </select>
                <p class="text-danger mode_payment_id_err"></p>
                <p>{{__('location.textMod')}}</p>
            </div>
        </div>
    </div>

    {{-- a decommenter --}}

    {{-- <div class="card-body" style="margin-top: -40px">
        <div class="">
            <label for="civilite" class="form-label">DATE DE PAIEMENT</label>
            <select name="date_payment" id="date_payment" class="form-control"
                page="loca_information_generale"><span class="caret"></span>
                <?php
                for ($i = 1; $i <= 31; $i++) {
                    echo '<option value="' . $i . '">' . $i . '</option>';
                }
                ?>
            </select>
            <p>Date de paiement du loyer prévue dans le contrat.</p>
        </div>
    </div> --}}

</div>
<div class="card" style="margin-top: 5px">
    {{-- @foreach ($logements as $logement) --}}
    <div class="card-header"
        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;text-transform: lowercase">
        {{__('location.loyers')}}
    </div>
    <div class="card-body cacher">
        <div class="">
            <label for="" class="form-label">{{__('location.loyerHC')}} *</label>
            <input type="number" min="0" name="" id="LHC" page="loca_information_generale"
                onchange='suc("loyer_HC","loyer_HC_err");'
                class="form-control @if ($errors->has('loyer_HC')) is-invalid @endif" placeholder="€" readonly
                aria-describedby="helpId">
        </div>

    </div>
    <div class="card-body cacher" style="margin-top: -40px";>
        <div class="">
            <label for="" class="form-label">{{__('location.charge')}}</label>
            <input type="number" min="0" name="" id="LC" page="loca_information_generale"
                onchange='suc("charge","charge_err"),calcul();' class="form-control" placeholder="€"
                aria-describedby="helpId" readonly>
        </div>
        <p class="text-danger charge_err"></p>
    </div>
    <div class="card-body cacher" style="margin-top: -40px";>
        <div class="">
            <label for="" class="form-label">{{__('location.total')}}*</label>
            <input type="number" min="0" name="" id="chargeT" page="loca_information_generale"
                readonly class="form-control" placeholder="€" aria-describedby="helpId">
        </div>
        <p class="text-danger charge_err"></p>
    </div>
    {{-- @endforeach --}}
    <div class="card-body" style="margin-top:-20px;">
        <label for="" class="form-label">{{__('location.loyers')}}</label>
        <input type="number" name="LoyerLocation" id="LoyerLocation" class="form-control">
        <label for="" class="form-label">{{__('location.charge')}}</label>
        <input type="number" name="ChargeLocation" id="ChargeLocation" class="form-control">
        <input type="hidden" id="IdBien" class="form-control" name="IdBien">
        {{-- <table class="table table-bordered mt-2">
            <thead>
                <tr class="align-middle">
                    <th>Loyer </th>
                    <td>
                        <input type="hidden" name="LoyerLocation" id="LoyerLocation" page="loca_information_generale"
                            onchange='suc("loyer_HC","loyer_HC_err");'
                            class="form-control @if ($errors->has('loyer_HC')) is-invalid @endif" placeholder="€"
                            readonly aria-describedby="helpId">
                        <span id="monSpan1" style="float: right;"></span>
                    </td>
                </tr>
                <tr class="align-middle">
                    <th>Charge</th>
                    <td>
                        <input type="hidden" name="ChargeLocation" id="ChargeLocation" page="loca_information_generale"
                            onchange='suc("charge","charge_err"),calcul();' class="form-control" placeholder="€"
                            aria-describedby="helpId" readonly>
                        <span id="monSpan2" style="float: right;"></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="hidden" id="tautaux" name="montants">
                                <p>Totaux : </p>
                            </div>
                            <div class="col-lg-6">
                                <span style="float: right;" id="monSpan3"></span>
                            </div>
                        </div>
                    </td>
                </tr>
            </thead>
        </table> --}}
    </div>
</div>

<div class="card" style="margin-top: 5px;">
    <div class="card-header"
        style="color:#4C8DCB;padding:10px;background-color:#F5F5F9;margin-top:20px;border-radius:0px;">
        Première quittance
    </div>
    <div class="card-body">
        <div class="">
            <label for="" class="form-label">{{__('location.finPeriode')}}</label>
            <input type="text" name="dateActuel" id="dateActuel" class="form-control" readonly placeholder=""
                aria-describedby="helpId">
            <p>{{__('location.datePremier')}}</p>
        </div>
    </div>
    {{-- <div class="card-body" style="margin-top: -40px">
        <div class="">
            <a href="javascript:;" onclick="calculateFirstPayment()" class="btn"
                style="border: 1px solid gray;background-color:#EEEEEE"> <i class="fa fa-calculator m-r-5"></i>
                Calculer les montants</a>
        </div>
    </div> --}}
    <div class="card-body" style="margin-top: -40px">
        <div class="">
            <label for="" class="form-label">{{__('location.loyerHC')}}</label>
            <input type="text" name="loyer_HC" id="loyer_HC" page="loca_information_generale" readonly
                class="form-control" placeholder="€"  aria-describedby="helpId">
        </div>
    </div>
    <div class="card-body" style="margin-top: -40px">
        <div class="">
            <label for="" class="form-label">{{__('location.charge')}}</label>
            <input type="text" name="charge" id="charge" page="loca_information_generale" readonly
                class="form-control" placeholder="€" aria-describedby="helpId">
            <input type="hidden" id="summe" name="summe">
        </div>
    </div>
</div>

{{-- a decommenter --}}

<div class="card" style="margin-top: 5px">
    <div class="card-header"
        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
        {{__('location.depotGarantie')}}
    </div>
    <div class="card-body">
        <div class="">
            <label for="" class="form-label" style="text-transform: uppercase !important">{{__('location.depotGarantie')}} *</label>
            <input type="number" min="0" name="garantie" page="loca_information_generale" id="garantie"
                onchange='suc("garantie","garantie_err");'
                class="form-control @if ($errors->has('garantie')) is-invalid @endif" placeholder="€"
                aria-describedby="helpId">
            @if ($errors->has('garantie'))
                <p class="text-danger">{{ $errors->first('garantie') }}</p>
            @endif
            <p class="text-danger garantie_err"></p>
        </div>
    </div>
</div>

{{-- a decommenter --}}

{{-- <div class="card" style="margin-top: 5px">
    <div class="card-header"
        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
        Allocations logement
    </div>
    <div class="card-body">
        <div class="">
            <label for="" class="form-label">PAIEMENT CAF/APL</label>
            <input type="number" min="0" name="allocation" page="loca_information_generale" id="allocation"
                onchange='suc("allocation","allocation_err");' class="form-control" placeholder="€"
                aria-describedby="helpId">
             <p class="text-danger allocation_err"></p>
            <p>Renseigner ici les aides telles que la CAF, l'APL, qui vous sont versées automatiquement. Le paiement
                sera généré automatiquement chaque mois comme déjà perçu.</p>
        </div>
    </div>
</div> --}}

<div class="card" style="margin-top: 5px">
    <div class="card-header"
        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
       {{__('location.locataire')}}
    </div>
    <div id="miampylocataire">
    </div>
    <div class="card-body" id="card-locataire" style="margin-top: 20px;">
        <div class="row align-middle">
            <div class="col-md-1 align-middle ">
                <label for="" class="form-label" style="text-transform: uppercase !important">{{__('location.locataire')}}</label>
            </div>
            <div class="col-md-6 align-middle" style="display: flex">
                <a href="" class="btn" data-bs-toggle="modal"
                    style="border:1px solid gray;color:blue;background-color:#f5f5f9;" data-bs-target="#Modallocataire"
                    onmouseup="">
                    <i class="fa fa-plus-circle"></i> {{__('location.ajoutLoc')}}
                </a>
                <p class="text-danger" style="margin-left: 20px;margin-top:10px" id="locerror"></p>
            </div>
            <p style="margin-top: 10px;">{{__('location.CliqueLoc')}}</p>
        </div>
    </div>
    <!-- Modal Body-->
    <div class="modal fade" id="Modallocataire" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#FAFAFA;">
                    <h5 class="modal-title" id="modalTitleId">{{__('location.nouvLoc')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <p class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7"><span
                        class="label m-r-2"
                        style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">INFORMATION</span>
                    {{__('location.selectLoc')}}</p>
                <div class="modal-body">
                    <div class="container-fluid">
                        <input type="hidden" name="_token" value="fnEwusS1PkgE0bV8mLGVXJWDBItTh67SY897I40J">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="" class="form-label" style="text-transform: uppercase !important">{{__('location.locataire')}}</label>
                                <div class="dropdown">
                                    <button class=" btn form-control dropdown-toggle  border-secondary "  type="button" id="titreLocataire" data-bs-toggle="dropdown" aria-expanded="false">
                                      {{__('location.locataire')}}
                                    </button>
                                    <ul class="dropdown-menu dropLocataire" aria-labelledby="titreLocataire" >
                                      <div class="p-1">
                                        <input type="text" class="form-control rechecheLocataire" placeholder="recherche">
                                      </div>
                                      <li><a class="dropdown-item nouveaux" href="#"><i class="fa fa-plus-circle"></i>{{__('location.nouvLoc')}}</a></li>
                                      <li class="bg-dark text-white p-1" style="text-transform: uppercase !important">{{__('location.locataire')}}</li>
                                      @forelse ($locataires as $locataire)
                                        <li class="testeLoc"><a class="dropdown-item  locataireClick pagelocation" id="LocPage" data-id="{{$locataire->id}}" href="#" >{{$locataire->TenantLastName . ' ' . $locataire->TenantFirstName}}</a></li>
                                      @empty
                                        <li class="tsisy"><p href="" class="dropdown-item " disabled>{{__('location.aucunLoc')}}</p></li>
                                      @endforelse
                                      <div class="auc"></div>
                                    </ul>
                                  </div>
                            </div>
                            {{-- <input type="hidden" name="locataire_id" id="locataire_id"> --}}
                            {{-- <div class="col-lg-6">
                                <label for="" class="form-label">LOCATAIRE</label>
                                <select name="locataire_id" id="locataire_id" page="loca_information_generale"
                                    class="form-control">
                                    <option value="" selected hidden>Selectioner le locataire</option>
                                    @foreach ($locataires as $locataire)
                                        <option value="{{ $locataire->id }}">
                                            {{ $locataire->TenantLastName . ' ' . $locataire->TenantFirstName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="col-lg-6">
                                <label for="" class="form-label">{{__('location.typeLoc')}}</label>
                                <input type="text" class="form-control control" name="typelocataire" id="typelocataire">
                                {{-- <select name="typelocataire" class="form-control control" id="typelocataire">
                                    <option value="Autre">Autre</option>
                                    <option value="Sociéte">Sociéte</option>
                                </select> --}}
                                <span  id="error_typelocataire" class="error_typelocataire text-danger"></span>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="" class="form-label">{{__("location.prenom")}} *</label>
                                <input type="hidden" name="locataire_id" id="locataire_id">
                                <input type="text" name="prenom" id="prenom" class="form-control control"
                                    placeholder="" aria-describedby="helpId">
                                <span  id="error_prenom" class="error_modif text-danger"></span>
                            </div>
                            <div class="col-lg-6">
                                <label for="" class="form-label">{{__('location.nom')}} *</label>
                                <input type="text" name="nomlocataire" id="nomlocataire" class="form-control control"
                                    placeholder="" aria-describedby="helpId">
                                <span  id="error_nomlocataire" class="error_nomlocataire text-danger"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="" class="form-label">E-MAIL</label>
                                <input type="email" name="emaillocataire" id="emaillocataire" class="form-control control"
                                    placeholder="" aria-describedby="helpId">
                                <span  id="error_emaillocataire" class="error_emaillocataire text-danger"></span>
                            </div>
                            <div class="col-lg-6">
                                <label for="" class="form-label">MOBILE</label>
                                <input type="tel"  name="mobillocataire" id="mobillocataire" class="form-control control"
                                    placeholder="" aria-describedby="helpId">
                                    <span  id="error_mobillocataire" class="error_mobillocataire text-danger"></span>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" style="border:1px solid #f5f5f9"
                        data-bs-dismiss="modal">Annuler</button>
                    <button type="button" id="ajout" class="btn btn-primary" >{{__('location.enregistrer')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
    integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
{{-- <script src="/js/intlTelInput/intlTelInput.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/js/intlTelInput.min.js"></script>
<script>
    $(document).ready(function() {
        var input = document.querySelector("#mobillocataire");
        var iti = window.intlTelInput(input, {
            separateDialCode: true,
            initialCountry: "fr",
            preferredCountries: ["fr", "us", "gb"],
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/js/utils.js",
        });

        function updateMobileField(phoneNumber) {
            iti.setNumber(phoneNumber);
        }

        $(".locataireClick").on("click", function() {
            var id = $(this).attr('data-id');
            $.ajax({
                type: "GET",
                url: "/getLocataire",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(data) {
                    for (var i = 0; i < data.length; i++) {
                        $('#locataire_id').val(id);
                        $('#prenom').val(data[i].TenantLastName);
                        $('#nomlocataire').val(data[i].TenantFirstName);
                        $('#emaillocataire').val(data[i].TenantEmail);
                        $('#mobillocataire').val(data[i].TenantMobilePhone);

                        // Mettre à jour le champ "mobillocataire" avec le code du pays sélectionné
                        updateMobileField(data[i].TenantMobilePhone);

                        $('#typelocataire').val(data[i].locataireType);
                        $("#typelocataire").attr("readonly", true);
                    }
                }
            });
        });
    });

    $('.dropLocataire').on('click', 'a.dropdown-item', function(e) {
        e.preventDefault();
        var textloc = $(this).text();
        $('#titreLocataire').text(textloc);
        $('.dropLocataire').find('a.dropdown-item').removeClass('active'); // Supprimer la classe active de tous les éléments
        $(this).addClass('active');
        if(textloc == '{{__('location.nouvLoc')}}'){
            $("#prenom").val('');
            $("#prenom").attr("readonly", false)
            $("#nomlocataire").attr("readonly", false)
            $("#emaillocataire").attr("readonly", false)
            $("#typelocataire").attr("readonly", false)
            $("#mobillocataire").attr("readonly", false)
            $("#nomlocataire").val('')
            $("#emaillocataire").val('')
            $("#mobillocataire").val('')
            $("#typelocataire").val('Autre')
            $("#locataire_id").val('')
        }else{
            $(".control").each((index, elem) => {
                    $(elem).removeClass('border')
                    $(elem).removeClass('border-danger')
                    $("#error_" + $(elem).attr('name')).text("")
            });
        }
    });

    $('.rechecheLocataire').on('input', function() {
        var recherche = $(this).val().toLowerCase();
        var resultatTrouve = false;
        $('.tsisy').hide()
        $('.testeLoc').each(function() {
        var texteLi = $(this).text().toLowerCase();
        if (texteLi.includes(recherche)) {
            $(this).show();
            resultatTrouve = true;
            $('.tsisy').hide()
        } else {
            $(this).hide();
            $('.tsisy').hide()
        }


        });

        if (!resultatTrouve) {
        $('.aucun-resultat').remove(); // Supprimer l'élément s'il existe déjà

        if (recherche !== '' && recherche !== 'ajouter nouveaux') {
            $('.auc').append('<li class="aucun-resultat p-3">Aucun résultat pour " '+ recherche + ' " </li>');
        }
        } else {
        $('.aucun-resultat').remove(); // Supprimer l'élément s'il existe déjà
        }
    });
    $("#previous-tab").hide();

    // Récupérer le champ input
    var champInput = document.getElementById("debut");

    // Ajouter un écouteur d'événement pour la saisie dans le champ
    var fin = document.getElementById('fin');
    fin.disabled = true;
    champInput.addEventListener("input", function() {

        // Désactiver le champ après la première saisie
        champInput.disabled = true;
        fin.disabled = false;

    });

    function calculateDifference() {
        var date1 = Date.parse(document.getElementById("debut").value);
        var date2 = Date.parse(document.getElementById("fin").value);
        if (date1 > date2) {
            document.getElementById("dure").value = '.';
            document.getElementById('errorD').innerHTML = "la date de fin doivent etre inferieur au date de debut"
            document.getElementById("fin").classList.add("is-invalid");
            document.getElementById("dure").value = ''
        } else {
            document.getElementById("fin").classList.remove("is-invalid");
            document.getElementById("errorD").innerHTML = "";
            // document.getElementById("fin").classList.add("is-valid");

        }
        var duration = moment.duration(moment(date2).diff(moment(date1)));
        var days = duration.days();
        var months = duration.months();
        var years = duration.years();
        document.getElementById("dure").value = years + ' Ans, ' + months + ' Mois, ' + days + ' Jours';
        $('#finerr').text('')

        function daysInMonth() {
            var date = new Date(document.getElementById('debut').value)
            return formatDate((date).lastDayOfTheMonth());
        }
        Date.prototype.lastDayOfTheMonth = function() {
            return new Date(this.getYear(), this.getMonth() + 1, 0);
        }

        function formatDate(maDate) {
            var moisFr = "" + (maDate.getMonth() + 1);
            if (moisFr.length == 1) {
                moisFr = "0" + moisFr;
                var ladate = new Date();
            }
            // return maDate.getDate()+"/"+moisFr+"/"+ladate.getFullYear();
            return ladate.getFullYear() + "-" + moisFr + "-" + maDate.getDate();

        }
        var dateActuel = daysInMonth()
        document.getElementById('dateActuel').value = dateActuel


        var charge = Number(document.getElementById("LC").value);
        var chargeH = Number(document.getElementById("LHC").value);
        var dateD = Date.parse(document.getElementById("debut").value);
        var dateF = Date.parse(document.getElementById("dateActuel").value);
        var dateFs = document.getElementById("dateActuel").value;
        // console.log(dateActuels)
        // console.log(dateF)
        var dateFes = dateFs.slice(-2);
        var difference = Math.abs(dateF - dateD);
        var differenceInDays = Math.floor(difference / (1000 * 60 * 60 * 24));
        if (dateActuels == dateF || dateF <  dateActuels) {
            var calcuC = ((differenceInDays * charge) / dateFes).toFixed(2);
            var calcuH = ((differenceInDays * chargeH) / dateFes).toFixed(2);
        } else {
            var calcuC = charge;
            var calcuH = chargeH;
        }
        document.getElementById("loyer_HC").value = calcuH
        document.getElementById("charge").value = calcuC
        var summe = (parseFloat(calcuC) + parseFloat(calcuH)).toFixed(2);
        document.getElementById("summe").value = summe
    }


    function daysInMonth() {
        return formatDate(new Date().lastDayOfTheMonth());
        // return formatDate((date).lastDayOfTheMonth());
    }
    Date.prototype.lastDayOfTheMonth = function() {
        return new Date(this.getYear(), this.getMonth() + 1, 0);
    }

    function formatDate(maDate) {
        var moisFr = "" + (maDate.getMonth() + 1);
        if (moisFr.length == 1) {
            moisFr = "0" + moisFr;
            var ladate = new Date();
        }
        var datas = ladate.getFullYear() + "-" + moisFr + "-" + maDate.getDate();
        return Date.parse(datas);
    }
    var dateActuels = daysInMonth();

    function suc(ids, classname) {
        $('#' + ids).removeClass('is-invalid')
        // $('#' + ids).addClass('is-valid')
        $('.' + classname).text(' ')
        // alert(a)
    }

    var setValLog = function () {
        var id = $("#logement_id").val()
        $.ajax({
            type: "GET",
            url: "/getcharge",
            data: {
                id: id
            },
            dataType: "json",
            success: function(data) {
                for (var i = 0; i < data.length; i++) {
                    $('#IdBien').val(data[i].identifiant)
                    $('#LC').val(data[i].charge)
                    $("#LC").attr("readonly", true)
                    $('#LHC').val(data[i].loyer)
                    $("#LHC").attr("readonly", true)
                    // var a = ($('#LC').val(data[i].loyer)).val()
                    // var b = ($('#LHC').val(data[i].charge)).val()
                    var loyer = Number(document.getElementById('LC').value)
                    var charge = Number(document.getElementById('LHC').value)
                    // $("#monSpan").innerHTML "Ma valeur"
                    document.getElementById('LoyerLocation').value = charge
                    document.getElementById('ChargeLocation').value = loyer
                    document.getElementById('chargeT').value = loyer + charge
                        var charge = Number(document.getElementById("LC").value);
                        var chargeH = Number(document.getElementById("LHC").value);
                        var dateD = Date.parse(document.getElementById("debut").value);
                        var dateF = Date.parse(document.getElementById("dateActuel").value);
                        var dateFs = document.getElementById("dateActuel").value;
                        var dateFes = dateFs.slice(-2);
                        var difference = Math.abs(dateF - dateD);
                        var differenceInDays = Math.floor(difference / (1000 * 60 * 60 * 24));
                        if (dateActuels == dateF || dateF <  dateActuels) {
                            var calcuC = ((differenceInDays * charge) / dateFes).toFixed(2);
                            var calcuH = ((differenceInDays * chargeH) / dateFes).toFixed(2);
                        } else {
                            var calcuC = charge;
                            var calcuH = chargeH;
                        }
                        document.getElementById("loyer_HC").value = calcuH
                        document.getElementById("charge").value = calcuC
                        var summe = (parseFloat(calcuC) + parseFloat(calcuH)).toFixed(2);
                        document.getElementById("summe").value = summe
                }
            }
        });
    }
    @if (isset($logement_id))
    setValLog()
    @endif
    // $(".locataireClick").on("click", function() {
    //     var id = $(this).attr('data-id')
    //     $.ajax({
    //         type: "GET",
    //         url: "/getLocataire",
    //         data: {
    //             id: id
    //         },
    //         dataType: "json",
    //         success: function(data) {
    //             for (var i = 0; i < data.length; i++) {
    //                 $('#locataire_id').val(id);
    //                 $('#prenom').val(data[i].TenantLastName);
    //                 $('#nomlocataire').val(data[i].TenantFirstName);
    //                 $('#emaillocataire').val(data[i].TenantEmail);
    //                 $('#mobillocataire').val(data[i].TenantMobilePhone);

    //                 // Désactiver temporairement l'écouteur d'événements "countrychange"
    //                 iti.destroy();
    //                 // Mise à jour du champ "mobillocataire" avec le code du pays sélectionné
    //                 var selectedCountry = iti.getCountryDataByPhoneNumber(data[i].TenantMobilePhone);
    //                 if (selectedCountry) {
    //                     iti.setCountry(selectedCountry.iso2);
    //                 }
    //                 $("#mobillocataire").val('+' + iti.getSelectedCountryData().dialCode);

    //                 // Réactiver l'écouteur d'événements "countrychange"
    //                 iti = window.intlTelInput(input, {
    //                     separateDialCode: true,
    //                     initialCountry: iti.getSelectedCountryData().iso2, // Restaurez le pays par défaut
    //                     preferredCountries: ["fr", "us", "gb"], // Vous pouvez spécifier ici vos pays préférés
    //                     utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/js/utils.js",
    //                 });

    //                 $('#typelocataire').val(data[i].locataireType);
    //                 $("#typelocataire").attr("readonly", true);
    //             }
    //         }
    //     });
    // });

    $("#logement_id").on("change", function() {
        setValLog()
    });
    function isValidEmail(email) {
        // Expression régulière pour valider l'adresse e-mail
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    $('#ajout').on('click', function() {
        var id = $("#locataire_id").val()
        var prenom = $("#prenom").val()
        var nomlocataire = $("#nomlocataire").val()
        var emaillocataire = $("#emaillocataire").val()
        var mobillocataire = $("#mobillocataire").val()
        var iti = window.intlTelInputGlobals.getInstance($("#mobillocataire")[0]);
        var mobillocataire = iti.getNumber();
        var typelocataire = $("#typelocataire").val()
        let isControlValid = [];
        $(".control").each((index, elem) => {
                if (!$(elem).val()) {
                    isControlValid.push(false);
                    $("#error_" + $(elem).attr('name')).text('{{__('location.champObligatoire')}}');
                } else {
                if ($(elem).attr('id') === 'emaillocataire') {
                    const email = $(elem).val();
                    if (!isValidEmail(email)) {
                        isControlValid.push(false);
                        $("#error_" + $(elem).attr('name')).text('{{__('location.textEmailValid')}}');
                    } else {
                        isControlValid.push(true);
                        $("#error_" + $(elem).attr('name')).text("");
                    }
                } else if ($(elem).attr('id') === 'mobillocataire') {
                    const isValidPhoneNumber = iti.isValidNumber(); // Vérifie si le numéro de téléphone est valide pour le pays choisi
                    if (!isValidPhoneNumber) {
                        isControlValid.push(false);
                        $("#error_" + $(elem).attr('name')).text('{{__('location.textTelValid')}}');
                    } else {
                        isControlValid.push(true);
                        $("#error_" + $(elem).attr('name')).text("");
                    }
                } else {
                    isControlValid.push(true);
                    $("#error_" + $(elem).attr('name')).text("");
                }
            }
        });

        if (!isControlValid.includes(false)) {
            $("#Modallocataire").modal('hide');
            $(".control").each((index, elem) => {
                    $(elem).val('')
            });
            $('#titreLocataire').text('Locataire');
            $('.dropLocataire').find('a.dropdown-item').removeClass('active');
            $.ajax({
            url: '/save-tempLoc',
            type: 'get',
            data: {
                id: id,
                prenom: prenom,
                nom: nomlocataire,
                emaillocataire: emaillocataire,
                mobillocataire: mobillocataire,
                typelocataire: typelocataire
            },
            success: function(data) {
                $("#locataire_id").val(data.id)
                $('#card-locataire').hide()
                // console.log(data);
                $("#miampylocataire").append(
                    '<div class="card-body" style="margin-top: 20px;" id="loc_' + data.id +
                    '">\
                        <div class="row align-middle">\
                            <div class="col-md-2 col-sm-12 align-middle ">\
                                <label for="" class="form-label">LOCATAIRES</label>\
                            </div>\
                            <div class="col-md-8 col-sm-12 align-middle" style="border: #3A87AD solid 1px;padding:10px">\
                                <div class="row">\
                                    <div class="col-2 mt-4">\
                                        <span class="badge badge-center bg-primary rounded-pill initiale" style="width:50px;height:50px;margin-left:25px;" id="initial">' +
                    data.nom.substring(0, 1)
                    .toUpperCase() + data.prenom.substring(0, 1).toUpperCase() + '</span>\
                                    </div>\
                                    <div class="col-md-6 col-sm-12">\
                                        <p style="margin-top:12px" class="text-primary">' + data.nom + ' ' + data
                    .prenom + ' </p>\
                                        <p style="margin-top:-18px"><i class="fa-regular fa-envelope"></i>' + " " +
                    ' ' + data.emaillocataire + ' </p>\
                                        <p style="margin-top:-18px"><i class="fa-solid fa-phone-volume"></i>' + " " +
                    ' ' + data.mobillocataire + '</p>\
                                        <p style="margin-top:-18px">' + data.typelocataire + '</p>\
                                    </div>\
                                </div>\
                            </div>\
                            <div class="col-md-2 align-middle ">\
                                <a href="" class="btn btn-outline-danger btn-sm suppressionLoc" id="' + data.id + '" ><i class="fa-solid fa-trash"></i> </a>\
                            </div>\
                        </div>')
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        }

    })
    $(document).on('click', '.suppressionLoc', function(e) {
        e.preventDefault()
        var id = $(this).attr('id');
        var parent = $(this).closest('#loc_' + id)
        parent.remove();
        $('#card-locataire').show()


    });
</script>
