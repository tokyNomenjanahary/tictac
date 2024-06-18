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

        .nav-tabs .nav-item .nav-link.active {
            border-top: 3px solid blue !important;
            border-bottom: 3px solid white !important;
        }

        .nav-tabs .nav-item .nav-link {
            color: blue !important;
            font-size: 13px !important;

        }
    </style>
    <div class="p-12">
        <header class="bg-white shadow" style="margin:25px auto;margin-left:25px;margin-right: 25px">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="row">
                    <div class="col-md-12 p-3">
                        <div class="float-start">
                            <h3><a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>
                                {{__(' Modification Locataire')}}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation" id="home-tab-li">
                            <a class="nav-link active" style="border:1px solid #f9f9f9;" id="home-tab"
                               data-bs-toggle="tab" data-bs-target="#information_generale" type="button" role="tab"
                               aria-controls="home" aria-selected="true">INFORMATION GENERALES</a>
                        </li>
                        <li class="nav-item" role="presentation" id="profile-tab-li">
                            <a class="nav-link" style="border:1px solid #f9f9f9;" id="profile-tab"
                               data-bs-toggle="tab" data-bs-target="#complementaire" type="button" role="tab"
                               aria-controls="profile" aria-selected="false">INFORMATION COMPLEMENTAIRES</a>
                        </li>
                        <li class="nav-item" role="presentation" id="messages-tab-li">
                            <a class="nav-link" style="border:1px solid #f9f9f9;" id="messages-tab"
                               data-bs-toggle="tab" data-bs-target="#garants" type="button" role="tab"
                               aria-controls="messages" aria-selected="false">GARANTS</a>
                        </li>
                        <li class="nav-item" role="presentation" id="contactUrgence-li">
                            <a class="nav-link" style="border:1px solid #f9f9f9;" id="contactUrgence"
                               data-bs-toggle="tab" data-bs-target="#urgence" type="button" role="tab"
                               aria-controls="messages" aria-selected="false">CONTACTS D'URGENCES</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        {{-- information géneral --}}
                        <div class="tab-pane active" id="information_generale" role="tabpanel"
                             aria-labelledby="home-tab"
                             style="">
                            @include('locataire.edit.edit_locataire_info_generale')
                        </div>
                        <div class="tab-pane " id="complementaire" role="tabpanel" aria-labelledby="profile-tab">
                            @include('locataire.edit.edit_locataire_info_complemantaire')
                        </div>
                        <div class="tab-pane" id="garants" role="tabpanel" aria-labelledby="messages-tab">
                            @include('locataire.edit.edit_locataire_garants')
                        </div>
                        <div class="tab-pane" id="urgence" role="tabpanel" aria-labelledby="messages-tab">
                            @include('locataire.edit.edit_locataire_urgence')
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @push('js')
        <script>
            $(document).ready(function () {
                if (localStorage.getItem("locataireGarantsAjout")) {
                    $('#home-tab, #information_generale').removeClass('active')
                    $('#messages-tab, #garants').addClass('active')
                }
                localStorage.removeItem("locataireGarantsAjout");

                if (localStorage.getItem("locataireUrgenceAjout")) {
                    $('#home-tab, #information_generale').removeClass('active')
                    $('#contactUrgence, #urgence').addClass('active')
                }
                localStorage.removeItem("locataireUrgenceAjout");
            })
        </script>
    @endpush
@endsection
