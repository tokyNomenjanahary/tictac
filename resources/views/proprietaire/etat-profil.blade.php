@extends('proprietaire.index')

@section('contenue')
<style>
  .small-v{
    display: none;
  }
  .lg-v{
    display: block
  }
  .w-50-cust{
    width: 400px;
    height: 400px;
    object-fit: contain;
  }
  .carousel-indicators{
    bottom: -10% !important;
  }

  .carousel-control-next-icon, .carousel-control-prev-icon {
    background: none !important;  
  }

  .carousel-indicators [data-bs-target]{
    background-color: #000 !important;
  }
  @media screen and (max-width: 756px) {
    .small-v{
      display: block;
    }
    .lg-v{
      display: none
    }
    .w-50-cust{
    width: auto;
  }
  }
</style>
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
          <div class="col-lg-4" style="text-align: right;">
            <div class="dropdown">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="true">
                    Action
                </button>
                <div class="dropdown-menu" data-popper-placement="bottom-start"
                  style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(126px, 41px);">
                    <a class="dropdown-item" href="{{ route('proprietaire.ajout-etat', $etat_lieu->id) }}">
                      Modifier
                    </a>
                    <a class="dropdown-item" href="{{ route('proprietaire.exporter-etat-word', $etat_lieu->id) }}">
                      Télecharger word
                    </a>
                    <a class="dropdown-item" href="{{ route('proprietaire.exporter-etat', $etat_lieu->id) }}">
                      Télecharger PDF
                    </a>
                </div>
            </div>
          </div>
        </div>
        <!-- END HEADER -->
        <div class="nav-align-top mb-4">
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#info-ge" aria-controls="info-ge" aria-selected="true">
                Info Générale
              </button>
            </li>
            @if ($etat_lieu->etat_pieces)
              @foreach ($etat_lieu->etat_pieces as $key => $piece)
              <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#{{'pi-' . \Illuminate\Support\Str::slug($piece->identifiant) .'-'. $key }}" aria-controls="{{ \Illuminate\Support\Str::slug($piece->identifiant) .'-'. $key }}" aria-selected="false">
                  {{ $piece->identifiant }}
                </button>
              </li>
              @endforeach
            @endif
            @if ($etat_lieu->observation)
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#etat-obs" aria-controls="etat-obs" aria-selected="true">
                Observation
              </button>
            </li>
            @endif
            @if (!$etat_lieu->etat_files->isEmpty())
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#etat-docs" aria-controls="etat-docs" aria-selected="true">
                Documents
              </button>
            </li>
            @endif
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade active show" id="info-ge" role="tabpanel">
              <div class="card mb-3">
                <h4 class="card-header">Information Générales</h4>
                <div class="card-body">
                  <div class="table-responsive text-nowrap row">
                    <div class="col-12 col-md-6 col-lg-6">
                      <p><strong>IDENTIFIANT : </strong> <span>{{ $etat_lieu->name }}</span></p>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                      <p><strong>LOCATION : </strong> 
                        @if ($etat_lieu->location)                    
                         <span>{{ $etat_lieu->location->identifiant }}</span>
                        @else
                          <span class="badge bg-label-warning">non assigné</span>
                        @endif
                      </p>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                      <p><strong>TYPE : </strong> <span>{{ $etat_lieu->type_etat->name }}</span></p>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
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
              <div class="card mb-3 lg-v">
                <h6 class="card-header">RELEVÉS DES COMPTEURS EAU, GAZ</h6>
                <div class="p-4 small-v">
                  @foreach ($etat_lieu->compteur_eaux as $key=>$eau)                    
                  <div class="card accordion-item mb-2">
                    <h2 class="accordion-header" id="headingTwo_{{ $key }}">
                      <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionTwo_{{ $key }}" aria-expanded="false" aria-controls="accordionTwo">
                        {{ $eau->name }}
                      </button>
                    </h2>
                    <div id="accordionTwo_{{ $key }}" class="accordion-collapse collapse" aria-labelledby="headingTwo_{{ $key }}" data-bs-parent="#accordionExample_{{ $key }}">
                      <div class="accordion-body">
                          <p><strong>N° de serie : </strong><span>{{ $eau->numero }}</span></p>
                          <p><strong>Volume : </strong><span>{{ $eau->volume }}</span></p>
                          <p><strong>Fonctionnement : </strong><span>@if ($eau->fonctionnement){{ $eau->fonctionnement->name }}@endif</span></p>
                          <p><strong>Observation : </strong><span>{{ $eau->observation }}</span></p>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
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
                <div class="p-4 small-v">
                  @foreach ($etat_lieu->compteur_electriques as  $key=>$elec)                    
                  <div class="card accordion-item mb-2">
                    <h2 class="accordion-header" id="headingTwo_{{ $key }}">
                      <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionTwo_{{ $key }}" aria-expanded="false" aria-controls="accordionTwo">
                        {{ $elec->name }}
                      </button>
                    </h2>
                    <div id="accordionTwo_{{ $key }}" class="accordion-collapse collapse" aria-labelledby="headingTwo_{{ $key }}" data-bs-parent="#accordionExample_{{ $key }}">
                      <div class="accordion-body">
                          <p><strong>N° de serie : </strong><span>{{ $elec->numero }}</span></p>
                          <p><strong>Volume : </strong><span>{{ $elec->volume }}</span></p>
                          <p><strong>Fonctionnement : </strong><span>@if ($elec->fonctionnement){{ $elec->fonctionnement->name }}@endif</span></p>
                          <p><strong>Observation : </strong><span>{{ $elec->observation }}</span></p>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
                <div class="card-body lg-v">
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
                <div class="p-4 small-v">
                  @foreach ($etat_lieu->type_chauffages as $key=>$type_chauffage)                    
                  <div class="card accordion-item mb-2">
                    <h2 class="accordion-header" id="headingTwo_{{ $key }}">
                      <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionTwo_{{ $key }}" aria-expanded="false" aria-controls="accordionTwo">
                        {{ $type_chauffage->name }}
                      </button>
                    </h2>
                    <div id="accordionTwo_{{ $key }}" class="accordion-collapse collapse" aria-labelledby="headingTwo_{{ $key }}" data-bs-parent="#accordionExample_{{ $key }}">
                      <div class="accordion-body">
                          <p><strong>N° de serie : </strong><span>{{ $type_chauffage->numero }}</span></p>
                          <p><strong>Volume : </strong><span>{{ $type_chauffage->volume }}</span></p>
                          <p><strong>Fonctionnement : </strong><span>@if ($type_chauffage->fonctionnement){{ $type_chauffage->fonctionnement->name }}@endif</span></p>
                          <p><strong>Observation : </strong><span>{{ $type_chauffage->observation }}</span></p>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
                <div class="card-body lg-v">
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
                <div class="p-4 small-v">
                  @foreach ($etat_lieu->production_eau_chaudes as $key=>$production_eau_chaude)                
                  <div class="card accordion-item mb-2">
                    <h2 class="accordion-header" id="headingTwo_{{ $key }}">
                      <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionTwo_{{ $key }}" aria-expanded="false" aria-controls="accordionTwo">
                        {{ $production_eau_chaude->name }}
                      </button>
                    </h2>
                    <div id="accordionTwo_{{ $key }}" class="accordion-collapse collapse" aria-labelledby="headingTwo_{{ $key }}" data-bs-parent="#accordionExample_{{ $key }}">
                      <div class="accordion-body">
                          <p><strong>Fonctionnement : </strong><span>@if ($production_eau_chaude->fonctionnement){{ $production_eau_chaude->fonctionnement->name }}@endif</span></p>
                          <p><strong>Observation : </strong><span>{{ $production_eau_chaude->observation }}</span></p>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
                <div class="card-body lg-v">
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
              @if ($etat_lieu->cle)   
              <div class="card mb-3">
                <h6 class="card-header">REMISE DES CLÉS</h6>
                <div class="p-4 small-v">            
                  <div class="card accordion-item mb-2">
                    <h2 class="accordion-header" id="headingTwo_{{ $key }}">
                      <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionTwo_{{ $key }}" aria-expanded="false" aria-controls="accordionTwo">
                        {{ $etat_lieu->cle->type }}
                      </button>
                    </h2>
                    <div id="accordionTwo_{{ $key }}" class="accordion-collapse collapse" aria-labelledby="headingTwo_{{ $key }}" data-bs-parent="#accordionExample_{{ $key }}">
                      <div class="accordion-body">
                          <p><strong>Nombre : </strong><span>{{ $etat_lieu->cle->nombre }}</span></p>
                          <p><strong>Date : </strong><span>{{ $etat_lieu->cle->date }}</span></p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body  lg-v">
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
                          
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              @endif
            </div>
            @if ($etat_lieu->observation)
            <div class="tab-pane fade" id="etat-obs" role="tabpanel">
              <p>
                {{ $etat_lieu->observation }}
              </p>
            </div>
            @endif
            @if (!$etat_lieu->etat_files->isEmpty())
            <div class="tab-pane fade" id="etat-docs" role="tabpanel">
              <div class="row">
                {{-- @foreach ($etat_lieu->etat_files as $files)
                  <div class="col-10">
                    <img src="{{ Storage::url($files->file_name) }}" class="100">
                  </div>
                @endforeach --}}

                {{-- caroussel --}}

                <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="false">
                  <div class="carousel-indicators">
                    @foreach ($etat_lieu->etat_files as $key => $files)
                    @if ($key == 0)
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{$key}}" class="active" aria-current="true" aria-label="Slide {{$key}}"></button>
                    @else
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{$key}}" aria-label="Slide {{$key}}"></button>
                    @endif
                    @endforeach
                  </div>
                  <div class="carousel-inner">
                    @foreach ($etat_lieu->etat_files as $key => $files)
                    @if ($key == 0)
                    <div class="carousel-item active">
                      <img src="{{ Storage::url($files->file_name) }}" class="d-block w-50-cust mx-auto">
                    </div>
                    @else
                    <div class="carousel-item">
                      <img src="{{ Storage::url($files->file_name) }}" class="d-block w-50-cust mx-auto">
                    </div>
                    @endif
                    @endforeach
                  </div>
                  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true">
                      <i class="fa-solid fa-chevron-left text-dark fs-2"></i>
                    </span>
                    <span class="visually-hidden">Previous</span>
                  </button>
                  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true">
                      <i class="fa-solid fa-chevron-right text-dark fs-2"></i>
                    </span>
                    <span class="visually-hidden">Next</span>
                  </button>
                </div>

                {{-- Fin caroussel --}}

              </div>
            </div>
            @endif
            @if ($etat_lieu->etat_pieces)
              @foreach ($etat_lieu->etat_pieces as $key => $piece)
              <div class="tab-pane fade" id="{{'pi-' . \Illuminate\Support\Str::slug($piece->identifiant) .'-'. $key }}" role="tabpanel">
                <div class="card mb-3">
                  <h6 class="card-header">Murs, plafond, sol</h6>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered overflow-hidden">
                        <thead>
                          <tr>
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
                  <h6 class="card-header">Equipement</h6>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered overflow-hidden">
                        <thead>
                          <tr>
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
    </div>
  <!-- / Content -->
</div>
<!-- Content wrapper -->
@endsection