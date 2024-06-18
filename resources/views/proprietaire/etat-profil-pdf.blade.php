@extends('proprietaire.index-pdf')
@section('contenue')
  <!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y pt-0">
      <div class="row g-0 justify-content-center p-4">
        <!-- HEADER -->
        <div class="row justify-content-between align-items-center mb-4">
          <div class="col-lg-4">
            <h3 class="page-header page-header-top m-0">{{ $etat_lieu->name }}</h3>
          </div>
        </div>
        <!-- END HEADER -->
        <div class="card mb-3">
          <h4 class="card-header">Information Générales</h4>
          <div class="card-body">
            <div class="table-responsive text-nowrap row">
              <div class="col-6">
                <p><strong>IDENTIFIANT : </strong> <span>{{ $etat_lieu->name }}</span></p>
              </div>
              <div class="col-6">
                <p><strong>LOCATION : </strong> 
                  @if ($etat_lieu->location)                    
                   <span>{{ $etat_lieu->type_etat->name }}</span>
                  @else
                    <span class="badge bg-label-warning">non assigné</span>
                  @endif
                </p>
              </div>
              <div class="col-6">
                <p><strong>TYPE : </strong> <span>{{ $etat_lieu->type_etat->name }}</span></p>
              </div>
              <div class="col-6">
                <p><strong>LOCATAIRE : </strong>
                  @if ($etat_lieu->location)                    
                  <span> {{ $etat_lieu->location->locataire->TenantLastName . ' ' . $etat_lieu->location->locataire->TenantFirstName }}</span>
                 @else
                   <span class="badge bg-label-warning">non assigné</span>
                 @endif
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="card mb-3">
          <h6 class="card-header">RELEVÉS DES COMPTEURS EAU, GAZ</h6>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered overflow-hidden">
                <thead>
                  <tr>
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
                      <strong>{{ $eau->name }}</strong>
                    </td>
                    <td>{{ $eau->numero }}</td>
                    <td>
                      {{ $eau->volume }}
                    </td>
                    <td>
                      @if ($eau->fonctionnement)
                      <span>{{ $eau->fonctionnement->name }}</span>
                      {{-- @else
                      <span class="badge bg-label-danger me-1">
                        pas defini
                      </span> --}}
                      @endif

                    </td>
                    {{-- @if ($eau->observation)
                      
                      @endif --}}
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
        <div class="card mb-3">
          <h6 class="card-header">RELEVE COMPTEUR ELECTRIQUE</h6>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered overflow-hidden">
                <thead>
                  <tr>
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
                      <strong>{{ $elec->name }}</strong>
                    </td>
                    <td>{{ $elec->numero }}</td>
                    <td>
                      {{ $elec->volume }}
                    </td>
                    <td>
                      @if ($elec->fonctionnement)
                      <span>{{ $elec->fonctionnement->name }}</span>
                      {{-- @else
                      <span class="badge bg-label-danger me-1">
                        pas defini
                      </span> --}}
                      @endif

                    </td>
                    {{-- @if ($elec->observation)
                      
                      @endif --}}
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
        <div class="card mb-3">
          <h6 class="card-header">TYPE DE CHAUFFAGE</h6>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered overflow-hidden">
                <thead>
                  <tr>
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
                      <strong>{{ $type_chauffage->name }}</strong>
                    </td>
                    <td>{{ $type_chauffage->numero }}</td>
                    <td>
                      {{ $type_chauffage->volume }}
                    </td>
                    <td>
                      @if ($type_chauffage->fonctionnement)
                      <span>{{ $type_chauffage->fonctionnement->name }}</span>
                      {{-- @else
                      <span class="badge bg-label-danger me-1">
                        pas defini
                      </span> --}}
                      @endif

                    </td>
                    {{-- @if ($type_chauffage->observation)
                      
                      @endif --}}
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
        <div class="card mb-3">
          <h6 class="card-header">PRODUCTION D’EAU CHAUDE</h6>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered overflow-hidden">
                <thead>
                  <tr>
                    <th>PRODUCTION</th>
                    <th>FONCTIONNEMENT</th>
                    <th>OBSERVATION</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($etat_lieu->production_eau_chaudes as $production_eau_chaude)                    
                  <tr>
                    <td>
                      <strong>{{ $production_eau_chaude->name }}</strong>
                    </td>
                    <td>
                      @if ($production_eau_chaude->fonctionnement)
                      <span>{{ $production_eau_chaude->fonctionnement->name }}</span>
                      {{-- @else
                      <span class="badge bg-label-danger me-1">
                        pas defini
                      </span> --}}
                      @endif

                    </td>
                    {{-- @if ($production_eau_chaude->observation)
                      
                      @endif --}}
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
        <div class="card mb-3">
          <h6 class="card-header">REMISE DES CLÉS</h6>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered overflow-hidden">
                <thead>
                  <tr>
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
                      <strong>{{ $etat_lieu->cle->type }}</strong>
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
    </div>
  <!-- / Content -->
  <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->
@endsection
