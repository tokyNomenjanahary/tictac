<label for="html5-text-input"
class="col-md-2 col-form-label text-md-end">Location</label>
<div class="col-lg-10">
<select name="location" id="location" class="form-control"
    required>
    <option value="">Choisir</option>
    @foreach ($locations as $key => $location)
        <option value="{{ $location->id }}">{{ $location->identifiant }}</option>
    @endforeach
</select>
</div>