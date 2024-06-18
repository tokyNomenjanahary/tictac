@extends('proprietaire.index')

@section('contenue')
    <style>
        label {
            font-size: 12px;
            color: rgb(88, 85, 85);
        }

        p {
            font-size: 12px;
        }

        .pdf-container {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%;
            /* 16:9 aspect ratio, adjust as needed */
            overflow: hidden;
        }

        .pdf-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        .document {
            border: solid rgb(221, 221, 221);
            border-width: 5px 1px 5px 1px;
            margin: 9px;
            font-size: 12px;
            font-family: Manrope, -apple-system, BlinkMacSystemFont, segoe ui, Roboto, Oxygen, Ubuntu, Cantarell, open sans, helvetica neue, sans-serif;
        }

        .rectangle {
            border: 2px solid rgb(5, 5, 5);
            /* Set the background color of the rectangle */
            padding: 15px;
            /* Add padding to create space within the rectangle */
        }
    </style>
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- HEADER -->



            <div id="entete_general">
                <div class="row" style="margin-top: 30px;">
                    <div class="row tete">
                        <div class="col-lg-4 col-sm-4 col-md-4 titre">
                            <h3 class="page-header page-header-top"> <a href="javascript:history.go(-1)"> <i
                                        class="fas fa-chevron-left"></i> </a>Revision du loyer</h3>
                        </div>
                    </div>
                </div>
                <div class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7; border-left: 4px solid rgb(58,135,173);">
                    {{-- <span class="label m-r-2"
                        style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">INFORMATION</span> --}}
                    <span style="font-size:25px ; color:rgb(76,141,203)">Information</span>
                    </p style="margin-top:50px;font-size:12px !important;">Une fois par an, à la date anniversaire du
                    contrat,
                    il
                    est d'usage de réviser le montant du loyer. (Formule: Loyer x nouvel indice / ancien indice = nouveau
                    loyer)
                    Pour la révision vous devez utiliser le nouvel Indice publié du même trimestre de référence que celui
                    indiqué
                    dans le bail.

                    Lors de la signature d'un contrat de location, le dernier indice connu (trimestre de référence et valeur
                    de
                    l'indice) doit être porté au contrat.

                    Une fois la révision du loyer faite, le logiciel modifiera le montant du loyer dans la location. Les
                    loyers
                    déjà
                    générés dans la rubrique Finances restent inchangés. </p>
                </div>
                <div class="alert m-t-15 mt-2 m-b-0 m-l-10 m-r-10" style="background-color: #FFF3BD;  border-left: 4px solid rgb(248,148,6);">
                    <h4>Indexation des loyers d'habitation plafonnée à 3,5 % !</h4>
                    <p style="color:#00000;margin-top:10px;font-size:12px !important;">Le « bouclier loyer » vise à limiter
                        la
                        hausse de l'indice de référence des loyers (IRL). Pour les révisons faites du 3e trimestre 2022 au
                        2e
                        trimestre 2023, la hausse de l'IRL est plafonnée à :
                    <ul>
                        <li>3,5 % en métropole ;</li>
                        <li>2 % en Corse ;</li>
                        <li>2,5 % en Outre-mer.</li>
                    </ul>

                    Les loyers ne pourront donc pas augmenter au-delà de ces pourcentages fixés pendant l'application du «
                    bouclier
                    loyer », soit jusqu'au 30 juin 2023.

                    Pensez à vérifier et arrondir le résultat afin de ne pas dépasser cette limite légale.</p>
                </div>
            </div>
            <div id="entete_appercu" class="d-none">
                <div class="row" style="margin-top: 30px;">
                    <div class="row tete">
                        <div class="col* titre">
                            <h3 class="page-header page-header-top"> <a href="javascript:history.go(-1)"> <i
                                        class="fas fa-chevron-left"></i> </a>Confirmez la révision de loyer</h3>
                        </div>
                    </div>
                </div>
                <div class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7; border-left: 4px solid rgb(58,135,173);">
                    {{-- <span class="label m-r-2"
                        style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">INFORMATION</span> --}}
                    <span style="font-size:25px ; color:rgb(76,141,203)">Information</span>
                    </p style="margin-top:50px;font-size:12px !important;">N'oubliez par d’envoyer à votre locataire une
                    lettre de notification d’augmentation du loyer par voie postale. Vous pouvez télécharger un modèle de
                    lettre de Révision du loyer en cours de bail sur la page suivante.N'oubliez par d’envoyer à votre
                    locataire une lettre de notification d’augmentation du loyer par voie postale. Vous pouvez télécharger
                    un modèle de lettre de Révision du loyer en cours de bail sur la page suivante.</p>
                </div>
            </div>
            <!-- END HEADER -->

            <!-- CARD FILTER -->
            <div class="row" id="info_general">
                <div class="col">
                    <div class="card mb-4 p-3">
                        <form action="" method="POST" enctype="multipart/form-data">

                            @csrf
                            <div class="mb-4">
                                <div class="card h-100">
                                    <div class="card-header border-bottom py-2 px-3"
                                        style="background-color: rgb(249,249,249)">
                                        <div class="card-title mb-0">
                                            <h5 class="m-0 me-2 w-auto">Révision de loyer</h5>
                                        </div>
                                    </div>

                                    <div class="card-body p-0">
                                        <div class="p-3 border-bottom pt-50 pb-12">


                                            <div class="mb-4 row">
                                                <label for="html5-text-input"
                                                    class="col-md-2 col-form-label text-md-end"></label>
                                                <div class="col" style="line-height: 1.5">
                                                    @if (count($revisions) > 0)
                                                        {{-- @php
                                                        use Carbon\Carbon;
                                                        $date = Carbon::createFromFormat('Y-m-d', $revisions[0]->date_revision);
                                                    @endphp --}}
                                                        <p style="font-size:14px"><b>Dernière révision faite le
                                                                {{ $revisions[0]->date_revision }}</b></p>
                                                        <p style="font-size:14px"><b>Dernier indice utilisé :
                                                                {{ $revisions[0]->nouvel_indice }}</b></p>
                                                    @endif
                                                    <ul>
                                                        @foreach ($revisions as $item)
                                                            @if ($loop->index == 0)
                                                                <li style="font-size:15px">Révision faite le
                                                                    {{ $item->date_revision }}. Ancien loyer
                                                                    {{ $item->ancien_loyer + $item->charge }}, nouveau loyer
                                                                    {{ $item->loyer_hors_charge + $item->charge }} <a
                                                                        href="#"
                                                                        onclick="comfirmationSuppression({{ $item->id }})"><i
                                                                            class="fa-solid fa-rotate-right"
                                                                            data-revision="{{ $item->id }}"></i>Annuler</a>
                                                                </li>
                                                            @else
                                                                <li style="font-size:15px">Révision faite le
                                                                    {{ $item->date_revision }}. Ancien loyer
                                                                    {{ $item->ancien_loyer + $item->charge }}, nouveau loyer
                                                                    {{ $item->loyer_hors_charge + $item->charge }}</li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="mb-4 row">
                                                <label for="html5-text-input"
                                                    class="col-md-2 col-form-label text-md-end">Location</label>
                                                <div class="col-lg-5">
                                                    <input type="text" name="location_identifiant"
                                                        value="{{ $location->Logement->identifiant . ' (' . $location->Locataire->TenantFirstName . ' ' . $location->Locataire->TenantLastName . ')' }}"
                                                        class="form-control" disabled>
                                                    <input type="hidden" id="location_id" name="location_id"
                                                        value="{{ $location->id }}">
                                                </div>
                                            </div>

                                            <div class="mb-4 row">
                                                <label for="html5-date-input"
                                                    class="col-md-2 col-form-label text-md-end">Date</label>
                                                <div class="col-lg-5">
                                                    <input type="date" name="date_revision" id="date_revision"
                                                        value="{{ now()->format('Y-m-d') }}" class="form-control">

                                                </div>
                                            </div>

                                            <div class="mb-4 row">
                                                <label for="html5-date-input"
                                                    class="col-md-2 col-form-label text-md-end">Loyer Hors Charges</label>
                                                <div class="col-lg-5">
                                                    <input type="number" min="1" onChange="checkMontant('loyer')"
                                                        id="loyer" name="loyer"
                                                        value="{{ $location->Logement->loyer }}" class="form-control">

                                                </div>
                                            </div>

                                            <div class="mb-4 row">
                                                <label for="html5-date-input"
                                                    class="col-md-2 col-form-label text-md-end">Charges</label>
                                                <div class="col-lg-5">
                                                    <input type="number" min="0" onChange="checkMontant('charge')"
                                                        id="charge" name="charge"
                                                        value="{{ $location->Logement->charge }}" class="form-control">

                                                </div>
                                            </div>

                                            <div class="mb-4 row">
                                                <label for="html5-date-input"
                                                    class="col-md-2 col-form-label text-md-end">Loyer CC</label>
                                                <div class="col-lg-5">
                                                    <input type="number" min="1" id="loyer_cc"
                                                        value="{{ $location->charge + $location->loyer_HC }}"
                                                        name="loyer_cc" class="form-control" disabled>

                                                </div>
                                            </div>

                                            <div class="mb-4 row">
                                                <label for="html5-text-input"
                                                    class="col-md-2 col-form-label text-md-end">Révision sur</label>
                                                <div class="col-lg-5">
                                                    <select name="location_id" id="type_loyer"
                                                        onChange="checkMontant('type_loyer')" class="form-control">
                                                        <option value="hors_charges">Hors charges</option>
                                                        <option value="charges_comprises">Loyer charges comprises
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mb-4 row">
                                                <label for="html5-date-input"
                                                    class="col-md-2 col-form-label text-md-end">Date de signature</label>
                                                <div class="col-lg-5">
                                                    <input type="date" name="date_signature"
                                                        value="{{ $location->debut }}" class="form-control">

                                                </div>
                                            </div>

                                            <div class="mb-4 row">
                                                <label for="html5-text-input"
                                                    class="col-md-2 col-form-label text-md-end">Dernière révision</label>
                                                <div class="col-lg-5">
                                                    <select name="location_id" id="" class="form-control">
                                                        <option value="">Choisir</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mb-4 row">
                                                <label for="html5-date-input"
                                                    class="col-md-2 col-form-label text-md-end">Indice de départ</label>
                                                <div class="col-lg-5">
                                                    @if (count($revisions) > 0)
                                                        <p><input type="number" min="0" id="indice_depart"
                                                                onChange="checkMontant('indice_depart')"
                                                                name="indice_depart" class="form-control"
                                                                value="{{ $revisions[0]->nouvel_indice }}"></p>
                                                    @else
                                                        <p><input type="number" min="0" id="indice_depart"
                                                                onChange="checkMontant('indice_depart')"
                                                                name="indice_depart" class="form-control"></p>
                                                    @endif
                                                    <p style="font-size:14px">Indice de la dernière révision ou celui
                                                        indiqué à la signature (si aucune révision n'a été faite).
                                                    </p>

                                                </div>
                                            </div>

                                            <div class="mb-4 row">
                                                <label for="html5-date-input"
                                                    class="col-md-2 col-form-label text-md-end">Nouvel indice</label>
                                                <div class="col-lg-5">
                                                    <p> <input type="number" min="0" id="nouvel_indice"
                                                            onChange="checkMontant('nouvel_indice')" name="nouvel_indice"
                                                            class="form-control"></p>
                                                    <p style="font-size:14px">Indice à utiliser pour la révision du loyer
                                                    </p>

                                                </div>
                                            </div>

                                            <div class="mb-4 row">
                                                <label for="html5-date-input"
                                                    class="col-md-2 col-form-label text-md-end">Nouveau loyer</label>
                                                <div class="col-lg-5">
                                                    <p><input type="number" min="1" id="nouveau_loyer"
                                                            value="{{ $location->charge + $location->loyer_HC }}"
                                                            name="nouveau_loyer" class="form-control"></p>
                                                    <p style="font-size:14px">Loyer hors charge : <span
                                                            id="nouv_hors_charge">{{ $location->loyer_HC }}</span>,
                                                        Charges
                                                        : <span id="nouv_charge">{{ $location->charge }} </span></p>
                                                    <p style="font-size:14px">Une fois la révision du loyer faite, le
                                                        logiciel modifiera le montant du loyer dans la location. Les loyers
                                                        déjà générés dans la rubrique Finances restent inchangés.
                                                    </p>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card text-center" style="margin-top: 5px">
                                <div class="row">
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-lg-5" style="padding: 15px;">
                                        <span type="button" onClick="displayInfo()" class="btn btn-primary">
                                            Continuer </span>
                                        <a class="btn btn-dark" style="color:white;"
                                            href=" {{ route('location.index') }}">Annuler</a>
                                        {{-- <a role="button" class="btn btn-dark float-left"
                                        href="{{ redirect()->route('documents.index') }}">Annuler</a> --}}

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('location.delete_revision_loyer') }}" method="post">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Avertissement</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id_revision" id="id_revision">
                                    Veuillez confirmer l'annulation du la révision.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-danger">Confirmer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
            <div class="row  d-none" tabindex='1' id="appercu">
                <div class="col">
                    <div class="card mb-4 p-3">
                        <form action="" method="POST" enctype="multipart/form-data">

                            @csrf
                            <div class="mb-4">
                                <div class="card h-100">
                                    <div class="card-header border-bottom py-2 px-3"
                                        style="background-color: rgb(249,249,249)">
                                        <div class="card-title mb-0">
                                            <h5 class="m-0 me-2 w-auto">Vérifiez et confirmez la révision de loyer</h5>
                                        </div>
                                    </div>

                                    <div class="card-body p-0">
                                        <div class="p-3 border-bottom pt-50 pb-12">

                                            <div class="mb-4 row">
                                                <label for="html5-date-input"
                                                    class="col-md-2 col-form-label text-md-end">Nouveau loyer</label>
                                                <div class="col-lg-5">
                                                    <input type="text" id='vue_nouveau_loyer_input'
                                                        name="vue_nouveau_loyer" class="form-control" disabled>

                                                </div>
                                            </div>

                                            <div class="mb-4 row">
                                                <label for="html5-date-input"
                                                    class="col-md-2 col-form-label text-md-end"></label>
                                                <div class="col font-weight-normal document">
                                                    @include('location.revision_apercu')
                                                </div>
                                            </div>

                                            <div class="mb-4 row">
                                                <label for="html5-date-input"
                                                    class="col-md-2 col-form-label text-md-end"></label>
                                                <div class="col">
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-secondary  dropdown-toggle"
                                                            data-bs-toggle="dropdown">
                                                            <i class="fa-solid fa-download"></i>&nbsp;Télécharger
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href=""
                                                                style="font-size: 14px"><i
                                                                    class="fa-regular fa-file-excel"></i>&nbsp;Export
                                                                format Excel</a>
                                                            <a class="dropdown-item" href=""
                                                                style="cursor:pointer;font-size: 14px"><i
                                                                    class="fa-solid fa-file-excel"></i>&nbsp;Export format
                                                                Open Office</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card text-center" style="margin-top: 5px">
                                <div class="row">
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-lg-5" style="padding: 15px;">
                                        <span type="button" class="btn btn-primary" onCLick="saveRevisionLoyer()">
                                            Confirmer la révision</span>
                                        <a class="btn btn-dark" style="color:white;"
                                            href=" {{ route('location.index') }}">Annuler</a>
                                        {{-- <a role="button" class="btn btn-dark float-left"
                                        href="{{ redirect()->route('documents.index') }}">Annuler</a> --}}

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- END CARD FILTER -->
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endPush
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        var date_location = @json($location->debut);
        var loyer_actuelle = @json($location->Logement->loyer);
        var charge_actuelle = @json($location->Logement->charge);

        function comfirmationSuppression(id) {
            event.preventDefault();
            alert('en cours de developpement');
            // $('#id_revision').val(id);
            // $('#deleteModal').modal('show');
        }

        function annulerRevision(indice) {
            event.preventDefault();

        }

        function saveRevisionLoyer() {
            let augmentation = parseFloat($('#nouveau_loyer').val()) - (loyer_actuelle + charge_actuelle);
            let loyer_hors_charge = parseFloat($('#nouveau_loyer').val()) - charge_actuelle;


            let information = {
                location_id: $('#location_id').val(),
                indice_depart: $('#indice_depart').val(),
                nouvel_indice: $('#nouvel_indice').val(),
                date_revision: $('#date_revision').val(),
                ancien_loyer: loyer_actuelle,
                loyer_hors_charge: loyer_hors_charge,
                charge: charge_actuelle,
                augmentation: augmentation
            }
            $("#myLoader").removeClass("d-none");
            jQuery.ajax({
                url: "{{ route('location.save_revision_loyer') }}",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                method: 'post',
                data: {
                    information: JSON.stringify(information)
                },
                success: function(result) {
                    toastr.success(result.message);
                    location.href = "/location"
                },
                error: function(data) {
                    let errors = data.responseJSON.errors;
                    $("#myLoader").addClass("d-none")
                    $.each(errors, function(key, value) {
                        toastr.error(value);
                    });
                    $("#myLoader").addClass("d-none")
                }
            });
        }

        function displayInfo() {
            /* check des informations */
            let augmentation = parseFloat($('#nouveau_loyer').val()) - (loyer_actuelle + charge_actuelle);
            let loyer_hors_charge = parseFloat($('#nouveau_loyer').val()) - charge_actuelle;
            let indice_depart = 0;
            let nouvel_indice = 0;
            let date = new Date($('#date_revision').val());
            if (!isNaN(parseFloat($('#indice_depart').val()))) {
                indice_depart = parseFloat($('#indice_depart').val());
            }
            if (!isNaN(parseFloat($('#nouvel_indice').val()))) {
                nouvel_indice = parseFloat($('#nouvel_indice').val());
            }

            if (indice_depart == 0 || nouvel_indice == 0) {
                toastr.error('Veuillez remplir ou verifier les indices(depart & nouvel)');
                return false;
            }
            if (loyer_hors_charge <= 0) {
                toastr.error('Le loyer ne peut pas être negatif');
                return false;
            }
            if (date <= new Date(date_location)) {
                toastr.error('La date de revision est anterieur au début de location (' + date_location + ')');
                return false;
            }
            /* fin check*/


            /* remplir les informations dans la vue d'apercu */
            $('#vue_loyer_hors_charges').html(loyer_hors_charge);
            $('#vue_charge').html(charge_actuelle);
            $('#vue_indice_depart').html($('#indice_depart').val());
            $('#vue_nouvel_indice').html($('#nouvel_indice').val());
            $('#vue_nouveau_loyer').html($('#nouveau_loyer').val());
            $('#vue_date_revision').html($('#date_revision').val());

            $('#vue_augmentation').html(augmentation.toFixed(2));
            $('#vue_nouveau_loyer_input').prop('disabled', false);
            $('#vue_nouveau_loyer_input').val($('#nouveau_loyer').val() + " € (" + $('#type_loyer').find("option:selected")
                .text() + ')');
            $('#vue_nouveau_loyer_input').prop('disabled', true);

            $('#entete_general').addClass('d-none');
            $('#info_general').addClass('d-none');
            $('#entete_appercu').removeClass('d-none');
            $('#appercu').removeClass('d-none');



            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function checkMontant(indice) {
            let loyer = parseFloat($('#loyer').val());
            let charge = parseFloat($('#charge').val());
            let indice_depart = 0;
            let nouvel_indice = 0;
            if (!isNaN(parseFloat($('#indice_depart').val()))) {
                indice_depart = parseFloat($('#indice_depart').val());
            }

            let loyer_cc = loyer + charge;
            // let temp_loyer = loyer;


            let nouveau_charge = charge;
            let nouveau_loyer = loyer;

            if (!isNaN(parseFloat($('#nouvel_indice').val()))) {
                nouvel_indice = parseFloat($('#nouvel_indice').val());
                if (indice_depart == 0 && nouvel_indice == 0) {
                    nouveau_loyer = loyer;
                } else if (indice_depart > 0 && $('#type_loyer').val() == 'charges_comprises') {
                    nouveau_loyer = loyer * nouvel_indice / indice_depart;
                    nouveau_charge = charge * nouvel_indice / indice_depart;

                } else if (indice_depart > 0) {
                    nouveau_loyer = loyer * nouvel_indice / indice_depart;

                } else if ($('#type_loyer').val() == 'charges_comprises') {
                    nouveau_loyer = loyer * nouvel_indice;
                    nouveau_charge = charge * nouvel_indice;
                } else {
                    nouveau_loyer = loyer * nouvel_indice;
                }
            }

            $('#loyer_cc').val(loyer_cc);
            $('#nouveau_loyer').val((nouveau_loyer + nouveau_charge).toFixed(2));
            $('#nouv_hors_charge').html(nouveau_loyer.toFixed(2));
            $('#nouv_charge').html(nouveau_charge.toFixed(2));


        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
@endpush
