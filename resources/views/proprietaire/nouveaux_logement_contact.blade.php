<style>
    label {
        color: black !important;
        margin-top: 12px;
    }

    input {
        border-radius: none !important;
    }

    .card {
        border-style: none;
        border-radius: 0px;
    }
    .contact
    {
        color: red;
    }
</style>

<div class="card" style="margin-top: 5px">
    <div class="card-header"
        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
        Contacts
    </div>
    <div class="card-body" style="margin-top: 20px;">
        <div class="row align-middle">
            <div class="col-md-1 align-middle ">
                <label for="" class="form-label">CONTACT</label>
            </div>
            <div class="col-md-11 align-middle mb-3">
                <div class="row" id="list_contact">
                    @if (isset($dataContacts))
                        @foreach ($dataContacts as $dataContact)
                            <div class="col-md-6 contact-container" id="container_contact_{{$dataContact->id }}" >
                                <div class="card mb-3 mt-3">
                                    <h5 class="card-header">{{$dataContact->name.' '.$dataContact->first_name}}</h5>
                                    <div class="card-body">
                                        <blockquote class="blockquote mb-0">
                                            <p>{{$dataContact->mobile}}</p>
                                            <p>{{$dataContact->email}}</p>
                                            <footer class="blockquote-footer">
                                            <cite title='{{$dataContact->code_postal.' '.$dataContact->pays}}'>{{$dataContact->adress}} {{$dataContact->ville}}</cite>
                                            </footer>
                                            <center>
                                                <a id="{{$dataContact->id}}" onClick="getDataContact({{$dataContact->id}},'{{$dataContact->unique_id_contact}}','{{$dataContact->name}}','{{$dataContact->first_name}}','{{$dataContact->email}}','{{$dataContact->mobile}}','{{$dataContact->adress}}','{{$dataContact->ville}}','{{$dataContact->code_postal}}','{{$dataContact->pays}}','{{$dataContact->comment}}')" data-bs-toggle="modal" data-bs-target="#modalContactModif" type="button" class="btn btn-sm btn-info rounded-pill btn-modifier">Modifier</a>
                                                <a onClick="deleteContactLogement({{$dataContact->id}})" id="supp_{{$dataContact->id}}" type="button" class="btn btn-sm btn-danger rounded-pill ms-3 btn-supprimer">Supprimer</a>
                                            </center>
                                        </blockquote>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if (isset($listContactsEdits))
                        @foreach ($listContactsEdits as $dataContact)
                            <div class="col-md-6 contact-container" id="container_contact_{{$dataContact->id }}" >
                                <div class="card mb-3 mt-3">
                                    <h5 class="card-header">{{$dataContact->name.' '.$dataContact->first_name}}</h5>
                                    <div class="card-body">
                                        <blockquote class="blockquote mb-0">
                                            <p>{{$dataContact->mobile}}</p>
                                            <p>{{$dataContact->email}}</p>
                                            <footer class="blockquote-footer">
                                            <cite title='{{$dataContact->code_postal.' '.$dataContact->pays}}'>{{$dataContact->adress}} {{$dataContact->ville}}</cite>
                                            </footer>
                                            <center>
                                                <a id="{{$dataContact->id}}" onClick="getDataContact({{$dataContact->id}},'{{$dataContact->unique_id_contact}}','{{$dataContact->name}}','{{$dataContact->first_name}}','{{$dataContact->email}}','{{$dataContact->mobile}}','{{$dataContact->adress}}','{{$dataContact->ville}}','{{$dataContact->code_postal}}','{{$dataContact->pays}}','{{$dataContact->comment}}')" data-bs-toggle="modal" data-bs-target="#modalContactModif" type="button" class="btn btn-sm btn-info rounded-pill btn-modifier">Modifier</a>
                                                <a onClick="deleteContactLogement({{$dataContact->id}})" id="supp_{{$dataContact->id}}" type="button" class="btn btn-sm btn-danger rounded-pill ms-3 btn-supprimer">Supprimer</a>
                                            </center>
                                        </blockquote>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-md-6 align-middle">
                <a href="" class="btn" data-bs-toggle="modal"
                    style="border:1px solid gray;color:blue;background-color:#f5f5f9;" data-bs-target="#modalContact"
                    onmouseup="">
                    <i class="fa fa-plus-circle"></i> Ajouter un contact
                </a>
            </div>
            <p style="margin-top: 10px;">Vous pouvez ajouter plusieurs garants si besoin. Ce contact sera sauvegardé
                dans la rubrique carnet.</p>
        </div>
    </div>
    <!-- Modal Body-->
    <div class="modal fade" id="modalContact" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#FAFAFA;">
                    <h5 class="modal-title" id="modalTitleId">Nouveau contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <p class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7"><span class="label m-r-2"
                        style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">INFORMATION</span>
                    Créer un
                    nouveau, ou choisir un déjà existant.</p>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div id="ajoutContact">
                            @csrf
                            <div class="row">
                                {{-- <input type="text" class="form-control" id="search" placeholder="recherche parmi le contact existant" /> --}}
                                <div class="dropdown">
                                    <button class="btn form-control dropdown-toggle  border-secondary titre"  type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                     Choisir un contact
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="width: 50%">
                                      <div class="p-1">
                                        <input type="text" class="form-control  " id="search"  placeholder="recherche">
                                      </div>
                                      <li><a class="dropdown-item nouveauxC" href="#"><i class="fa fa-plus-circle"></i>&nbsp;Ajouter nouveau</a></li>
                                      <li class="text-white p-1 text-center" style="background-color: blue;">CARNET</li>

                                      <div id="results" class="text-danger" style="padding-left: 5px;"></div>
                                    </ul>
                                  </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="" class="form-label">CATEGORIE</label>
                                    <select name="categorie" id="civilite" class="form-select">
                                        @foreach ($categorieContact as $categorie)
                                            <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">TYPE</label>
                                    <select name="type" id="civilite" class="form-control">
                                        <option value="0">Particulier</option>
                                        <option value="1">Société/autre</option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">NOM *</label>
                                    <input type="text" name="name" id="" class="form-control control" placeholder="" aria-describedby="helpId">
                                    <span style="color: #dc3545;" id="error_name"></span>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">PRÉNOM *</label>
                                    <input type="text" name="first_name" id="" class="form-control" placeholder="" aria-describedby="helpId">
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">E-MAIL</label>
                                    <input type="email" name="email" id="" class="form-control control" placeholder="" aria-describedby="helpId">
                                    <span style="color: #dc3545;" id="error_email"></span>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">MOBILE</label>
                                    <input type="number" name="mobile" id="mobile_contact" class="form-control control" placeholder="" aria-describedby="helpId">
                                    <span style="color: #dc3545;" id="error_mobile"></span>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">ADRESSE</label>
                                    <input type="text" name="adress" id="" class="form-control control" placeholder="" aria-describedby="helpId">
                                    <span style="color: #dc3545;" id="error_adress"></span>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">VILLE</label>
                                    <input type="text" name="ville" id="" class="form-control control" placeholder="" aria-describedby="helpId">
                                    <span style="color: #dc3545;" id="error_ville"></span>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">CODE POSTAL</label>
                                    <input type="mail" name="code_postal" id="" class="form-control" placeholder="" aria-describedby="helpId">
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">PAYS</label>
                                    <input type="text" name="pays" id="" class="form-control" placeholder="" aria-describedby="helpId">
                                </div>
                            </div>
                            <div class="row p-3">
                                <label for="" class="form-label">COMENTAIRE</label>
                                <textarea name="comment" class="form-control" id="" cols="30" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" style="border:1px solid #f5f5f9" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="saveContactLogement">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- end Modal --}}
<div class="card" style="margin-top: 5px">
    <div class="card-body" style="margin-top: -5px">
        <div class="row">
            <div class="col-md-12">
                <div class="float-start">

                </div>
                <div class="float-end">
                    <button type="button" class="btn btn-primary" id="precedentContrat"> Précédent </button>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="modal fade" id="modalContactModif" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#FAFAFA;">
                    <h5 class="modal-title" id="modalTitleId">Modifier contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <p class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7"><span class="label m-r-2"
                    style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">INFORMATION</span>
                Modification de votre contact</p>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div id="modifContact">
                            <div class="row">
                                <input type="hidden" id="id_modif" name="id_modif">
                                <input type="hidden" id="unique_id_modif" name="unique_id_modif">
                                <div class="col-lg-6">
                                    <label for="" class="form-label">CATEGORIE</label>
                                    <select name="categorie_modif" id="categorie_modif" class="form-select">
                                        @foreach ($categorieContact as $categorie)
                                            <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">TYPE</label>
                                    <select name="type_modif" id="type_modif" class="form-select">
                                        <option value="0">Particulier</option>
                                        <option value="1">Société/autre</option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">NOM *</label>
                                    <input type="text" name="name_modif" id="name_modif" class="form-control control_modif" placeholder="" aria-describedby="helpId">
                                    <span style="color: #dc3545;" id="error_name_modif" class="error_modif"></span>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">PRÉNOM *</label>
                                    <input type="text" name="first_name_modif" id="first_name_modif" class="form-control" placeholder="" aria-describedby="helpId">
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">E-MAIL</label>
                                    <input type="email" name="email_modif" id="email_modif" class="form-control control_modif" placeholder="" aria-describedby="helpId">
                                    <span style="color: #dc3545;" id="error_email_modif" class="error_modif"></span>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">MOBILE</label>
                                    <input type="number" name="mobile_modif" id="mobile_modif" class="form-control control_modif" placeholder="" aria-describedby="helpId">
                                    <span style="color: #dc3545;" id="error_mobile_modif" class="error_modif"></span>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">ADRESSE</label>
                                    <input type="mail" name="adress_modif" id="adress_modif" class="form-control control_modif" placeholder="" aria-describedby="helpId">
                                    <span style="color: #dc3545;" id="error_adress_modif" class="error_modif"></span>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">VILLE</label>
                                    <input type="text" name="ville_modif" id="ville_modif" class="form-control control_modif" placeholder="" aria-describedby="helpId">
                                    <span style="color: #dc3545;" id="error_ville_modif" class="error_modif"></span>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">CODE POSTAL</label>
                                    <input type="mail" name="code_postal_modif" id="code_postal_modif" class="form-control" placeholder="" aria-describedby="helpId">
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">PAYS</label>
                                    <input type="text" name="pays_modif" id="pays_modif" class="form-control" placeholder="" aria-describedby="helpId">
                                </div>
                            </div>
                            <div class="row p-3">
                                <label for="" class="form-label">COMENTAIRE</label>
                                <textarea name="comment_modif" class="form-control" id="comment_modif" cols="30" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" style="border:1px solid #f5f5f9" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="saveModifContact">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>
    <input hidden type="text" name="idContact[]" id="dataIDContact">

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $("#precedentContrat").click(function() {
            $('#contrat-tab').tab('show');
        });

  document.getElementById("search").addEventListener("input", function() {
    if(document.getElementById("search").value ==''){
                document.getElementById("results").textContent = "entrer le nom du contact";

            }
  var url = "/searchContact";
  var datas = {
    search: this.value
  };

  $.ajax({
    url: url,
    data: datas,
    method: "get",
    success: function(data) {
      if (data.length > 0) {
        document.getElementById("results").textContent = "";
        var list = document.createElement("ul");
        for (var i = 0; i < data.length; i++) {
      var option = document.createElement("li");
       option.className = "dropdown-item ajoutContact";
       option.value = data[i].id;
       option.textContent = data[i].name;
       option.style="padding:0px;color:blue;font-size:15px;";
      list.appendChild(option);
    }
    document.getElementById("results").appendChild(list);
             $('.ajoutContact').click(function(){
                var id = $(this).attr('value');
                $.ajax({
                    url:"/getsearchContact/"+id,
                    method: "get",
                    success: function(data) {
                        for (var i = 0; i < data.length; i++) {
                            $("input[name=email]").val(data[i].email);
                            $("input[name=name]").val(data[i].name);
                            $("input[name=adress]").val(data[i].adress);
                            $("input[name=pays]").val(data[i].pays);
                            $("input[name=ville]").val(data[i].ville);
                            $("textarea[name=comment]").val(data[i].comment);
                            $("input[name=mobile]").val(data[i].mobile);
                        }
                    }
                });
             });
        } else {
            if(document.getElementById("search").value ==''){
                document.getElementById("results").textContent = "entrer le nom du contact";

            }else{
                document.getElementById("results").textContent = "Pas des resultats pour "+document.getElementById("search").value ;
            }
        }
        }
        });
        });
        $('.nouveauxC').click(function(){
            $("input[name=email]").val('');
            $("input[name=name]").val('');
            $("input[name=adress]").val('');
            $("input[name=pays]").val('');
            $("input[name=ville]").val('');
            $("textarea[name=comment]").val('');
            $("input[name=mobile]").val('');
        });


        /*** Suppression du contact sur le logement  ***/
        function deleteContactLogement(id){
            let url = "/deleteContactLogement/" + id
            $.ajax({
                type: "GET",
                url: url,
                success:function(){
                    toastr.success("Conctact bien supprimer.");
                    // __("logement.delete-contact-success")
                    var child = $("#supp_" + id).closest("#container_contact_" + id).remove()
                }
            })
        }

        function getDataContact(id,unique_id_modif,name,first_name,email,mobile,adress,ville,code_postal,pays,comment){
            $('#id_modif').val(id);
            $('#unique_id_modif').val(unique_id_modif);
            $('#name_modif').val(name);
            $('#first_name_modif').val(first_name);
            $('#email_modif').val(email);
            $('#mobile_modif').val(mobile);
            $('#adress_modif').val(adress);
            $('#ville_modif').val(ville);
            $('#code_postal_modif').val(code_postal);
            $('#pays_modif').val(pays);
            $('#comment_modif').val(comment);
        }

        $(document).ready(function() {
            /*** insertion de la nouvel contact ***/
            const modalContact = new bootstrap.Modal('#modalContact')
            var dataIDContact = []
            $('#saveContactLogement').click(function(event){
                event.preventDefault();
                var categorie = $("select[name=categorie]");
                var type = $("select[name=type]");
                var first_name = $("input[name=first_name]");
                let name = $("input[name=name]");
                var email = $("input[name=email]");
                var mobile = $("input[name=mobile]");
                var adress = $("input[name=adress]");
                var ville = $("input[name=ville]");
                var code_postal = $("input[name=code_postal]");
                var pays = $("input[name=pays]");
                var comment = $("textarea[name=comment]");
                var control = $('.control');
                let isControlValid = [];
                let cle = ["name","mobile","adress","ville"]
                $(".control").each((index, elem) => {
                    if (!$(elem).val()) {
                        isControlValid.push(false);
                        $(elem).addClass('border')
                        $(elem).addClass('border-danger')
                        $("#error_" + $(elem).attr('name')).text("veiller remplir ce champ!")
                    } else {
                        isControlValid.push(true)
                        $(elem).removeClass('border')
                        $(elem).removeClass('border-danger')
                        $("#error_" + $(elem).attr('name')).text("")
                    }
                });

                if (!isControlValid.includes(false)) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: "{{ route('proprietaire.saveContactLogement')}}",
                        data: {
                            categorie: categorie.val(),
                            type: type.val(),
                            first_name: first_name.val(),
                            name: name.val(),
                            email: email.val(),
                            mobile: mobile.val(),
                            adress: adress.val(),
                            ville: ville.val(),
                            code_postal: code_postal.val(),
                            pays: pays.val(),
                            comment: comment.val()
                        },
                        success: function(data) {
                            dataIDContact.push(data.id)
                            $('#dataIDContact').val(dataIDContact);
                            $('#list_contact').append('<div class="col-md-6 contact-container" id="container_contact_'+data.id+'" >\
                                                            <div class="card mb-3 mt-3">\
                                                            <h5 class="card-header">'+data.name+' '+data.first_name+'</h5>\
                                                            <div class="card-body">\
                                                            <blockquote class="blockquote mb-0">\
                                                                    <p>'+data.mobile+'</p>\
                                                                    <p>'+data.email+'</p>\
                                                                    <footer class="blockquote-footer">\
                                                                    <cite title='+data.code_postal+' '+data.pays+'>'+data.adress+' '+data.ville+'</cite>\
                                                                    </footer>\
                                                                    <center>\
                                                                        <a id="'+ data.id +'" onClick="getDataContact(\''+data.id+'\',\''+data.unique_id_contact+'\',\''+data.name+'\',\''+data.first_name+'\',\''+data.email+'\',\''+data.mobile+'\',\''+data.adress+'\',\''+data.ville+'\',\''+data.code_postal+'\',\''+data.pays+'\',\''+data.comment+'\')" data-bs-toggle="modal" data-bs-target="#modalContactModif" type="button" class="btn btn-sm btn-info rounded-pill btn-modifier">Modifier</a>\
                                                                        <a onClick="deleteContactLogement('+data.id+')" id="supp_'+ data.id +'"" type="button" class="btn btn-sm btn-danger rounded-pill ms-3 btn-supprimer">Supprimer</a>\
                                                                    </center>\
                                                                </blockquote>\
                                                            </div>\
                                                        </div>')
                            toastr.success("Votre contact a été bien enregistrer.");
                            // __("logement.save-contact-success")

                            modalContact.hide();
                            first_name.val('');
                            name.val('');
                            email.val('');
                            mobile.val('');
                            adress.val('');
                            ville.val('');
                            code_postal.val('');
                            pays.val('');
                            comment.val('');
                        },
                        error: function(data) {
                            let msgs = data.responseJSON.message
                            $.each(msgs,function (key,value) {
                                $('#error_' + key).text(value)
                            });
                        }
                    });
                }

            })

            /*** sauvgarde la modification du contact ***/
            const modalModifContact = new bootstrap.Modal('#modalContactModif')
            const myModalEl = document.getElementById('modalContactModif')
                    myModalEl.addEventListener('hidden.bs.modal', event => {
                        $(".control_modif").each((index, elem) => {
                            $('.error_modif').text('')
                            $(elem).removeClass('border')
                            $(elem).removeClass('border-danger')
                        });
                    })
            $('#saveModifContact').click(function(event){
                event.preventDefault();
                let id_modif = $("input[name=id_modif]");
                let unique_id_modif = $("input[name=unique_id_modif]");
                var categorie_modif = $("select[name=categorie_modif]");
                var type_modif = $("select[name=type_modif]");
                var first_name_modif = $("input[name=first_name_modif]");
                let name_modif = $("input[name=name_modif]");
                var email_modif = $("input[name=email_modif]");
                var mobile_modif = $("input[name=mobile_modif]");
                var adress_modif = $("input[name=adress_modif]");
                var ville_modif = $("input[name=ville_modif]");
                var code_postal_modif = $("input[name=code_postal_modif]");
                var pays_modif = $("input[name=pays_modif]");
                var comment_modif = $("textarea[name=comment_modif]");
                var control_modif = $('.control_modif');
                let isControlModifValid = [];
                let cle = ["name_modif","mobile_modif","adress_modif","ville_modif"]
                $(".control_modif").each((index, elem) => {
                    if (!$(elem).val()) {
                        isControlModifValid.push(false);

                        $(elem).addClass('border')
                        $(elem).addClass('border-danger')
                        $("#error_" + $(elem).attr('name')).text("veiller remplir ce champ!")
                    } else {
                        isControlModifValid.push(true)
                        $(elem).removeClass('border')
                        $(elem).removeClass('border-danger')
                        $("#error_" + $(elem).attr('name')).text("")
                    }
                });

                if (!isControlModifValid.includes(false)) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    /*** Modification du contact sur le logement ***/
                    $.ajax({
                        type: "POST",
                        url: "{{ route('proprietaire.updateContactLogement')}}",
                        data: {
                            id_modif: id_modif.val(),
                            unique_id_modif: unique_id_modif.val(),
                            categorie_modif: categorie_modif.val(),
                            type_modif: type_modif.val(),
                            first_name_modif: first_name_modif.val(),
                            name_modif: name_modif.val(),
                            email_modif: email_modif.val(),
                            mobile_modif: mobile_modif.val(),
                            adress_modif: adress_modif.val(),
                            ville_modif: ville_modif.val(),
                            code_postal_modif: code_postal_modif.val(),
                            pays_modif: pays_modif.val(),
                            comment_modif: comment_modif.val()
                        },
                        success: function(data) {
                            $("#container_contact_" + data.id).remove();
                            $('#list_contact').append('<div class="col-md-6 contact-container" id="container_contact_'+data.id+'" >\
                                                            <div class="card mb-3 mt-3">\
                                                            <h5 class="card-header">'+data.name+' '+data.first_name+'</h5>\
                                                            <div class="card-body">\
                                                            <blockquote class="blockquote mb-0">\
                                                                    <p>'+data.mobile+'</p>\
                                                                    <p>'+data.email+'</p>\
                                                                    <footer class="blockquote-footer">\
                                                                    <cite title='+data.code_postal+' '+data.pays+'>'+data.adress+' '+data.ville+'</cite>\
                                                                    </footer>\
                                                                    <center>\
                                                                        <a id="'+ data.id +'" onClick="getDataContact(\''+data.id+'\',\''+data.unique_id_contact+'\',\''+data.name+'\',\''+data.first_name+'\',\''+data.email+'\',\''+data.mobile+'\',\''+data.adress+'\',\''+data.ville+'\',\''+data.code_postal+'\',\''+data.pays+'\',\''+data.comment+'\')" data-bs-toggle="modal" data-bs-target="#modalContactModif" type="button" class="btn btn-sm btn-info rounded-pill btn-modifier">Modifier</a>\
                                                                        <a onClick="deleteContactLogement('+data.id+')" id="supp_'+ data.id +'"" type="button" class="btn btn-sm btn-danger rounded-pill ms-3 btn-supprimer">Supprimer</a>\
                                                                    </center>\
                                                                </blockquote>\
                                                            </div>\
                                                        </div>')
                            toastr.success("Votre contact a été bien modifier.");
                            // __("logement.update-contact-success")

                            modalModifContact.hide();

                        },
                        error: function(data) {
                            let msgs = data.responseJSON.message
                            $.each(msgs,function (key,value) {
                                $('#error_' + key).text(value)
                            });
                        }
                    });
                }

            })

        });
    </script>

@endpush

