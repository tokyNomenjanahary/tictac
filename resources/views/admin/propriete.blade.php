@extends('layouts.adminappinner')

@section('content')

<?php
$message = Session::get('message');
$message1 = Session::get('message1');
$message2 = Session::get('message2');
$message3 = Session::get('message3');
$message4 = Session::get('message4');
$message5 = Session::get('message5');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Ajout de propriete
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="box box-primary filtre-box">
                <div class="col-sm-2 col-md-2 col-xs-4 filtre-col">
                    <label>Date : </label>
                    <div class="datepicker-outer">
                        <div class="custom-datepicker">
                            <input class="form-control date_field" typ e="text" id="date_report" name="date_report" readonly value="@if(isset($date_report)) {{$date_report}} @else {{date('d/m/Y')}} @endif" placeholder="dd/mm/yyyy">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form method="post" action="{{route('sauver_value')}}">
            @csrf
            <div class="form-group">

                @if ($message)
                <p class="alert alert-danger">
                    <?php
                    echo $message;
                    Session::put('message', null);
                    ?>
                </p>
                @endif
                @if ($message1)
                <p class="alert alert-success">
                    <?php
                    echo $message1;
                    Session::put('message1', null);
                    ?>
                </p>
                @endif
                @if ($message3)
                <p class="alert alert-danger">
                    <?php
                    echo $message3;
                    Session::put('message3', null);
                    ?>
                </p>
                @endif
                @if ($message2)
                <p class="alert alert-success">
                    <?php
                    echo $message2;
                    Session::put('message2', null);
                    ?>
                </p>
                @endif
                @if ($message4)
                <p class="alert alert-danger">
                    <?php
                    echo $message4;
                    Session::put('message4', null);
                    ?>
                </p>
                @endif
                @if ($message5)
                <p class="alert alert-danger">
                    <?php
                    echo $message5;
                    Session::put('message5', null);
                    ?>
                </p>
                @endif
                <h3>Selection du domaine</h3>
                <select id="sortingField" name="categorie" class="form-control" name="categorie">
                    <option>Sélectionner domaine</option>

                    @foreach ($domaines as $domaine )
                    <option value="{{$domaine->id}}"> {{ $domaine->domaine}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <h3>Selection du propriete</h3>
                <br>
                <select id="inText" name="propriete" class="form-control" onchange="getValeurs()">
                    <option>Sélectionner la propriete</option>
                    @foreach ($proprietes as $propriete)
                    <option value="{{$propriete->id}}{{$propriete->nom}}" name="id">{{$propriete->nom}}</option>
                    @endforeach
                </select>
            </div>
            <div id="sum" class="form-group" style="margin-top:40px;">
            </div>
            <button type="submit" class="btn btn-info pull-right ">Enregistrer</button>
        </form>

    </section>
</div>
<style type="text/css">
    .filtre-box {
        height: 90px;
    }

    .filtre-col {
        margin-top: 5px;
    }
</style>
<script>
    //  var a=document.getElementById('inText').value;
    //      console.log(a);
    $(document).ready(function() {
        $("#date_report").datepicker({
            format: "dd/mm/yyyy",
            minDate: "-0d",
            setDate: new Date()
        });

        $("#date_report").on('change', function() {
            location.href = "?date_report=" + $(this).val();
        });

        //    var a= $("#inText").on("input").val();

        //    console.log(a);
    });


    function getValeurs() {
        var pardefaut = '<input type="text" class="form-control affichage" id="val" name="valeur" placeholder="ajouter la valeur">';
        var devise = '<select class="form-control" name="valeur">\n' +
        '<option>Selectionner la devise</option>\n' +
        '@foreach ($devises as $devise)<option>{{$devise->Nom}}</option>@endforeach'
        '</select>'
        var langue = '<select class="form-control" name="valeur">\n' +
        '<option>Selectionner la langue</option>\n' +
        '@foreach ($langues as $langue)<option>{{$langue->Nom}}</option>@endforeach'
        '</select>'
        var timezone = '<select class="form-control" name="valeur">\n' +
        '<option>Selectionner la timezone</option>\n' +
        '@foreach ($timezones as $timezone)<option>{{$timezone->Nom}}</option>@endforeach'
        '</select>'
        var valeur_afficher = document.getElementById('inText').value;
        var exacte = valeur_afficher.substr(1);
        console.log(exacte);
        console.log(valeur_afficher);
        if (exacte == 'devise' || exacte == 'Devise') {
            document.getElementById("sum").innerHTML = devise;
        } else if ((exacte == 'langue' || exacte == 'Langue')) {
            document.getElementById("sum").innerHTML = langue;
        } else if (exacte == 'timezone' || exacte == 'Timezone') {
            document.getElementById("sum").innerHTML = timezone;
        } else {
            document.getElementById("sum").innerHTML = pardefaut;
        }
    }
</script>
@endsection