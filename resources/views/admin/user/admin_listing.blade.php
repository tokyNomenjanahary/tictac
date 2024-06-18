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
                        <h3 class="box-title">Users Listing</h3>
                        <div class="box-tools">
                            <form id="searchForm" method="GET">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="search_name" @if(!empty($search_name['search_name'])) value="{{$search_name['search_name']}}" @endif class="form-control pull-right" placeholder="Search">
                                           <div class="input-group-btn">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">
                            <tr>
                                <th>S. No.</th>
                                <th>
                                    @if(!empty($shorting['short']) && $shorting['short']=='name' && !empty($shorting['type']) && ($shorting['type']=='desc' || $shorting['type']=='DESC'))
                                    <a href="{{url(getConfig('admin_prefix') . '/siteusers?short=name&type=asc')}}">User name <i class="fa fa-sort-asc"></i></a>
                                    @elseif(!empty($shorting['short']) && $shorting['short']=='name' && !empty($shorting['type']) && ($shorting['type']=='asc' || $shorting['type']=='ASC'))
                                    <a href="{{url(getConfig('admin_prefix') . '/siteusers?short=name&type=desc')}}">User name <i class="fa fa-sort-desc"></i></a>
                                    @else
                                    <a href="{{url(getConfig('admin_prefix') . '/siteusers?short=name&type=asc')}}">User name <i class="fa fa-sort"></i></a>
                                    @endif
                                </th>
                                <th>
                                    @if(!empty($shorting['short']) && $shorting['short']=='email' && !empty($shorting['type']) && ($shorting['type']=='desc' || $shorting['type']=='DESC'))
                                    <a href="{{url(getConfig('admin_prefix') . '/siteusers?short=email&type=asc')}}">Email <i class="fa fa-sort-asc"></i></a>
                                    @elseif(!empty($shorting['short']) && $shorting['short']=='email' && !empty($shorting['type']) && ($shorting['type']=='asc' || $shorting['type']=='ASC'))
                                    <a href="{{url(getConfig('admin_prefix') . '/siteusers?short=email&type=desc')}}">Email <i class="fa fa-sort-desc"></i></a>
                                    @else
                                    <a href="{{url(getConfig('admin_prefix') . '/siteusers?short=email&type=asc')}}">Email <i class="fa fa-sort"></i></a>
                                    @endif
                                </th>
                                <th>
                                    @if(!empty($shorting['short']) && $shorting['short']=='date' && !empty($shorting['type']) && ($shorting['type']=='desc' || $shorting['type']=='DESC'))
                                    <a href="{{url(getConfig('admin_prefix') . '/siteusers?short=date&type=asc')}}">Created Date <i class="fa fa-sort-asc"></i></a>
                                    @elseif(!empty($shorting['short']) && $shorting['short']=='date' && !empty($shorting['type']) && ($shorting['type']=='asc' || $shorting['type']=='ASC'))
                                    <a href="{{url(getConfig('admin_prefix') . '/siteusers?short=date&type=desc')}}">Created Date <i class="fa fa-sort-desc"></i></a>
                                    @elseif(empty($shorting))
                                    <a href="{{url(getConfig('admin_prefix') . '/siteusers?short=date&type=asc')}}">Created Date <i class="fa fa-sort-asc"></i></a>
                                    @else
                                    <a href="{{url(getConfig('admin_prefix') . '/siteusers?short=date&type=asc')}}">Created Date <i class="fa fa-sort"></i></a>
                                    @endif
                                </th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                            @if(!empty($users))
                            @foreach($users as $key => $user)
                            <tr>
                                <td>{{$users->firstItem() + $key}}</td>
                                <td><a href="{{url(getConfig('admin_prefix') . '/user_profile/'.base64_encode($user->id))}}">{{$user->first_name}}@if(!empty($user->last_name)){{' '. $user->last_name}}@endif</a></td>
                                <td>{{$user->email}}</td>
                                <td>{{date('d M Y H:i:s', strtotime(adjust_gmt($user->created_at)))}}</td>
                                <td>{{$user->designation}}</td>
                                <td class="action-td">
                                    @if(empty($user->deleted_at))
                                    <a title="Delete" class="action-icon-del" id="{{base64_encode($user->id)}}"
                                       redirect-url="{{url(getConfig('admin_prefix') . '/deleteUser/')}}"><i class="fa fa-trash-o"></i></a>
                                    @endif
                                    <a title="Edit user profile" class="action-toggle-edit"
                                       href="{{url(getConfig('admin_prefix') . '/edit_profile/' . base64_encode($user->id))}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>

                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>{{'No record found'}}</tr>
                            @endif
                        </table>
                    </div>
                    <div class="pull-right">
                        @if($users)
                        @if($shorting)
                        {{ $users->appends($shorting)->links('vendor.pagination.bootstrap-4') }}
                        @elseif(!empty($search_name))
                        {{ $users->appends($search_name)->links('vendor.pagination.bootstrap-4') }}
                        @else
                        {{ $users->links('vendor.pagination.bootstrap-4') }}
                        @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="mi-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="modal-btn-yes">Yes</button>
                <button type="button" class="btn btn-default" id="modal-btn-no">No</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="upgrade_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    Jusqu'en quelle date voulez vous que cet user restera premium?
                </h4>
            </div>
            <div class="modal-body">
                <div class="col-sm-4 col-md-4 col-xs-6 filtre-col">
                <div class="datepicker-outer">
                    <div class="custom-datepicker">
                        <input class="form-control date_field" type="text" id="date_premium" name="date_report" value="{{date('d/m/Y')}}" readonly placeholder="dd/mm/yyyy">
                    </div>
                </div>
                <input type="hidden" id="url_upgrade">
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-save-upgrade">Enregistrer</button>
                <button type="button" class="btn btn-default" id="modal-btn-no" data-dismiss="modal">Annuler</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#date_premium").datepicker({
            format: "dd/mm/yyyy",
            minDate: "-0d",
            setDate : new Date()
        });

        $(".action-toggle-upgrade").on('click', function(){
            $('#url_upgrade').val($(this).attr('data-href'));
            $('#upgrade_modal').modal('show');
        });

        $("#btn-save-upgrade").on('click', function(){
            var url = $('#url_upgrade').val() + "&date=" + $("#date_premium").val();
            location.href = url;
        });
    });
</script>
@endsection
