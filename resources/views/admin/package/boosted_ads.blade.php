@extends('layouts.adminappinner')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Boosted Ads
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
                        <h3 class="box-title">Package Listing</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">
                            <tr>
                                <th>Ads</th>
                                <th>User</th>
                                <th>Upselling</th>
                                <th>Tarif</th>
                                <th>Expiry Date</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                            @if(!empty($ads))
                            @foreach($ads as $key => $ad)
                            <tr>
                                <td>{{$ad->title}}</td>
                                <td>{{$ad->first_name}} {{$ad->last_name}}</td>
                                <td>{{$ad->fr_title}}</td>
                                <td>{{$ad->price}}€ / {{$ad->duration}} {{$ad->unit}}</td>
                                <td>{{$ad->expiry_date}}</td>
                                <td>{{adjust_gmt($ad->created_at)}}</td>
                                <td>
                                    <a href="{{adUrl($ad->ad_id)}}" title ="Edit"><span class="fa fa-eye" aria-hidden="true"></span></a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr><td colspan="6">{{'No record found'}}</td></tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>    
@endsection