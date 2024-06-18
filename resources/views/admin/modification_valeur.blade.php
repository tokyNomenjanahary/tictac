@extends('layouts.adminappinner')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Modification valeur
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
                                <input class="form-control date_field" typ
                                e="text" id="date_report" name="date_report" readonly value="@if(isset($date_report)) {{$date_report}} @else {{date('d/m/Y')}} @endif" placeholder="dd/mm/yyyy">
                            </div>
                        </div>
                    </div>
                </div>
        </div>
<form method="post" action="{{route('sauver_modif')}}">
    @csrf
        @if (\Session::has('danger'))
            <div class="alert alert-danger">
                <ul style="list-style-type:none;">
                    <li>{!! \Session::get('danger') !!}</li>
                </ul>
            </div>
        @endif
    @foreach ($valeurs as $valeur)
        
            <div class="form-group">
                <label for="devise">Domaine</label>
                <input type="text" class="form-control" id="domaine" name="valeur" value="{{$valeur->domaine}}" readonly>
            </div>

            <div class="form-group">
                <label for="devise">Propriete</label>
                <input type="text" class="form-control" id="propriete" name="valeur" value="{{$valeur->nom}}"  readonly value="">
            </div>
            <br>
            <div class="form-group" id="result">

            </div>
            <!-- <div class="form-group">
                <label for="devise">Nouveau valeur</label>
                <input type="text" class="form-control" id="valeur" name="valeur" value="{{$valeur->valeur}}">
            </div> -->

            <button type="submit" class="btn btn-info pull-right ">Enregistrer</button>
     @endforeach
</form>
        
    </section>
</div>
<style type="text/css">
    .filtre-box 
    {
        height: 90px;
    }

    .filtre-col
    {
        margin-top: 5px;
    }
</style>
<script>
     $(document).ready(function() {
        $("#date_report").datepicker({
            format: "dd/mm/yyyy",
            minDate: "-0d",
            setDate : new Date()
        });

        $("#date_report").on('change', function(){
            location.href = "?date_report=" + $(this).val();
        });
    });
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
        var pardefaut = '<input type="text" class="form-control affichage" id="val" name="valeur" placeholder="Entrer la nouvelle valeur">';
        var exacte=document.getElementById('propriete').value;
    console.log(exacte);
    if (exacte == 'devise' || exacte == 'Devise') {
            document.getElementById("result").innerHTML = devise;
        } else if ((exacte == 'langue' || exacte == 'Langue')) {
            document.getElementById("result").innerHTML = langue;
        } else if (exacte == 'timezone' || exacte == 'Timezone') {
            document.getElementById("result").innerHTML = timezone;
        } else {
            document.getElementById("result").innerHTML = pardefaut;
        }
</script> 
@endsection