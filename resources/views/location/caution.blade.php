<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>caution location</title>
    <style>
        ul{
            list-style-type: none;
        }
        .border{
            border: 1px solid black !important;
            padding-left: 5px !important;F
        }
    </style>
</head>

<body>
    <div class="container" style="border: 3px solid gray;color:black;background-color:#FFFFFF;padding:30px;margin-top:15px;margin-bottom:15px;">
        <div class="row text-center">
            <h1>Acte de caution solidaire</h1><br>
            <h5>POUR LES BAILLEURS N'AYANT PAS SOUSCRIT D'ASSURANCE GARANTISSANT LES OBLIGATIONS LOCATIVES LOI N°94-624 DU 21 JUILLET 1994</h5>
        </div> <br>
        <div class="row border">
            <h4 >LA CAUTION</h4>
        </div>
        <div class="row mt-5">
            <ul>
                @foreach($locations as $location)
                  @foreach($location->garants as $garant)
                    <li>Nom : {{$garant->prenom}}</li>
                    <li>Demeurant : </li>
                    <li>Dénommé(s) ci-après « LA CAUTION », (au singulier)</li>
                  @endforeach
                @endforeach
            </ul>
        </div> <br>
        <div class="row border">
            <h4>LE BAILLEUR</h4>
        </div>
        <div class="row">
            <ul>
                <li>Nom : {{ Auth::user()->first_name }}</li>
                <li>Demeurant : </li>
                <li>Dénommé(s) ci-après « LE BAILLEUR », (au singulier)</li>
            </ul>
        </div> <br>
        <div class="row border">
            <h4>LE(S) LOCATAIRES</h4>
        </div>
        <div class="row">
            <ul>
                @foreach($locations as $location)
                    <li>Nom : {{$location->Locataire->TenantLastName}}</li>
                    <li>Demeurant : {{$location->Locataire->TenantBirthPlace	}}</li>
                    <li>Né(e) à : {{$location->Locataire->TenantAddress}}</li>
                    <li>Dénommé(s) ci-après « LE LOCATAIRE », (au singulier)</li>
                @endforeach
            </ul>
        </div> <br>
        <div class="row">
            <h3>Dénommé(s) ci-après « LA CAUTION », (au singulier)</h3>
        </div>
        <div class="row">
            <ul>
                @foreach($locations as $location)
                    <li>Durée de contrat : {{$location->debut}} à {{$location->fin}}</li>
                    <li>Date de début du bail : {{$location->debut}}</li>
                    <li>Montant du loyer mensuel hors charges : {{$location->loyer_HC}}</li>
                    <li>Montant des charges : {{$location->charge}}</li>
                @endforeach
            </ul> <br>
            @foreach($locations as $location)
              @foreach($location->garants as $garant)
               <p>Je soussigné {{$garant->prenom}}, demeurant
                Déclare me porter caution solidaire, sans bénéfice de discussion ni de division, du règlement des loyers, charges, taxes, impôts,
                réparations locatives, toutes indemnités et intérêts de retard dus par le LOCATAIRE : <br>
                - En vertu du bail consenti pour les locaux qu'il occupera à compter du : {{$location->debut}} <br>
                - Du bien situé à : {{$location->Logement->adresse}} <br>
                J'ai pris connaissance du montant du loyer et du contrat de location et un exemplaire m'a été remis.
                Je serai donc tenu de satisfaire à toutes les obligations du LOCATAIRE en cas de défaillance de sa part, à l'égard du BAILLEUR et ce
                pour une durée de 9 ans à compter de la date de départ du bail, soit le 28.05.2023.
                Il est rappelé l'avant dernier alinéa de l'article 22-1 de la loi du 6 juillet 1989 :
                "Lorsque le cautionnement d'obligation résultant d'un contrat de location conclu en application du présent titre ne comporte aucune
                indication de durée ou lorsque la durée du cautionnement est stipulée indéterminée, la caution peut le résilier unilatéralement. La
                résiliation prend effet au terme du contrat de location, qu'il s'agisse du contrat initial ou d'un contrat reconduit ou renouvelé, au cours
                duquel le bailleur reçoit notification de la résiliation."
                Je reconnais également avoir pris connaissance de l'article 2297 du code civil, selon lequel :
                Si je suis privée des bénéfices de discussion ou de division, je reconnais ne pas pouvoir exiger du BAILLEUR qu'il poursuive d'abord le
                LOCATAIRE ou qu'il divise ses poursuites entre les cautions. A défaut, je conserve le droit de me prévaloir de ces bénéfices.
                La CAUTION doit reproduire la mention suivante au moment de la signature :
                « Par cet acte, je m'engage à payer au propriétaire en cas de défaillance du locataire, les loyers et charges qui s'élèvent à (indiquez impérativement le montant du
                loyer charges comprises en chiffres et en lettres) 1799.99 €, par mois, révisés annuellement selon la variation de l'indice de référence des loyers publié par l'INSEE,
                des charges récupérables, des indemnités d'occupation, des dégradations et réparations locatives et des frais de procédures, indemnités, pénalités et dommagesintérêts,
                dans la limite d'un montant de (indiquez impérativement le montant maximal de votre engagement en chiffres et en lettres, prévoir un montant représentant au
                moins deux ans de loyers et charges). Je reconnais que je ne peux pas exiger du propriétaire qu'il poursuive d'abord le locataire ou qu'il divise ses poursuites entre les
                cautions. »</p> <br>
              @endforeach
            @endforeach
                <h5>Pour l'exécution éventuelle de cet engagement, en cas de litige, le tribunal du lieu de la location sera seul compétent.</h5>
        </div> <br>
        <div class="row">
            <div class="col-md-3 text-center">
                <h4>La CAUTION</h4>
                <p> Le présent acte de caution est transmis et accepté pour la caution.</p>
            </div>
            <div class="col-md-3 text-center">
                <h4>Le BAILLEUR</h4>
                <p>Le présent acte de caution est transmis et accepté pour lepropriétaire.</p>
            </div>
            <div class="col-md-3 text-center">
                <p>Fait le {{ \Carbon\Carbon::now()->format('d.m.Y') }}</p>
            </div>
        </div><br>
        <div class="row text-center">
            <p style="text-align:center; font-size:20px;">© Bailti </p>
        </div>
    </div>
    <script type="text/javascript" src="../assets/client/layout1/js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"
        integrity="sha512-1/RvZTcCDEUjY/CypiMz+iqqtaoQfAITmNSJY17Myp4Ms5mdxPS5UV7iOfdZoxcGhzFbOm6sntTKJppjvuhg4g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>
