<style>
    label {
        margin-top: 12px;
    }

    .form-label{
        color: rgb(108, 106, 106) !important;
    }
    p{
        color: rgb(108, 106, 106) !important;
    }
    input {
        border-radius: none !important;
    }

    .card {
        border-style: none;
        border-radius: 0px;
    }

    .form-control:disabled,
    .form-control[readonly] {
        background-color: #FFFFFF !important;
        opacity: 1;
    }
</style>
    <form action="{{route('modificationlocation',$location->id)}}" method="GET" >
        @csrf
        <div class="card">
            <div class="card-header"
                style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                {{__('location.BienLoue')}}
            </div>
            <div class="card-body">
                <div class="">
                    <label for="logement_id" class="form-label">BIEN </label>
                    <select name="logement_id" id="logement_id" page="loca_information_generale" onchange='suc("logement_id","logement_id_err");' class="form-select" >
                        @foreach ($logements as $logement)
                            <option @if($location->logement_id == $logement->id) selected @endif value="{{$logement->id}}">{{$logement->identifiant}}</option>
                        @endforeach
                    </select>

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
                <div class="">
                    <label for="civilite" class="form-label">{{__('location.type')}} *</label>
                    <select name="type_location_id" id="type_location_id" page="loca_information_generale" onchange='suc("type_location_id","type_location_id_err");' class="form-control @if ($errors->has('type_location_id')) is-invalid @endif" ><span class="caret"></span>
                        @foreach ($type_locations as $type_location)
                            <option @if($location->type_location_id == $type_location->id) selected @endif value="{{$type_location->id}}">{{$type_location->description}}</option>
                        @endforeach
                    </select>

                    <p class="text-danger type_location_id_err"></p>
                </div>
            </div>
            <div class="card-body" style="margin-top: -40px">
                <div class="">
                    <label for="" class="form-label">{{__('location.identifiant')}}</label>
                    <input type="text" name="identifiant" id="identifiant" value="{{$location->identifiant}}" page="loca_information_generale"  class="form-control" placeholder=""
                        aria-describedby="helpId" >


                </div>
            </div>
            <div class="card-body" style="margin-top: -40px">
                <div class="">
                    <label for="" class="form-label">{{__('location.debut')}}*</label>
                    <input type="date" name="debut" id="debut" page="loca_information_generale" value="{{$location->debut}}" class="form-control @if ($errors->has('debut')) is-invalid @endif" placeholder="" aria-describedby="helpId" >

                </div>
            </div>
            <div class="card-body" style="margin-top: -40px">
                <div class="">
                    <label for="" class="form-label">{{__('location.fin')}}</label>
                    <input type="date" name="fin" id="fin" onchange="calculateDifference();" value="{{$location->fin}}"  page="loca_information_generale" class="form-control @if ($errors->has('fin')) is-invalid @endif" placeholder="" aria-describedby="helpId" >


                </div>
            </div>
            {{-- <div class="card-body" style="margin-top: -40px">
                <div class="">
                    <label for="" class="form-label">DURÉE DU BAIL</label>
                    <input type="text" name="dure" id="dure" class="form-control" value="{{$location->dure}}" placeholder=""
                        aria-describedby="helpId" page="loca_information_generale" readonly>
                </div>
            </div> --}}
            <div class="card-body" style="margin-top: -40px">
                <div class="">
                    <label for="civilite" class="form-label">{{__('location.payment')}}  *</label>
                    <select name="type_payment_id" id="type_payment_id" class="form-control" page="loca_information_generale" ><span class="caret"></span>
                        @foreach ($type_payments as $type_payment)
                            <option @if($location->type_payment_id == $type_payment->id) selected @endif value="{{$type_payment->id}}">{{$type_payment->description}}</option>
                        @endforeach
                    </select>
                    <p class="text-danger type_payment_id_err"></p>
                </div>
            </div>
            <div class="card-body" style="margin-top: -40px">
                <div class="">
                    <label for="mode_payement" class="form-label">{{__('location.modePayment')}}</label>
                    <select name="mode_payment_id" id="mode_payment_id" class="form-control" page="loca_information_generale"><span class="caret"></span>
                        @foreach ($mode_payments as $mode_payment)
                            <option  value="{{$mode_payment->id}}" @if($location->mode_payment_id == $mode_payment->id) selected @endif>{{$mode_payment->description}}</option>
                        @endforeach
                    </select>
                    <p class="text-danger mode_payment_id_err"></p>
                    <p>{{__('location.textMod')}}</p>
                </div>
            </div>
            <div class="card-body" style="margin-top: -40px;display:none">
                <div class="">
                    <label for="civilite" class="form-label">DATE DE PAIEMENT</label>
                    <select name="date_payment" id="date_payment"  class="form-control" page="loca_information_generale"><span class="caret"></span>
                    <?php
                    for ($i=1; $i<=31 ; $i++) {
                        echo '<option value="'.$i.'">'.$i. '</option>';
                    }
                        ?>
                    </select>
                    <p>Date de paiement du loyer prévue dans le contrat.</p>
                </div>
            </div>
        </div>
        {{-- <div class="card" style="margin-top: 5px">
            <div class="card-header"
                style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                Loyer
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <label for="" class="form-label">LOYER HORS CHARGES *</label>
                        <input type="number" name="loyer_HC" value="{{$location->loyer_HC}}"  id="loyer_HC" page="loca_information_generale"  class="form-control @if ($errors->has('loyer_HC')) is-invalid @endif" placeholder="€" aria-describedby="helpId" >
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <label for="" class="form-label">CHARGES</label>
                        <input type="number" name="charge" id="charge" page="loca_information_generale" value="{{$location->charge}}" class="form-control" placeholder="€" aria-describedby="helpId" >
                    </div>
                    <p class="text-danger charge_err"></p>
                </div>
            </div>
        </div> --}}
        <div class="card" style="margin-top: 5px">
            <div class="card-header"
                style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                {{__('location.depot')}}
            </div>
            <div class="card-body">
                <div class="">
                    <label for="" class="form-label">{{__('location.depot')}} *</label>
                    <input type="number" name="garantie" value="{{$location->garantie}}" page="loca_information_generale" id="garantie" onchange='suc("garantie","garantie_err");' class="form-control @if ($errors->has('garantie')) is-invalid @endif" placeholder="€" aria-describedby="helpId" >
                </div>
            </div>
        </div>
        <div class="card" style="margin-top: 5px;display:none">
            <div class="card-header"
                style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                Allocations logement
            </div>
            <div class="card-body">
                <div class="">
                    <label for="" class="form-label">PAIEMENT CAF/APL</label>
                    <input type="number" name="allocation" value="{{$location->allocation}}" page="loca_information_generale" id="allocation" onchange='suc("allocation","allocation_err");' class="form-control" placeholder="€"
                        aria-describedby="helpId" >
                    {{-- <input type="hidden" name="locataire_id" page="loca_information_generale" id="locataire_id" value="1"> --}}
                    <p class="text-danger allocation_err"></p>
                    <p>Renseigner ici les aides telles que la CAF, l'APL, qui vous sont versées automatiquement. Le paiement
                        sera généré automatiquement chaque mois comme déjà perçu.</p>
                </div>
            </div>
        </div>
        <div class="card" style="margin-top: 5px">
            <div class="card-header"
                style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                {{__('location.locataire')}}
            </div>
            <div id="miampylocataire" class="p-3">
                <div class="row align-middle mt-3">
                    <div class="col-md-2 align-middle ">
                        <label for="" class="form-label" style="text-transform: uppercase !important">{{__('location.locataire')}}</label>
                    </div>
                    <div class="col-md-8 align-middle" style="border: #3A87AD solid 1px;padding:10px">
                        <input type="hidden" name="" value="{{$location->locataire_id}}" id="modlocataire_id">
                        <p style="" id="modNom" class="text-primary">{{$location->Locataire->TenantFirstName .' '. $location->Locataire->TenantLastName}}</p>
                        <p style="margin-top:-12px" id="modemail"><i class="fa-regular fa-envelope"></i>&nbsp;{{$location->Locataire->TenantEmail}}</p>
                        <p style="margin-top:-12px" id="modmobil"><i class="fa-solid fa-phone-volume"></i>&nbsp;{{$location->Locataire->TenantMobilePhone}}</p>
                        <p style="margin-top:-12px" id="modtype">{{$location->Locataire->locataireType}}</p>
                    </div>
                    <div class="col-md-2 align-middle ">
                        <a href="" class="btn btn-primary text-center" style="width: 20px" data-bs-toggle="modal" onclick='mod("{{$location->Locataire->TenantFirstName}}","{{$location->Locataire->TenantLastName}}","{{$location->Locataire->locataireType}}","{{$location->Locataire->TenantEmail}}","{{$location->Locataire->TenantMobilePhone}}")' data-bs-target="#locataire"><i class="fa fa-pencil me-1"></i> </a>
                    </div>
                </div>
            </div>
            <div class="card" style="margin-top: 5px">
                <div class="row">
                    <div class="col-md-12" style="padding: 15px;">
                        <div class="float-end">
                            <a href="" class="btn btn-secondary">{{__('location.Annuler')}}</a>
                            <button type="submit" class="btn btn-primary"> {{__('location.enregistrer')}} </button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="card-body" style="margin-top: 20px;">
                <div class="row align-middle">
                    <div class="col-md-1 align-middle ">
                        <label for="" class="form-label">LOCATAIRES</label>
                    </div>
                    <div class="col-md-6 align-middle">
                        <a href="" class="btn" data-bs-toggle="modal"
                            style="border:1px solid gray;color:blue;background-color:#f5f5f9;" data-bs-target="#locataire"
                            onmouseup="">
                            <i class="fa fa-plus-circle"></i> Ajouter un locataire
                        </a>
                    </div>
                    <p style="margin-top: 10px;">Cliquez sur le bouton pour ajouter un ou plusieurs locataires ou
                        co-locataires.</p>
                </div>
            </div> --}}
            <!-- Modal Body-->
            <div class="modal fade" id="locataire" tabindex="-1" role="dialog"   aria-labelledby="modalTitleId"
                aria-hidden="true">
                <div class="modal-dialog" role="document" >
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#FAFAFA;">
                            <h5 class="modal-title" id="modalTitleId">{{__('location.modificationLocataire')}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <p class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7"><span
                                class="label m-r-2"
                                style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">INFORMATION</span>
                            {{__('location.modifierLeLocataire')}}.</p>
                        <div class="modal-body">
                            <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="" class="form-label">{{__('location.locataire')}}</label>
                                            <select name="locataire_id" id="locataire_id" class="form-control">
                                                @foreach ($locataires as $locataire)
                                                    <option value="{{$locataire->id}}" @if($location->locataire_id == $locataire->id) selected @endif>{{$locataire->TenantFirstName .' '.$locataire->TenantFirstName}}</option>
                                                @endforeach
                                            </select>
                                            {{-- <input type="text" class="form-control"  id="search">
                                            <div id="search-results" ></div> --}}
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="" class="form-label">{{__('location.typeLoc')}}</label>
                                            <input type="text" class="form-control" name="" id="typelocataire" readonly>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="" class="form-label">{{__("location.prenom")}} *</label>
                                            <input type="text" name="" id="prenom" class="form-control"
                                                placeholder="" aria-describedby="helpId" readonly>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="" class="form-label">{{__('location.nom')}} *</label>
                                            <input type="text" name="" id="nomlocataire" class="form-control"
                                                placeholder="" aria-describedby="helpId" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="" class="form-label">E-MAIL</label>
                                            <input type="mail" name="" id="emaillocataire" class="form-control"
                                                placeholder="" aria-describedby="helpId" readonly>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="" class="form-label">MOBILE</label>
                                            <input type="text" name="" id="mobillocataire" class="form-control"
                                                placeholder="" aria-describedby="helpId" readonly>
                                        </div>
                                    </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" style="border:1px solid #f5f5f9"
                                data-bs-dismiss="modal">{{__('location.Annuler')}}</button>
                            <button type="button" id="ajoutmod" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close">{{__('location.enregistrer')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
    integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function mod(nom,prenom,type,email,mobil){
            $('#nomlocataire').val(nom)
            $('#prenom').val(prenom)
            $('#typelocataire').val(type)
            $('#emaillocataire').val(email)
            $('#mobillocataire').val(mobil)
        }
        $("#locataire_id").on("change", function() {
        var id = $("#locataire_id").val()
        $.ajax({
            type: "GET",
            url: "/getLocataire",
            data: {
                id:id
            },
            dataType: "json",
            success: function (data) {
                for (var i = 0; i < data.length; i++) {
                    $('#prenom').val(data[i].TenantLastName)
                    $("#prenom").attr("readonly", true)
                    $('#nomlocataire').val(data[i].TenantFirstName)
                    $("#nomlocataire").attr("readonly", true)
                    $('#emaillocataire').val(data[i].TenantEmail)
                    $("#emaillocataire").attr("readonly", true)
                    $('#mobillocataire').val(data[i].TenantMobilePhone)
                    $("#mobillocataire").attr("readonly", true)
                    $('#typelocataire').val(data[i].locataireType)
                    $("#typelocataire").attr("readonly", true)

                }
                if ($("#prenom").val() && $("#nomlocataire").val() && $("#emaillocataire").val() && $("#mobillocataire").val() && $("#typelocataire").val()) {
                    $("#ajout").prop("disabled", false); // Désactive le bouton par défaut
                } else {
                    $("#ajout").prop("disabled", true); // Désactive le bouton par défaut
                }
            }
        });

        $('#ajoutmod').on('click', function () {
            $('#modNom').text($('#nomlocataire').val());
            $('#modemail').text($('#emaillocataire').val());
            $('#modmobil').text($('#mobillocataire').val());
            $('#modtype').text($('#typelocataire').val());
            $('#modlocataire_id').text($('#locataire_id').val());
        });
    });
    </script>

