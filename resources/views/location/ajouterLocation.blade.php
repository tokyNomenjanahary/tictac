@extends('proprietaire.index')

<style>
    .nav-link.active {
        border-bottom: 3px solid #4C8DCB !important;
    }

    hr {
        color: blue;
        width: 2px;
    }

</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

@section('contenue')
    <style>
        .form-label{
            color: rgb(108, 106, 106) !important;
        }
        p{
            color: rgb(108, 106, 106) !important;
        }
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

        .nav-tabs .nav-item .nav-link:not(.active) {
            background-color: rgb(250, 250, 250);
        }
        .nav-tabs .nav-item .nav-link.active  {
            border-top: 3px solid blue !important;
            border-bottom: 3px solid white !important;
        }
        .nav-tabs .nav-item .nav-link   {
            color: blue !important;
            font-size: 13px !important;

        }
        a{
            text-decoration: none !important;
        }
        @media only screen and (max-width: 600px) {
            #garant{
                display: none;
            }
            li{
                width: 50%;
            }

            .navbar-nav-right {
                flex-basis: auto !important;
            }
            .card{
                box-shadow: none !important;
                /* margin-left:-35px; */
            }
            .initiale{
                display: none;
            }
            .form-mobil{
                padding-left: 0px;
                padding-right: 0px;
            }
        }



    </style>
    <div class="p-12">
        <header class="bg-white shadow " style="margin:25px auto;margin-left:25px;margin-right: 25px;padding-bottom:50px !important;">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="row">
                    <div class="col-md-12 p-3">
                        <div class="float-start">
                            <h3><a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>{{__('location.nouveaux')}}</h3>
                        </div>
                    </div>
                </div>
                @if( $count_logement < 1)
                    <div class="row" >
                        <div class="card" >
                            <div class="card-header"
                                style="">
                                <h5>Vous n'avez pas de <b>Biens</b></h5>
                            </div>
                            <div class="card-body mt-0" style="margin-top:-30px;">
                                <a href="{{route('proprietaire.nouveaux')}}" class="btn btn-primary">Creér un Bien</a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation" id="generale">
                                <a class="nav-link active gen"  style="border:1px solid #f9f9f9;" id="home-tab"
                                    data-bs-toggle="tab" data-bs-target="#loca_information_generale" type="button" role="tab"
                                    aria-controls="home" aria-selected="true">{{__('location.infoGen')}}</a>
                            </li>
                            <li class="nav-item" role="presentation" id="complementaire">
                                <a class="nav-link" style="border:1px solid #f9f9f9;" id="profile-tab"
                                    data-bs-toggle="tab" data-bs-target="#loca_complementaire" type="button" role="tab"
                                    aria-controls="profile" aria-selected="false" >{{__('location.complementaire')}}</a>
                            </li>
                            <li class="nav-item" role="presentation" id="garant">
                                <a class="nav-link" style="border:1px solid #f9f9f9;" id="messages-tab"
                                    data-bs-toggle="tab" data-bs-target="#loca_garants" type="button" role="tab"
                                    aria-controls="messages" aria-selected="false">{{__('location.garant')}}</a>
                            </li>

                            <li class="nav-item" role="presentation" id="document"">
                                <a class="nav-link" style="border:1px solid #f9f9f9;" id="document-tab"
                                    data-bs-toggle="tab" data-bs-target="#loca_documents" type="button" role="tab"
                                    aria-controls="messages" aria-selected="false">{{__('location.document')}}</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <form  id="formulaire_global" method="POST" class="form-mobil">
                        @csrf
                        <div class="tab-content" id="contenue" style="padding: 0 !important;" >
                            <div class="tab-pane active"  id="loca_information_generale" role="tabpanel" aria-labelledby="home-tab">
                                @include('location.nouveaux_location_info_generale')
                            </div>
                            <div class="tab-pane " id="loca_complementaire" role="tabpanel" aria-labelledby="profile-tab">
                                @include('location.nouveaux_location_info_complemantaire')
                            </div>
                            <div class="tab-pane " id="loca_garants" role="tabpanel" aria-labelledby="messages-tab">
                                @include('location.nouveaux_location_garants')
                            </div>
                            <div class="tab-pane " id="loca_documents" role="tabpanel" aria-labelledby="document-tab">
                                @include('location.nouveaux_location_documents')
                            </div>
                            @include('location.buttonSubmit')
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </header>
    </div>


    @push("plugin")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <script>
    $(document).ready(function () {
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[type="hidden"]').attr('value')
                }
        });
        $('input[type="number"]').on('input', function() {
            // console.log('teste');
            var value = parseFloat($(this).val());
            if (value < 0  ) {
            $(this).addClass('is-invalid');
            $(this).next('.error-message').remove();
            $(this).after('<span class="error-message text-danger" style="font-size:13px;">Veuillez saisir un nombre positif</span>');
            } else if (value === '') {
            value = 0;
            } else {
            $(this).removeClass('is-invalid');
            $(this).next('.error-message').remove();
            }
        });

        $( "#lasa" ).click(function(e) {
            e.preventDefault()
            $("#myLoader").removeClass("d-none")
            //info generale
            let formData = new FormData()
            let logement_id = $('#logement_id').val()
            let type_location_id = $('#type_location_id').val()
            let identifiant = $('#identifiant').val()
            let debut = $('#debut').val()
            let IdBien = $('#IdBien').val()
            let fin = $('#fin').val()
            let dure = $('#dure').val()
            dateActuel = $('#dateActuel').val()
            let summe = $('#summe').val()
            let renouvellement = $('#renouvellement').val()
            let type_payment_id = $('#type_payment_id').val()
            let mode_payment_id = $('#mode_payment_id').val()
            let date_payment = $('#date_payment').val()
            let loyer_HC = $('#loyer_HC').val()
            let LoyerLocation = $('#LoyerLocation').val()
            let ChargeLocation = $('#ChargeLocation').val()
            let charge = $('#charge').val()
            let garantie = $('#garantie').val()
            let allocation = $('#allocation').val()
            let locataire_id = $('#locataire_id').val()


            //info complementaire
            let montant = $('#montant').val()
            let description = $('#description').val()
            let conditions = $('#conditions').val()
            let commentaires = $('#commentaires').val()
            let montant_locataire = $('#montant_locataire').val()
            let description_locataire = $('#description_locataire').val()


            //garant
            var nom = [];
            $("input[name='nom[]']").each(function() {
                nom.push($(this).val());
            });

            var prenom = []
            $("input[name='prenom[]']").each(function() {
                prenom.push($(this).val());
            });

            var date_naissance = []
            $("input[name='date_naissance[]']").each(function() {
                date_naissance.push($(this).val());
            });

            var lieu = []
            $("input[name='lieu[]']").each(function() {
                lieu.push($(this).val());
            });

            var email = []
            $("input[name='email[]']").each(function() {
                email.push($(this).val());
            });

            var mobil = []
            $("input[name='mobil[]']").each(function() {
                mobil.push($(this).val());
            });
            for (let i = 0; i < nom.length; i++) {
                formData.append('nom[]', nom[i])
            }
            for (let i = 0; i < prenom.length; i++) {
                formData.append('prenom[]', prenom[i])
            }
            for (let i = 0; i < date_naissance.length; i++) {
                formData.append('date_naissance[]', date_naissance[i])
            }
            for (let i = 0; i < lieu.length; i++) {
                formData.append('lieu[]', lieu[i])
            }
            for (let i = 0; i < email.length; i++) {
                formData.append('email[]', email[i])
            }
            for (let i = 0; i < mobil.length; i++) {
                formData.append('mobil[]', mobil[i])
            }
            formData.append("logement_id", logement_id);
            formData.append("type_location_id", type_location_id);
            formData.append("identifiant", identifiant);
            formData.append("debut", debut);
            formData.append("fin", fin);
            formData.append("dateActuel", dateActuel);
            formData.append("dure", dure);
            formData.append("summe", summe);
            formData.append("IdBien", IdBien);
            formData.append("renouvellement", renouvellement);
            formData.append("type_payment_id", type_payment_id);
            formData.append("mode_payment_id", mode_payment_id);
            formData.append("date_payment", date_payment);
            formData.append("loyer_HC", loyer_HC);
            formData.append("charge", charge);
            formData.append("LoyerLocation", LoyerLocation);
            formData.append("ChargeLocation", ChargeLocation);
            formData.append("garantie", garantie);
            formData.append("allocation", allocation);
            formData.append("locataire_id", locataire_id);

            formData.append("montant", montant);
            formData.append("description", description);
            formData.append("conditions", conditions);
            formData.append("commentaires", commentaires);
            formData.append("montant_locataire", montant_locataire);
            formData.append("description_locataire", description_locataire);

            if (plugin) {
                for (let index = 0; index < plugin.initialPreviewConfig.length; index++) {
                    if (plugin.initialPreviewConfig[index].id != "0") {
                    formData.append('location_doc[]', plugin.initialPreviewConfig[index].id);
                    }
                }
            }

            // console.log(nom)
            // formData.append("nom", nom);
            // formData.append("prenom[]", prenom);
            // formData.append("date_naissance[]", date_naissance);
            // formData.append("lieu[]", lieu);
            // formData.append("email[]", email);
            // formData.append("mobil[]", mobil);



            $.ajax({
                type: "POST",
                url : "/enregistrementL",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function (data) {
                    if ($.isEmptyObject(data.errors)) {
                        @if (Auth::user()->need_guide)
                        window.location = "{{ redirect()->route('proprietaire.bureau')->getTargetUrl() }}";
                        @else
                        window.location = "{{ redirect()->route('location.index')->getTargetUrl() }}";
                        @endif
                    }else{
                        printErrorMsg(data.errors)
                        $("#myLoader").addClass("d-none")
                    }
                }
            });


            function printErrorMsg(msg){
                var paris = []
                $.each(msg, function (i, value) {
                    // console.log(i)
                    $('.'+i+'_err').text('{{__('location.champObligatoire')}}')
                    // $('#'+i).addClass('is-invalid')

                    paris.push(i)
                    if(i == "locataire_id"){
                        $('#locerror').text('{{__('location.champObligatoire')}}')
                    }

                });
                $('html, body').animate({
                        scrollTop: $("#" + paris[0]).offset().top
                }, 500);
                $("#" + paris[0]).focus()
                var par = paris[0]
                var parentDivClass = $('#'+par).attr("page");
                var c = '[data-bs-target=' +'"' + '#'+ parentDivClass +'"' + ']'
                $('#myTab li a').removeClass('active')
                $(c).addClass('active')
                $('#contenue div').removeClass('active')
                $('#'+parentDivClass).addClass('active')
                $("#next-tab").show();
                $("#previous-tab").hide();

            }
        });
    });
    </script>
    @endpush

@endsection
