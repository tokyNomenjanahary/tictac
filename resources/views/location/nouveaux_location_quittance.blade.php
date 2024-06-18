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
        Adresse de quittancement
    </div>
    <div class="card-body">
        <div class="">
            <label for="" class="form-label">ADRESSE</label>
            <textarea name="" id=""class="form-control" cols="20" rows="5"
                placeholder="EX. Rélevé compteur EDF : 33487..."></textarea>
            <p>Saisir si l'adresse de quittancement est différente de l'adresse du bien loué</p>
        </div>
    </div>
</div>
<div class="card" style="margin-top: 5px">
    <div class="card-header"
        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
        Quittance
    </div>
    <div class="card-body">
        <div class="">
            <label for="civilite" class="form-label">DATE DE PAIEMENT</label>
            <select name="" id="civilite" class="form-control"><span class="caret"></span>
                <?php
                    for ($i=1; $i <=31 ; $i++) {
                       echo '<option>' .$i. '</option>';
                    }
                 ?>
            </select>
            <p>Cette date définit la période de la quittance de loyer. Si vous choisissez le 15 du mois, les
                quittances
                seront datées du 15 au 14 du mois suivant.</p>
        </div>
    </div>
    <div class="card-body">
        <div class="">
            <label for="civilite" class="form-label">GÉNÉRATION DU LOYER</label>
            <select name="" id="civilite" class="form-control"><span class="caret"></span>
                <option value="">Même date que quittancement</option>
            </select>
            <p>Si vous choisissez le J-5, le loyer et l'avis d'échéance sera généré 5 jours avant la date de
                quittancement. Ex. Le loyer du 1 au 30 Avril sera généré le 26 Mars.</p>
            <p>Pour chaque contrat de location, les loyers sont générés automatiquement chaque mois dans la rubrique
                Finances.</p>
        </div>
    </div>
    <div class="card-body" style="margin-top: -40px;">
        <div class="">
            <label for="civilite" class="form-label">TITRE DU DOCUMENT</label>
            <select name="" id="civilite" class="form-control"><span class="caret"></span>
                <option value="">Choisir</option>
                <option value="">Quittance</option>
                <option value="">Facture</option>
            </select>
        </div>
    </div>
    <div class="card-body" style="margin-top:2px">
        <div class="row align-middle">
            <div class="col-md-2  align-middle">
                <h6 style="color: black;">NUMÉROTATION</h6>
            </div>
            <div class="form-check form-switch col-md-10 align-left">
                <input type="checkbox" name="" id="flexSwitchCheckChecked"
                    class="form-check-input form-control" placeholder="" aria-describedby="helpId">
            </div>
            <p>Activer ou désactiver la numérotation automatique du document.</p>
        </div>
    </div>
    <div class="card-body" style="margin-top:2px">
        <div class="row align-middle">
            <div class="col-md-2  align-middle">
                <h6 style="color: black;">AVIS EN DEUXIÈME PAGE</h6>
            </div>
            <div class="form-check form-switch col-md-10 align-left">
                <input type="checkbox" name="" id="flexSwitchCheckChecked"
                    class="form-check-input form-control" placeholder="" aria-describedby="helpId">
            </div>
            <p>Générer l'avis d'échéance pour le mois suivant en deuxième page de la quittance du mois en cours.</p>
        </div>
    </div>
</div>
<div class="card" style="margin-top: 5px">
    <div class="card-header"
        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
        Texte pour la quittance
    </div>
    <div class="card-body">
        <div class="">
            <label for="" class="form-label">TEXTE</label>
            <textarea name="" id=""class="form-control" cols="20" rows="5"
                placeholder="EX. Paiement sous huitaine..."></textarea>
            <p>Texte à afficher automatiquement en bas de la Quittance. Ex. Paiement sous huitaine.</p>
        </div>
    </div>
</div>
<div class="card" style="margin-top: 5px">
    <div class="card-header"
        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
        Texte pour l'avis d'échéance
    </div>
    <div class="card-body">
        <div class="">
            <label for="" class="form-label">TEXTE</label>
            <textarea name="" id=""class="form-control" cols="20" rows="5"
                placeholder="EX. Paiement sous huitaine..."></textarea>
            <p>Texte à afficher automatiquement en bas de l'Avis d'échéance. Ex. Paiement sous huitaine.</p>
        </div>
    </div>
</div>

