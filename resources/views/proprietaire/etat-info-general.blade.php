<div class="card-header border-bottom py-2 px-3">
  <div class="card-title mb-0">
    <h5 class="m-0 me-2 w-auto">Informations générales</h5>
  </div>
</div>
<div class="card-body p-0">
  <div class="p-3 border-bottom">
    <div class="mb-3 row">
      <label for="etat-type" class="col-1 col-lg-2 col-form-label text-end text-nowrap">Type :</label>
      <div class="col-lg-5">
        <select id="etat-type" class="form-select" name="type_etat_id">
          @foreach ($type_etats as $type_etat)
            @if (isset($type_id))
            <option value="{{ $type_etat->id }}" @if ($type_etat->id == $type_id) selected @endif>{{ $type_etat->name }}</option>
            @elseif (isset($etat_lieu))
                @if($etat_lieu->type_etat)
                <option value="{{ $type_etat->id }}" @if ($type_etat->id == $etat_lieu->type_etat->id) selected @endif>{{ $type_etat->name }}</option>
                @endif
                <option value="{{ $type_etat->id }}" >{{ $type_etat->name }}</option>
            @else
            <option value="{{ $type_etat->id }}">{{ $type_etat->name }}</option>
            @endif
          @endforeach
        </select>
      </div>
    </div>
    <div class="mb-3 row">
      <label for="etat_name" class="col-1 col-lg-2 col-form-label text-end text-nowrap">Identifiant</label>
      <div class="col-lg-5">
        @if(isset($a))

        <input
          class="form-control"
          @if (isset($etat_lieu)) value = "{{ $etat_lieu->identifiant }}" @endif
          type="text" placeholder="Identifiant"
          id="etat_name" name="etat_name" aria-describedby="helpId">
        <span class="text-danger no-error" id="err_etat_name"></span>
        @else
        <input
          class="form-control"
          @if (isset($etat_lieu)) value = "{{ $etat_lieu->name }}" @endif
          type="text" placeholder="Identifiant"
          id="etat_name" name="etat_name" aria-describedby="helpId">
        <span class="text-danger no-error" id="err_etat_name"></span>
        @endif
      </div>
    </div>
    <div class="mb-3 row">
      <label for="etat_location" class="col-1 col-lg-2 col-form-label text-end text-nowrap">Location</label>
      <div class="col-lg-5">
        <select id="etat_location_id" class="form-select" name="etat_location_id">
          <option value="" selected>Choisir</option>
          @foreach ($locations as $location)
            @if (isset($location_id))
            <option value="{{ $location->id }}" @if ($location->id == $location_id) selected @endif>{{ $location->identifiant }}</option>
            @elseif (isset($etat_lieu) && $etat_lieu->location)
            <option value="{{ $location->id }}" @if ($location->id == $etat_lieu->location->id) selected @endif>{{ $location->identifiant }}</option>
            @else
            <option value="{{ $location->id }}">{{ $location->identifiant }}</option>
            @endif
          @endforeach
        </select>
        <span class="text-danger no-error" id="err_etat_location_id"></span>
      </div>
    </div>
  </div>
</div>
