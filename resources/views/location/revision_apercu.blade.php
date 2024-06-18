<div class="row" style="margin-top:20px">
    <div class="col" style="width: 60%;padding-left: 25px">
        <p id="vue_user_name"><b>{{ Auth::user()->first_name }}</b></p>
        <br>
        <br>
        <br>
        <br>
        <br>
    </div>
    <div class="col" style="width: 3%">
       <p class="text-end"><b>A :</b></p>
       
    </div>
    <div class="col" style="width: 37%">
        <p><b><span id="vue_locataire_info">{{ $location->Locataire->TenantFirstName . ' ' . $location->Locataire->TenantLastName }}</span></b></p>
        <p id="vue_locataire_adresse">{{ $location->Logement->adresse }}</p>
       
    </div>
</div>
<div class="row">
    <div class="col" style="line-height: 1.0;padding-left: 25px">
        <p><b>Date</b> : <span id="vue_date_revision"></span></p>
        <p>
            <div class="rectangle">
                <p ><h4 class="text-center">RÉVISION DU LOYER</h4></p>
            </div>
        </p>
        <br>
        <p>Bonjour <span id="vue_locataire">M. {{ $location->Locataire->TenantFirstName . ' ' . $location->Locataire->TenantLastName }}</span></p>
        <br>
        <p>Je vous communique par la présente l'augmentation du loyer du bien que je vous loue sis</p>
        <p id="vue_logement_adresse">{{ $location->Logement->adresse }}</p>
        <br>
        <p>Le loyer est révisable conformément aux conditions précisées dans le contrat de bail.</p>
        <br>
        <p>Le nouveau loyer qui s’appliquera sera de :<b><span id="vue_nouveau_loyer"> </span>€</b></p>
        <br>
        <p>Loyer hors charges :<span id="vue_loyer_hors_charges"> </span>€</p>
        <p>Charges :<span id="vue_charge"> </span>€</p>
        {{-- <p>TVA : <span id="vue_tva" >1,974.07 €</span></p>
        <p>Autres paiements : <span id="vue_autres_paimenent"></span>€</p> --}}
        <p>Montant de l'augmentation : <span id="vue_augmentation" ></span>€</p>
        <p>Indice de départ utilisé pour la révision du loyer : <span id="vue_indice_depart"></span> </p>
        <p>Nouvel indice :<span id="vue_nouvel_indice"></span></p>
        <br>
        <p>Cette augmentation prend effet dès à présent. Je vous remercie de bien vouloir l'appliquer lors du règlement
            de votre prochain loyer.</p>
        <br>
        <p>Je vous prie de bien vouloir agréer, l’expression de mes sentiments distingués.</p>
    </div>
</div>
<div class="row">
    <div class="col-6">

    </div>
    <div class="col-6 text-end">
        <br>
        <br>
        <br>
        <br>
        
        <p id="vue_user_name">{{ Auth::user()->first_name }}</p>
    </div>
</div>
