<div class="mb-3 row">
    <label for="identifiant"
        class="col-1 col-lg-2 col-form-label text-end text-nowrap">{{ __('inventaire.identifiant') }}*</label>
    <div class="col-lg-5">
        <input class="form-control" type="text" placeholder="Ex. Inventaire de..." id="identifiant" name="identifiant"
            @if (isset($inventaire)) value="{{ $inventaire->identifiant }}" @else value="" @endif
            aria-describedby="helpId">
        <span class="help-block">{{ __('inventaire.donne_identifiant') }}</span>
        <span class="text-danger no-error" id="err_identifiant"></span>
    </div>
</div>
<div class="mb-3 row">
    <label for="bien" class="col-1 col-lg-2 col-form-label text-end text-nowrap">{{ __('revenu.Bien') }}</label>
    <div class="col-lg-5">
        <select id="bien" class="form-select" onchange="checkLocation()" name="bien">
            @if (!isset($identifiant))
            <option value="">{{ __('revenu.Pas_lié_a_un_bien') }}</option>
            @endif
            @foreach ($logements as $logement)
                @if (isset($inventaire->logement_id) && $inventaire->logement_id == $logement->id)
                    <option value="{{ $logement->id }}" selected>{{ $logement->identifiant }}</option>
                @else
                    <option value="{{ $logement->id }}">{{ $logement->identifiant }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>
<div class="mb-3 row">
    <label for="etat_location"
        class="col-1 col-lg-2 col-form-label text-end text-nowrap">{{ __('revenu.Location') }}</label>
    <div class="col-lg-5">
        <select id="location" class="form-select" name="location">
            @if (!isset($identifiant))
            <option value="" selected>{{ __('revenu.lie') }}</option>
            @endif
            @foreach ($locations as $location)
                @if (isset($inventaire->location_id) && $inventaire->location_id == $location->id)
                    <option value="{{ $location->id }}" selected>{{ $location->identifiant }}</option>
                @else
                    <option value="{{ $location->id }}">{{ $location->identifiant }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>


