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
            Publication
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
        @if ($message = Session::get('status'))
        <div class="alert alert-success fade in alert-dismissable">
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

                                <input class="filtre form-control" type="text" id="daterange" readonly name="daterange" @if(isset($start_date)) value="{{$start_date}}-{{$end_date}}" @endif />
                                <input type="hidden" id="date_debut" @if(isset($start_date)) value="{{$start_date}}" @else 
                                value="{{date('d/m/Y')}}" @endif>
                                <input type="hidden" id="date_limit" @if(isset($end_date)) value="{{$end_date}}" @else 
                                value="{{date('d/m/Y')}}" @endif>
                            </div>

                            <div class="col-sm-2 col-md-2 col-xs-4 filtre-col" style="margin-bottom: 20px;">
                                <label>User : </label>

                                <select id="sel-user" class="filtre selectpicker">
                                    <option value="0"></option>
                                    @foreach($users as $user)
                                    <option @if(isset($currentUser) && $currentUser == $user->id) selected @endif value="{{$user->id}}">{{$user->first_name . " " . $user->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-2 col-md-2 col-xs-4 filtre-col" style="margin-bottom: 20px;">
                                <label>Status : </label>

                                <select id="sel-status" class="filtre selectpicker">
                                    <option value="0"></option>
                                    <option  @if(isset($status) && $status == "Visible") selected @endif value="Visible">Visible</option>
                                    <option @if(isset($status) && $status == "En attente de validation") selected @endif value="En attente de validation">En attente de validation</option>
                                </select>
                            </div>
                            <div class="col-sm-2 col-md-2 col-xs-4 filtre-col" style="margin-bottom: 20px;">
                                <label>Nombre de ligne : </label>
                                     <select id="sel-stati" class="stati selectpicker">
                                    <option value="0"></option>
                                    <option @if(isset($stati) && $stati == 20) selected @endif value="20">20</option>
                                    <option @if(isset($stati) && $stati == 50) selected @endif value="50">50</option>
                                    <option @if(isset($stati) && $stati == 100) selected @endif value="100">100</option>
                                    <option @if(isset($stati) && $stati == 1000) selected @endif value="1000">1000</option>
                                 
                                  </select>
                             </div>
                             
                        </div>
                        <div style="margin-bottom: 20px;">
                            <div class="Total">
                                Total : {{$data->total()}}
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
                                       <a class="col-short" href="javascript:" @if(isset($search_query) && $search_query['sort'] == "date_enregistrement" && !$search_query['desc']) data-type-sort="desc" @endif data-sort="date_enregistrement">
                                            Date  
                                            @if(isset($search_query) && $search_query['sort'] == "date_enregistrement" && $search_query['desc'])
                                            <i class="fa fa-sort-asc"></i>
                                            @elseif(isset($search_query) && $search_query['sort'] == "date_enregistrement" && !$search_query['desc'])
                                            <i class="fa fa-sort-desc"></i>
                                            @else
                                            <i class="fa fa-sort"></i>
                                            @endif

                                        </a> 
                                    </th>
                                    <th>
                                        <a class="col-short" href="javascript:" @if(isset($search_query) && $search_query['sort'] == "first_name" && !$search_query['desc']) data-type-sort="desc" @endif data-sort="first_name">
                                            User name 
                                            @if(isset($search_query) && $search_query['sort'] == "first_name" && $search_query['desc'])
                                            <i class="fa fa-sort-asc"></i>
                                            @elseif(isset($search_query) && $search_query['sort'] == "first_name" && !$search_query['desc'])
                                            <i class="fa fa-sort-desc"></i>
                                            @else
                                            <i class="fa fa-sort"></i>
                                            @endif

                                        </a>

                                    </th>
                                    <th>
                                        <a class="col-short" href="javascript:" @if(isset($search_query) && $search_query['sort'] == "lien" && !$search_query['desc']) data-type-sort="desc" @endif data-sort="lien">
                                            Lien 
                                            @if(isset($search_query) && $search_query['sort'] == "lien" && $search_query['desc'])
                                            <i class="fa fa-sort-asc"></i>
                                            @elseif(isset($search_query) && $search_query['sort'] == "lien" && !$search_query['desc'])
                                            <i class="fa fa-sort-desc"></i>
                                            @else
                                            <i class="fa fa-sort"></i>
                                            @endif

                                        </a>
                                    </th>
                                    <th>Texte</th>
                                    <th> 
                                        Status
                                    </th>
                                    <th> 
                                       Login
                                    </th>
                                    <th>
                                        Mdp
                                    </th>
                                    <th>
                                        Proxy
                                    </th>
                                    <th>
                                        Date vérification
                                    </th>
                                    <th>
                                        Status vérification
                                    </th>
                                    <th>
                                        site
                                    </th>
                                    <th> 
                                        Action
                                    </th>
                                </tr>
                                @if(!empty($data))
                                @foreach($data as $key => $d)
                                <tr>
                                    <td id="date-{{$d->id}}">{{$d->date_enregistrement}}</td>
                                    <td>{{$d->first_name}}</td>
                                    <td id="lien-{{$d->id}}"><a href="{{$d->lien}}">{{$d->lien}}</a></td>
                                    <td id="texte-{{$d->id}}">{{html_entity_decode($d->texte, ENT_QUOTES | ENT_HTML5)}}</td>
                                    <td id="status-{{$d->id}}">{{ucfirst($d->status)}}</td>
                                    <td id="login-{{$d->id}}">{{$d->login}}</td>
                                    <td id="mdp-{{$d->id}}">{{$d->mdp}}</td>
                                    <td id="proxy-{{$d->id}}">{{$d->proxy}}</td>
                                    <td>{{$d->date_verification}}</td>
                                    <td>{{$d->status_verification}}</td>
                                    <td>{{$d->site}}</td>
                                    <td>
                                        <a href="javascript:" data-id="{{$d->id}}" class="edit-item edit-item-{{$d->id}} btn btn-info btn-sm" title ="Edit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                        <a href="{{route('admin.deletePub', [$d->id])}}" class="delete-item btn btn-danger btn-sm" title ="Annuler"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>{{'No record found'}}</tr>
                                @endif
                            </table>
                        </div>
                        <div class="pull-right">
                            @if($data) 
                            @if(!empty($search_query)) 
                            {{ $data->appends($search_query)->links('vendor.pagination.bootstrap-4') }}
                            @else
                            {{ $data->links('vendor.pagination.bootstrap-4') }}
                            @endif
                            @endif
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
                                <label>Status</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xs-12 full">
                                <select id="status-edit" name="status" >
                                    <option value="Attente validation">
                                        Attente validation
                                    </option>
                                    <option value="Visible">
                                        Visible
                                    </option>
                                </select>
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
            var status = $('#status-' + id).text();
            var login = $('#login-' + id).text();
            var mdp = $('#mdp-' + id).text();
            var proxy = $('#lien-' + id).text();
            $('#id-edit').val(id);
            $('#lien-edit').val(lien);
            $('#texte-edit').val(texte);
            $('#status-edit').val(status);
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
        $(".stati").on('change', function(){
            executeFiltre();
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
            var status = $('#sel-status').val();
            var user = $('#sel-user').val();
            var stati = $('#sel-stati').val();

            var params = "?start=" + start + "&end=" + end;
            if(user != 0) {
                params += '&user=' + user;
            }

            if(status != 0) {
                params += '&status=' + status;
            }
            if(stati != 0) {
                params += '&stati=' + stati;
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