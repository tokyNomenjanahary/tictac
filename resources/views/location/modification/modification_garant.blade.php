@if(count($garants) > 0)
<div class="card" style="margin-top: 5px">
    <div class="card-header"
        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
        Garant
    </div>

    <form action="{{route('modification.garant')}}" method="POST">
        @csrf
    @forelse ($garants as $garant)
        <div id="" class="p-3">
            <div class="row align-middle mt-3">
                <div class="col-md-2 align-middle ">
                    <label for="" class="form-label">Garants</label>
                </div>

                <div class="col-md-8 align-middle" style="border: #3A87AD solid 1px;padding:10px">
                    <input type="hidden" name="" value="" id="modlocataire_id">
                    <p style="" id="garantNom_{{$garant->id}}" class="text-primary">{{ $garant->nom . ' ' . $garant->prenom }}</p>
                    <p style="margin-top:-12px" id="garantDate_{{$garant->id}}">{{ $garant->date_naissance . ' à ' . $garant->lieu }}</p>
                    <p style="margin-top:-12px" id="garantEmail_{{$garant->id}}">{{ $garant->email }}</p>
                    <p style="margin-top:-12px" id="garantMobil_{{$garant->id}}">{{ $garant->mobil }}</p>
                    <input type="hidden" id="inputid_{{$garant->id}}" name="id[]" value="{{ $garant->id }}">
                    <input type="hidden" id="inputNom_{{$garant->id}}" name="nom[]" value="{{ $garant->nom }}">
                    <input type="hidden" id="inputPrenom_{{$garant->id}}" name="prenom[]" value="{{ $garant->prenom }}">
                    <input type="hidden" id="inputDate_{{$garant->id}}" name="date_naissance[]" value="{{ $garant->date_naissance }}">
                    <input type="hidden" id="inputLieu_{{$garant->id}}" name="lieu[]" value="{{ $garant->lieu }}">
                    <input type="hidden" id="inputEmail_{{$garant->id}}" name="email[]" value="{{ $garant->email }}">
                    <input type="hidden" id="inputMobil_{{$garant->id}}" name="mobil[]" value="{{ $garant->mobil }}">
                </div>
                <div class="col-md-2 align-middle ">
                    <a href="" class="btn btn-primary text-center"
                        onclick="garant('{{$garant->id}}','{{ $garant->email }}','{{ $garant->mobil }}','{{ $garant->nom }}','{{ $garant->prenom }}','{{ $garant->date_naissance }}','{{ $garant->lieu }}')"
                        style="width: 20px" data-bs-toggle="modal" data-bs-target="#garant_{{$garant->id}}_modal"><i
                            class="fa fa-pencil me-1"></i> </a>
                </div>
            </div>
        </div>
        <div class="modal fade" id="garant_{{$garant->id}}_modal"  tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#FAFAFA;">
                        <h5 class="modal-title" id="modalTitleId">Modification garant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="" class="form-label">NOM *</label>
                                    <input type="text" name="" id="nomgarant_{{$garant->id}}" class="form-control"
                                        placeholder="" aria-describedby="helpId">
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">PRÉNOM *</label>
                                    <input type="text" name="" id="prenomgarant_{{$garant->id}}" class="form-control"
                                        placeholder="" aria-describedby="helpId">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="" class="form-label">Date de naissance</label>
                                    <input type="date" name="" id="datenaissance_{{$garant->id}}" class="form-control"
                                        placeholder="" aria-describedby="helpId">
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">Lieu de naissance</label>
                                    <input type="text" name="" id="lieu_{{$garant->id}}" class="form-control"
                                        placeholder="" aria-describedby="helpId">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="" class="form-label">E-MAIL</label>
                                    <input type="mail" name="" id="emailgarant_{{$garant->id}}" class="form-control"
                                        placeholder="" aria-describedby="helpId">
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">MOBILE</label>
                                    <input type="text" name="" id="mobilgarant_{{$garant->id}}" class="form-control"
                                        placeholder="" aria-describedby="helpId">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" style="border:1px solid #f5f5f9"
                            data-bs-dismiss="modal">Annuler</button>
                        <button type="button" id="modifiergar" class="btn btn-primary tay" data-bs-dismiss="modal"
                            aria-label="Close" data-id="{{$garant->id}}">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>
    @empty

    @endforelse

</div>
<div class="card" style="margin-top: 5px">
    <div class="row">
        <div class="col-md-12" style="padding: 15px;">
            <div class="float-end">
                <a href="" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary"> Sauvegarder </button>
            </div>
        </div>
    </div>
</div>
@else
<form action="{{route('garant')}}" method="POST">
    @csrf
    <div class="card" style="margin-top: 5px">
        <div class="card-header"
            style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
            Garants
        </div>

        <div id="miampy">
        </div>

        <div class="card-body" style="margin-top: 20px;">
            <div class="row align-middle">
                <div class="col-md-1 align-middle ">
                    <label for="" class="form-label">GARANTS</label>
                </div>
                <div class="col-md-6 align-middle">
                    <a href="" class="btn" data-bs-toggle="modal"
                        style="border:1px solid gray;color:blue;background-color:#f5f5f9;" data-bs-target="#modalId"
                        onmouseup="">
                        <i class="fa fa-plus-circle"></i> Ajouter un garant
                    </a>
                </div>
                <p style="margin-top: 10px;">Vous pouvez ajouter plusieurs garants si besoin. Ce contact sera
                    sauvegardé
                    dans la rubrique carnet.</p>
            </div>
        </div>

        <!-- Modal Body-->
        <div class="modal fade" id="modalId" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
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
                        nouveau garant pour cette location</p>

                    <div class="modal-body">
                        <div class="container-fluid" id="nouv">
                            <input type="hidden" name="location_id" value="{{$location->id}}">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="" class="form-label">PRÉNOM *</label>
                                        <input type="text" id="prenoms" class="form-control"
                                            placeholder="" aria-describedby="helpId">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="" class="form-label">NOM *</label>
                                        <input type="text"  id="nom" class="form-control"
                                            placeholder="" aria-describedby="helpId">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="" class="form-label">DATE DE NAISSANCE</label>
                                        <input type="date"  id="date" class="form-control"
                                            placeholder="" aria-describedby="helpId">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="" class="form-label">LIEU DE NAISSANCE</label>
                                        <input type="text"  id="lieu" class="form-control"
                                            placeholder="" aria-describedby="helpId">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="" class="form-label">E-MAIL</label>
                                        <input type="mail"  id="email" class="form-control"
                                            placeholder="" aria-describedby="helpId">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="" class="form-label">MOBILE</label>
                                        <input type="text"  id="mobil" class="form-control"
                                            placeholder="" aria-describedby="helpId">
                                    </div>
                                </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" style="border:1px solid #f5f5f9"
                            data-bs-dismiss="modal">Annuler</button>

                        <button id="submitBtn" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card" style="margin-top: 5px">
        <div class="row">
            <div class="col-md-12" style="padding: 15px;">
                <div class="float-end">
                    <a href="" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary"> Sauvegarder </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endif





<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
    integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $("#submitBtn").prop("disabled", true); // Désactive le bouton par défaut

    // // Vérifie si tous les champs de formulaire sont remplis lorsque le contenu d'un champ est modifié
    $("#nouv .form-control").on("input", function(){
        if ($("#prenoms").val() && $("#nom").val() && $("#date").val() && $("#lieu").val() && isValidEmail($("#email").val()) && $("#mobil").val()) {
            $("#submitBtn").prop("disabled", false); // Désactive le bouton par défaut
        } else {
            $("#submitBtn").prop("disabled", true); // Désactive le bouton par défaut
        }
    });

    function isValidEmail(email) {
        // Expression régulière pour valider une adresse email
        var emailRegex = /\S+@\S+\.\S+/;
        return emailRegex.test(email);
    }
    $("#submitBtn").click(function(e) {
        e.preventDefault()
        var prenoms =  $("#prenoms").val()
        var nom     =  $("#nom").val()
        var date    =  $("#date").val()
        var lieu    =  $("#lieu").val()
        var email   =  $("#email").val()
        var mobil   =  $("#mobil").val()
        // console.log(a)
        $.ajax({
            url: '/save-tempGar',
            type: 'get',
            data: {
                prenoms : prenoms,
                nom : nom,
                date : date,
                lieu : lieu,
                email : email,
                mobil : mobil
            },
            success: function(data) {
                console.log(data);
                $("#miampy").append('<div class="card-body" style="margin-top: 20px;">\
                        <div class="row align-middle">\
                            <div class="col-md-2 align-middle ">\
                                <label for="" class="form-label">GARANTS</label>\
                            </div>\
                            <div class="col-md-8 align-middle" style="border: #3A87AD solid 1px;padding:10px">\
                                <p style="margin-top:12px">'+ data.nom + ' ' + data.prenoms +' </p>\
                                <p style="margin-top:-18px">'+ data.date + ' à ' + data.lieu +'</p>\
                                <p style="margin-top:-18px">'+ data.email +'</p>\
                                <p style="margin-top:-18px">'+ data.mobil +'</p>\
                            </div>\
                            <input type="hidden" name="noms[]" class="nom" value="'+ data.nom +'">\
                            <input type="hidden" name="prenoms[]" value="' + data.prenoms +'">\
                            <input type="hidden" name="date_naissance[]" value="'+ data.date + '">\
                            <input type="hidden" name="lieus[]" value="' + data.lieu +'">\
                            <input type="hidden" name="emails[]" value="'+ data.email +'">\
                            <input type="hidden" name="mobils[]" value="'+ data.mobil +'">\
                            <div class="col-md-2 align-middle ">\
                                <a href="" class="btn btn-outline-danger btn-sm" ><i class="fa-solid fa-trash"></i> </a>\
                            </div>\
                        </div>')
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    });

    function garant(id,email, mobil, nom, prenom, date, lieu) {
        document.getElementById('nomgarant_'+ id).value = nom;
        document.getElementById('prenomgarant_'+ id).value = prenom;
        document.getElementById('emailgarant_'+ id).value = email;
        document.getElementById('mobilgarant_'+ id).value = mobil;
        document.getElementById('datenaissance_'+ id).value = date;
        document.getElementById('lieu_'+ id).value = lieu;
    }
    $('.tay').on('click', function(e) {
        e.preventDefault()
        var id = $(this).attr('data-id');
        $('#garantNom_' + id).text($('#nomgarant_' + id).val() + ' ' + $('#prenomgarant_' + id).val());
        $('#garantDate_' + id).text($('#datenaissance_' + id).val() + ' à ' + $('#lieu_' + id).val());
        $('#garantEmail_' + id).text($('#emailgarant_' + id).val());
        $('#garantMobil_' + id).text($('#mobilgarant_' + id).val());
        $('#inputid_' + id).val(id);
        $('#inputNom_' + id).val($('#nomgarant_' + id).val());
        $('#inputPrenom_' + id).val($('#prenomgarant_' + id).val());
        $('#inputDate_' + id).val($('#datenaissance_' + id).val());
        $('#inputLieu_' + id).val($('#lieu_' + id).val());
        $('#inputEmail_' + id).val($('#emailgarant_' + id).val());
        $('#inputMobil_' + id).val($('#mobilgarant_' + id).val());
    })
</script>
