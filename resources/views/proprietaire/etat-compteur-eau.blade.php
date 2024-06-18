<div>
  <div class="card-header border-bottom py-2 px-3 mb-3">
    <div class="card-title mb-0">
      <h5 class="m-0 me-2 w-auto">Relevés des compteurs eau, gaz...</h5>
    </div>
  </div>
  <div class="" id="releve-compteur-eau">
    @if (isset($etat_lieu))
      @foreach ($etat_lieu->compteur_eaux as $compteur_eau)
      <div class="row p-3 align-items-start">
        <div class="col-md-1 text-end">
        <input type="number" name="compteur_eau_modif[]" value="{{ $compteur_eau->id }}" hidden>
        </div>
        <div class="col-lg-2">
          <div>
            <label class="col-form-label text-end">Type de releve</label>
          </div>
          <input class="form-control" type="text" name="name_eau[]" value="{{ $compteur_eau->name }}">
        </div>
        <div class="col-lg-2">
          <div>
            <label class="col-form-label text-end">n° de serie</label>
          </div>
          <input class="form-control" type="text" name="numero_eau[]" value="{{ $compteur_eau->numero }}">
        </div>
        <div class="col-lg-2">
          <div>
            <label class="col-form-label text-end">M3</label>
          </div>
          <input class="form-control" type="number" name="volume_eau[]" value="{{ $compteur_eau->volume }}">
        </div>
        <div class="col-lg-2">
          <div>
            <label for="fonction-eau" class="col-form-label text-end">Fonctionnement</label>
          </div>
          <select id="fonction-eau" class="form-select" name="fontion_eau[]">
            <option value="" @if (!$compteur_eau->fonctionnement) selected @endif>Choisir</option>
            @foreach ($fonctionnements as $fonctionnement)
            @if ($compteur_eau->fonctionnement)
            <option value="{{ $fonctionnement->id }}" @if($fonctionnement->id == $compteur_eau->fonctionnement->id) selected @endif>{{ $fonctionnement->name }}</option>
            @else
            <option value="{{ $fonctionnement->id }}">{{ $fonctionnement->name }}</option>
            @endif
            @endforeach
          </select>
        </div>
        <div class="col-lg">
          <div>
            <label class="col-form-label text-end">Observation</label>
          </div>
          <input class="form-control" type="text" placeholder="Description" name="observation_eau[]" value="{{ $compteur_eau->observation }}" >
        </div>
      </div>
      @endforeach
    @else
    <div class="row p-3 align-items-start">
      <div class="col-md-1 text-end"></div>
      <input type="number" name="compteur_eau_modif[]" hidden>
      <div class="col-lg-2">
        <div>
          <label class="col-form-label text-end">Type de releve</label>
        </div>
        <input class="form-control" type="text" name="name_eau[]">
      </div>
      <div class="col-lg-2">
        <div>
          <label class="col-form-label text-end">n° de serie</label>
        </div>
        <input class="form-control" type="text" name="numero_eau[]">
      </div>
      <div class="col-lg-2">
        <div>
          <label class="col-form-label text-end">M3</label>
        </div>
        <input class="form-control" type="number" name="volume_eau[]">
      </div>
      <div class="col-lg-2">
        <div>
          <label for="fonction-eau" class="col-form-label text-end">Fonctionnement</label>
        </div>
        <select id="fonction-eau" class="form-select" name="fontion_eau[]">
          <option value="" selected>Choisir</option>
          @foreach ($fonctionnements as $fonctionnement)
            <option value="{{ $fonctionnement->id }}">{{ $fonctionnement->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-lg">
        <div>
          <label class="col-form-label text-end">Observation</label>
        </div>
        <input class="form-control" type="text" placeholder="Description" name="observation_eau[]">
      </div>
    </div>
    @endif
  </div>
  <div class="row border-bottom g-0">
    <div class="col-md-1">

    </div>
    <div class="col ps-3 mb-3">
      <span class="btn btn-primary" id="add-eau">Ajouter un autre champ</span>
    </div>
  </div>
</div>
