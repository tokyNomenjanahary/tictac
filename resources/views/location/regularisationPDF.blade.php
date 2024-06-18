<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        p{
            font-size: 12px;
            line-height: 8px;
        }
    </style>
</head>
<body>

        <div class="row align-middle mt-2 p-3" >
            <div class="col-md-2 text-end" style="margin-top:10px">
            </div>

            <div class="col-10 " >
                <div class="row">
                    <div class="col-6">
                        <p>{{Auth()->user()->first_name. ' ' . Auth()->user()->last_name}}</p>
                    </div>
                    <div class="col-6" style="margin-left:500px;margin-top:-250px;">
                        <p>{{$regularisation->location->Locataire->civilite . ' ' . $regularisation->location->Locataire->TenantFirstName .' '. $regularisation->location->Locataire->TenantLastName}}</p>
                        <p>{{$regularisation->location->Locataire->TenantAddress}}</p>
                        <p>{{$regularisation->location->Locataire->TenantCity}}</p>
                        <p>{{$regularisation->location->Locataire->TenantZip}}</p>
                    </div>
                </div>
                <div class="row mt-4">
                    <p>Date {{\Carbon\Carbon::now()->format('Y-m-d')}}</p>
                </div>
                <div class="row mt-3 text-center tit" style="border-top:2px black solid;padding:20px;border-bottom: 2px solid black">
                        <b style="font-size: 19px;color:black">Régularisation des charges locatives </b>
                </div>
                <div class="row mt-4 text">
                    <p>Bonjour {{$regularisation->location->Locataire->civilite . ' ' . $regularisation->location->Locataire->TenantFirstName .' '. $regularisation->location->Locataire->TenantLastName}},</p>
                    <p>Propriétaire du bien situé à {{$regularisation->location->Logement->adresse}}, j'ai procédé à une régularisation des charges locatives. </p>
                    <p>Le montant de cette régularisation est donc de {{$regularisation->location->Logement->charge}}€ en ma faveur. </p>
                    <p>Vous trouverez ci-après le bilan des charges réelles et des provisions de charges appelées sur la période {{\Carbon\Carbon::parse($regularisation->date_debut)->format('Y-m-d')}} - {{\Carbon\Carbon::parse($regularisation->date_fin)->format('Y-m-d')}}. </p>
                    <p>Les pièces justificatives des charges sont tenues à votre disposition. </p>
                    <p>N’hésitez pas à me contacter si vous souhaitez des renseignements complémentaires.</p>
                    <p>Je vous prie de bien vouloir agréer, l’expression de mes sentiments distingués. </p>
                </div>
                <div class="row mt-4 text-end">
                    <p>{{Auth()->user()->first_name. ' ' . Auth()->user()->last_name}}</p>
                </div>
                <div class="row mt-5">
                    <p>Bilan de la régularisation des charges sur la période: {{\Carbon\Carbon::parse($regularisation->date_debut)->format('Y-m-d')}} - {{\Carbon\Carbon::parse($regularisation->date_fin)->format('Y-m-d')}}</p>
                </div>
                <div class="row mt-2">
                    <p>Charges locatives réelles: {{$regularisation->location->Logement->charge}}€ </p>
                </div>
                <div class="row mt-2">
                    <p>Provisions sur charges appelées: {{$regularisation->location->Logement->charge}}€</p>
                </div>
                <div class="row mt-2">
                    <p>Crédit en faveur du Propriétaire : {{$regularisation->location->Logement->charge}}€</p>
                </div>
            </div>
        </div>

</body>
</html>
