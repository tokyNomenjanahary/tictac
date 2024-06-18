<div>
  <div class="card-header border-bottom py-2 px-3">
    <div class="card-title mb-0">
      <h5 class="m-0 me-2 w-auto">Remise des clés.</h5>
    </div>
  </div>
  <div class="border-bottom">
    <div class="row p-3 align-items-start">
      <div class="col-md-1 text-end">
        <input type="number" name="cle_modif" @if (isset($etat_lieu) && $etat_lieu->cle) value="{{ $etat_lieu->cle->id }}" @endif hidden>
      </div>
      <div class="col-lg-2">
        <div>
          <label class="col-form-label text-end">Type de cle</label>
        </div>
        <input class="form-control" type="text" name="name_cle" @if (isset($etat_lieu) && $etat_lieu->cle) value="{{ $etat_lieu->cle->type }}" @endif>
      </div>
      <div class="col-lg-2">
        <div>
          <label class="col-form-label text-end">nombre</label>
        </div>
        <input class="form-control" type="number" name="nombre_cle" @if (isset($etat_lieu) && $etat_lieu->cle) value="{{ $etat_lieu->cle->nombre }}" @endif>
      </div>
      <div class="col-lg-2">
        <div>
          <label class="col-form-label text-end">date</label>
        </div>
        <input class="form-control" type="date" name="date_cle" @if (isset($etat_lieu) && $etat_lieu->cle) value="{{ $etat_lieu->cle->date }}" @endif>
      </div>
      <div class="col-lg">
        <div>
          <label class="col-form-label text-end">Commentaire</label>
        </div>
        <input class="form-control" type="text" placeholder="Description" name="commentaire_cle"  @if (isset($etat_lieu) && $etat_lieu->cle) value="{{ $etat_lieu->cle->observation }}" @endif>
      </div>
    </div>
  </div>
</div>