<div>
  <div class="card-header border-bottom py-2 px-3">
    <div class="card-title mb-0">
      <h5 class="m-0 me-2 w-auto">Relevé compteur électrique.</h5>
    </div>
  </div>

  <div class="" id="releve-compteur-electrique">
    @if (isset($etat_lieu))
      @foreach ($etat_lieu->compteur_electriques as $compteur_electriques)
      <div class="row p-3 align-items-start">
        <div class="col-md-1 text-end">
          <input type="number" hidden name="compteur_electriques_modif[]" value="{{ $compteur_electriques->id }}">
        </div>
        <div class="col-lg-2">
          <div>
            <label class="col-form-label text-end">Type de releve</label>
          </div>
          <input class="form-control" type="text" name="name_electrique[]" value="{{ $compteur_electriques->name }}">
        </div>
        <div class="col-lg-2">
          <div>
            <label class="col-form-label text-end">n° de serie</label>
          </div>
          <input class="form-control" type="text" name="numero_electrique[]" value="{{ $compteur_electriques->numero }}">
        </div>
        <div class="col-lg-2">
          <div>
            <label class="col-form-label text-end">kwh</label>
          </div>
          <input class="form-control" type="number" name="volume_electrique[]" value="{{ $compteur_electriques->volume }}">
        </div>
        <div class="col-lg-2">
          <div>
            <label class="col-form-label text-end">Fonctionnement</label>
          </div>
          <select class="form-select" name="fonction_electrique[]">
          <option value="" @if(!$compteur_electriques->fonctionnement ) selected @endif>Choisir</option>
            @foreach ($fonctionnements as $fonctionnement)
            @if ($compteur_electriques->fonctionnement)
            <option value="{{ $fonctionnement->id }}" @if($fonctionnement->id == $compteur_electriques->fonctionnement->id ) selected @endif>{{ $fonctionnement->name }}</option>
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
          <input class="form-control" type="text" placeholder="Description" name="observartion_electrique[]" value="{{ $compteur_electriques->observation }}">
        </div>
      </div>
      @endforeach
    @else
    <div class="row p-3 align-items-start">
      <div class="col-md-1 text-end"></div>
      <div class="col-lg-2">
        <div>
          <label class="col-form-label text-end">Type de releve</label>
        </div>
        <input type="number" hidden name="compteur_electriques_modif[]">
        <input class="form-control" type="text" name="name_electrique[]" >
      </div>
      <div class="col-lg-2">
        <div>
          <label class="col-form-label text-end">n° de serie</label>
        </div>
        <input class="form-control" type="text" name="numero_electrique[]">
      </div>
      <div class="col-lg-2">
        <div>
          <label class="col-form-label text-end">kwh</label>
        </div>
        <input class="form-control" type="number" name="volume_electrique[]">
      </div>
      <div class="col-lg-2">
        <div>
          <label class="col-form-label text-end">Fonctionnement</label>
        </div>
        <select class="form-select" name="fonction_electrique[]">
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
        <input class="form-control" type="text" placeholder="Description" name="observartion_electrique[]">
      </div>
    </div>
    @endif
  </div>
  <div class="row border-bottom g-0">
    <div class="col-md-1">

    </div>
    <div class="col ps-3 mb-3">
      <span class="btn btn-primary"  id="add-elec">Ajouter un autre champ</span>
    </div>
  </div>
</div>