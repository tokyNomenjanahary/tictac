
@extends('proprietaire.index')
@push("styles")
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush
@section('contenue')
    <div class="content-wrapper"
         style="font-family: Manrope, -apple-system,BlinkMacSystemFont,segoe ui,Roboto,Oxygen,Ubuntu,Cantarell,open sans,helvetica neue,sans-serif;">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row tete mt-4">
                <div class="col-lg-6 col-sm-4 col-12 col-md-4 titre t-sm">
                    @if(isset($agenda))
                        <h3 class="page-header page-header-top">{{__('agenda.modification')}}</h3>
                    @else
                        <h3 class="page-header page-header-top">{{__('agenda.nouveau')}}</h3>
                    @endif
                </div>
            </div>
            <form>
                @csrf
                <div class="card mb-4 p-4">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <label for="" class="form-label">{{__('agenda.Objet')}}</label>
{{--                            <input type="text" name="objet" id="objet" class="form-control control" placeholder="" oninput="err(this)" aria-describedby="helpId">--}}
                            <select name="objet" id="objet" onchange="err(this)" class="form-select">
                              @if(isset($agenda))<option value="{{$agenda->objet}}">{{$agenda->objet}}</option> @endif
                                <option value="Remise de clefs">{{__('agenda.Remise_de_clefs')}}</option>
                                <option value="Etat des lieux de sortie">{{__('agenda.Etat_des_lieux_de_sortie')}}</option>
                                <option value=" Signature de contrat">{{__('agenda.Signature_de_contrat')}}</option>
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <label for="" class="form-label">{{__('revenu.Location')}}</label>
                            <select id="location" name="location" onchange="err(this)" @if(isset($agenda))  disabled @endif  class="form-select">
                                @if(isset($locationActuelle))<option value="">{{$locationActuelle->identifiant}}</option> @endif
                                <option>{{__('depense.Choisir')}}</option>
                                @foreach($locations as $location)
                                    <option value="{{$location->id}}">{{$location->identifiant}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <label for="" class="form-label">{{__('agenda.Lieu_rdv')}} </label>
                             <input type="text" @if(isset($agenda)) value="{{$agenda->adresse_rdv}}" @endif name="lieu" id="lieu" class="form-control" oninput="err(this)"  aria-describedby="helpId">
                             <input type="text" name="agenda_id" @if(isset($agenda)) value="{{$agenda->id}}" @endif  id="agenda_id"  hidden class="form-control control">
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <label for="" class="form-label">{{__('echeance.Locataire')}}</label>
                            <input type="text" id="locataire" @if(isset($locationActuelle)) value="{{$locationActuelle->Locataire->TenantFirstName}}" @endif readonly class="form-control control" oninput="err(this)"  aria-describedby="helpId">
                            <input type="hidden" id="locataire_id" name="locataire_id" @if(isset($locationActuelle)) value="{{$locationActuelle->Locataire->user_account_id}}" @endif readonly>
                            <input type="hidden" id="id_locataire" name="id_locataire">
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <label for="" class="form-label">{{__('agenda.debut_rdv')}}</label>
                            <input type="datetime-local" @if(isset($agenda)) value="{{$agenda->start_time}}" @endif name="debut" id="debut" class="form-control control" placeholder="" onchange="err(this)" aria-describedby="helpId">
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <label for="" class="form-label">{{__('agenda.fin_rdv')}} </label>
                            <input type="datetime-local" @if(isset($agenda)) value="{{$agenda->finish_time}}" @endif name="fin" id="fin" class="form-control control" placeholder="" onchange="err(this)" aria-describedby="helpId">
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="mb-3">
                                <label for="" class="form-label">{{__("ticket.Déscription")}}</label>
                                <textarea name="description"  id="description"  rows="8" class="form-control" oninput="err(this)">@if(isset($agenda)) {{$agenda->description}} @endif</textarea>
                            </div>
                        </div>
                        <div class="mb-3 mt-3 row text-center">
                            <label for="html5-text-input" class="col-md-2 col-form-label text-md-end"></label>
                            <div class="col-lg-8">
                                <a class="btn btn-dark" href="" style="color:white;">{{__('depense.Annuler')}}</a>
                                <button class="btn btn-primary" id="save">{{__('depense.Sauvegarder')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('script')
@endpush

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[type="hidden"]').attr('value')
                }
            });
            $("#location").on("change", function() {
                var locationId = $("#location").val()
                $.ajax({
                    type: 'get',
                    url: '/getNomLocataire',
                    dataType: "json",
                    data: {
                        locationId:locationId
                    },
                    success: function(data) {
                        $('#locataire').val(data.locataire.TenantFirstName)
                        $('#locataire_id').val(data.locataire.user_account_id)
                        $('#id_locataire').val(data.locataire.id)
                        $('#location').val(data.id)
                    }
                });
            });
        })

        $("#save").on("click", function(e) {
            e.preventDefault()
            var objet = $("#objet").val()
            var lieu = $("#lieu").val()
            var locataire = $("#locataire").val()
            var locataire_id = $("#locataire_id").val()
            var debut = $("#debut").val()
            var fin = $("#fin").val()
            var description = $("#description").val()
            var agenda_id= $("#agenda_id").val()
            var id_locataire=$("#id_locataire").val()
            var locationId = $("#location").val()

            $("#myLoader").removeClass("d-none")


            $.ajax({
                type: 'POST',
                url: '/sauver_rdv',
                dataType: "json",
                data: {
                    objet: objet,
                    locataire: locataire,
                    debut: debut,
                    fin: fin,
                    lieu: lieu,
                    description: description,
                    locataire_id:locataire_id,
                    agenda_id:agenda_id,
                    id_locataire:id_locataire,
                    locationId:locationId
                },
                success: function(data) {
                    if ($.isEmptyObject(data.errors)) {
                        $("#myLoader").addClass("d-none")
                        window.location = "{{ route('proprietaire.agenda') }}"
                        toastr.success(data.messages);
                    } else {
                        ErrorMsg(data.errors)
                        $("#myLoader").addClass("d-none")
                        console.log("Il ya une erreur lors de l'enregistrement des donnees!");
                    }
                }
            });

            function ErrorMsg(msg) {
                var keys = Object.keys(msg);
                keys.forEach(function(key) {
                    $('#' + key).addClass('is-invalid');
                    $('#' + key).parent().find('.invalid-feedback')
                        .remove(); // Supprimer tous les messages d'erreur existants
                    $('#' + key).parent().append('<div class="invalid-feedback">' + msg[key][0] + '</div>');
                });
            }
        })
        function err(element) {
            $(element).removeClass('is-invalid');
        }
    </script>
@endpush
