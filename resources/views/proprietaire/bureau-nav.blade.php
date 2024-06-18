<div class="nav-dash">
  <div class="card p-4">
    <div class="row justify-content-around">
      <div class="p-0 w-auto nav-dash-container">
        <a href="{{ route('proprietaire.nouveaux') }}">
          <div
            class="rounded-circle border-2 bg-primary nav-dash-items row justify-content-center align-items-center g-0 mx-auto">
            <div class="w-auto m-0 p-0">
              <i class="fa-sharp fa-solid fa-house-chimney text-white nav-dash-icon-size"></i>
            </div>
          </div>
        </a>
        <div class="w-auto mt-3">
          <p class="form-label text-center">nouveau bien</p>
        </div>
      </div>

      <div class="p-0 w-auto nav-dash-container">
        <a href="{{ route('locataire.ajouterColocataire') }}">
          <div
            class="rounded-circle border-2 bg-primary nav-dash-items row justify-content-center align-items-center g-0 mx-auto">
            <div class="w-auto m-0 p-0">
              <i class="fa-solid fa-user text-white nav-dash-icon-size"></i>
            </div>
          </div>
        </a>
        <div class="w-auto mt-3">
          <p class="form-label text-center">nouveau locataire</p>
        </div>
      </div>

      <div class="p-0 w-auto nav-dash-container">
        <a href="{{route('location.create')}}">
          <div
            class="rounded-circle border-2 bg-primary nav-dash-items row justify-content-center align-items-center g-0 mx-auto">
            <div class="w-auto m-0 p-0">
              <i class="fa-sharp fa-solid fa-key text-white nav-dash-icon-size"></i>
            </div>
          </div>
        </a>
        <div class="w-auto mt-3">
          <p class="form-label text-center">nouvelle location</p>
        </div>
      </div>

      <div class="p-0 w-auto nav-dash-container">
        <a href="{{route('ajout-revenu')}}">
          <div
            class="rounded-circle border-2 bg-primary nav-dash-items row justify-content-center align-items-center g-0 mx-auto">
            <div class="w-auto m-0 p-0">
              <i class="fa-solid fa-plus text-white nav-dash-icon-size"></i>
            </div>
          </div>
        </a>
        <div class="w-auto mt-3">
          <p class="form-label text-center">ajouter un revenu </p>
        </div>
      </div>

      <div class="p-0 w-auto nav-dash-container">
        <a href="{{route('ajout-depense')}}">
          <div
            class="rounded-circle border-2 bg-primary nav-dash-items row justify-content-center align-items-center g-0 mx-auto">
            <div class="w-auto m-0 p-0">
              <i class="fa-solid fa-minus text-white nav-dash-icon-size"></i>
            </div>
          </div>
        </a>
        <div class="w-auto mt-3">
          <p class="form-label text-center">ajouter une dépense</p>
        </div>
      </div>

    </div>
  </div>
</div>
