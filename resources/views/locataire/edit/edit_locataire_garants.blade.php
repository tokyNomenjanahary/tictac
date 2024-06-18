<h5 class="card-header">Listes des garants</h5>
<div class="card-body" style="margin-top: 20px;">
    <div class="row align-middle">
        <div class="col-md-1 align-middle ">
            <label for="" class="form-label">GARANTS</label>
        </div>
        <div class="col-md-6 align-middle">
            <a href="" class="btn" data-bs-toggle="modal"
               style="border:1px solid gray;color:blue;background-color:#f5f5f9;" data-bs-target="#modalGarant"
               onmouseup="">
                <i class="fa fa-plus-circle"></i> Ajouter un garant
            </a>
        </div>
        <p style="margin-top: 10px;">Vous pouvez ajouter plusieurs garants si besoin.</p>
    </div>
</div>
<div class="table-responsive text-nowrap">
    <table id="editlocataire" class="table">
        <thead>
        <tr>
            <th>{{__('Prenoms')}}</th>
            <th>{{__('Nom')}}</th>
            <th>{{__('Email')}}</th>
            <th>{{__('Actions')}}</th>
        </tr>
        </thead>
        <tbody class="table-border-bottom-0">
        @forelse($locataire->LocatairesGarants as $LocatairesGarants)
            <tr>
                <td id="tdGarantPrenoms{{$LocatairesGarants->id}}">{{$LocatairesGarants->garantPrenoms}}</td>
                <td id="tdGarantNom{{$LocatairesGarants->id}}">{{$LocatairesGarants->garantNom}}</td>
                <td id="tdGarantEmail{{$LocatairesGarants->id}}">{{$LocatairesGarants->garantEmail}}</td>
                <td>
                    <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="modal"data-bs-target="#modalEdit{{$LocatairesGarants->id}}">{{__('Modifier')}}</button>
                    <button type="button" class="btn btn-danger" onclick="Ondelete({{$LocatairesGarants->id}})"><i class="fa-solid fa-trash" style="color:white;"></i></button>

                </td>
            </tr>
            <!-- Modal edit-->
            <div class="modal fade" id="modalEdit{{$LocatairesGarants->id}}" tabindex="-1" role="dialog"
                 aria-labelledby="modalTitleId"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#FAFAFA;">
                            <h5 class="modal-title" id="modalTitleId">Modification Garant</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <p class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7"><span
                                    class="label m-r-2"
                                    style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">INFORMATION</span>
                            Modification d'un garant</p>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <form id="myForm{{$LocatairesGarants->id}}">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="" class="form-label">PRÉNOM *</label>
                                            <input required type="text" name="garantPrenoms"
                                                   id="garantPrenoms{{$LocatairesGarants->id}}" class="form-control"
                                                   placeholder="" aria-describedby="helpId"
                                                   value="{{$LocatairesGarants->garantPrenoms}}">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="" class="form-label">NOM *</label>
                                            <input required type="text" name="garantNom"
                                                   id="garantNom{{$LocatairesGarants->id}}" class="form-control"
                                                   placeholder="" aria-describedby="helpId"
                                                   value="{{$LocatairesGarants->garantNom}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="" class="form-label">DATE DE NAISSANCE</label>
                                            <input type="date" name="garantDateNaissance"
                                                   id="garantDateNaissance{{$LocatairesGarants->id}}"
                                                   class="form-control"
                                                   placeholder="" aria-describedby="helpId"
                                                   value="{{$LocatairesGarants->garantDateNaissance}}">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="" class="form-label">LIEU DE NAISSANCE</label>
                                            <input type="text" name="garantLieuNaissance"
                                                   id="garantLieuNaissance{{$LocatairesGarants->id}}"
                                                   class="form-control"
                                                   placeholder="" aria-describedby="helpId"
                                                   value="{{$LocatairesGarants->garantLieuNaissance}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="" class="form-label">E-MAIL</label>
                                            <input type="mail" name="garantEmail"
                                                   id="garantEmail{{$LocatairesGarants->id}}" class="form-control"
                                                   placeholder="" aria-describedby="helpId"
                                                   value="{{$LocatairesGarants->garantEmail}}">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="" class="form-label">MOBILE</label>
                                            <input type="number" name="garantMobile"
                                                   id="garantMobile{{$LocatairesGarants->id}}" class="form-control"
                                                   placeholder="" aria-describedby="helpId"
                                                   value="{{$LocatairesGarants->garantMobile}}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn" style="border:1px solid #f5f5f9"
                                                data-bs-dismiss="modal">Annuler
                                        </button>
                                        <button id="submitBtn{{$LocatairesGarants->id}}" class="btn btn-primary"
                                                 aria-label="Close">Sauvegarder
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($LocatairesGarants)
                @push('js')
                    <script>
                        $(document).ready(function () {
                            $('#editlocataire').DataTable();
                            $('#modalEdit{{$LocatairesGarants->id}}').on('shown.bs.modal', function () {
                                $('#myForm{{$LocatairesGarants->id}}').submit(function (e) {
                                    e.preventDefault()
                                    var data = new FormData();
                                    data.append("_token", "{{ csrf_token() }}");
                                    data.append("idGarant", "{{$LocatairesGarants->id}}");
                                    data.append("garantPrenoms", $('#garantPrenoms{{$LocatairesGarants->id}}').val());
                                    data.append("garantNom", $('#garantNom{{$LocatairesGarants->id}}').val());
                                    data.append("garantDateNaissance", $('#garantDateNaissance{{$LocatairesGarants->id}}').val());
                                    data.append("garantLieuNaissance", $('#garantLieuNaissance{{$LocatairesGarants->id}}').val());
                                    data.append("garantEmail", $('#garantEmail{{$LocatairesGarants->id}}').val());
                                    data.append("garantMobile", $('#garantMobile{{$LocatairesGarants->id}}').val());
                                    $.ajax({
                                        url: "{{ route('locataire.info_garants.edit') }}",
                                        type: "POST",
                                        processData: false,
                                        contentType: false,
                                        data: data,
                                        beforeSend: function () {
                                            $('#myLoader').removeClass('d-none')
                                        },
                                        success: function (data) {
                                            $('#myLoader').addClass('d-none')
                                            $('#modalEdit{{$LocatairesGarants->id}}').modal({
                                                // backdrop: 'static',
                                                // keyboard: false
                                            }).modal('hide');
                                            $('#tdGarantPrenoms' + data.locataireGarant.idGarant).text(data.locataireGarant.garantPrenoms)
                                            $('#tdGarantNom' + data.locataireGarant.idGarant).text(data.locataireGarant.garantNom)
                                            $('#tdGarantEmail' + data.locataireGarant.idGarant).text(data.locataireGarant.garantEmail)

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
                                })
                            });
                        })
                        function Ondelete(id)
                            {
                            $('#myLoader').removeClass('d-none')
                            $.ajax({
                            url: "/suppGarantsEditLocataire/"+id,
                            type: 'get',
                            success: function(data) {
                             $('#myLoader').addClass('d-none')
                             location.reload();
                             localStorage.setItem("locataireGarantsAjout", 1);
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(textStatus, errorThrown);
                            }
                        });
                            }
                    </script>
                @endpush
            @endif
        @empty
            <tr class="empty">
                <td class="align-middle text-center" colspan="9">Aucun garant enregistré</td>
            </tr>
        @endforelse

        </tbody>
    </table>
    <div class="card" style="margin-top: 5px">
        <div class="row">
            <div class="col-md-12" style="padding: 15px;">
                <div class="float-end">
                    <button id="precedentGarant" class="btn btn-secondary">{{__('Precedent')}}</button>
                    <button id="suivantGarant" class="btn btn-primary"> {{__('Suivant')}}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Body-->
    <div class="modal fade" id="modalGarant" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#FAFAFA;">
                    <h5 class="modal-title" id="modalTitleId">Nouveau garant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <p class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7"><span
                            class="label m-r-2"
                            style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">INFORMATION</span>
                    Créer un
                    nouveau garant pour le locataire</p>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form id="formLocatairesGarantsModal">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="" class="form-label">PRÉNOM *</label>
                                    <input required type="text" name="garantPrenoms" id="garantPrenoms"
                                           class="form-control"
                                           placeholder="" aria-describedby="helpId">
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">NOM *</label>
                                    <input required type="text" name="garantNom" id="garantNom" class="form-control"
                                           placeholder="" aria-describedby="helpId">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="" class="form-label">DATE DE NAISSANCE</label>
                                    <input type="date" name="garantDateNaissance" id="garantDateNaissance"
                                           class="form-control"
                                           placeholder="" aria-describedby="helpId">
                                    <span class="text-danger" id="garantDateNaissanceErrorMsg"></span>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">LIEU DE NAISSANCE</label>
                                    <input type="text" name="garantLieuNaissance" id="garantLieuNaissance"
                                           class="form-control"
                                           placeholder="" aria-describedby="helpId">
                                    <span class="text-danger" id="garantLieuNaissanceErrorMsg"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="" class="form-label">E-MAIL</label>
                                    <input type="mail" name="garantEmail" id="garantEmail" class="form-control"
                                           placeholder="" aria-describedby="helpId">
                                    <span class="text-danger" id="garantEmailErrorMsg"></span>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">MOBILE</label>
                                    <input type="number" name="garantMobile" id="garantMobile" class="form-control"
                                           placeholder="" aria-describedby="helpId">
                                    <span class="text-danger" id="garantMobileErrorMsg"></span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn" style="border:1px solid #f5f5f9"
                                        data-bs-dismiss="modal">Annuler
                                </button>
                                <button id="submitBtn" class="btn btn-primary" aria-label="Close">Sauvegarder</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script>
        $(document).ready(function () {
            $('#precedentGarant').click(function (e){
                e.preventDefault();
                $('#profile-tab, #complementaire').addClass('active')
                $('#messages-tab, #garants').removeClass('active')
            })
            $('#suivantGarant').click(function (e){
                e.preventDefault();
                $('#messages-tab, #garants').removeClass('active')
                $('#contactUrgence, #urgence').addClass('active')
            })
            $('#formLocatairesGarantsModal').submit(function (e) {
                e.preventDefault();
                var url = window.location.href;
                var info_gen_id = url.split("/").pop();
                var data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                var fields = $(this).find(':input, textarea').not(':button');
                fields.each(function () {
                    data.append(this.id, $(this).val());
                });
                data.append("info_gen_id", info_gen_id);
                $.ajax({
                    url: "{{ route('locataire.info_garants') }}",
                    type: "POST",
                    processData: false,
                    contentType: false,
                    data: data,
                    beforeSend: function () {
                        $('#myLoader').removeClass('d-none')
                    },
                    success: function (data) {
                        $('#myLoader').addClass('d-none')
                        location.reload();
                        localStorage.setItem("locataireGarantsAjout", 1);
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
