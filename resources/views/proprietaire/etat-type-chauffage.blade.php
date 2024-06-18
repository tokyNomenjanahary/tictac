<div>
  <div class="card-header border-bottom py-2 px-3">
    <div class="card-title mb-0">
      <h5 class="m-0 me-2 w-auto">Type de chauffage.</h5>
    </div>
  </div>
  <div class="" id="type-chauffage">
    @if (isset($etat_lieu))
      @foreach ($etat_lieu->type_chauffages as $type_chauffage)
      <div class="row p-3 align-items-start">
        <div class="col-md-1 text-end">
          <input type="number" name="type_chauffage_modif[]" value="{{ $type_chauffage->id }}" hidden>
        </div>
        <div class="col-lg-2">
          <div>
            <label class="col-form-label text-end">Type de releve</label>
          </div>
          <input class="form-control" type="text" name="name_chauffage[]" value="{{ $type_chauffage->name }}">
        </div>
        <div class="col-lg-2">
          <div>
            <label class="col-form-label text-end">n° de serie</label>
          </div>
          <input class="form-control" type="text" name="numero_chauffage[]" value="{{ $type_chauffage->numero }}">
        </div>
        <div class="col-lg-2">
          <div>
            <label class="col-form-label text-end">M3/kwh</label>
          </div>
          <input class="form-control" type="number" name="volume_chauffage[]" value="{{ $type_chauffage->volume }}">
        </div>
        <div class="col-lg-2">
          <div>
            <label class="col-form-label text-end">Fonctionnement</label>
          </div>
          <select class="form-select" name="fonction_chauffage[]">
            <option value="" @if (!$type_chauffage->fonctionnement) selected @endif>Choisir</option>
            @foreach ($fonctionnements as $fonctionnement)
              @if ($type_chauffage->fonctionnement)
              <option value="{{ $fonctionnement->id }}" @if($fonctionnement->id == $type_chauffage->fonctionnement->id) selected @endif>{{ $fonctionnement->name }}</option>
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
          <input class="form-control" type="text" placeholder="Description" name="observation_chauffage[]"  value="{{ $type_chauffage->observation }}">
        </div>
      </div>
      @endforeach
    @else
    <div class="row p-3 align-items-start">
      <div class="col-md-1 text-end"></div>
      <div class="col-lg-2">
        <div>
          <input type="number" name="type_chauffage_modif[]" hidden>
          <label class="col-form-label text-end">Type de releve</label>
        </div>
        <input class="form-control" type="text" name="name_chauffage[]">
      </div>
      <div class="col-lg-2">
        <div>
          <label class="col-form-label text-end">n° de serie</label>
        </div>
        <input class="form-control" type="text" name="numero_chauffage[]">
      </div>
      <div class="col-lg-2">
        <div>
          <label class="col-form-label text-end">M3/kwh</label>
        </div>
        <input class="form-control" type="number" name="volume_chauffage[]">
      </div>
      <div class="col-lg-2">
        <div>
          <label class="col-form-label text-end">Fonctionnement</label>
        </div>
        <select class="form-select" name="fonction_chauffage[]">
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
        <input class="form-control" type="text" placeholder="Description" name="observation_chauffage[]">
      </div>
    </div>
    @endif
  </div>
  <div class="row border-bottom g-0">
    <div class="col-md-1">

    </div>
    <div class="col ps-3 mb-3">
      <span class="btn btn-primary"  id="add-chauffage">Ajouter un autre champ</span>
    </div>
  </div>
</div>