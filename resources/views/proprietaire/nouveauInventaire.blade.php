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
            .card{
                width: 330px;
                margin-left: -25px;
            }
            .navbar-nav-right {
                flex-basis: auto !important;
            }
        }


    </style>
    <div class="p-12">
        <header class="bg-white shadow" style="margin:25px auto;margin-left:25px;margin-right: 25px">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="row">
                    <div class="col-md-12 p-3">
                        <div class="float-start">
                            <h3><a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>Nouvel inventaire</h3>
                        </div>
                    </div>
                </div>
                    <div class="row">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation" id="generale">
                                <a class="nav-link active gen"  style="border:1px solid #f9f9f9;" id="home-tab"
                                    data-bs-toggle="tab" data-bs-target="#loca_information_generale" type="button" role="tab"
                                    aria-controls="home" aria-selected="true">INFORMATION GENERALES</a>
                            </li>
                            <li class="nav-item" role="presentation" id="complementaire">
                                <a class="nav-link" style="border:1px solid #f9f9f9;" id="profile-tab"
                                    data-bs-toggle="tab" data-bs-target="#loca_complementaire" type="button" role="tab"
                                    aria-controls="profile" aria-selected="false" >PIECE 1</a>
                            </li>
                            <li class="nav-item" role="presentation" id="garant">
                                <a class="nav-link" style="border:1px solid #f9f9f9;" id="messages-tab"
                                    data-bs-toggle="tab" data-bs-target="#loca_garants" type="button" role="tab"
                                    aria-controls="messages" aria-selected="false">NOUVELLE PIECE</a>
                            </li>

                            <li class="nav-item" role="presentation" id="document">
                                <a class="nav-link" style="border:1px solid #f9f9f9;" id="document-tab"
                                    data-bs-toggle="tab" data-bs-target="#loca_documents" type="button" role="tab"
                                    aria-controls="messages" aria-selected="false">OBSERVATIONS</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <form  id="inventaire_global" method="POST">
                        @csrf
                        <div class="tab-content" id="contenue">
                            <div class="tab-pane active" id="loca_information_generale" role="tabpanel" aria-labelledby="home-tab">
                                @include('proprietaire.nouveaux_inventaire_info_generale')
                            </div>
                            <div class="tab-pane " id="loca_complementaire" role="tabpanel" aria-labelledby="profile-tab">
                                @include('proprietaire.nouveaux_inventaire_info_generale')
                            </div>
                            <div class="tab-pane " id="loca_garants" role="tabpanel" aria-labelledby="messages-tab">
                                @include('proprietaire.nouveaux_inventaire_info_generale')
                            </div>
                            <div class="tab-pane " id="loca_documents" role="tabpanel" aria-labelledby="document-tab">
                                @include('proprietaire.nouveaux_inventaire_info_generale')
                            </div>
                            @include('proprietaire.inventaireSubmit')
                            </form>
                        </div>
                    </div>
            </div>
        </header>
    </div>

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
            //boutton suivnat precedent
        let activeTab = $('.nav-tabs > .nav-item > .active');
        let activeTabId = activeTab.attr('id');
        if (activeTabId == "home-tab"){
            $("#precedent").hide();
        }
    
    
        $('.nav-link').on('click', function () {
            // Mettre à jour l'affichage des boutons en fonction de l'onglet actif
            let activeTab = $('.nav-tabs > .nav-item > .active');
            let activeTabId = activeTab.attr('id');
            if (activeTabId === "home-tab") {
            $("#precedent").hide();
            $("#suivant").show();
            } else if (activeTabId === "document-tab") {
            $("#precedent").show();
            $("#suivant").hide();
            } else {
            $("#precedent").show();
            $("#suivant").show();
            }
      });
    
    
        $('#suivant').click(function () {
            let currentTab = $('.nav-tabs > .nav-item > .active');
            let nextTab = $(currentTab).parent().next().find('.nav-link');
    
            if (nextTab.length) {
                nextTab.tab('show');
            }
            let activeTab = $('.nav-tabs > .nav-item > .active');
            let activeTabId = activeTab.attr('id');
            if(activeTabId == "profile-tab" ){
                $("#precedent").show();
            }
            if(activeTabId == "document-tab" ){
                $("#suivant").hide();
            }
        });
    
    
        $('#precedent').click(function (e) {
            e.preventDefault()
            let activeTabe = $('.nav-tabs > .nav-item > .active');
            let activeTabIde = activeTabe.attr('id');
            if(activeTabIde == "profile-tab"){
                $("#precedent").hide();
                $("#suivant").show();
            }
            if(activeTabIde == "document-tab"){
                $("#suivant").show();
            }
    
            let currentTab = $('.nav-tabs > .nav-item > .active');
            let previousTab = $(currentTab).parent().prev().find('.nav-link');
            if (previousTab.length) {
                    previousTab.tab('show');
                }
            });
         // fin boutton suivnat precedent
         $( "#submiter" ).click(function(e) {
            e.preventDefault()
            $("#myLoader").removeClass("d-none")
            //info generale
            let bien = $('#bien').val()
            let location = $('#location').val()
            let identifiant = $('#identifiant').val()
            let formData = new FormData()
            formData.append("bien", bien);
            formData.append("location", location);
            formData.append("identifiant", identifiant);

             $.ajax({
                type: "POST",
                url : "sauvegarderI",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(data) {
                    if ($.isEmptyObject(data.errors)) {
                        window.location = "{{ route('inventaire.index') }}"
                    } else {
                        ErrorMsg(data.errors)
                        $("#myLoader").addClass("d-none")
                    }
                }
            });
            function ErrorMsg(msg) {
                var keys = Object.keys(msg);
                keys.forEach(function(key) {
                    $('#' + key).addClass('is-invalid');
                    $('#' + key).parent().find('.invalid-feedback').remove(); // Supprimer tous les messages d'erreur existants
                    $('#' + key).parent().append('<div class="invalid-feedback">' + msg[key][0] + '</div>');
                });
            }
        });
         });

    </script>

@endsection
