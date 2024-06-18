@extends('proprietaire.index')
<style>
    p{
        line-height: 5px;
    }
   .icon-size{
        font-size: 4rem !important;
    }

    th {
        color: blue !important;
        font-size: 10px !important;
        font-weight: bold !important;
    }
    td{
        font-size:13px;
    }
    p{
        line-height: 8px;
        font-size: 12px;
    }

    .circle{
        width: 60px;
        height: 60px;
        border-radius: 50%;
        color: white;
        background-color: #697a8d;
    }

    .show-mobile {
        /* position: sticky;
        top: 0; */
        z-index: 1;
        background-color: #f2f2f2;
    }

   @media screen and (max-width: 920px)  {
        .map {
            display: none;
        }
        .nouv{
            margin-top: -05px;
        }
        #flush-collapseOne{
            display: none;
        }
        #phone{
            display: block;
        }
        .act:hover .badge{
            color: black;
        }
        .mobile-size{
            width: 100% !important;
            margin-bottom: 1rem;
            text-align: center;
        }

        .item-status-mobile{
            width: 100% !important;
            text-align: center !important;
        }

        .item-mobile{
            width: 100% !important;
            display: flex !important;
            justify-content: center !important;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }

        .item-mobile-nouveau{
            text-align: right !important;
            margin-top: -100px !important;
            margin-bottom: 100px;
            display: flex;
            justify-content: end;
        }

        th:not(.show-mobile) {
            display: none;
        }

        td:not(.show-mobile) {
            display: none;
        }

        .pop-up-mobile{
            display: block;
        }

        .pop-up-mobile-no{
            display: none;
        }

        .top-mobile{
            margin-top: -50px !important;
        }

    }

    @media screen and (min-width: 920px) {
        .pop-up-mobile{
            display: none;
        }

        .pop-up-mobile-no{
            display: block;
        }
    }

</style>
@push("styles")
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush
@section('contenue')
<div class="container">
    <div class="row" style="margin-top: 30px;">
        <div class="col-lg-4">
            <h3 class="page-header page-header-top">{{ $propertyType }}</h3>
        </div>
        <div class="col-lg-4 item-mobile">
            <ul class="nav nav-tabs" id="myTab" role="tablist" style="border: none;">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" style="border:1px solid #EBF2E2;color:blue;" id="home-tab"
                        data-bs-toggle="tab" data-bs-target="#liste_logement_actifs" type="button" role="tab"
                        aria-controls="home" aria-selected="true"><i class="fa fa-check m-r-5"></i> Actifs <span
                            class="badge bg-primary">{{ count($listLogement) }}</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" style="border:1px solid #f9f9f9;color:blue;" id="profile-tab"
                        data-bs-toggle="tab" data-bs-target="#liste_logement_archive" type="button" role="tab"
                        aria-controls="profile" aria-selected="false"> <i class="fa fa-folder-open m-r-5"></i> Archives
                        <span class="badge bg-primary">{{ count($listLogementArchive) }}</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="col-lg-4 item-mobile-nouveau" style="text-align: right;">
            <div class="dropdown">
                <a class="btn btn-primary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Nouveau logement
                </a>
                <ul class="dropdown-menu">
                    <li><a href="{{route('proprietaire.nouveaux')}}" class="dropdown-item"><i class="fa fa-plus-circle"></i> Nouveau logement</a></li>
                    <li><a href="{{route('proprietaire.importerBien')}}" class="dropdown-item"><i class="fa fa-cloud-upload me-1"></i> Importer</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="mt-4 mb-4">
        <div class="row top-mobile">
            <div class="col-4 mobile-size">
                <div class="card">
                    <div class="row p-3">
                        <div class="col-3">
                            <i class='bx bx-home icon-size'></i>
                        </div>
                        <div class="col-9">
                            <h6>Loué</h6>
                            <h3>{{ $countLogementLocation }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 mobile-size">
                <div class="card">
                    <div class="row p-3">
                        <div class="col-3">
                            <i class='bx bx-dollar-circle icon-size'></i>
                        </div>
                        <div class="col-9">
                            <h6>Valeur locative</h6>
                            <h3>{{ number_format($valeurLocative,2,'.',' ') }} $</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 mobile-size">
                <div class="card">
                    <div class="row p-3">
                        <div class="col-3">
                            <i class='bx bx-building-house icon-size'></i>
                        </div>
                        <div class="col-9">
                            <h6>Valeur des actifs</h6>
                            <h3>{{ number_format($valeurActifs,2,'.',' ') }} $</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8" style="background-color:white;margin-top:15px; padding:15px 15px 15px 15px;">

        <p class="mt-2">Utilisez les options pour filtrer</p>

        <div class="col-lg-3 col-md-12 mb-3">
            <div class="form-group">
                <div class="form-contoll">
                    <select id="en_location" class="form-select" name="">
                        <option value="all">Avec ou sans location<span class="caret"></span></option>
                        <option value="non_loue">Non loué</option>
                        <option value="log_loue">Loué</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-12">
            <div class="form-group">
                <div class="form-contoll">
                    <input id="search" class="form-control" name="" placeholder="Recherche">
                </div>
            </div>
        </div>
    </div>

    <header class="bg-white " style="margin:25px auto;">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="row">
                @if($notif = Session::get('succes'))
                    <div class="alert alert-success" role="alert" style="margin-top: 15px;">
                        {{ $notif }} <br>
                        @if ($url = Session::get('urlAds'))
                            <span style="color: #697a8d">Voici le lien de votre annonce : {{ $url }} <br></span>
                            <a href="{{ $url }}">Ou cliquez ici pour voir l'annonce</a>
                        @endif
                    </div>
                @endif
                @if($idLogement = Session::get('idLogement'))
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Logement ajouté</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div><hr>
                                <div class="modal-body">
                                    Votre logement a été bien enregistré. Est-ce que vous voulez publier une annonce sur votre logement?
                                    Si Oui, votre annonce est geré automatiquement à l'aide de votre donnée que vous avez entré.
                                </div><hr>
                                <div class="modal-footer">
                                    <a href="{{route('proprietaire.genererAnonceLog',$idLogement)}}" type="button" class="btn btn-primary">Oui</a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="tab-content" style="padding-left: 12px;">
                    <div id="liste_logement_actifs" class="tab-pane active" role="tabpanel" aria-labelledby="messages-tab">
                        @include('proprietaire.liste_logement_actifs')
                    </div>
                    <div id="liste_logement_archive" class="tab-pane" role="tabpanel" aria-labelledby="messages-tab">
                        @include('proprietaire.liste_logement_archive')
                    </div>
                </div>
            </div>
        </div>

      <!-- Modal Body -->
      <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
      <div class="modal fade" id="modalId" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
         <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="modalTitleId">Avertisemment</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                  Vous ne pouvez pas suprimer cette logement car il y a encore de contrat en cours !
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Fermer</button>
               </div>
            </div>
         </div>
      </div>

      <!-- Modal trigger button -->


      <!-- Modal Body -->
      <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
      <div class="modal fade" id="modal2" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
         <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="modalTitleId">Suppression</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                  Voulez vous vraiment suprimer cette logement?
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">Annuler</button>
                  <a href="#" type="button" class="btn btn-danger ">Suprimmer</a>
               </div>
            </div>
         </div>
      </div>
   </header>
 </div>


@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
    $(document).ready(function () {
        table_logement_actif = $('#table_logement_actif').DataTable(
            {
                searching: true,
                language: {
                    //si la version en ligne n'existe plus, le fichier est dans
                    // /resource/lang/datatable-logement-fr-FR.json
                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json',
                },
            }
        );

        table_logement_archive = $('#table_logement_archive').DataTable(
            {
                searching: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json',
                },
            }
        );
        $('#table_logement_actif tbody').on('click', '.chambre', function() {
            var button = $(this);
            var data_id = button.attr('data-id');
            var row = button.closest('tr');

            if (row.next().hasClass('details-row')) {
                // Masquer les détails
                row.next().remove();
                button.html('<i class="fa fa-plus"></i>');
            } else {
                // Afficher les détails
                $.ajax({
                    type: "GET",
                    url: "{{ route('proprietaire.chambreInLogement')}}",
                    data:{ data_id: data_id},
                    success: function(data) {
                        var content = '';
                        $.each(data, function(index){
                            content += '<tr>\
                                            <td class="show-mobile"></td>\
                                            <td><div class="d-flex justify-content-center align-items-center circle" >CH</div></td>\
                                            <td class="show-mobile">\
                                                <a href="/detail/'+data[index].id+'">\
                                                    <i >'+data[index].identifiant+'</i><br>\
                                                    <p class="map" style="font-size: 10px"><i class="bx bx-map"></i>'+data[index].adresse+'</p>\
                                                </a>\
                                            </td>\
                                            <td>Chambre</td>\
                                            <td>'+data[index].batiment+'</td>\
                                            <td>'+data[index].superficie+'</td>\
                                            <td>Locataire</td>\
                                            <td>'+data[index].loyer+'</td>\
                                            <td></td>\
                                            <td class="show-mobile">\
                                                <div class="dropdown">\
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">\
                                                        <i class="bx bx-dots-horizontal-rounded"></i>\
                                                    </button>\
                                                    <div class="dropdown-menu">\
                                                        <a class="dropdown-item" href="/detail/'+data[index].id+'">\
                                                            <i class="bx bxl-figma me-1"></i>Detail\
                                                        </a>\
                                                        <a class="dropdown-item" href="/editLogement/'+data[index].id+'">\
                                                            <i class="bx bx-edit-alt me-1"></i>Modifier\
                                                        </a>\
                                                        <a class="dropdown-item" href="/deleteLogementEnfant/'+data[index].id+'">\
                                                            <i class="bx bx-x-circle me-1"></i>Enlever ce chambre\
                                                        </a>\
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#deleteLogement'+data[index].id+'">\
                                                            <i class="bx bx-trash me-1"></i> Delete\
                                                        </a>\
                                                    </div>\
                                                </div>\
                                            </td>\
                                            <td></td>\
                                            <div class="modal fade" id="deleteLogement'+data[index].id+'" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">\
                                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">\
                                                    <div class="modal-content">\
                                                        <div class="modal-header">\
                                                            <h5 class="modal-title" id="modalTitleId">Suppression</h5>\
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>\
                                                        </div>\
                                                        <div class="modal-body">\
                                                            Voulez vous vraiment suprimer cette logement ?\
                                                        </div>\
                                                        <div class="modal-footer">\
                                                            <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">Annuler</button>\
                                                            <a href="/deleteLogement/'+data[index].id+'" type="button" class="btn btn-danger ">Suprimmer</a>\
                                                        </div>\
                                                    </div>\
                                                </div>\
                                            </div>\
                                        </tr>'
                        })
                        var data = table_logement_actif.row(row).data();
                        var detailsAccordion = $('<div class="accordion"><div class="accordion-item"><table class="table table-hover">'+content+'</table></div></div>');
                        var detailsCell = $('<td class="show-mobile" colspan="' + table_logement_actif.columns()[0].length + '"></td>').append(detailsAccordion);
                        var detailsRow = $('<tr class="details-row"></tr>').append(detailsCell);
                        row.after(detailsRow);
                        button.html('<i class="fa fa-minus"></i>');
                    },
                    error: function(data){
                        var content = '';
                            content += '<tr style="text-align: center;">\
                                            <td class="show-mobile">'+data.responseJSON.message+'. <a href="/addLogementEnfant/'+data.responseJSON.idLogementMere+'" class="btn btn-secondary btn-sm">Ajouter une chambre</a></td>\
                                        </tr>';

                        var data = table_logement_actif.row(row).data();
                        var detailsAccordion = $('<div class="accordion"><div class="accordion-item"><table class="table table-hover">'+content+'</table></div></div>');
                        var detailsCell = $('<td class="show-mobile" colspan="' + table_logement_actif.columns()[0].length + '"></td>').append(detailsAccordion);
                        var detailsRow = $('<tr class="details-row"></tr>').append(detailsCell);
                        row.after(detailsRow);
                        button.html('<i class="fa fa-minus"></i>');
                        // toastr.error(data.responseJSON.message);
                    }

                })

            }
        });

        $('#table_logement_archive tbody').on('click', '.chambreArchive', function() {
            var button = $(this);
            var data_id = button.attr('data-id');
            var row = button.closest('tr');

            if (row.next().hasClass('details-row')) {
                // Masquer les détails
                row.next().remove();
                button.html('<i class="fa fa-plus"></i>');
            } else {
                // Afficher les détails
                $.ajax({
                    type: "GET",
                    url: "{{ route('proprietaire.chambreInLogement')}}",
                    data:{ data_id: data_id},
                    success: function(data) {
                        var content = '';
                        $.each(data, function(index){
                            content += '<tr>\
                                            <td></td>\
                                            <td><div class="d-flex justify-content-center align-items-center circle" >CH</div></td>\
                                            <td class="show-mobile">\
                                                <a href="/detail/'+data[index].id+'">\
                                                    <i >'+data[index].identifiant+'</i><br>\
                                                    <p class="map" style="font-size: 10px"><i class="bx bx-map"></i>'+data[index].adresse+'</p>\
                                                </a>\
                                            </td>\
                                            <td>Chambre</td>\
                                            <td>'+data[index].batiment+'</td>\
                                            <td>'+data[index].superficie+'</td>\
                                            <td>Locataire</td>\
                                            <td>'+data[index].loyer+'</td>\
                                            <td></td>\
                                            <td class="show-mobile">\
                                                <div class="dropdown">\
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">\
                                                        <i class="bx bx-dots-horizontal-rounded"></i>\
                                                    </button>\
                                                    <div class="dropdown-menu">\
                                                        <a class="dropdown-item" href="/detail/'+data[index].id+'">\
                                                            <i class="bx bxl-figma me-1"></i>Detail\
                                                        </a>\
                                                        <a class="dropdown-item" href="/editLogement/'+data[index].id+'">\
                                                            <i class="bx bx-edit-alt me-1"></i>Modifier\
                                                        </a>\
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#deleteLogement'+data[index].id+'">\
                                                            <i class="bx bx-trash me-1"></i> Delete\
                                                        </a>\
                                                    </div>\
                                                </div>\
                                            </td>\
                                            <td></td>\
                                            <div class="modal fade" id="deleteLogement'+data[index].id+'" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">\
                                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">\
                                                    <div class="modal-content">\
                                                        <div class="modal-header">\
                                                            <h5 class="modal-title" id="modalTitleId">Suppression</h5>\
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>\
                                                        </div>\
                                                        <div class="modal-body">\
                                                            Voulez vous vraiment suprimer cette logement ?\
                                                        </div>\
                                                        <div class="modal-footer">\
                                                            <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">Annuler</button>\
                                                            <a href="/deleteLogement/'+data[index].id+'" type="button" class="btn btn-danger ">Suprimmer</a>\
                                                        </div>\
                                                    </div>\
                                                </div>\
                                            </div>\
                                        </tr>'
                        })
                        var data = table_logement_actif.row(row).data();
                        var detailsAccordion = $('<div class="accordion"><div class="accordion-item"><table class="table table-hover">'+content+'</table></div></div>');
                        var detailsCell = $('<td colspan="' + table_logement_actif.columns()[0].length + '"></td>').append(detailsAccordion);
                        var detailsRow = $('<tr class="details-row"></tr>').append(detailsCell);
                        row.after(detailsRow);
                        button.html('<i class="fa fa-minus"></i>');
                    },
                    error: function(data){
                        console.log(data)
                        toastr.error(data.responseJSON.message);
                    }

                })

            }
        });

        // $('.chambre').click(function(){
        //     var id = $(this).attr('data-id');
        //     $.ajax({
        //             type: "GET",
        //             url: "{{ route('proprietaire.chambreInLogement')}}",
        //             data:{ data_id: id},
        //             success: function(data) {
        //                 toastr.success(data.message);
        //             },
        //             error: function(data){
        //                 toastr.error(data.message);
        //             }

        //         })
        // })


        $('#type_logement').on('change', function() {
            var selectedValue = this.value;
            if (selectedValue === 'all') {
                table_logement_actif.search('').columns().search('').draw();
            } else {
                table_logement_actif.column(3).search(selectedValue).draw();
            }
        });

        $('#type_logement').on('change', function() {
            var selectedValue = this.value;
            if (selectedValue === 'all') {
                table_logement_archive.search('').columns().search('').draw();
            } else {
                table_logement_archive.column(3).search(selectedValue).draw();
            }
        });

        $('#en_location').on('change', function() {
            var selectedValue = this.value;
            // var name_locataire = $('.name_locataire').val();
            if (selectedValue === 'all') {
                table_logement_actif.search('').columns().search('').draw();
            }
            if (selectedValue == 'non_loue'){
                table_logement_actif.column(6).search('non_loue').draw();
            }
            if (selectedValue == 'log_loue'){
                table_logement_actif.column(6).search('log_loue').draw();
            }
        });

        $('#en_location').on('change', function() {
            var selectedValue = this.value;
            // var name_locataire = $('.name_locataire').val();
            if (selectedValue === 'all') {
                table_logement_archive.search('').columns().search('').draw();
            }
            if (selectedValue == 'non_loue'){
                table_logement_archive.column(6).search('non_loue').draw();
            }
            if (selectedValue == 'log_loue'){
                table_logement_archive.column(6).search('log_loue').draw();
            }
        });

        $('#search').on('keyup', function() {
            table_logement_actif.search(this.value).draw();
        });
        $('#rec').on('keyup', function() {
            table_logement_actif.search(this.value).draw();
            // alert('hjkds')
        });


        $('#search').on('keyup', function() {
            table_logement_archive.search(this.value).draw();
        });
    });

        $(".check_logement").change(function(){
            if ($(".check_logement").is(':checked')) {
                $("#section_btn_logement").show();
            } else {
                $("#section_btn_logement").hide();
            }
        });

        /*** Tous selectionnée ***/
        $("#check_all").change( function(){
            if ($("#check_all").is(':checked')) {
                if ($(".check_logement").length != 0) {
                    $(".check_logement").prop("checked", true);
                    $("#section_btn_logement").show();
                }
            } else {
                $(".check_logement").prop("checked", false);
                $("#section_btn_logement").hide();
            }
        });

        /*** Tous selectionnées si tous check_logement sont cochés ***/
        $(".check_logement").change(function() {
            if($(".check_logement:checked").length == $(".check_logement").length) {
                $("#check_all").prop("checked", true);
            } else {
                $("#check_all").prop("checked", false);
            }
        });

        $(".check_logement_archive").change(function(){
            if ($(".check_logement_archive").is(':checked')) {
                $("#section_btn_logement_archive").show();
            } else {
                $("#section_btn_logement_archive").hide();
            }
        });

        /*** Tous selectionnée sur archive ***/
        $("#check_all_archive").change( function(){
            if ($("#check_all_archive").is(':checked')) {
                if ($(".check_logement_archive").length != 0) {
                    $(".check_logement_archive").prop("checked", true);
                    $("#section_btn_logement_archive").show();
                }
            } else {
                $(".check_logement_archive").prop("checked", false);
                $("#section_btn_logement_archive").hide();
            }
        });

        /*** Tous selectionnées si tous check_logement_archive sont cochés ***/
        $(".check_logement_archive").change(function() {
            if($(".check_logement_archive:checked").length == $(".check_logement_archive").length) {
                $("#check_all_archive").prop("checked", true);
            } else {
                $("#check_all_archive").prop("checked", false);
            }
        });
        /*** tableau pour stocker la liste des id de logement ***/
        var logement_id = [];

        /*** Function qui verifie s'il y a de case à coché ***/
        function verif_check_logement(check) {
            check.each(function(){
                logement_id.push($(this).attr('id'));
            });
            if (logement_id.length > 0) {
                return logement_id;
            } else {
                return false;
            }
        }

        /*** Function pour l'exportation des informations de logement ***/
        function sendData(data_ids) {
            data_id = new Set(data_ids);
            var tableauIdLogement = btoa(Array.from(data_id));
            if(tableauIdLogement){
                location.href = '/downloadExelLogement/'+tableauIdLogement;
                // $.ajax({
                //     type: "GET",
                //     url: "{{ route('proprietaire.exportInfoLogement','idLogement')}}",
                //     data: {data_id : tableauIdLogement},
                //     success: function (data) {
                //         var strDataId = JSON.stringify(data.data_id);

                //         location.href = '/downloadExelLogement/'+strDataId;
                //         toastr.success('Votre telechagement est terminé')
                //     },
                //     error: function (data) {
                //         toastr.error(data.message);
                //     }
                // })
            }
        }

        /*** Function qui archive ou desarchive le logement ***/
        function archive_desarchive_logement(data_id) {
            data_id = btoa(data_id);

            if(data_id){
                location.href = '/archive_multiple_logement/'+data_id;
                // $.ajax({
                //     type: "GET",
                //     url: "{{ route('proprietaire.archive_multiple_logement','idLogement')}}",
                //     data: {data_id : data_id},
                //     success: function (data) {
                //         location.href = '/logement';
                //         toastr.success(data.message);
                //     },
                //     error: function (data) {
                //         toastr.error(data.message);
                //     }
                // })
            }
        }

        /*** Function pour la suppression multiple ***/
        function delete_logement(data_id) {
            // data_id = btoa(data_id);
            if(data_id){
                $.ajax({
                    type: "GET",
                    url: "{{ route('proprietaire.delete_logement_multiple')}}",
                    data:{ data_id: data_id},
                    success: function(data) {
                        toastr.success(data.message);
                        setTimeout(function(){
                            location.reload();
                        }, 500);

                    },
                    error: function(data){
                        toastr.error(data.responseJSON.message);
                    }

                })
            }
        }

        /*** exportation des informations de logement ***/
        $('#export_info_logement').click(function(){
            let data_id = verif_check_logement($('.check_logement:checked'));
            if(data_id){
                sendData(data_id);
            }else{
                toastr.error("veuillez selectionner un logement s'il vous plait!");
            }
        });

        /*** exportation des informations de logement sur archive ***/
        $('#export_info_logement_archive').click(function(){
            let data_id = verif_check_logement($('.check_logement_archive:checked'));
            if (data_id) {
                sendData(data_id);
            }else{
                toastr.error("veuillez selectionner un logement s'il vous plait!");
            }
        });

        /*** archive des logements ***/
        $('#archive_multiple_logement').click(function(){
            let data_id = verif_check_logement($('.check_logement:checked'));
            if (data_id) {
                archive_desarchive_logement(data_id)
            }else{
                toastr.error("veuillez selectionner un logement s'il vous plait!");
            }
        });

        /*** desarchive multiple de logement ***/
        $('#desarchive_multiple_logement').click(function(){
            let data_id = verif_check_logement($('.check_logement_archive:checked'));
            if (data_id) {
                archive_desarchive_logement(data_id)
            }else{
                toastr.error("veuillez selectionner un logement s'il vous plait!");
            }
        })

        /*** Suppression multiple de logement actifs ***/
        $('#delete_logement_multiple').click(function(){
            let data_id = verif_check_logement($('.check_logement:checked'));
            if (data_id) {
                $("#deleteLogementMultiple").hide();
                delete_logement(data_id)
            }else{
                toastr.error("veuillez selectionner un logement s'il vous plait!");
            }
        })

        /*** Suppression multiple de logement archivé ***/
        $('#delete_logement_multiple_archive').click(function(){
            let data_id = verif_check_logement($('.check_logement_archive:checked'));
            if (data_id) {
                delete_logement(data_id)
            }else{
                toastr.error("veuillez selectionner un logement s'il vous plait!");
            }
        })
    </script>
@endpush
@endsection
