@extends('layouts.adminappinner')
@push('scripts')
	<!-- <script src="{{ asset('js/admin/manageusers.js') }}"></script> -->
@endpush
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Payment by city
            <!-- <small>List of user subscribed</small> -->
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
                                <th>City</th>
                                <th>Number</th>
                                <th>Payment</th>
                            </tr>
                            @if(!empty($all_city))
                            @foreach($all_city as $city => $payment)
                            <tr>
                                <td>{{ $city }}</td>
                                <td>{{ $all_number[$city] }}</td>
                                <td>&euro;{{$payment}}</td>
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