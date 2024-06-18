<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body style="font-size: 20px">
<div style="padding: 30px 20px">
    <div>
        <div>
            DE :
        </div>
        <div style="margin-top: 10px">
            A : {{ $locataire->TenantFirstName . ' ' . $locataire->TenantLastName}}
        </div>
    </div>
    <div  style="margin-top: 10px">
        @php
            setlocale(LC_TIME, 'French');
            $mytime = Carbon\Carbon::now()->formatLocalized('%d %B %Y');
        @endphp
        Fait le {{ $mytime }}
    </div>
    <div style="text-align: center">
        <h2>Demande de justificatif d’assurance</h2>
    </div>
    <div>
        <p>Madame, Monsieur,</p>
        <p>
            A ce jour, je n'ai toujours pas reçu de justificatif ou d'attestation prouvant que le logement que vous occupez depuis le
        </p>
        <p>
            Je vous rappelle que vous êtes dans l'obligation légale de vous couvrir contre les risques dont vous avez à répondre en qualité de locataire, aussi je vous remercie
            de bien vouloir me communiquer dans les meilleurs délais les pièces justifiant de cette assurance.
        </p>
        <p>
            Dans cette attente, veuillez agréer, Madame, Monsieur, l'assurance de mes sentiments distingués.
        </p>
    </div>
</div>

</body>
</html>