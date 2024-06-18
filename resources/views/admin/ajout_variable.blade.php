@extends('layouts.adminappinner')

@section('content')
                
                  <?php
                    $message = Session::get('message');
                    $message1 = Session::get('message1');
                  ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Ajout de variable domaines
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


        @if ($message)
             <p class="alert alert-success">
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
<form method="post" action="{{route('sauver_variable')}}">
    @csrf
        <div class="form-group">
            <label for="ma_variable">Ajouter domaine</label>
            <input type="text" class="form-control" id="ma_variable" name="variable" placeholder="Exemple : .com , .fr, ...">
        </div>
              
        <button type="submit" class="btn btn-info pull-right ">Enregistrer</button>
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
</script> 
@endsection