@extends('layouts.adminappinner')

<!-- Push a script dynamically from a view -->
@push('styles')
<!--    <link href="{{ asset('css/admin/datatables.net-bs/dataTables.bootstrap.min.css') }}" rel="stylesheet">-->
@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Indicateur pourcentage vente par Ville
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        @if ($message = Session::get('error'))
        <div class="alert alert-danger fade in alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            {{ $message }}
        </div>

        @endif

        <div class="row" style="background: white;">
            <div class="col-xs-12">
                    <div class="row">
                        <div class="box box-primary filtre-box">
                            <div class="col-sm-2 col-md-2 col-xs-4 filtre-col" style="margin-bottom: 20px;">
                                <label>Date : </label>

                                <input class="filtre form-control" type="text" id="daterange" readonly name="daterange"
                                       @if(isset($start_date)) value="{{$start_date}}-{{$end_date}}" @endif />

                                <input type="hidden" id="date_debut"
                                       @if(isset($start_date)) value="{{$start_date}}"
                                       @else value="{{date('d/m/Y')}}" @endif>
                                <input type="hidden" id="date_limit"
                                       @if(isset($end_date)) value="{{$end_date}}"
                                       @else value="{{date('d/m/Y')}}" @endif>
                            </div>


                            <div class="col-sm-2 col-md-2 col-xs-4 filtre-col" style="margin-bottom: 20px;">
                                <label>IP user / Recheche User </label>

                                <select id="sel-fb" class="groupe selectpicker">
                                    <option value="0"></option>
                                    <option @if(isset($currentChoi) && $currentChoi == 1) selected @endif value="1">Utilisateur de cette ville (IP)</option>
                                    <option @if(isset($currentChoi) && $currentChoi == 2) selected @endif value="2">Utilisateur qui recherche dans cette ville</option>


                                </select>
                            </div>

                        </div>

                </div>
                <div class="row">
                    <div class="box box-primary">

                    <!-- /.box-header -->
                        <input type="hidden" id="short" name="">
                        <input type="hidden" id="desc" name="">
                        <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">
                            <tr>
                                <th>
                                    @if(!empty($shorting['short']) &&
                                        $shorting['short'] == 'city' &&
                                        !empty($shorting['type']) &&
                                        ($shorting['type'] == 'desc' || $shorting['type'] == 'DESC'))
                                        <a href="{{url(getConfig('admin_prefix') .
                                                        "/indicateur_vente?short=city&type=asc&start=${search_query['start']}&end=${search_query['end']}&choix=${currentChoi}")}}">Ville <i class="fa fa-sort-asc"></i></a>

                                    @elseif(!empty($shorting['short']) &&
                                        $shorting['short'] == 'city'  &&
                                        !empty($shorting['type']) &&
                                        ($shorting['type'] == 'asc' || $shorting['type']=='ASC'))
                                        <a href="{{url(getConfig('admin_prefix') .
                                                        "/indicateur_vente?short=city&type=desc&start=${search_query['start']}&end=${search_query['end']}&choix=${currentChoi}")}}">Ville <i class="fa fa-sort-desc"></i></a>
                                    @else
                                        <a href="{{url(getConfig('admin_prefix') .
                                                        "/indicateur_vente?short=city&type=asc&start=${search_query['start']}&end=${search_query['end']}&choix=${currentChoi}")}}">Ville <i class="fa fa-sort"></i></a>
                                    @endif

                                </th>


                                <th>
                                    @if(!empty($shorting['short']) &&
                                        $shorting['short'] == 'nbVente' &&
                                        !empty($shorting['type']) &&
                                        ($shorting['type'] == 'desc' || $shorting['type'] == 'DESC'))
                                        <a href="{{url(getConfig('admin_prefix') .
                                                    "/indicateur_vente?short=nbVente&type=asc&start=${search_query['start']}&end=${search_query['end']}&choix=${currentChoi}")}}">Nombre de vente <i class="fa fa-sort-asc"></i></a>

                                    @elseif(!empty($shorting['short']) &&
                                        $shorting['short'] == 'nbVente'  &&
                                        !empty($shorting['type']) &&
                                        ($shorting['type'] == 'asc' || $shorting['type']=='ASC'))
                                        <a href="{{url(getConfig('admin_prefix') .
                                                        "/indicateur_vente?short=nbVente&type=desc&start=${search_query['start']}&end=${search_query['end']}&choix=${currentChoi}")}}">Nombre de vente <i class="fa fa-sort-desc"></i></a>
                                    @else
                                        <a href="{{url(getConfig('admin_prefix') .
                                                        "/indicateur_vente?short=nbVente&type=asc&start=${search_query['start']}&end=${search_query['end']}&choix=${currentChoi}")}}">Nombre de vente <i class="fa fa-sort"></i></a>
                                    @endif

                                </th>

                                @if(isset($currentChoi) && $currentChoi == 1)
                                    <th>Nombre user dans cette ville</th>
                                    <th>Pourcentage user de cette ville (IP)</th>
                                @endif
                                @if(isset($currentChoi) && $currentChoi == 2)
                                    <th>Nombre user qui cherches</th>
                                    <th>Pourcentage user qui Recherche</th>
                                @endif
                            </tr>

                            @if(!empty($all_city))
                                @foreach($all_city as $city => $payment)
                                <tr>
                                    <td>{{ $city }}</td>
                                    <td>{{ $all_number[$city] }}</td>

                                    @if(isset($currentChoi) && $currentChoi == 1)

                                        <?php $indIp = getNbUserVilleCente($city, $all_number[$city]); ?>

                                         @if(!is_null($indIp))
                                             <td>{{$indIp['nbuser']}} </td>

                                                @if($indIp['resultat'] == $all_number[$city])
                                                    <td>1</td>
                                                @else
                                                    <td>{{$indIp['resultat']}}</td>
                                                @endif
                                                {{--<td>{{$indIp['resultat']}} / 100</td>--}}
                                         @endif
                                     @endif

                                     @if(isset($currentChoi) && $currentChoi == 2)

                                          <?php $indRech = getNbUserRechercheCente($city, $all_number[$city]); ?>

                                          @if(!is_null($indRech))
                                             <td>{{ $indRech['nbRe'] }}</td>

                                                  @if($indRech['resultat'] == $all_number[$city])
                                                      <td>1</td>
                                                  @else
                                                      <td>{{ $indRech['resultat'] }}</td>
                                                  @endif

                                             {{--<td>{{ $indRech['resultat'] }} / 100</td>--}}
                                          @endif
                                     @endif

                                </tr>
                                @endforeach
                            @else
                            	<tr><td colspan="8">{{'No record found'}}</td></tr>
                            @endif
                        </table>
                    </div>

                    </div>
                </div>

            </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="edit-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        Modification
                    </h4>
                </div>
                <div class="modal-body">
                    <form id="form-edit" method="POST" action="{{route('admin.save-edit-pub')}}">
                        {{csrf_field()}}
                        <div class="row">
                        <input type="hidden" name="id" id="id-edit">
                        <div class="col-sm-4 col-md-4 col-xs-6">
                            <label>Lien</label>
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 col-md-4 col-xs-6 full">
                                <input type="text" class="full" name="lien" id="lien-edit">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 col-md-4 col-xs-6">
                                <label>Texte</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xs-12 full">
                                <textarea class="full" name="texte" id="texte-edit" rows="5">

                                </textarea>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-4 col-md-4 col-xs-12">
                                <label>Login</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xs-12 full">
                                <input class="full" type="text" name="login" id="login-edit">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xs-12">
                                <label>Mdp</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xs-12 full">
                                <input class="full" type="text" name="mdp" id="mdp-edit">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-xs-12">
                                <label>Proxy</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xs-12 full">
                                <input  class="full" type="text" name="proxy" id="proxy-edit">
                            </div>
                        </div>
                    </form>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn-save-pub">Enregistrer</button>
                    <button type="button" class="btn btn-default" id="modal-btn-no" data-dismiss="modal">Annuler</button>
                </div>
            </div>
        </div>
    </div>
    </section>
</div>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style type="text/css">
    .input-edit
    {
        width: 100%;
    }
    .action-edit-button
    {
        display: none;
    }
    .full
    {
        width: 100%;
    }

    .filtre-col
    {
        margin-left: 10px;
    }

    .Total
    {
        float: right;
        margin-right: 20px;
        font-size: 25px;
        font-weight: bold;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $('#btn-save-pub').on('click', function(){
            $('#form-edit').submit();
        });

        $('.edit-item').on('click', function(){
            var id = $(this).attr('data-id');
            var lien = $('#lien-' + id).text();
            var texte = $('#texte-' + id).text();

            var login = $('#login-' + id).text();
            var mdp = $('#mdp-' + id).text();
            var proxy = $('#lien-' + id).text();
            $('#id-edit').val(id);
            $('#lien-edit').val(lien);
            $('#texte-edit').val(texte);

            $('#login-edit').val(login);
            $('#mdp-edit').val(mdp);
            $('#proxy-edit').val(proxy);
            $('#edit-modal').modal('show');
        });

        $('#daterange').daterangepicker({
            opens: 'left',
            locale: {
              format: 'DD/MM/YYYY'
            },
            startDate : $('#date_debut').val(),
            endDate : $('#date_limit').val()
          }, function(start, end, label) {
                executeFiltre();

          });

        $(".filtre").on('change', function(){
            executeFiltre();
        });

        $(".groupe").on('change', function(){
            executeGroupe();
        });



        $('.col-short').on('click', function(){
            var short = $(this).attr("data-sort");
            var type = $(this).attr("data-type-sort");
            $('#short').val(short);
            $('#desc').val(type);
            executeFiltre();
        });

        function executeFiltre()
        {
            var drp = $('#daterange').data('daterangepicker');
            console.log(drp.startDate.format('DD/MM/YYYY'));
            var start = drp.startDate.format('YYYY-MM-DD');
            var end = drp.endDate.format('YYYY-MM-DD');


            var choix = $('#sel-fb').val();

            var params = "?start=" + start + "&end=" + end;

            if(choix != 0) {
                params += '&choix=' +choix;
            }



            if($('#short').val() != "") {
                params += '&sort=' + $('#short').val();
            }

            if($('#desc').val() != "") {
                params += '&desc=' + $('#desc').val();
            }

            location.href = params;
        }

        function executeGroupe()
        {
            var drp = $('#daterange').data('daterangepicker');
            console.log(drp.startDate.format('DD/MM/YYYY'));
            var start = drp.startDate.format('YYYY-MM-DD');
            var end = drp.endDate.format('YYYY-MM-DD');


            var choix = $('#sel-fb').val();

            var params = "?start=" + start + "&end=" + end;
            if(choix != 0) {
                params += '&choix=' +choix;
            }



            if($('#short').val() != "") {
                params += '&sort=' + $('#short').val();
            }

            if($('#desc').val() != "") {
                params += '&desc=' + $('#desc').val();
            }

            location.href = params;
        }
    });

</script>
@endsection
