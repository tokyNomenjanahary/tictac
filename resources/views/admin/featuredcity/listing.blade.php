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
            Featured City
            <small>Manage city</small>
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
                        <h3 class="box-title">Featured City Listing</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">
                            <tr>
                                <th>S. No.</th>
                                <th>Country Name</th>
                                <th>City Name</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                            @if(!empty($featuredCities))
                            @foreach($featuredCities as $key => $fCity)
                            <tr>
                                <td>{{$featuredCities->firstItem() + $key}}</td>
                                <td>{{$fCity->location_data->country}}</td>
                                <td>{{$fCity->location_data->city}}</td>
                                <td>{{date('d M Y', strtotime($fCity->created_at))}}</td>
                                <td class="action-td">
                                    @if(!empty($fCity->is_active))
                                    <a title="Disable" class="action-toggle-on" href="{{url(getConfig('admin_prefix') . '/activeDeactiveFeaturedCity/'.base64_encode($fCity->id).'/0')}}"><i class="fa fa-toggle-on"></i></a>
                                    @else 
                                    <a title="Enable" class="action-toggle-off" href="{{url(getConfig('admin_prefix') . '/activeDeactiveFeaturedCity/'.base64_encode($fCity->id).'/1')}}"><i class="fa fa-toggle-off"></i></a>
                                    @endif
                                    @if(empty($fCity->deleted_at))
                                    <a title="Delete" class="action-icon-del" id="{{base64_encode($fCity->id)}}" redirect-url="{{getConfig('admin_prefix')}}/deleteFeaturedCity/"><i class="fa fa-trash-o"></i></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr><td colspan="7">{{'No record found'}}</td></tr>
                            @endif
                        </table>
                    </div>
                    <div class="pull-right">
                        @if(!empty($featuredCities)) 
                        {{ $featuredCities->links('vendor.pagination.bootstrap-4') }}
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>    
@endsection