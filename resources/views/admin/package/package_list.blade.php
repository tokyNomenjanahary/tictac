@extends('layouts.adminappinner')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Package List<small>Manage Packages</small>
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
                                <th>S. No.</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Duration</th>
                                <th>Action</th>
                            </tr>
                            @if(!empty($packages))
                            @foreach($packages as $key => $package)
                            <tr>
                                <td>{{$packages->firstItem() + $key}}</td>
                                <td>{{str_limit($package->title, 30,'...')}}</td>
                                <td>{{str_limit($package->description, 60, '...')}}</td>
                                <td>&euro;{{$package->amount}}</td>
                                <td>{{$package->duration . " " . $package->unite}}</td>
                                <td>
                                    <a href="{{route('admin.editPackage',[base64_encode($package->id)])}}" class="btn btn-info btn-sm" title ="Edit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                    @if($package->is_active == '1')
                                    <a href="{{route('admin.packagestatus',[base64_encode($package->id),base64_encode('0')])}}"  class="btn btn-danger btn-sm" title ="De-activate"><span class="glyphicon glyphicon-remove" aria-hidden="true" ></span></a>
                                    @else
                                    <a href="{{route('admin.packagestatus',[base64_encode($package->id), base64_encode('1')])}}" class="btn btn-success btn-sm" title ="Activate"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>
                                    @endif
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