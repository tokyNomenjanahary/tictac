@extends('proprietaire.index')
<style>
  .nav-dash-icon-size{
    font-size: 3.5rem;
  }
  #etat-des-lieux_length, #etat-des-lieux-arc_length {
    display: none;
  }
  #etat-des-lieux_filter, #etat-des-lieux_info, #etat-des-lieux-arc_filter, #etat-des-lieux_paginate{
    padding: 1rem;
  }
  #etat-des-lieux_filter, #etat-des-lieux-arc_filter {
    display: block !important;
  }
  .initial {
    display: inline-block;
    width: 40px !important;
    height: 40px !important;
    line-height: 40px;
  }

  th {
      color: blue !important;
      font-size: 10px !important;
  }
  td{
      font-size:13px;
      min-height: 40px;
  }
  .btn-new-state {
    text-align: right;
  }

  .btn-new-state-lg {
    text-align: left;
  }

  .mbr-3 {
    margin-bottom: 0rem;
  }

  @media screen and (max-width: 576px) {
    .btn-new-state {
      margin-top: 1rem !important; 
      text-align: center;
    }
    .cust-d-none {
      display: none !important;
    }
    
    .mbr-3 {
      margin-bottom: 1rem !important;
    }
  }

</style>
@section('contenue')


<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
      <!-- HEADER -->
      <div class="row justify-content-between align-items-center mb-4">
        <div class="col-12 col-lg-4 col-sm-12 col-md-3 mbr-3 btn-new-state btn-new-state-lg">
          <h3 class="page-header page-header-top m-0">État des lieux</h3>
        </div>
        <div class="col-12 col-lg-4 col-sm-7 col-md-5">
          <ul class="nav nav-tabs justify-content-center" role="tablist" style="border: none;">
              <li class="nav-item etat-status">
                  <a class="nav-link active unchecked" style="border:1px solid #EBF2E2;color:blue;" type="botton"
                  role="tab" data-bs-toggle="tab" data-bs-target="#etat-container-active" aria-controls="etat-container-active" aria-selected="false">
                    <i class="fa fa-check"></i>
                    Actifs
                    <span class="badge bg-primary" id="ActifsCounts">{{ $count_active }}</span>
                  </a>
              </li>
              <li class="nav-item etat-status">
                  <a class="nav-link unchecked" style="border:1px solid #f9f9f9;color:blue;" type="botton"
                  role="tab" data-bs-toggle="tab" data-bs-target="#etat-container-arc" aria-controls="etat-container-arc" aria-selected="true">
                    <i class="fa fa-folder-open"></i> Archives
                      <span class="badge bg-primary" id="ArchiveCounts">{{ $count_arc }}</span>
                  </a>
              </li>
          </ul>
        </div>
        <div class="col-12 col-lg-4 col-sm-5 col-md-4 btn-new-state">
          <a class="btn btn-primary" href="{{ route('proprietaire.ajout-etat') }}">
              Nouvel état des lieux
          </a>
        </div>
      </div>      
      <div class="row g-0 justify-content-center" style="background-color:white;margin-top: 30px;border:1px solid #eeeeee;">
        <form>
          <div class="tab-content">
            <div id="etat-container-active" class="tab-pane fade active show">
              <table class="table table-hover table-responsive" style="margin-bottom:0px;" id="etat-des-lieux">
                <thead>
                  <tr>
                    <th><input type="checkbox" style="height: 20px;width:20px;" class="select_all_state"></th>
                    <th>Identifiant</th>
                    <th class="cust-d-none">type</th>
                    <th class="cust-d-none">Bien</th>
                    <th class="cust-d-none">Locataire</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($etat_lieux as $etat_lieu)
                  @if ($etat_lieu->is_active)
                  <tr style="cursor: pointer" onclick="leave({{ $etat_lieu->id }},'{{ Illuminate\Support\Str::slug($etat_lieu->name) }}');">
                    <td>
                      <input type="checkbox" style="height: 20px;width:20px;" id="{{ "check_etat_" . $etat_lieu->id }}" value="{{ $etat_lieu->id }}" class="check-etat stop-prop">
                    </td>
                    <td style="max-width: 300px">
                      <span style="cursor: pointer" data-bs-toggle="tooltip" data-bs-html="true">
                      {{ $etat_lieu->name }}
                      </span>
                    </td>
      
                    <td class="cust-d-none">{{ $etat_lieu->type_etat->name }}</td>
      
                    <td class="cust-d-none">
                        @if ($etat_lieu->location && $etat_lieu->location->logement)
                        <span>{{ $etat_lieu->location->logement->identifiant }}</span>
                        @else
                        <span class="badge bg-label-info w-auto">Non assigné</span>
                        @endif
                    </td>
          
                    <td scope="row" class="row align-items-center g-0 cust-d-none">
                        @if ($etat_lieu->location && $etat_lieu->location->locataire) 
                          @php
                            $bg = sprintf('#%06X', mt_rand(0, 0xFFFF));
                          @endphp
                          <div class="initial rounded-circle text-center text-white" style="background: {{ $bg }};">
                            {{ $etat_lieu->location->locataire->TenantLastName[0] . $etat_lieu->location->locataire->TenantFirstName[0] }} 
                          </div>
                          <div class="col ps-2"> {{ $etat_lieu->location->locataire->TenantLastName . ' ' . $etat_lieu->location->locataire->TenantFirstName }}</div>
                        @else
                          <span class="badge bg-label-info w-auto">Non assigné</span>
                        @endif
                    </td>      
                    <td>
                      <div class="dropdown handle-event">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow stop-prop mx-4 my-2" data-bs-toggle="dropdown">
                          <i class="bx bx-dots-horizontal-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item" href="{{ route('proprietaire.ajout-etat', $etat_lieu->id) }}"><i class="fa fa-pencil me-1"></i> Modifier</a>
                          <a class="dropdown-item" href="{{ route('proprietaire.exporter-etat', $etat_lieu->id) }}"><i class="fa-regular fa-file-pdf"></i> Telecharger pdf</a>
                          <a class="dropdown-item" href="{{ route('proprietaire.exporter-etat-word', $etat_lieu->id) }}"><i class="fa-regular fa-file-word"></i> Telecharger word</a>
                          <a class="dropdown-item archive-etat-one" data-id="{{ $etat_lieu->id }}"><i class="fas fa-archive me-1"></i>Archiver</a>
                          <a class="dropdown-item delete-etat-one" data-id="{{ $etat_lieu->id }}" data-bs-toggle="modal" data-bs-target="#deleteState"><i class="fa-solid fa-trash" style="color:red;"></i> Supprimer</a>
                        </div>
                      </div>                    
                    </td>
                  </tr>
                  @endif
                  @endforeach
                </tbody>
              </table>
              <div class="d-flex ps-3 mb-3 d-none" id="action_btn">
                <button class="btn btn-danger btn-sm handle-click delete-etat">
                  <i class="fa-solid fa-trash"></i>&nbsp;SUPPRIMER
                </button>
                <button class="btn btn-secondary btn-sm handle-click archive-etat">
                  <i class="fa-solid fa-trash"></i>&nbsp;ARCHIVER
                </button>
              </div>
            </div>
            <div id="etat-container-arc" class="tab-pane fade">
              <table class="table table-hover table-responsive" style="margin-bottom:0px;" id="etat-des-lieux-arc">
                <thead>
                  <tr>
                    <th><input type="checkbox" style="height: 20px;width:20px;" class="select_all_state_arc"></th>
                    <th>Identifiant</th>
                    <th style="min-width: 200;"  class="cust-d-none">type</th>
                    <th class="cust-d-none">Bien</th>
                    <th class="cust-d-none">Locataire</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($etat_lieux as $etat_lieu)
                  @if (!$etat_lieu->is_active) 
                  <tr style="cursor: pointer" onclick="leave({{ $etat_lieu->id }}, '{{ Illuminate\Support\Str::slug($etat_lieu->name) }}');">
                    <td>
                      <input type="checkbox" style="height: 20px;width:20px;" id="{{ "check_etat_" . $etat_lieu->id }}" value="{{ $etat_lieu->id }}" class="check-etat-arc stop-prop">
                    </td>
                    <td style="max-width: 300px">
                      <span style="cursor: pointer" data-bs-toggle="tooltip" data-bs-html="true">
                      {{ $etat_lieu->name }}
                      </span>
                    </td>
      
                    <td><span>{{ $etat_lieu->type_etat->name }}</span></td>
      
                    <td class="cust-d-none">
                        @if ($etat_lieu->location && $etat_lieu->location->logement)
                        <span>{{ $etat_lieu->location->logement->identifiant }}</span>
                        @else
                        <span class="badge bg-danger">Non assigné</span>
                        @endif
                    </td>
          
                    <td scope="row" class="row align-items-center g-0 custom-d-none">
                        @if ($etat_lieu->location && $etat_lieu->location->locataire) 
                          @php
                            $bg = sprintf('#%06X', mt_rand(0, 0xFFFF));
                          @endphp
                          <span class="initial rounded-circle text-center text-white" style="background: {{ $bg }};">
                            {{ $etat_lieu->location->locataire->TenantLastName[0] . $etat_lieu->location->locataire->TenantFirstName[0] }} 
                          </span>
                          <div class="col ps-2"> {{ $etat_lieu->location->locataire->TenantLastName . ' ' . $etat_lieu->location->locataire->TenantFirstName }}</div>
                        @else
                          <span class="badge bg-danger">Non assigné</span>
                        @endif
                    </td>      
                    <td>
                      <div class="dropdown handle-event">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow stop-prop mx-4 my-2" data-bs-toggle="dropdown">
                          <i class="bx bx-dots-horizontal-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item" href="{{ route('proprietaire.ajout-etat', $etat_lieu->id) }}"><i class="fa fa-pencil me-1"></i>Modifier</a>
                          <a class="dropdown-item" href="{{ route('proprietaire.exporter-etat', $etat_lieu->id) }}"><i class="fa-regular fa-file-pdf"></i> Telecharger pdf</a>
                          <a class="dropdown-item" href="{{ route('proprietaire.exporter-etat-word', $etat_lieu->id) }}"><i class="fa-regular fa-file-word"></i> Telecharger word</a>
                          <a class="dropdown-item archive-etat-one" data-id="{{ $etat_lieu->id }}"><i class="fas fa-archive me-1"></i>Désarchiver</a>
                          {{-- <a class="dropdown-item delete-etat-one" data-id="{{ $etat_lieu->id }}"><i class="fa-solid fa-trash" style="color:red;"></i> Supprimer</a> --}}
                        </div>
                      </div>
                      <div class="modal fade" id="deleteModal{{$etat_lieu->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalTitleId">Suppression</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Voulez-vous vraiment supprimer cet élément?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary ferme" data-bs-dismiss="modal">{{ __('depense.Annuler') }}</button>
                                    <button type="button" class="btn btn-danger delete-confirm{{$etat_lieu->id}}">{{__('finance.Supprimer')}}</button>
                                </div>
                            </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                  @endif
                  @endforeach
                </tbody>
              </table>
              <div class="d-flex ps-3 mb-3 d-none" id="action_btn_arc">
                <button class="btn btn-danger btn-sm handle-click-arc delete-etat">
                  <i class="fa-solid fa-trash"></i>&nbsp;SUPPRIMER
                </button>
                <button class="btn btn-secondary btn-sm archive-etat handle-click-arc">
                  <i class="fa-solid fa-trash"></i>&nbsp;DESARCHIVER
                </button>
              </div>
            </div>
          </div>
        </form>     
      </div>

    </div>
  <!-- / Content -->
  <div class="content-backdrop fade"></div>
  <div class="modal fade" id="deleteState" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment supprimer cet élément?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('depense.Annuler') }}</button>
                <button class="btn btn-danger" id="trashed">{{__('finance.Supprimer')}}</button>
            </div>
        </div>
    </div>
  </div>
</div>
<!-- Content wrapper -->
@push('css')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
  integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush
<script src="{{ asset('assets/js/etat-index.js') }}"></script>
<script>
  function leave(id, nom) {
    location.href = "{{ route('proprietaire.ajout-etat') }}" + "/" + id + "/" + nom
  }
</script>
@endsection