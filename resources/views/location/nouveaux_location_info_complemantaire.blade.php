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
<div class="card" style="margin-top: 5px">
    <div class="card-header"
        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
        {{__('location.traveauxProprio')}}
    </div>
    <div class="card-body">
        <div class="">
            <label for="" class="form-label">{{__('location.montant')}}</label>
            <input type="number" min="0" name="montant" id="montant" class="form-control" placeholder="€"
                aria-describedby="helpId">
        </div>
        <div class="">
            <label for="" class="form-label">{{__('location.description')}}</label>
            <textarea name="description" id="description" class="form-control" cols="20" rows="5"
                placeholder={{__('location.descriptionTrav')}}></textarea>
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
            <input type="number" min="0" name="montant_locataire" id="montant_locataire" class="form-control" placeholder="€"
                aria-describedby="helpId">
        </div>
        <div class="">
            <label for="" class="form-label">{{__('location.description')}}</label>
            <textarea name="description_locataire" id="description_locataire" class="form-control" cols="20" rows="5"
                placeholder={{__('location.descriptionTrav')}}></textarea>
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
            <label for="" class="form-label" style="text-transform: uppercase">{{__('location.conditionParticuliere')}}</label>
            <textarea name="conditions" id="conditions"class="form-control" cols="20" rows="5"
                placeholder={{__('location.conditionParticulier')}}></textarea>
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
                placeholder={{__('loaction.exempleCOM')}}></textarea>
            <p>{{__('location.notePriver')}}</p>
        </div>
    </div>
</div>

