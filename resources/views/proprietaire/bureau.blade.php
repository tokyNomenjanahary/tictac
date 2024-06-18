@extends('proprietaire.index')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/bureau.css') }}" />
<style>
  .agenda:hover {
    background: #e9e5e5;
  }
</style>
@endsection

@section('contenue')
  <!-- Content wrapper -->
  <div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
      <!-- DASHBOARD NAV CARD  -->
      @include('proprietaire.bureau-nav')
      <!-- END DASHBOARD NAV CARD -->

      <!-- GESTION -->
      @include('proprietaire.bureau-gestion')
      <!-- END GESTION -->

      <!-- FINANCES -->
      <!-- Order Statistics -->
      <div class="row">

        <div class="col-lg-6 col-xl-6">
          <div class="card h-100">
            <div class="card-header border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
              <div class="card-title mb-0 row justify-content-between align-items-center w-100 g-0">
                <h5 class="m-0 me-2 w-auto text-center">Finances</h5>
                <a href="#" title="Gérer" class="w-auto text-secondary p-0">
                </a>
              </div>
            </div>
            <div class="card-body p-3">
              <div class="nav-align-top">
                <div class="tab-content p-0 pt-4 shadow-none">
                  <div class="tab-pane fade show active" id="navs-top-this-month" role="tabpanel">
                    <p class="ms-2">
                      <span class="badge badge-center rounded-pill bg-warning">{{$loyerEnattente}}</span>
                      <a href="{{route('proprietaire.finance')}}"> Paiement(s) en attente</a>
                    </p>
                    <p class="ms-2">
                      <span class="badge badge-center rounded-pill bg-danger">{{$loyerEnretard}}</span>
                        <a href="{{route('proprietaire.finance')}}">Paiement(s) en retard</a>
                    </p>
                    <div class="row justify-content-between ms-2">
                      <div class="col">
                        <p class="form-label">REVENU BRUT</p>
                        <h4 class="text-success">{{$revenuBrute}} €</h4>
                      </div>
                      <div class="col">
                        <p class="form-label">DÉPENSES</p>
                        <h4 class="text-danger">{{$depense}} €</h4>
                      </div>
                    </div>
                    <div class="row align-items-center ms-2">
                      <div class="col-3">
                        <i class="fa-sharp fa-solid fa-coins nav-dash-icon-size"></i>
                      </div>
                      <div class="col">
                        <div>
                          <p>RÉSULTAT NET</p>
                        </div>
                        <div>
                          <h3 class="text-success">{{$totalNet}} €</h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6 col-xl-6">
          <div class="card">
            <div class="card-header border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
              <div class="card-title mb-0 row justify-content-between align-items-center w-100 g-0">
                <h5 class="m-0 me-2 w-auto">Mon agenda</h5>
                <a href="#" title="Gérer" class="w-auto text-secondary p-0">
                  
                </a>
              </div>
            </div>
            <div class="card-body p-3">
              <div class="border border-1">
                @forelse ($agendas as $agenda)
                <div class="d-flex flex-row align-items-center border border-bottom cursor-pointer agenda"
                data-bs-toggle="modal" data-bs-target="#eventModal-{{$agenda->id}}">
                  <div class="p-2 col-7">{{$agenda->objet}}</div>
                  <div class="p-2 col-5 text-end">
                    <div>{{explode(' ',$agenda->start_time)[0]}} à {{substr(explode(' ',$agenda->start_time)[1], 0, 5)}}</div>
                  </div>
                </div>
                <div class="modal" tabindex="-1" role="dialog" id="eventModal-{{$agenda->id}}">
                  <div class="modal-dialog" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title"></h5>
                          </div>
                          <div class="modal-body">
                              <span style="border: 1px solid gray;padding: 5px;">{{explode(' ',$agenda->start_time)[0]}}</span> -
                              <span style="border: 1px solid gray;padding: 5px;">{{explode(' ',$agenda->finish_time)[0]}}</span>
                              <div class="mt-5">
                                  <h5 class="">Lieu de rendez-vous</h5>
                                  <p>Adresse : <span> {{$agenda->adresse_rdv}}</span></p>
                                  <h5 class="">Locataire</h5>
                                  <p><span>{{$agenda->locataire->TenantFirstName . '' . $agenda->locataire->TenantLastName }}</span></p>
                                  <h5 class="">Description</h5>
                                  <p> <span>{{$agenda->description}}</span></p>
                              </div>
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-info" data-bs-dismiss="modal">FERMER</button>
                          </div>
                      </div>
                  </div>
                </div>
                @empty
                <h4 class="text-center text-secondary mt-3">Aucun rendez-vous pour le moment</h4>
                @endforelse
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--/ Order Statistics -->
      <!-- END FINANCES -->
    </div>
    <!-- / Content -->
  </div>
  <!-- Content wrapper -->
@endsection
