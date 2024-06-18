<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        p {
            margin: 0 0 10px 0;
        }

        body {
            font-size: 14px;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        .text-center {
            text-align: center;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .ft-s strong {
            font-size: 14px;
        }

        .ft-s {
            margin: 0 0 0 20px;
            /* display: flex;
            justify-content: center;
            flex-wrap: wrap; */
        }

        /* .col-6 {
            width: 40%;
        } */
        .bg-th {
            background: #aac9e4;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Layout container -->
            <div class="">
                <!-- Content wrapper -->
                <div class="content ">
                    <!-- Content wrapper -->
                    <div class="content-wrapper">
                        <!-- Content -->
                        <div class="container-xxl flex-grow-1 container-p-y pt-0">
                            <div class="row g-0 justify-content-center p-4">
                                <!-- HEADER -->
                                <div>
                                    <h1 class="text-center">{{ $etat_lieu->name }}</h1>
                                </div>
                                <!-- END HEADER -->
                                <div class="">
                                    <h3>INFORMATION GÉNÉRALES</h3>
                                    <div class="ft-s">
                                        <div class="col-6">
                                            <p><strong>IDENTIFIANT : </strong> <span>{{ $etat_lieu->name }}</span></p>
                                        </div>
                                        <div class="col-6">
                                            <p><strong>TYPE : </strong> <span>{{ $etat_lieu->type_etat->name }}</span>
                                            </p>
                                        </div>
                                        <div class="col-6">
                                            <p><strong>BIEN : </strong>
                                                @if ($etat_lieu->location)
                                                    <span>{{ $etat_lieu->location->identifiant }}</span>
                                                @else
                                                    <span class="">non assigné</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-6">
                                            <p><strong>LOCATAIRE : </strong>
                                                @if ($etat_lieu->location)
                                                    <span>
                                                        {{ $etat_lieu->location->locataire->TenantLastName . ' ' . $etat_lieu->location->locataire->TenantFirstName }}</span>
                                                @else
                                                    <span class="">non assigné</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <h3>RELEVÉS DES COMPTEURS EAU, GAZ</h3>
                                    <div class="">
                                        <div class="">
                                            <table class="">
                                                <thead>
                                                    <tr class="bg-th">
                                                        <th>TYPE DE RELEVE</th>
                                                        <th>N° DE SERIE</th>
                                                        <th>m3</th>
                                                        <th>FONCTIONNEMENT</th>
                                                        <th>OBSERVATION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($etat_lieu->compteur_eaux as $eau)
                                                        <tr>
                                                            <td>
                                                                <span>{{ $eau->name }}</span>
                                                            </td>
                                                            <td>{{ $eau->numero }}</td>
                                                            <td>
                                                                {{ $eau->volume }}
                                                            </td>
                                                            <td>
                                                                @if ($eau->fonctionnement)
                                                                    <span>{{ $eau->fonctionnement->name }}</span>
                                                                @endif

                                                            </td>
                                                            <td style="max-width: 250px">
                                                                {{ $eau->observation }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <h3 class="">RELEVE COMPTEUR ELECTRIQUE</h3>
                                    <div class="">
                                        <div class="">
                                            <table class="">
                                                <thead>
                                                    <tr class="bg-th">
                                                        <th>TYPE DE RELEVE</th>
                                                        <th>N° DE SERIE</th>
                                                        <th>m3</th>
                                                        <th>FONCTIONNEMENT</th>
                                                        <th>OBSERVATION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($etat_lieu->compteur_electriques as $elec)
                                                        <tr>
                                                            <td>
                                                                <span>{{ $elec->name }}</span>
                                                            </td>
                                                            <td>{{ $elec->numero }}</td>
                                                            <td>
                                                                {{ $elec->volume }}
                                                            </td>
                                                            <td>
                                                                @if ($elec->fonctionnement)
                                                                    <span>{{ $elec->fonctionnement->name }}</span>
                                                                @endif
                                                            </td>
                                                            <td style="max-width: 250px">
                                                                {{ $elec->observation }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <h3 class="">TYPE DE CHAUFFAGE</h3>
                                    <div class="">
                                        <div class="">
                                            <table class="">
                                                <thead>
                                                    <tr class="bg-th">
                                                        <th>CHAUFFAGE</th>
                                                        <th>N° DE SERIE</th>
                                                        <th>m3</th>
                                                        <th>FONCTIONNEMENT</th>
                                                        <th>OBSERVATION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($etat_lieu->type_chauffages as $type_chauffage)
                                                        <tr>
                                                            <td>
                                                                <span>{{ $type_chauffage->name }}</span>
                                                            </td>
                                                            <td>{{ $type_chauffage->numero }}</td>
                                                            <td>
                                                                {{ $type_chauffage->volume }}
                                                            </td>
                                                            <td>
                                                                @if ($type_chauffage->fonctionnement)
                                                                    <span>{{ $type_chauffage->fonctionnement->name }}</span>
                                                                @endif

                                                            </td>
                                                            <td style="max-width: 250px">
                                                                {{ $type_chauffage->observation }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <h3 class="">PRODUCTION D’EAU CHAUDE</h3>
                                    <div class="">
                                        <div class="">
                                            <table class="">
                                                <thead>
                                                    <tr class="bg-th">
                                                        <th>PRODUCTION</th>
                                                        <th>FONCTIONNEMENT</th>
                                                        <th>OBSERVATION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($etat_lieu->production_eau_chaudes as $production_eau_chaude)
                                                        <tr>
                                                            <td>
                                                                <span>{{ $production_eau_chaude->name }}</span>
                                                            </td>
                                                            <td>
                                                                @if ($production_eau_chaude->fonctionnement)
                                                                    <span>{{ $production_eau_chaude->fonctionnement->name }}</span>
                                                                @endif

                                                            </td>
                                                            <td style="max-width: 250px">
                                                                {{ $production_eau_chaude->observation }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <h3 class="">REMISE DES CLÉS</h3>
                                    <div class="">
                                        <div class="">
                                            <table class="">
                                                <thead>
                                                    <tr class="bg-th">
                                                        <th>Cle</th>
                                                        <th>NOMBRE</th>
                                                        <th>DATE</th>
                                                        <th>COMMENTAIRE</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        @if ($etat_lieu->cle)
                                                            <td>
                                                                <span>{{ $etat_lieu->cle->type }}</span>
                                                            </td>
                                                            <td>{{ $etat_lieu->cle->nombre }}</td>
                                                            <td>
                                                                {{ $etat_lieu->cle->date }}
                                                            </td>
                                                            <td>
                                                                <span>{{ $etat_lieu->cle->observation }}</span>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="page-break"></div>
                            <div class="row g-0 justify-content-center p-4">
                                @if ($etat_lieu->etat_files)
                                    <h1 class="text-center">Photo(s)</h1>
                                    @foreach ($etat_lieu->etat_files as $file)
                                        @if (file_exists(storage_path('app/public/' . $file->file_name)))
                                            <div class="text-center">
                                                <img width="60%" height="auto"
                                                    src="{{ storage_path('app/public/' . $file->file_name) }}"
                                                    alt="--------" />
                                            </div>
                                            <br>
                                            <br>
                                        @endif
                                    @endforeach
                                @endif
                            </div>

                            <div class="page-break"></div>
                            <div class="row g-0 justify-content-center p-4">
                                @if ($etat_lieu->etat_pieces)
                                    @foreach ($etat_lieu->etat_pieces as $key => $piece)
                                        <h1 class="text-center">{{ $piece->identifiant }}</h1>
                                        <div class="tab-pane fade" id="{{ $piece->identifiant . '-' . $key }}"
                                            role="tabpanel">
                                            <div class="card mb-3">
                                                <h3>MURS, PLAFOND, SOL</h3>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered overflow-hidden">
                                                            <thead>
                                                                <tr class="bg-th">
                                                                    <th>ELEMENT</th>
                                                                    <th>REVÊTEMENT</th>
                                                                    <th>ETAT D'USURE</th>
                                                                    <th>OBSERVATION</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if ($piece->properties)
                                                                    @foreach ($piece->properties as $property)
                                                                        <tr>
                                                                            <td>
                                                                                <strong>{{ $property->element }}</strong>
                                                                            </td>
                                                                            <td>{{ $property->revetement }}</td>
                                                                            <td>
                                                                                @if ($property->etat_usure)
                                                                                    {{ $property->etat_usure->name }}
                                                                                @endif
                                                                            </td>
                                                                            <td style="max-width: 250px">
                                                                                {{ $property->commentaire }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <h3>EQUIPEMENT</h3>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered overflow-hidden">
                                                            <thead>
                                                                <tr class="bg-th">
                                                                    <th>ELEMENT</th>
                                                                    <th>MATÉRIAUX</th>
                                                                    <th>ETAT D'USURE</th>
                                                                    <th>FONCTIONNEMENT</th>
                                                                    <th>OBSERVATION</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if ($piece->equipements)
                                                                    @foreach ($piece->equipements as $equipement)
                                                                        <tr>
                                                                            <td>
                                                                                <strong>{{ $equipement->element }}</strong>
                                                                            </td>
                                                                            <td>{{ $equipement->materiaux }}</td>
                                                                            <td>
                                                                                @if ($equipement->etat_usure)
                                                                                    {{ $equipement->etat_usure->name }}
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @if ($equipement->fonctionnement)
                                                                                    {{ $equipement->fonctionnement->name }}
                                                                                @endif
                                                                            </td>
                                                                            <td style="max-width: 250px">
                                                                                {{ $equipement->commentaire }}
                                                                            </td>
                                                                        </tr>
                                                                        <div class="page-break"></div>
                                                                    @endforeach
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                        </div>
                    </div>
                    <!-- Content wrapper -->
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
    </div>
</body>

</html>
