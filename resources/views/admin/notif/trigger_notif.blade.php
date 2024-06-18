@extends('layouts.adminappinner')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Notification
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
            <div class="col-md-12 show-message">
                <!-- general form elements -->
                <form id="edit-package" method="POST" action="{{route('admin.save-notif-subscription')}}" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    
                    <div class="box box-primary ">
                        <div class="box-header with-border">
                            <h3 class="box-title">Trigger Notification</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                        <label>Selectionnez un prenom <sup>*</sup></label>
                                        <select name="user_prenom">
                                            @foreach($users as $user)
                                            @if(trim($user->first_name) != "" && !is_null($user->first_name))
                                            <option value="{{$user->id}}">
                                            {{$user->first_name}}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @if ($errors->has('title'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                        <label>Sélectionnez le scénario <sup>*</sup></label>
                                        <select name="user_package">
                                            @foreach($packages as $p)
                                            <option value="{{$p->id}}">{{$p->title}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('title'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-footer">
                                    <a href="{{route('admin.packageList')}}" class="btn btn-default">Cancel</a>
                                    <button type="submit" class="btn btn-info" id="edit-profile-step-3">Envoyez les notifs</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div>  
<style type="text/css">
    select
    {
        width : 270px;
    }
</style>  
@endsection