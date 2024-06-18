<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <title>Document</title>
</head>
<body>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Bien</th>
                <th>Fichier</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($Documents as $document)
                <tr>
                    <td>{{ $document->date }}</td>
                    <td>{{ $document->logement_id }}</td>
                    <td>{{ $document->nomFichier }}</td>
                    <td>{{ $document->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
</body>
</html>