<div class="row mt-4">
  <!-- Order Statistics -->
  <div class="col-md-6 col-lg-3 col-xl-3 order-0 mb-4">
    <div class="card h-100">
      <div class="card-header border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
        <div class="card-title mb-0 row justify-content-between align-items-center w-100 g-0">
          <h5 class="m-0 me-2 w-auto">Biens</h5>
          <a href="{{ route('proprietaire.logement') }}" title="Gérer" class="w-auto text-secondary p-0">
          </a>
        </div>
      </div>
      <div class="card-body p-3">
        <div class="d-flex justify-content-around align-items-center">
          <div class="">
            <i class="fa-sharp fa-solid fa-house-chimney nav-dash-icon-size text-warning"></i>
          </div>
          <div class="d-flex flex-column align-items-center gap-1">
            <h2 class="mb-0 bg-label-warning rounded-pill p-2">{{$countBien}}</h2>
            <span>EN TOUT</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Order Statistics -->

  <!-- Order Statistics -->
  <div class="col-md-6 col-lg-3 col-xl-3 order-0 mb-4">
    <div class="card h-100">
      <div class="card-header border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
        <div class="card-title mb-0 row justify-content-between align-items-center w-100 g-0">
          <h5 class="m-0 me-2 w-auto">Locataires</h5>
          <a href="{{ route('locataire.locataire') }}" title="Gérer" class="w-auto text-secondary p-0">
          </a>
        </div>
      </div>
      <div class="card-body p-3">
        <div class="d-flex justify-content-around align-items-center">
          <div class="">
            <!-- <i class="fa-sharp fa-solid fa-house-chimney"></i> -->
            <i class="fa-solid fa-users nav-dash-icon-size text-success"></i>
          </div>
          <div class="d-flex flex-column align-items-center gap-1">
            <h2 class="mb-0 bg-label-success rounded-pill p-2">{{$countLocataire}}</h2>
            <span>EN TOUT</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Order Statistics -->

  <!-- Order Statistics -->
  <div class="col-md-6 col-lg-3 col-xl-3 order-0 mb-4">
    <div class="card h-100">
      <div class="card-header border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
        <div class="card-title mb-0 row justify-content-between align-items-center w-100 g-0">
          <h5 class="m-0 me-2 w-auto">Locations</h5>
          <a href="{{ route('location.index') }}" title="Gérer" class="w-auto text-secondary p-0">
          </a>
        </div>
      </div>
      <div class="card-body p-3">
        <div class="d-flex justify-content-around align-items-center">
          <div class="">
            <i class="fa-sharp fa-solid fa-key nav-dash-icon-size text-danger"></i>
          </div>
          <div class="d-flex flex-column align-items-center gap-1">
            <h2 class="mb-0 bg-label-danger rounded-pill p-2">{{$countLocation}}</h2>
            <span>EN TOUT</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Order Statistics -->

  <!-- Order Statistics -->
  <div class="col-md-6 col-lg-3 col-xl-3 order-0 mb-4">
    <div class="card h-100">
      <div class="card-header border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
        <div class="card-title mb-0 row justify-content-between align-items-center w-100 g-0">
          <h5 class="m-0 me-2 w-auto">Contrats</h5>
          <a href="#" title="Gérer" class="w-auto text-secondary p-0">
          </a>
        </div>
      </div>
      <div class="card-body p-3">
        <div class="d-flex justify-content-around align-items-center">
          <div class="">
            <i class="fa-sharp fa-solid fa-file-signature nav-dash-icon-size text-primary"></i>
          </div>
          <div class="d-flex flex-column align-items-center gap-1">
            <h2 class="mb-0 bg-label-primary rounded-pill p-2">00</h2>
            <span>EN TOUT</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Order Statistics -->
</div>