<h5 class="card-header">Liste des contacts d’urgence</h5>
<div class="card-body" style="margin-top: 20px;">
    <div class="row align-middle">
        <div class="col-md-1 align-middle ">
            <label for="" class="form-label">Contact d'urgence</label>
        </div>
        <div class="col-md-6 align-middle">
            <a href="" class="btn" data-bs-toggle="modal"
               style="border:1px solid gray;color:blue;background-color:#f5f5f9;" data-bs-target="#modalUrgence"
               onmouseup="">
                <i class="fa fa-plus-circle"></i> {{__('Ajouter un contact d\'urgence')}}
            </a>
        </div>
        <p style="margin-top: 10px;">{{__('Vous pouvez ajouter plusieurs contacts d\'urgence si besoin.')}}</p>
    </div>
</div>
<div class="table-responsive text-nowrap">
    <table id="editlocataireUrgence" class="table">
        <thead>
        <tr>
            <th>{{__('Prenoms')}}</th>
            <th>{{__('Nom')}}</th>
            <th>{{__('Email')}}</th>
            <th>{{__('Actions')}}</th>
        </tr>
        </thead>
        <tbody class="table-border-bottom-0">
        @forelse($locataire->LocataireUrgence as $LocataireUrgence)
            <tr>
                <td id="tdUrgencePrenoms{{$LocataireUrgence->id}}">{{$LocataireUrgence->urgencePrenoms}}</td>
                <td id="tdUrgenceNom{{$LocataireUrgence->id}}">{{$LocataireUrgence->urgenceNom}}</td>
                <td id="tdUrgenceEmail{{$LocataireUrgence->id}}">{{$LocataireUrgence->urgenceEmail}}</td>
                <td>
                    <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="modal"data-bs-target="#modalEditUrgence{{$LocataireUrgence->id}}">{{__('Modifier')}}</button>
                    <button type="button" class="btn btn-danger" onclick="OndeleteUrgence({{$LocataireUrgence->id}})"><i class="fa-solid fa-trash" style="color:white;"></i></button>

                </td>
            </tr>
            <!-- Modal edit-->
            <div class="modal fade" id="modalEditUrgence{{$LocataireUrgence->id}}" tabindex="-1" role="dialog"
                 aria-labelledby="modalTitleId"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#FAFAFA;">
                            <h5 class="modal-title" id="modalTitleId">{{__('Modification contact d\'urgence')}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <p class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7"><span
                                    class="label m-r-2"
                                    style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">{{__('INFORMATION')}}</span>
                            {{__('Modification d\'un contact d\'urgence')}}</p>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <form id="myFormUrgence{{$LocataireUrgence->id}}">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="" class="form-label">PRÉNOM *</label>
                                            <input required type="text" name="garantPrenoms"
                                                   id="urgencePrenoms{{$LocataireUrgence->id}}" class="form-control"
                                                   placeholder="" aria-describedby="helpId"
                                                   value="{{$LocataireUrgence->urgencePrenoms}}">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="" class="form-label">NOM *</label>
                                            <input required type="text" name="urgenceNom"
                                                   id="urgenceNom{{$LocataireUrgence->id}}" class="form-control"
                                                   placeholder="" aria-describedby="helpId"
                                                   value="{{$LocataireUrgence->urgenceNom}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="" class="form-label">DATE DE NAISSANCE</label>
                                            <input type="date" name="urgenceDateNaissance"
                                                   id="urgenceDateNaissance{{$LocataireUrgence->id}}"
                                                   class="form-control"
                                                   placeholder="" aria-describedby="helpId"
                                                   value="{{$LocataireUrgence->urgenceDateNaissance}}">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="" class="form-label">LIEU DE NAISSANCE</label>
                                            <input type="text" name="urgenceLieuNaissance"
                                                   id="urgenceLieuNaissance{{$LocataireUrgence->id}}"
                                                   class="form-control"
                                                   placeholder="" aria-describedby="helpId"
                                                   value="{{$LocataireUrgence->urgenceLieuNaissance}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="" class="form-label">E-MAIL</label>
                                            <input type="mail" name="urgenceEmail"
                                                   id="urgenceEmail{{$LocataireUrgence->id}}" class="form-control"
                                                   placeholder="" aria-describedby="helpId"
                                                   value="{{$LocataireUrgence->urgenceEmail}}">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="" class="form-label">MOBILE</label>
                                            <input type="number" name="urgenceMobile"
                                                   id="urgenceMobile{{$LocataireUrgence->id}}" class="form-control"
                                                   placeholder="" aria-describedby="helpId"
                                                   value="{{$LocataireUrgence->urgenceMobile}}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn" style="border:1px solid #f5f5f9"
                                                data-bs-dismiss="modal">Annuler
                                        </button>
                                        <button id="submitBtn{{$LocataireUrgence->id}}" class="btn btn-primary"
                                                 aria-label="Close">Sauvegarder
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($LocataireUrgence)
                @push('js')
                    <script>
                        $(document).ready(function () {
                            $('#editlocataireUrgence').DataTable();
                            $('#myFormUrgence{{$LocataireUrgence->id}}').submit(function (e) {
                                e.preventDefault()
                                var data = new FormData();
                                data.append("_token", "{{ csrf_token() }}");
                                data.append("idUrgence", "{{$LocataireUrgence->id}}");
                                data.append("urgencePrenoms", $('#urgencePrenoms{{$LocataireUrgence->id}}').val());
                                data.append("urgenceNom", $('#urgenceNom{{$LocataireUrgence->id}}').val());
                                data.append("urgenceDateNaissance", $('#urgenceDateNaissance{{$LocataireUrgence->id}}').val());
                                data.append("urgenceLieuNaissance", $('#urgenceLieuNaissance{{$LocataireUrgence->id}}').val());
                                data.append("urgenceEmail", $('#urgenceEmail{{$LocataireUrgence->id}}').val());
                                data.append("urgenceMobile", $('#urgenceMobile{{$LocataireUrgence->id}}').val());
                                $.ajax({
                                    url: "{{ route('locataire.info_urgence.edit') }}",
                                    type: "POST",
                                    processData: false,
                                    contentType: false,
                                    data: data,
                                    beforeSend: function () {
                                        $('#myLoader').removeClass('d-none')
                                    },
                                    success: function (data) {
                                        $('#myLoader').addClass('d-none')
                                        $('#modalEditUrgence{{$LocataireUrgence->id}}').modal({
                                            // backdrop: 'static',
                                            // keyboard: false
                                        }).modal('hide');

                                        $('#tdUrgencePrenoms' + data.locataireUrgence.idUrgence).text(data.locataireUrgence.urgencePrenoms)
                                        $('#tdUrgenceNom' + data.locataireUrgence.idUrgence).text(data.locataireUrgence.urgenceNom)
                                        $('#tdUrgenceEmail' + data.locataireUrgence.idUrgence).text(data.locataireUrgence.urgenceEmail)

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
                        })
                        function OndeleteUrgence(id)
                            {
                            $('#myLoader').removeClass('d-none')
                            $.ajax({
                            url: "/suppUrgenceEditLocataire/"+id,
                            type: 'get',
                            success: function(data) {
                             $('#myLoader').addClass('d-none')
                             location.reload();
                             localStorage.setItem("locataireUrgenceAjout", 1);
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
                <td class="align-middle text-center" colspan="9">Aucun contact d'urgence enregistré</td>
            </tr>
        @endforelse

        </tbody>
    </table>
    <div class="card" style="margin-top: 5px">
        <div class="row">
            <div class="col-md-12" style="padding: 15px;">
                <div class="float-end">
                    <button id="precedentUrgence" class="btn btn-secondary">{{__('Precedent')}}</button>
                    <a href="{{route('locataire.locataire')}}" id="precedentUrgence" class="btn btn-primary">{{__('Terminer')}}</a>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Body-->
    <div class="modal fade" id="modalUrgence" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#FAFAFA;">
                    <h5 class="modal-title" id="modalTitleId">Nouveau contact d'urgence</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <p class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7"><span
                            class="label m-r-2"
                            style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">{{__('INFORMATION')}}</span>
                    {{__(' Créer un nouveau contact d\'urgence pour le locataire')}}</p>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form id="formLocatairesUrgenceModal">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="" class="form-label">PRÉNOM *</label>
                                    <input required type="text" name="urgencePrenoms" id="urgencePrenoms"
                                           class="form-control"
                                           placeholder="" aria-describedby="helpId">
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">NOM *</label>
                                    <input required type="text" name="urgenceNom" id="urgenceNom" class="form-control"
                                           placeholder="" aria-describedby="helpId">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="" class="form-label">DATE DE NAISSANCE</label>
                                    <input type="date" name="urgenceDateNaissance" id="urgenceDateNaissance"
                                           class="form-control"
                                           placeholder="" aria-describedby="helpId">
                                    <span class="text-danger" id="urgenceDateNaissanceErrorMsg"></span>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">LIEU DE NAISSANCE</label>
                                    <input type="text" name="urgenceLieuNaissance" id="urgenceLieuNaissance"
                                           class="form-control"
                                           placeholder="" aria-describedby="helpId">
                                    <span class="text-danger" id="urgenceLieuNaissanceErrorMsg"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="" class="form-label">E-MAIL</label>
                                    <input type="mail" name="urgenceEmail" id="urgenceEmail" class="form-control"
                                           placeholder="" aria-describedby="helpId">
                                    <span class="text-danger" id="urgenceEmailErrorMsg"></span>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">MOBILE</label>
                                    <input type="number" name="urgenceMobile" id="urgenceMobile" class="form-control"
                                           placeholder="" aria-describedby="helpId">
                                    <span class="text-danger" id="urgenceMobileErrorMsg"></span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn" style="border:1px solid #f5f5f9"
                                        data-bs-dismiss="modal">{{__('Annuler')}}
                                </button>
                                <button id="submitBtn" class="btn btn-primary" aria-label="Close">{{__('Sauvegarder')}}</button>
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
            $('#precedentUrgence').click(function (e){
                e.preventDefault();
                $('#messages-tab, #garants').addClass('active')
                $('#contactUrgence, #urgence').removeClass('active')
            })
            $('#formLocatairesUrgenceModal').submit(function (e) {
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
                    url: "{{ route('locataire.info_urgence') }}",
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
                        localStorage.setItem("locataireUrgenceAjout", 1);
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
