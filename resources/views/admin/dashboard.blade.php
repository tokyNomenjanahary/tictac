@extends('layouts.adminappinner')

@section('content')
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
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
<!--      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>-->
    </section>
    
    <!-- Main content -->
    <section class="content">
        <h4 style="text-align: center;font-size: 26px;font-family: arial;margin-top: 69px;"> Bienvenu dans l'espace admin de Bailti </h4>
         </section>
</div>


<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script>
     $(document).ready(function() {
        $(function() {
          $('input[name="daterange"]').daterangepicker({
            opens: 'left',
            locale: {
              format: 'DD/MM/YYYY'
            }
          }, function(start, end, label) {
                //console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                var mydate = new Date();
                const month = mydate.getMonth()+1;
                location.href = "?debut=" + start.format('YYYY-MM-DD') + '&limit=' + end.format('YYYY-MM-DD') + "&adComunity=" + isAdsComunity();;
          });
        });
        $("#date_report").datepicker({
            format: "dd/mm/yyyy",
            minDate: "-0d",
            setDate : new Date()
        });

        $("#date_report").on('change', function(){
            location.href = "?date_report=" + $(this).val() + "&adComunity=" + isAdsComunity();
        });

        $("#filter-day").on('click', function(){
            var mydate = new Date();
            const month = mydate.getMonth()+1;
            location.href = "?date_report=" + mydate.getDate() + '/' + (month < 10 ? '0' + month : month)  + '/' + mydate.getFullYear()+"&show=D&adComunity=" + isAdsComunity();
        });

        $("#filter-week").on('click', function(){
            var mydate = new Date();
            const month = mydate.getMonth()+1;
            location.href = "?show=W&adComunity=" + isAdsComunity();
        }); 

        $("#filter-month").on('click', function(){
            var mydate = new Date();
            const month = mydate.getMonth()+1;
            location.href = "?date_report=" + mydate.getDate() + '/' + (month < 10 ? '0' + month : month)  + '/' + mydate.getFullYear()+"&show=M&adComunity=" + isAdsComunity();
        });

        $("#adComunity").on('change', function(){
            var queryParams = new URLSearchParams(window.location.search);

            // Set new or modify existing parameter value. 
            queryParams.set("adComunity", isAdsComunity());

            history.pushState(null, null, "?"+queryParams.toString());
            location.href = window.location.search;
        });
         

        function isAdsComunity()
        {
            if($('#adComunity').is(':checked')) {
                return true;
            } else return false;
        }
    });
</script>
@endsection