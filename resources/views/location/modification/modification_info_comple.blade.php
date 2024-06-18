<style>
    label {
        color: black !important;
        margin-top: 12px;
    }

    input {
        border-radius: none !important;
    }

    .card {
        border-style: none;
        border-radius: 0px;
    }
</style>
<form action="{{route('modification.complementaire',$location->id)}}" method="GET">
    @csrf

<div class="card" style="margin-top: 5px">
    <div class="card-header"
        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
        {{__('location.traveauxProprio')}}
    </div>
    <div class="card-body">
        <div class="">
            <label for="" class="form-label">{{__('location.montant')}}</label>
            <input type="text" name="montant" id="montant" class="form-control" placeholder="€"
                aria-describedby="helpId" value="{{ !empty($location->travauxProprietaire->Montant) ? $location->travauxProprietaire->Montant : ''}}">
        </div>
        <div class="">
            <label for="" class="form-label">DESCRIPTION</label>
            <textarea name="description" id="description" class="form-control" cols="20" rows="5"
                placeholder={{__('location.descriptionTrav')}}>{{ !empty($location->travauxProprietaire->description) ? $location->travauxProprietaire->description : ''}}</textarea>
            <p>{{__('location.descriptionProprio')}}</p>
        </div>
    </div>
</div>
<div class="card" style="margin-top: 5px">
    <div class="card-header"
        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
        {{__('location.traveauxLocataire')}}
    </div>
    <div class="card-body">
        <div class="">
            <label for="" class="form-label">{{__('location.montant')}}</label>
            <input type="text" name="montant_locataire" id="montant_locataire" class="form-control" placeholder="€"
                aria-describedby="helpId" value="{{ !empty($location->travauxLocataire->montant_locataire) ? $location->travauxLocataire->montant_locataire : '' }}">
        </div>
        <div class="">
            <label for="" class="form-label">DESCRIPTION</label>
            <textarea name="description_locataire" id="description_locataire" class="form-control" cols="20" rows="5"
                placeholder={{__('location.descriptionTrav')}}>{{ !empty($location->travauxLocataire->montant_locataire) ? $location->travauxLocataire->description_locataire : '' }}</textarea>
            <p>{{__('location.descriptionLoc')}}</p>
        </div>
    </div>
</div>
<div class="card" style="margin-top: 5px">
    <div class="card-header"
        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
        {{__('location.conditionParticuliere')}}
    </div>
    <div class="card-body">
        <div class="">
            <label for="" class="form-label">{{__('location.conditionParticuliere')}}</label>
            <textarea name="conditions" id="conditions"class="form-control" cols="20" rows="5"
                placeholder={{__('location.conditionParticulier')}}>{{$location->conditions}}</textarea>
            <p>{{__('location.info')}}</p>
        </div>
    </div>
</div>
<div class="card" style="margin-top: 5px">
    <div class="card-header"
        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
        {{__('location.commentaireLocation')}}
    </div>
    <div class="card-body">
        <div class="">
            <label for="" class="form-label">{{__('location.commentaire')}}</label>
            <textarea name="commentaires" id="commentaires"class="form-control" cols="20" rows="5"
                placeholder={{__('location.exempleCOM')}}>{{$location->commentaires}}</textarea>
            <p>{{__('location.notePriver')}}</p>
        </div>
    </div>
</div>
<div class="card" style="margin-top: 5px">
    <div class="row">
        <div class="col-md-12" style="padding: 15px;">
            <div class="float-end">
                <a href="" class="btn btn-secondary">{{__('location.Annuler')}}</a>
                <button type="submit" class="btn btn-primary"> {{__('location.enregistrer')}} </button>
            </div>
        </div>
    </div>
</div>
</form>

