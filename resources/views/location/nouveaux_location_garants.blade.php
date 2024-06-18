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


</style>
{{-- <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648722693/css/intlTelInput.min_ft4ncf.css"
          rel="stylesheet"> --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/css/intlTelInput.css">

<div class="card" style="margin-top: 5px">
    <div class="card-header"
        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;text-transform: lowercase">
        {{__('location.garant')}}
    </div>
    <div id="miampy">
        {{-- <div class="card-body" style="margin-top: 20px;">
            <div class="row align-middle">
                <div class="col-md-1 align-middle ">
                    <label for="" class="form-label">GARANTS</label>
                </div>
                <div class="col-md-9 align-middle" style="border: #3A87AD solid 1px;padding:10px">
                    <p style="margin-top:12px">Safidy Mahafaly</p>
                    <p style="margin-top:-18px">Societe</p>
                    <p style="margin-top:-18px">Safidy@gmail.com</p>
                    <p style="margin-top:-18px">0345092565</p>
                </div>
                <input type="hidden" name="nom">
                <input type="hidden" name="prenom">
                <input type="hidden" name="date_naissance">
                <input type="hidden" name="date_naissance">
                <input type="hidden" name="lieu">
                <input type="hidden" name="email">
                <input type="hidden" name="mobil">
                <div class="col-md-2 align-middle ">
                    <a href="" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-pen"></i></a>
                    <a href="" class="btn btn-outline-danger btn-sm" ><i class="fa-solid fa-trash"></i> </a>
                </div>
            </div>
        </div> --}}
    </div>

    <div class="card-body" style="margin-top: 20px;">
        <div class="row align-middle">
            <div class="col-md-1 align-middle ">
                <label for="" class="form-label">{{__('location.garant')}}</label>
            </div>
            <div class="col-md-6 align-middle">
                <a href="" class="btn" data-bs-toggle="modal"
                    style="border:1px solid gray;color:blue;background-color:#f5f5f9;" id="newGarant" data-bs-target="#modalId"
                    onmouseup="">
                    <i class="fa fa-plus-circle"></i> {{__('location.ajoutGarant')}}
                </a>
            </div>
            <p style="margin-top: 10px;">{{__('location.infoGarant')}}</p>
        </div>
    </div>

    <!-- Modal Body-->
    <div class="modal fade modalIdGarant" id="modalId" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#FAFAFA;">
                    <h5 class="modal-title" id="modalTitleId">{{__('location.nouveauxGarant')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <p class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7"><span
                        class="label m-r-2"
                        style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">INFORMATION</span>
                        {{__('location.infoModalGarant')}}</p>

                <div class="modal-body">
                    <div class="container-fluid">
                        <form id="myForm" class="garantModal">
                            <input type="hidden" name="_token"
                                value="fnEwusS1PkgE0bV8mLGVXJWDBItTh67SY897I40J">
                            {{-- <div class="row">
                                <div class="col-lg-6">
                                    <label for="" class="form-label">GARANT(S)</label>
                                    <input type="text" name="garant" id="garant" class="form-control"
                                        placeholder="ajouter un nouveau..." aria-describedby="helpId">
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">TYPE</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">Particulier</option>
                                        <option value="">Société/autre</option>
                                    </select>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="dropdown">
                                        <button class="btn form-control dropdown-toggle  border-secondary titre"  type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                         {{__('location.garant')}}
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="width: 65%">
                                          <div class="p-1">
                                            <input type="text" class="form-control rechechegarant" placeholder="recherche">
                                          </div>
                                          <li><a class="dropdown-item nouveaux" id="dropdown-item" href="#"><i class="fa fa-plus-circle"></i>{{__('location.nouveauxGarant')}}</a></li>
                                          <li class="bg-dark text-white p-1">{{__('location.garant')}}</li>
                                          @forelse ($garants as $garant)
                                            <li class="testeGar"><a class="dropdown-item  garant pageGr" id="dropdown-item" href="#" data-date="{{$garant->date_naissance}}" data-lieu="{{$garant->lieu}}" data-email="{{$garant->email}}" data-mobil="{{$garant->mobil}}" data-prenom="{{$garant->prenom}}" data-nom="{{$garant->nom}}">{{$garant->nom . ' ' . $garant->prenom}}</a></li>
                                          @empty
                                            <li class="tsisy"><p href="" class="dropdown-item " disabled>{{__('location.aucunGarant')}}</p></li>
                                          @endforelse
                                          <div class="auc"></div>
                                        </ul>
                                      </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">{{__('location.prenom')}} *</label>
                                    <input type="text" name="prenoms" id="prenoms" class="form-control controlG"
                                        placeholder="" aria-describedby="helpId">
                                    <span  id="error_prenoms" class="error_prenoms text-danger"></span>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">{{__('location.nom')}} *</label>
                                    <input type="text" name="nom" id="nom" class="form-control controlG"
                                        placeholder="" aria-describedby="helpId">
                                    <span  id="error_nom" class="error_nom text-danger"></span>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="" class="form-label">{{__('location.dateNaiss')}}</label>
                                    <input type="date" name="date" id="date" class="form-control controlG"
                                        placeholder="" aria-describedby="helpId">
                                    <span  id="error_date" class="error_date text-danger"></span>

                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">{{__('location.LieuNaiss')}}</label>
                                    <input type="text" name="lieu" id="lieu" class="form-control controlG"
                                        placeholder="" aria-describedby="helpId">
                                    <input type="hidden" name="check" id="check" class="form-control">
                                    <span  id="error_lieu" class="error_lieu text-danger"></span>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="" class="form-label">E-MAIL</label>
                                    <input type="email" name="email" id="email" class="form-control controlG" placeholder="" aria-describedby="helpId">
                                    <span  id="error_email" class="error_email text-danger"></span>
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label">MOBILE</label>
                                    <input type="tel" name="mobil" id="mobil" class="form-control controlG"
                                        placeholder="" aria-describedby="helpId">
                                    <span  id="error_mobil" class="error_mobil text-danger"></span>
                                </div>
                            </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" style="border:1px solid #f5f5f9"
                        data-bs-dismiss="modal">Annuler</button>
                    </form>
                    <button id="submitBtn" class="btn btn-primary sauvegarde">{{__('location.enregistrer')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{{-- <script src="/js/intlTelInput/intlTelInput.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/js/intlTelInput.min.js"></script>
<script>
// $("#mobil").intlTelInput({
//     separateDialCode: true,
//     customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
//         return "+" + selectedCountryData.dialCode + " " + selectedCountryPlaceholder;
//     }
// });
$(document).ready(function () {
    var input = document.querySelector("#mobil");
    var iti = window.intlTelInput(input, {
        separateDialCode: true,
        initialCountry: "fr",
        preferredCountries: ["fr", "us", "gb"],
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/js/utils.js",
    });

    function updateMobileField(phoneNumber) {
        iti.setNumber(phoneNumber);
        console.log(phoneNumber)
    }

    $('.dropdown-menu').on('click', 'a#dropdown-item', function(e) {
        e.preventDefault();
        var text = $(this).text();
        $('.titre').text(text);
        $('.dropdown-menu').find('a.dropdown-item ').removeClass('active'); // Supprimer la classe active de tous les éléments
        $(this).addClass('active');
        if(text == '{{__('location.nouveauxGarant')}}'){
            $("#prenoms").val('');
            $("#nom").val('')
            $("#date").val('')
            $("#lieu").val('')
            $("#email").val('')
            $("#mobil").val('')
            $('#check').val('');
            // $("#submitBtn").prop("disabled", true);
        }else{
            var nom = $(this).attr('data-nom');
            var prenom = $(this).attr('data-prenom');
            var email = $(this).attr('data-email');
            var mobil = $(this).attr('data-mobil');
            updateMobileField(mobil);
            var lieu  = $(this).attr('data-lieu');
            var date  = $(this).attr('data-date');

            $('#nom').val(nom)
            $('#prenoms').val(prenom)
            $('#email').val(email)
            $('#mobil').val(mobil)
            $('#lieu').val(lieu)
            $('#date').val(date)
            $(".control").each((index, elem) => {
                    $(elem).removeClass('border')
                    $(elem).removeClass('border-danger')
                    $("#error_" + $(elem).attr('name')).text("")
            });
            // $("#submitBtn").prop("disabled", false);
        }
    });

    $('.rechechegarant').on('input', function() {
    var recherche = $(this).val().toLowerCase();
    var resultatTrouve = false;
    $('.tsisy').hide()
    $('.testeGar').each(function() {
      var texteLi = $(this).text().toLowerCase();
      if (texteLi.includes(recherche)) {
        $(this).show();
        resultatTrouve = true;
        $('.tsisy').hide()
      } else {
        $(this).hide();
        $('.tsisy').hide()
      }


    });

    if (!resultatTrouve) {
      $('.aucun-resultat').remove(); // Supprimer l'élément s'il existe déjà

      if (recherche !== '' && recherche !== 'ajouter nouveaux') {
        $('.auc').append('<li class="aucun-resultat p-3">Aucun résultat pour " '+ recherche + ' " </li>');
      }
    } else {
      $('.aucun-resultat').remove(); // Supprimer l'élément s'il existe déjà
    }
  });










    // $("#submitBtn").prop("disabled", true); // Désactive le bouton par défaut

    // Vérifie si tous les champs de formulaire sont remplis lorsque le contenu d'un champ est modifié
    // $(".form-control").on("input", function(){
    //     if ($("#prenoms").val() && $("#nom").val() && $("#date").val() && $("#lieu").val() && isValidEmail($("#email").val()) && $("#mobil").val()) {
    //         $("#submitBtn").prop("disabled", false); // Désactive le bouton par défaut
    //     } else {
    //         $("#submitBtn").prop("disabled", true); // Désactive le bouton par défaut
    //     }
    // });
    //Reinitialiser les champs dans le modal a chaque show du modal
    $('.modalIdGarant').on('hidden.bs.modal', function () {
        $("#prenoms").val('');
        $("#nom").val('')
        $("#date").val('')
        $("#lieu").val('')
        $("#email").val('')
        $("#mobil").val('')
        $('#check').val('');
        $('.titre').text('Garant');
        $('.dropdown-menu').find('a.dropdown-item').removeClass('active');
        // $("#submitBtn").prop("disabled", true);

  });
    function isValidEmail(email) {
        // Expression régulière pour valider une adresse email
        var emailRegex = /\S+@\S+\.\S+/;
        return emailRegex.test(email);
    }
    // $(document).on('click', '#newGarant', function(e) {
    //  $('.closeG').remove();
    // });
    $("#submitBtn").click(function(e) {
        e.preventDefault()
        $('.closeG').remove();
        var prenoms =  $("#prenoms").val()
        var nom     =  $("#nom").val()
        var date    =  $("#date").val()
        var lieu    =  $("#lieu").val()
        var email   =  $("#email").val()
        var check   =  $('#check').val()
        var mobil   =  $("#mobil").val()

        let isControlValid = [];
        $(".controlG").each((index, elem) => {
                if (!$(elem).val()) {
                    isControlValid.push(false);
                    console.log()
                    $("#error_" + $(elem).attr('name')).text('{{__('location.champObligatoire')}}');
                } else {
                if ($(elem).attr('id') === 'email') {
                    const email = $(elem).val();
                    if (!isValidEmail(email)) {
                        isControlValid.push(false);
                        $("#error_" + $(elem).attr('name')).text('{{__('location.textEmailValid')}}');
                    } else {
                        isControlValid.push(true);
                        $("#error_" + $(elem).attr('name')).text("");
                    }
                } else if ($(elem).attr('id') === 'mobil') {
                    const isValidPhoneNumber = iti.isValidNumber(); // Vérifie si le numéro de téléphone est valide pour le pays choisi
                    if (!isValidPhoneNumber) {
                        isControlValid.push(false);
                        $("#error_" + $(elem).attr('name')).text('{{__('location.textTelValid')}}');
                    } else {
                        isControlValid.push(true);
                        $("#error_" + $(elem).attr('name')).text("");
                    }
                } else {
                    isControlValid.push(true);
                    $("#error_" + $(elem).attr('name')).text("");
                }
            }
        });
        if (!isControlValid.includes(false)) {
            console.log('teste')
            $("#modalId").modal('hide');
            $(".control").each((index, elem) => {
                    $(elem).val('')
            });
            $.ajax({
                url: '/save-tempGarTable',
                type: 'get',
                data: {
                    prenoms : prenoms,
                    nom : nom,
                    date : date,
                    lieu : lieu,
                    email : email,
                    mobil : mobil,
                    check : check
                },
                success: function(data) {
                    for (var i = 0; i < data.length; i++) {
                        console.log(data[i].id)
                       $("#miampy").append('<div class="card-body closeG" style="margin-top: 20px;" id="garant_'+data[i].id+'" >\
                            <div class="row align-middle">\
                                <div class="col-md-2 align-middle ">\
                                    <label for="" class="form-label">GARANTS</label>\
                                </div>\
                                <div class="col-md-8 align-middle" style="border: #3A87AD solid 1px;padding:10px">\
                                    <div class="row">\
                                        <div class="col-2 mt-4">\
                                            <span class="badge badge-center bg-primary rounded-pill " style="width:50px;height:50px;margin-left:25px;" id="initial">' + data[i].nom.substring(0,1).toUpperCase() + data[i].prenom.substring(0,1).toUpperCase() + '</span>\
                                        </div>\
                                        <div class="col-6">\
                                            <p style="margin-top:12px">'+ data[i].nom + ' ' + data[i].prenom +' </p>\
                                            <p style="margin-top:-18px">'+ data[i].date + ' à ' + data[i].lieu +'</p>\
                                            <p style="margin-top:-18px"><i class="fa-regular fa-envelope"></i>&nbsp;&nbsp;'+ data[i].email +'</p>\
                                            <p style="margin-top:-18px"><i class="fa-solid fa-phone-volume"></i>&nbsp;&nbsp;'+ data[i].numero +'</p>\
                                        </div>\
                                    </div>\
                                </div>\
                                <input type="hidden" name="nom[]" class="nom" value="'+ data[i].nom+'">\
                                <input type="hidden" name="prenom[]" value="' + data[i].prenom +'">\
                                <input type="hidden" name="date_naissance[]" value="'+ data[i].date + '">\
                                <input type="hidden" name="lieu[]" value="' + data[i].lieu +'">\
                                <input type="hidden" name="email[]" value="'+ data[i].email +'">\
                                <input type="hidden" name="mobil[]" value="'+ data[i].numero +'">\
                                <div class="col-md-2 align-middle ">\
                                    <a href="" class="btn btn-outline-danger btn-sm suprimeGarant" id="'+data[i].id+'" ><i class="fa-solid fa-trash"></i> </a>\
                                    <a href="" class="btn btn-outline-info btn-sm modifGarant" id="'+data[i].id+'" data-bs-target="#modalId" data-bs-toggle="modal"><i class="fa fa-solid fa-pencil"></i> </a>\
                                </div>\
                            </div>')
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        }else{
            console.log(isControlValid)
        }
    });
});

$(document).on('click', '.suprimeGarant', function(e) {
    e.preventDefault()
    var id = $(this).attr('id');
    var parent = $(this).closest('#garant_'+ id)
    parent.remove();
    $.ajax({
            url: "/supp-tempGar/" + id,
            type: 'get',
            success: function(data) {
              console.log(data)
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
});
    $(document).on('click', '.modifGarant', function(e) {
        e.preventDefault();
       var id = $(this).attr('id');
    //    var formId = $(this).closest('#garant_'+ id)
    //    formId.remove();
    $.ajax({
        url: '/garant/' + id,
        type: 'GET',
        success: function(data) {
            $('#check').val(id);
            $('#nom').val(data.nom);
            $('#prenoms').val(data.prenom);
            $('#lieu').val(data.lieu);
            $('#date').val(data.date);
            $('#email').val(data.email);
            $('#mobil').val(data.numero);
            // Remplissez ici les autres champs du modal avec les données récupérées
        },
        error: function() {
            alert('Une erreur est survenue lors de la récupération des données du locataire.');
        }
    });
});

</script>

