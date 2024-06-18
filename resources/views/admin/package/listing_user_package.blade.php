@extends('layouts.adminappinner')
@push('scripts')
<script src="{{ asset('js/admin/manageusers.js') }}"></script>
@endpush
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Subscription
            <small>List of user subscribed</small>
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
            <div class="col-xs-12 show-message">
                <div class="box box-primary">

                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">
                            <tr>
                                <th>User</th>
                                <th>Package Type</th>
                                <th>Date</th>
                                <th>Start Date</th>                                  
                                <th>End Date</th>
                                <th>Ad url</th>


                            </tr>
                            @if(!empty($userList))
                            @foreach($userList as $key => $user)
                            <tr>
                                <td><a href="{{url(getConfig('admin_prefix') . '/siteusers/user_profile/'.base64_encode($user->id))}}">{{$user->first_name . ' ' . $user->last_name}}</a></td>
                                <td>{{$user->type_package}}</td>
                                <td>{{adjust_gmt($user->created_at)}}</td>
                                <td>{{adjust_gmt($user->start_date)}}</td>
                                <td>{{adjust_gmt($user->end_date)}}</td>
                                <td>@if(!is_null($user->last_ad_id))<a href="{{adUrl($user->last_ad_id)}}" target="_blank">{{adUrl($user->last_ad_id)}}</a>@endif</td>

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
    </section>
</div>    
@endsection