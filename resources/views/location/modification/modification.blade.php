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
@section('contenue')
    <style>
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
            header{
                margin:25px auto;
                padding-bottom:50px !important;
                margin-left: 18px !important;
                margin-right: 18px !important;
            }
            .card{
                width: 330px;
                margin-left: -25px;
                box-shadow: none !important;
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
                            <h3><a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>{{__('location.modificationLocation')}}</h3>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active"  style="border:1px solid #f9f9f9;" id="home-tab"
                                data-bs-toggle="tab" data-bs-target="#loca_information_generale" type="button" role="tab"
                                aria-controls="home" aria-selected="true">{{__('location.infoGen')}}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" style="border:1px solid #f9f9f9;" id="profile-tab"
                                data-bs-toggle="tab" data-bs-target="#loca_complementaire" type="button" role="tab"
                                aria-controls="profile" aria-selected="false" >{{__('location.complementaire')}}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" style="border:1px solid #f9f9f9;" id="messages-tab"
                                data-bs-toggle="tab" data-bs-target="#loca_garants" type="button" role="tab"
                                aria-controls="messages" aria-selected="false">{{__('location.garant')}}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" style="border:1px solid #f9f9f9;" id="messages-tab"
                                data-bs-toggle="tab" data-bs-target="#loca_documents" type="button" role="tab"
                                aria-controls="messages" aria-selected="false">{{__('location.document')}}</a>
                        </li>
                        {{-- <li class="nav-item" role="presentation">
                            <a class="nav-link" style="border:1px solid #f9f9f9;" id="messages-tab"
                                data-bs-toggle="tab" data-bs-target="#loca_garants" type="button" role="tab"
                                aria-controls="messages" aria-selected="false">GARANTS</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" style="border:1px solid #f9f9f9;" id="messages-tab"
                                data-bs-toggle="tab" data-bs-target="#loca_quittance" type="button" role="tab"
                                aria-controls="messages" aria-selected="false">QUITTANCES</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" style="border:1px solid #f9f9f9;" id="messages-tab"
                                data-bs-toggle="tab" data-bs-target="#loca_assurance" type="button" role="tab"
                                aria-controls="messages" aria-selected="false">ASSURANCE</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" style="border:1px solid #f9f9f9;" id="messages-tab"
                                data-bs-toggle="tab" data-bs-target="#loca_documents" type="button" role="tab"
                                aria-controls="messages" aria-selected="false">DOCUMENTS</a>
                        </li> --}}
                    </ul>

                    <!-- Tab panes -->

                    <div class="tab-content" id="contenue">
                        {{-- information géneral --}}

                        <div class="tab-pane active" id="loca_information_generale" role="tabpanel" aria-labelledby="home-tab">
                            @include('location.modification.nouveaux_location_info_generale')
                        </div>
                        <div class="tab-pane " id="loca_complementaire" role="tabpanel" aria-labelledby="profile-tab">
                            @include('location.modification.modification_info_comple')
                        </div>
                        <div class="tab-pane" id="loca_quittance" role="tabpanel" aria-labelledby="messages-tab">
                            {{-- @include('location.nouveaux_location_quittance') --}}
                        </div>
                        <div class="tab-pane " id="loca_garants" role="tabpanel" aria-labelledby="messages-tab">
                            @include('location.modification.modification_garant')
                        </div>
                        <div class="tab-pane" id="loca_assurance" role="tabpanel" aria-labelledby="messages-tab">
                            {{-- @include('location.nouveaux_location_assurance') --}}
                        </div>
                        <div class="tab-pane " id="loca_documents" role="tabpanel" aria-labelledby="messages-tab">
                            @include('location.nouveaux_location_documents')
                        </div>
                        {{-- @include('location.buttonSubmit') --}}

                    </div>
                </div>
            </div>
        </header>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@endsection
