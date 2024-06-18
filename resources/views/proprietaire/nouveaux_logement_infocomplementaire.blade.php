
<style>
    .radio{
        margin-top: 10px;
    }
    .lr{
        margin-top: 7px;
    }
    /* .form-label{
        text-align: end !important;
    } */
</style>

<div class="card ">
    <div class="card-header" style="color: #4C8DCB">
        Informations complémentaires :
    </div>
    <div class="card-body">
        <div class="row align-middle" style="border-bottom: 1px solid rgb(223, 216, 216)">
            <div class="col-md-2 ">
               <label for="" class="form-label" >TYPE D'HABITAT </label>
            </div>
            <div class="col-md-3 align-middle">
                <div class="form-check form-check-inline">
                    <input class="form-check-input radio" type="radio" id="immeuble_collectif" name="type_habitat" value="0" @if(isset($detailLogement->infoComplementaireLogement->type_habitat)) @if($detailLogement->infoComplementaireLogement->type_habitat == 0) @checked(true) @endif @endif>
                    <label class="form-check-label lr" for="immeuble_collectif">Immeuble colectif</label>
                </div>
            </div>
            <div class="col-md-3 align-middle">
                <div class="form-check form-check-inline">
                    <input class="form-check-input radio" type="radio" id="immeuble_individuel" name="type_habitat" value="1" @if(isset($detailLogement->infoComplementaireLogement->type_habitat)) @if($detailLogement->infoComplementaireLogement->type_habitat == 1) @checked(true) @endif @endif>
                    <label class="form-check-label lr" for="immeuble_individuel">Immeuble Individuel</label>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body" style="margin-top: -40px;">
        <div class="row align-middle" style="border-bottom: 0.2px solid rgb(223, 216, 216)">
            <div class="col-md-2 align-middle ">
               <label for="" class="form-label">RÉGIME JURIDIQUE DE L’IMMEUBLE</label>
            </div>
            <div class="col-md-3 align-middle">
                <div class="form-check form-check-inline">
                    <input class="form-check-input radio" type="radio" id="coproprietaire" name="coproprietaire" value="0" @if(isset($detailLogement->infoComplementaireLogement->coproprietaire)) @if($detailLogement->infoComplementaireLogement->coproprietaire == 0) @checked(true) @endif @endif>
                    <label class="form-check-label lr" for="coproprietaire">Copropriété</label>
                </div>
            </div>
            <div class="col-md-3 align-middle">
                <div class="form-check form-check-inline">
                    <input class="form-check-input radio" type="radio" id="monocoproprietaire" name="coproprietaire" value="1" @if(isset($detailLogement->infoComplementaireLogement->coproprietaire)) @if($detailLogement->infoComplementaireLogement->coproprietaire == 1) @checked(true) @endif @endif>
                    <label class="form-check-label lr" for="monocoproprietaire">Mono propriété</label>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body" style="margin-top: -40px;">
        <div class="row align-middle" style="border-bottom: 0.2px solid rgb(223, 216, 216)">
            <div class="col-md-2 align-middle ">
               <label for="" class="form-label">DÉSIGNATION DES PARTIES ET ÉQUIPEMENTS</label>
            </div>
            <div class="col-md-10 align-middle">
                <div class="row">
                    @foreach ($listEquipements as $list)
                        <div class="col-md-6">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input radio" type="checkbox" id="equipements{{ $list->id }}" value="{{ $list->id }}" name="equipements[]" @if(isset($equipements)) @if(count($equipements) > 0) @if(in_array($list->id,$equipements)) @checked(true) @endif @endif @endif>
                                <label class="form-check-label lr" for="equipements{{ $list->id }}">{{ $list->equipement }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="card-body" style="margin-top: -40px;">
        <div class="row align-middle" style="border-bottom: 0.2px solid rgb(223, 216, 216)">
            <div class="row align-middle">
                <div class="col-md-2 align-middle " >
                   <label for="" class="form-label" >AUTRES DÉPENDANCE</label>
                </div>
                <div class="col-md-6 align-middle mb-3">
                    <input type="text" class="form-control" name="autre_dependance" @if(isset($detailLogement->infoComplementaireLogement->autre_dependance)) value="{{ $detailLogement->infoComplementaireLogement->autre_dependance }}" @endif>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card mt-1">
    <div class="card-header" style="color: #4C8DCB">
        Informations financières :
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card-body" style="margin-top: -20px">
                <div class="autocomplete-container mb-1" id="autocompletess">
                    <label for="" class="form-label">Taxe d'habitation :</label>
                    <input id="taxe_habitation" name="taxe_habitation" class="form-control info-general-c"  type="number" placeholder="" @if(isset($detailLogement->infoComplementaireLogement->taxe_habitation)) value="{{ $detailLogement->infoComplementaireLogement->taxe_habitation }}" @endif>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-body" style="margin-top: -20px">
                <div class="">
                    <label for="" class="form-label">Taxe foncière :</label>
                    <input type="number" name="taxe_fonciere" id="taxe_fonciere" class="form-control" placeholder="" aria-describedby="helpId" @if(isset($detailLogement->infoComplementaireLogement->taxe_fonciere)) value="{{ $detailLogement->infoComplementaireLogement->taxe_fonciere }} " @endif>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card-body" style="margin-top: -40px">
                <div class="">
                    <label for="" class="form-label">Date d'acquisition :</label>
                    <input type="date" name="date_acquisition" id="date_acquisition" class="form-control" placeholder="" aria-describedby="helpId" @if(isset($detailLogement->infoComplementaireLogement->date_acquisition)) value="{{ $detailLogement->infoComplementaireLogement->date_acquisition }}" @endif>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card-body" style="margin-top: -40px">
                <div class="">
                    <label for="" class="form-label">Prix d'acquisition :</label>
                    <input type="number" name="prix_acquisition" min="0" id="prix_acquisition" class="form-control info-general-c" placeholder="" aria-describedby="helpId" @if(isset($detailLogement->infoComplementaireLogement->prix_acquisition)) value="{{ $detailLogement->infoComplementaireLogement->prix_acquisition }}" @endif>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card-body" style="margin-top: -40px">
                <div class="">
                    <label for="" class="form-label">Frais d'acquisition :</label>
                    <input type="number" name="frais_acquisition" id="frais_acquisition" class="form-control info-general-c" placeholder="" aria-describedby="helpId" @if(isset($detailLogement->infoComplementaireLogement->frais_acquisition)) value="{{ $detailLogement->infoComplementaireLogement->frais_acquisition }}" @endif>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card-body" style="margin-top: -40px">
                <div class="">
                    <label for="" class="form-label">Valeur actuel :</label>
                    <input type="number" name="valeur_actuel" id="valeur_actuel" class="form-control info-general-c" placeholder="" aria-describedby="helpId" @if(isset($detailLogement->infoComplementaireLogement->valeur_actuel)) value="{{ $detailLogement->infoComplementaireLogement->valeur_actuel }}" @endif>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card" style="margin-top: 5px">
    <div class="card-body" style="margin-top: -5px">
        <div class="row">
            <div class="col-md-12">
                <div class="float-start">

                </div>
                <div class="float-end">
                    <button type="button" class="btn btn-primary" id="precedentInfoGeneral"> Précédent </button>
                    <button type="button" class="btn btn-primary" id="suivantPhoto"> Suivant </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $("#precedentInfoGeneral").click(function() {
            $('#home-tab').tab('show');
        });

        $("#suivantPhoto").click(function() {
            $('#photo-tab').tab('show');
        });
    </script>

@endpush
