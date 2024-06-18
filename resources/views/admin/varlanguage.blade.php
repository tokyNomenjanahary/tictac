@extends('layouts.adminappinner')

@section('content')
<?php
$message = Session::get('message');
$message1 = Session::get('message1');
$message3 = Session::get('message3');
$message2 = Session::get('message2');
$message4 = Session::get('message4');
$message5 = Session::get('message5');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Variable domaines
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->

        <!-- date -->

        <div class="row">
            <div class="box box-primary filtre-box">
                <div class="col-sm-2 col-md-2 col-xs-4 filtre-col">
                    <label>Date : </label>
                    <div class="datepicker-outer">
                        <div class="custom-datepicker">
                            <input class="form-control date_field" type="text" id="date_report" name="date_report" readonly value="@if(isset($date_report)) {{$date_report}} @else {{date('d/m/Y')}} @endif" placeholder="dd/mm/yyyy">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- fin date -->
        <div class="row text-center">
            <h2 class="text-center"> Tous les variables domaines</h2>
        </div>
        <br>
        @if ($message)
        <p class="alert alert-success">
            <?php
            echo $message;
            Session::put('message', null);
            ?>
        </p>
        @endif
        @if ($message1)
        <p class="alert alert-info">
            <?php
            echo $message1;
            Session::put('message1', null);
            ?>
        </p>
        @endif
        @if ($message3)
        <p class="alert alert-success">
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
        <p class="alert alert-success">
            <?php
            echo $message4;
            Session::put('message4', null);
            ?>
        </p>
        @endif
        @if ($message5)
        <p class="alert alert-success">
            <?php
            echo $message5;
            Session::put('message5', null);
            ?>
        </p>
        @endif
        <div class="row">
            <div class="col-lg-offset-4 col-lg-4">
                <table class="table table-hover text-center">
                    @foreach ($domaines as $domaine )
                    <tr style="height: 20px;border:2px solid gray;">
                        <td style="background-color:cornflowerblue;">{{$domaine->domaine}}</td>
                        <td ><button class="btn btn-primary" onclick="return confirm('êtes vous sûr de vouloir supprimer cet élément ?')"><a href="{{route('supprimer_domaine',['id' => $domaine->id])}}" style="color: white;">Supprimer</a></button> 
                        <button class="btn btn-primary"><a href="{{route('editer_domaine',['id' => $domaine->id])}}" style="color: white;">Modifier</a></button></td>
                    </tr>
                    @endforeach
                </table>
            </div>

            <div class="col-lg-4">
                <td><button style="margin-top:10px;" class="btn btn-primary"><a href="{{route('ajout_valeur')}}" style="color: white;padding-right:12px;padding-left:12px;">ajout propriete</a></button><br>
                <td><button style="margin-top:10px;" class="btn btn-primary"><a href="{{route('supprimer_propriete')}}" style="color: white;">modifier/supprimer</a></button>

            </div>
        </div>
           <hr style="border:1px solid gray;margin-right:5px;">
         <div class="row">
            <div class="col-xs-12 show-message center">

                    @foreach ($domaines as $domaine)
                    <div class="col-md-3" style="border:1px solid gray ;margin-left:5px;height:400px;">
                        <div class="text-center" >
                
                         <h2 class="text-center card-title">{{$domaine->domaine}} </h2>
                         <hr style="border:1px solid gray;margin-right:5px;">
                            @foreach ($valeurs as $valeur )
                                @foreach ($proprietes as $propriete )
                                    @if ($domaine->domaine ==$valeur->domaine )
                                        @if ($propriete->nom ==$valeur->nom )
                                           <span style="font-weight:bold;color:black;font-size:20px;">{{$propriete->nom}} :</span>
                                             <span style="font-size:20px ;">{{$valeur->valeur}}</span>  <p><button class="btn btn-primary" style="margin-right:5px ;"><a href="{{route('editer_valeur',['id' => $valeur->id])}}" style="color:white;" id="delete">Modifier</a></button><button class="btn btn-danger" onclick="return confirm('êtes vous sûr de vouloir supprimer cet élément ?')"><a href="{{route('supprimer_valeur',['id' => $valeur->id])}}" style="color: white;">Supprimer</a></button></p>
                                        @endif
                                    @endif
                                @endforeach
                            @endforeach
                        </div>
                      
                            
                    </div>
                        
                    @endforeach
                    <div>
            
        </div>

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
    $(document).ready(function() {
        $("#date_report").datepicker({
            format: "dd/mm/yyyy",
            minDate: "-0d",
            setDate: new Date()
        });

        $("#date_report").on('change', function() {
            location.href = "?date_report=" + $(this).val();
        });
    });
</script>
@endsection