<div>
  <div class="card-header border-bottom py-2 px-3">
    <div class="card-title mb-0">
      <h5 class="m-0 me-2 w-auto">Production d'eau chaude.</h5>
    </div>
  </div>
  <div class="" id="eau-chaude">
    @if (isset($etat_lieu))
      @foreach ($etat_lieu->production_eau_chaudes as $production_eau_chaude)
      <div class="row p-3 align-items-end">
        <div class="col-md-1 text-end">
          <input type="number" name="production_eau_chaude_modif[]" value="{{ $production_eau_chaude->id }}" hidden>
        </div>
        <div class="col-lg-2">
          <div>
            <label class="col-form-label text-end">Type de production</label>
          </div>
          <input class="form-control" type="text" name="name_production_eau[]" value="{{ $production_eau_chaude->name }}">
        </div>
        <div class="col-lg-2">
          <div>
            <label class="col-form-label text-end">Fonctionnement</label>
          </div>
          <select class="form-select"  name="fonction_production_eau[]">
            <option selected value="" @if(!$production_eau_chaude->fonctionnement) selected @endif>Choisir</option>
            @foreach ($fonctionnements as $fonctionnement)
            @if ($production_eau_chaude->fonctionnement)
            <option value="{{ $fonctionnement->id }}" @if($production_eau_chaude->fonctionnement->id == $fonctionnement->id) selected @endif>{{ $fonctionnement->name }}</option>
            @else
            <option value="{{ $fonctionnement->id }}">{{ $fonctionnement->name }}</option>
            @endif
            @endforeach
          </select>
        </div>
        <div class="col-lg-4">
          <div>
            <label class="col-form-label text-end">Observation</label>
          </div>
          <input class="form-control" type="text" placeholder="Description" name="observation_production_eau[]" value="{{ $production_eau_chaude->observation }}">
        </div>
      </div>
      @endforeach
    @else
    <div class="row p-3 align-items-end">
      <div class="col-md-1 text-end"></div>
      <div class="col-lg-2">
        <div>
          <label class="col-form-label text-end">Type de production</label>
        </div>
        <input class="form-control" type="text" name="name_production_eau[]">
        <input type="number" name="production_eau_chaude_modif[]" hidden>
      </div>
      <div class="col-lg-2">
        <div>
          <label class="col-form-label text-end">Fonctionnement</label>
        </div>
        <select class="form-select"  name="fonction_production_eau[]">
          <option selected value="">Choisir</option>
          @foreach ($fonctionnements as $fonctionnement)
            <option value="{{ $fonctionnement->id }}">{{ $fonctionnement->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-lg-4">
        <div>
          <label class="col-form-label text-end">Observation</label>
        </div>
        <input class="form-control" type="text" placeholder="Description" name="observation_production_eau[]">
      </div>
    </div>
    @endif
  </div>
  <div class="row border-bottom g-0">
    <div class="col-md-1">

    </div>
    <div class="col ps-3 mb-3">
      <span class="btn btn-primary"  id="add-eau-chaude">Ajouter un autre champ</span>
    </div>
  </div>
</div>