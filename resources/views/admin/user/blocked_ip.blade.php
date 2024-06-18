@extends('layouts.adminappinner')

<!-- Push a script dynamically from a view -->
@push('styles')
<!--    <link href="{{ asset('css/admin/datatables.net-bs/dataTables.bootstrap.min.css') }}" rel="stylesheet">-->
@endpush

<!-- Push a script dynamically from a view -->
@push('scripts')
<!--    <script src="{{ asset('js/admin/datatables.net/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/admin/datatables.net-bs/dataTables.bootstrap.min.js') }}"></script>-->

<script src="{{ asset('js/admin/manageusers.js') }}"></script>
@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Site Users
            <small>Manage users</small>
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
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Blocked IP</h3>
                        <div class="box-tools">
                            <button type="submit" id="btn-add-ip" class="btn btn-default">Ajouter</button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">
                            <tr>
                                <th> 
                                    Ip
                                </th>
                                <th> 
                                    Date
                                </th>
                                <th> 
                                    Action
                                </th>
                            </tr>
                            @if(!empty($ips))
                            @foreach($ips as $key => $ip)
                            <tr>
                                <td data-id="{{$ip->id}}">{{$ip->ip}}</td>
                                <td>{{$ip->date}}</td>
                                <td>
                                    <a title="Delete" class="action-ip-del" href="{{getConfig('admin_prefix')}}/delete-ip/{{$ip->id}}"><i class="fa fa-trash-o"></i></a>
                                </td>
                                
                            </tr>
                            @endforeach
                            @else
                            <tr>{{'No record found'}}</tr>
                            @endif
                        </table>
                    </div>
                    <div class="pull-right">
                        @if($ips) 
                        {{ $ips->links('vendor.pagination.bootstrap-4') }}
                        @endif
                        

                        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="ip_modal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">
                                            Saisissez l'ip
                                        </h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-4 col-md-4 col-xs-6">
                                                <input type="text" class="form-control" id="ip" name="ip">
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" id="btn-save-ip">Enregistrer</button>
                                        <button type="button" class="btn btn-default" id="modal-btn-no" data-dismiss="modal">Annuler</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div> 
<script type="text/javascript">
    $('#btn-add-ip').on('click', function(){
        $('#ip_modal').modal('show');
    });
    $('#btn-save-ip').on('click', function(){
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            url: "{{getConfig('admin_prefix')}}/save-ip",
            type: 'post',
            data : { "ip" : $('#ip').val() },
            success: function (data) {
                location.reload();
            }
        });

    });
</script> 
@endsection