<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Justificatif d'assurance</title>
    <style>
        hr {
            border-width: 2px;
        }

        body::before {
            content: "www.bailti.fr";
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            background-color: white;
            padding: 5px;
        }

    </style>
</head>

<body style="line-height: 1.2;">
    <div class="row">
        <div class="col">
            <p>DE : {{ $nomproprio }}</p>
            <br>
            <br>
            <br>
        </div>
    </div>
    <div style="margin-left: 75%">
        <p>A: {{ $location->Locataire->TenantLastName }}</p>
        <p>{{ $location->Logement->adresse }}</p>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col">
            <p>Fait le {{ \Carbon\Carbon::now()->format('d/m/Y') }} </p>
        </div>
    </div>
    <br>
    <br>
    <br>
    <div class="row">
        <div class="col">
            <div>
                <hr>
                <p>
                <h2 class="text-center">Demande de justificatif d'assurance</h2>
                </p>
                <hr>
            </div>
            </p>
            <br>
            <p>Madame, Monsieur,</p>
            <br>
            <p>A ce jour, je n'ai toujours pas reçu de justificatif ou d'attestation prouvant que le bien que vous
                occupez depuis le
                {{ \Carbon\Carbon::parse($location->debut)->format('d.m.Y') }}, en qualité de locataire, est bien assuré
            </p>
            <br>
            <p>Je vous rappelle que vous êtes dans l'obligation légale de vous couvrir contre les risques dont vous avez
                à
                répondre en qualité de locataire, aussi je vous remercie de bien vouloir me communiquer dans les
                meilleurs
                délais les pièces justifiant de cette assurance.</p>
            <br>
            <p>Dans cette attente, veuillez agréer, Madame, Monsieur, l'assurance de mes sentiments distingués</p>
            <br>
        </div>
    </div>
    <div>
        <p style="text-align:right">{{ $nomproprio }}</p>
        @if(!empty($signaturePro))
            <p style="text-align:right">
                <img
                    style="height: {{$signaturePro['desiredHeight']}};width: {{$signaturePro['desiredWidth']}}; margin-top: 5px;"
                    src={{$signaturePro['path']}}>
            </p>
        @endif
    </div>
</body>

</html>
