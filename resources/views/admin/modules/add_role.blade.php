@extends('layouts.adminappinner')
<!-- Push a stye dynamically from a view -->

@push('styles')
<link rel="stylesheet" href="/css/sumoselect.min.css">
@endpush

@push('scripts')
<script src="/js/jquery.sumoselect.min.js"></script>
@endpush
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper clearfix">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            
            Gestion de Rôles
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
            <!-- left column -->
            <div class="col-md-12 show-message">
                <!-- general form elements -->
                <form id="editProfile" method="POST" action="{{route('admin.save_role')}}" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    <div class="box box-primary ">
                        <div class="box-body">
                            <div class="row">
                                @if(isset($role))
                                <input type="hidden" name="id" value="{{$role->id}}">
                                @endif
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>Nom <sup>*</sup></label>
                                        <input type="text" class="form-control" placeholder="Nom du rôle" name="designation" id="nom" @if(isset($role)) value="{{$role->designation}}" @endif/>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Modules<sup>*</sup></label>
                                        <div class="custom-selectbx">
                                            <select class="sumo-select" sumo-required="true" placeholder="{{__('filters.no_selected')}}" name="modules[]" id="type_musics" multiple="">
                                                @foreach($modules as $key => $module)
                                                <option 
                                                @if(!isset($role) && $key == 0)  
                                                selected=""
                                                @elseif(isset($role) && in_array($module->id, $role->modules))
                                                selected=""
                                                @endif 
                                                value="{{$module->id}}">{{$module->nom}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary" id="edit-profile-step-3">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- /.box -->
            </div>
            <!--/.col (left) -->
        </div>
        <!--/.col (right) -->
    </section>
</div>
@endsection
<style type="text/css">
    .SumoSelect {
        width: 100% !important;
    }
</style>
<!-- /.row -->
@push('scripts')
<script type="text/javascript">
     $(document).ready(function(){
        $('.sumo-select').SumoSelect();
     });
 </script>
@endpush