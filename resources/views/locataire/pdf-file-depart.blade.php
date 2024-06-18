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
            DE : ________________
        </div>
        <div style="margin-top: 10px">
            A : ________________
        </div>
    </div>
    <div  style="margin-top: 10px">
        @php
            setlocale(LC_TIME, 'french');
            $mytime = Carbon\Carbon::now()->formatLocalized('%d %B %Y');
            $birth_date = '';
            if ($locataire->TenantBirthDate) {
                $date = Carbon\Carbon::createFromFormat('Y-m-d', $locataire->TenantBirthDate);
                $date->locale('fr');
                $birth_date = $date->isoFormat('DD MMMM YYYY');
            }
        @endphp
        Fait le {{ $mytime }}
    </div>
    <div style="text-align: center">
        <h2>Déclaration au départ du locataire</h2>
    </div>
    <div>
        <p>Madame, Monsieur,</p>
        <p>
            Je vous signale le départ du locataire ci-après dénommé et vous communique les renseignements en ma possession.
        </p>
        <p>
            Adresse des lieux loués : 
        </p>
        <p>
            Nom et prénoms du (des) locataire(s) partant(s) : {{ $locataire->TenantFirstName . ' ' . $locataire->TenantLastName}} (né(e) le {{ $birth_date }}
        </p>
        <p>
            Date du départ :  
        </p>
        <p>Nouvelle adresse du (des) locataire(s) : </p>
        <p>Nom et prénoms du (des) propriétaire(s) : </p>
        <p>Je vous prie de croire, Madame, Monsieur, en mes très distinguées salutations.</p>
    </div>
</div>

</body>
</html>