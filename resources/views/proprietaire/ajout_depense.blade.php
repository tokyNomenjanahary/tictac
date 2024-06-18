@extends('proprietaire.index')
@section('contenue')
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- HEADER -->
            <div class="mb-4">
                <div>
                    <h3 class="page-header page-header-top m-0"> {{ __('finance.Ajouter_une_depense') }}</h3>
                </div>
            </div>
            <!-- END HEADER -->

            <!-- CARD FILTER -->
            <form action="{{ route('finance.enregistrer-depense') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <div class="card h-100">
                        <div class="card-header border-bottom py-2 px-3">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2 w-auto">{{ __('depense.Depense') }}</h5>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-3 border-bottom">
                                <div class="mb-3 row">
                                    <label for="html5-text-input"
                                           class="col-md-2 col-form-label text-md-end">{{ __('revenu.Location') }}</label>
                                    <div class="col-lg-5">
                                        <select name="location" id="location" class="form-select">
                                            <option value="">{{ __('revenu.lie') }}</option>
                                            @foreach ($locations as $location)
                                                <option value="{{ $location->id }}">{{ $location->identifiant }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="html5-text-input"
                                        class="col-md-2 col-form-label text-md-end">{{ __('revenu.Bien') }}</label>
                                    <div class="col-lg-5">
{{--                                        <select name="bien" id="bien" onchange="err(this)" class="form-select">--}}
{{--                                            <option value="">{{ __('revenu.Pas_lié_a_un_bien') }}</option>--}}
{{--                                            @foreach ($logements as $logement)--}}
{{--                                                <option value="{{ $logement->id }}">{{ $logement->identifiant }}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
                                        <input type="text" class="form-control" id="bien" name="bien" hidden>
                                        <input type="text" class="form-control" readonly id="Identifiantbien">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="html5-text-input"
                                           class="col-md-2 col-form-label text-md-end">{{ __('depense.payeur') }} *
                                    </label>
                                    <div class="col-lg-5">
{{--                                        <select class="form-select" id="locataire" onchange="err(this)" name="locataire_id">--}}
{{--                                            <option value="">{{ __('revenu.Selectionner_le_payeur') }}</option>--}}
{{--                                            @foreach ($locataires as $locataire)--}}
{{--                                                <option value="{{ $locataire->id }}">{{ $locataire->TenantFirstName }}--}}
{{--                                                </option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
                                        <input type="text" class="form-control" id="locataire" name="locataire" hidden>
                                        <input type="text" readonly class="form-control" id="Nomlocataire">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="html5-text-input"
                                        class="col-md-2 col-form-label text-md-end">{{ __('revenu.Type') }}*</label>
                                    <div class="col-lg-5">
                                        <select class="form-select" onchange="err(this)" id="Type" name="Type">
                                            <option value="">{{ __('depense.Choisir') }}</option>
                                            <option value="{{ __('depense.fonciers') }}">{{ __('depense.fonciers') }}
                                            </option>
                                            <option value="{{ __('depense.Amortissements') }}">
                                                {{ __('depense.Amortissements') }}</option>
                                            <option value="{{ __('depense.Avoir') }}">{{ __('depense.Avoir') }}</option>
                                            <option value="{{ __('depense.Charge_deductible') }}">
                                                {{ __('depense.Charge_deductible') }}</option>
                                            <option value="{{ __('depense.charge_locataire') }}">
                                                {{ __('depense.charge_locataire') }}</option>
                                            <option value="{{ __('depense.charge_administration') }}">
                                                {{ __('depense.charge_administration') }}</option>
                                            <option value="{{ __('depense.annonces') }}">{{ __('depense.annonces') }}
                                            </option>
                                            <option value="{{ __('depense.deplacement') }}">
                                                {{ __('depense.deplacement') }}</option>
                                        </select>
                                    </div>
                                </div>
{{--                                <div class="mb-3 row">--}}
{{--                                    <label for="html5-text-input"--}}
{{--                                        class="col-md-2 col-form-label text-md-end">{{ __('revenu.Fréquence') }}</label>--}}
{{--                                    <div class="col-lg-5">--}}
{{--                                        <select class="form-select" id="frequence" name="frequence">--}}
{{--                                            <option value="Une fois">{{ __('revenu.Une_fois') }}</option>--}}
{{--                                            <option value="Réccurent">{{ __('revenu.Réccurent') }}</option>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="mb-3 row">
                                    <label for="html5-date-input"
                                        class="col-md-2 col-form-label text-md-end">{{ __('finance.Date') }}*</label>
                                    <div class="col-lg-5">
                                        <input type="date" class="form-control" id="date" name="date"
                                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-header border-bottom py-2 px-3">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2 w-auto">{{ __('finance.Montant') }}</h5>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="p-3 border-bottom">

                                <div class="mb-3 row">
                                    <label for="html5-date-input"
                                        class="col-md-2 col-form-label text-md-end">{{ __('finance.Montant') }} *
                                    </label>
                                    <div class="col-lg-5">
                                        <input class="form-control" type="number" placeholder="€" id="montant"
                                            name="montant">
                                        {{-- <p>Saisir un montant ou un pourcentage. € ou %</p> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 border-bottom">
                            <div class="card-header border-bottom py-2 px-3 mb-3">
                                <div class="card-title mb-0">
                                    <h5 class="m-0 me-2 w-auto">{{ __('depense.Autres_informations') }}</h5>
                                </div>
                            </div>
                            <div class="mb-3 row ">
                                <label for="html5-text-input"
                                    class="col-md-2 col-form-label text-md-end">{{ __('depense.Description') }}</label>
                                <div class="col-lg-5">
                                    <textarea class="form-control" id="Autres_informations" name="Autres_informations"></textarea>
                                </div>
                            </div>
                        </div>
                            <div class="mb-3 mt-3 row text-center">
                                <label for="html5-text-input" class="col-md-2 col-form-label text-md-end"></label>
                                <div class="col-lg-8">
                                    <a class="btn btn-dark" href="{{ route('finance.annuler') }}"
                                        style="color:white;">{{ __('depense.Annuler') }}</a>
                                    <button class="btn btn-primary"
                                        id="enregistrer-depense">{{ __('depense.Sauvegarder') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END CARD FILTER -->
        </div>
        <!-- / Content -->
        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->
@endsection
@push('script')
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[type="hidden"]').attr('value')
                }
            });
        })
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
                    console.log(data)
                    $('#locataire').val(data.locataire.id)
                    $('#Nomlocataire').val(data.locataire.TenantFirstName)
                    $('#bien').val(data.logement.id)
                    $('#Identifiantbien').val(data.logement.identifiant)
                }
            });
        });

        $("#enregistrer-depense").on("click", function(e) {
            e.preventDefault()
            var bien = $("#bien").val()
            var location = $("#location").val()
            var Type = $("#Type").val()
            var frequence = $("#frequence").val()
            var date = $("#date").val()
            var locataire = $("#locataire").val()
            var montant = $("#montant").val()
            var Autres_informations = $("#Autres_informations").val()

            $("#myLoader").removeClass("d-none")


            $.ajax({
                type: 'POST',
                url: 'enregistrer-depense',
                dataType: "json",
                data: {
                    bien: bien,
                    location: location,
                    Type: Type,
                    frequence: frequence,
                    date: date,
                    locataire: locataire,
                    Autres_informations: Autres_informations,
                    montant: montant,
                },
                success: function(data) {
                    if ($.isEmptyObject(data.errors)) {
                        window.location = "{{ route('proprietaire.finance') }}"
                    } else {
                        ErrorMsg(data.errors)
                        $("#myLoader").addClass("d-none")
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
