@extends('proprietaire.index')
@section('contenue')
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row alert" style="background-color:#bce8f1">
                <div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                        style="float:right;"></button>
                    <span class="lead"
                        style="line-height: 40px;font-size: 25px;margin-bottom: 20px;">{{ __('bilan.Information') }}</span>
                    <br>
                    <p style="margin-top:10px;font-size:15px;">{{ __('bilan.detail_info') }}</p>
                </div>
            </div>


            <div class="row">
                <div class="card mb-4 p-3">
                    <div class="row">

                        <div class="col-lg-3 col-md-12 mb-3">
                            <div class="form-group">
                                    <select id="annee" class="form-select" name="annee" onchange="updateBilan()">
                                        @for ($i = now()->year; $i >= 2019; $i--)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                            </div>
                        </div>
                        @if(isset($biens ))
                            <div class="col-lg-3 col-md-12 mb-3">
                                <div class="form-group">
                                    <div class="form-contoll">
                                        <select id="bien" class="form-select" onchange="updateBilan()">
                                            <option value="">Tous les biens</option>
                                            @foreach ($biens as $item)
                                                <option value="{{ $item->id }}">{{ $item->identifiant }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="card mb-4 p-3">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row align-items-center g-0 px-2" style="border: 1px solid #F3F5F6;margin-top:10px;padding-top:8px;">
                                <div class="col">
                                    <h2>{{ __('finance.Tous_les_biens') }}</h2>
                                </div>
                            </div>
                            <div class="row align-items-center g-0 px-2" style="border: 1px solid #F3F5F6;margin-top:10px;padding:8px;">
                                <div class="col-lg-6" style="border: 1px solid #F3F5F6;padding:10px;">
                                    <p class="form-label">{{ __('finance.revenu_brut') }}</p>
                                    <h4 class="text-success"> <span id="revenu_brute">{{ $revenuBrute }} </span> €
                                    </h4>
                                </div>
                                <div class="col-lg-6" style="border: 1px solid #F3F5F6;padding:10px;">
                                    <p class="form-label">{{ __('finance.depense') }}</p>
                                    <h4 class="text-danger"><span id="depense">{{ -$depense }} €</span></h4>
                                </div>
                            </div>
                            <div class="row align-items-center g-0 px-2"
                                style="border: 1px solid #F3F5F6;margin-top:10px;padding-top:8px;">
                                <div class="col">
                                    <ul>
                                        <li>{{ __('finance.Loyer_hors_Charges') }} <span id="sommeHC">
                                                {{ $sommeHC }}</span> €</li>
                                        <li>{{ __('finance.Charges') }} <span id="sommeC">{{ $sommeC }} </span>€
                                        </li>
                                        <li>{{ __('bilan.Autre_revenu') }} <span id="autre_revenu"> {{ $autreRevenu }} €
                                            </span></li>
                                        <li>{{ __('finance.tva_encaissee') }} 0.00 €</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row align-items-center g-0 px-2" style="border: 1px solid #F3F5F6;margin-top:8px;">
                                <div class="w-auto">
                                    <i class="fas fa-chart-line nav-dash-icon-size"></i>
                                </div>
                                <div class="col">
                                    <div style="padding-top:10px;">
                                        <p>{{ __('finance.resultat_net') }}</p>
                                    </div>
                                    <div>
                                        <h3 class="text-success" id="total">{{ $total }} €</span></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row alert" style="background-color:#F9F9F9">
                <div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                        style="float:right;"></button>
                    <span class="lead"
                        style="line-height: 40px;font-size: 25px;margin-bottom: 20px;">{{ __('bilan.aide') }}</span>
                    <br>
                    <p style="margin-top:10px;font-size:15px;">{{ __('bilan.detail_aide') }}</p>
                </div>
            </div>
            @if(isset($biens))
            <div class="row">
                <div class="tab-content" style="padding-left: 12px;margin-top:-38px;padding-right: 12px;">
                    <div class="tab-pane active " id="coloc_acif" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row"
                            style="background-color:white;margin-top: 30px;border:1px solid #eeeeee;padding:25px;">
                            <div class="row-fluid pb-2 px-0">
                                <div class="col col-md-7 col-lg-4">
                                    <select class="form-select" style="padding: 8px;" id="bilanFoncier">
                                        <option value="revenuFoncierTab" selected="selected">Revenus fonciers : Régime réel
                                        </option>
                                        <option value="LmnpReelTab">LMNP : Régime réel</option>
                                        <option value="MicroBicTab">Micro-entreprise BIC </option>
                                        <option value="MicroFoncierTab">Micro Foncier </option>
                                    </select>
                                </div>
                            </div>
                            @include('proprietaire.revenuFoncier')
                            <div>
                                <button type="button" class="btn btn-secondary btn-sm" id="export_bilan_fiscal">Export</button>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- @include('proprietaire.suggestion') --}}
        </div>
            @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                datasets: [{
                    label: 'Revenues',
                    data: {!! json_encode($revenues) !!},
                    borderColor: 'green',
                    fill: false,
                }, {
                    label: 'Dépénses',
                    data: {!! json_encode($expenses) !!},
                    borderColor: 'red',
                    fill: true,
                    backgroundColor: '#F2EBE6'
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        function updateInterface(data) {
            $('#revenu_brute').html(data.revenuBrute);
            $('#depense').html((data.depense=='0')? data.depense:'-'+data.depense);
            $('#sommeHC').html(data.sommeHC);
            $('#sommeC').html(data.sommeC);
            $('#autre_revenu').html(data.autreRevenu);
            $('#total').html(data.total);

            /* mettre à jour graph */
            myChart.destroy();
            myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Revenues',
                        data: data.revenues,
                        borderColor: 'green',
                        fill: false,
                    }, {
                        label: 'Dépénses',
                        data: data.expenses ,
                        borderColor: 'red',
                        fill: true,
                        backgroundColor: '#F2EBE6'
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });

            /* mettre a jour l'affichage dans le revenu foncier */
            $('.totals').html(data.totals+" €");
            $('.somme-avec').html(data.sommeAvec+" €");
            $('.sommeHC').html(data.sommeHC+" €");
            $('.annee').html($('#annee').val());
        }



        function updateBilan() {
            let annee = $('#annee').val();
            let bien = $('#bien').val();
            $("#myLoader").removeClass("d-none");
            jQuery.ajax({
                url: "{{ route('proprietaire.filtre_bilan') }}",
                method: 'get',
                data: {
                    annee: annee,
                    bien: bien
                },
                success: function(result) {
                    updateInterface(result.data);
                    $("#myLoader").addClass("d-none");
                },
                error: function(data) {

                }
            });
        }
    </script>
@endsection
