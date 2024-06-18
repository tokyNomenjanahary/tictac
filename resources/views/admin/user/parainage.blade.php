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
                                <th> 
                                    Email
                                </th>
                                <th> 
                                    Parrain
                                </th>
                                <th> 
                                    Statut
                                </th>
                                <th> 
                                    Date
                                </th>
                            </tr>
                            @if(!empty($users))
                            @foreach($users as $key => $user)
                            <tr>
                                <td>{{$user->email}}</td>
                                <td><a href="{{url(getConfig('admin_prefix') . '/user_profile/'.base64_encode($user->user_id))}}">{{$user->first_name}}</a></td>
                                <td>@if($user->statut==1) Activé @else Non activé @endif</td>
                                <td>{{$user->date}}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>{{'No record found'}}</tr>
                            @endif
                        </table>
                    </div>
                    <div class="pull-right">
                        @if($users) 
                        {{ $users->links('vendor.pagination.bootstrap-4') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>    
@endsection