@extends('layouts.adminappinner')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Edit sentence Premium Advantage<small>Manage Package</small>
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
                <form id="edit-package" method="POST" action="{{ route('admin.manage-phrase')}}" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    
                    <div class="box box-primary ">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit sentence Premium Advantage</h3>
                        </div>
                        <div class="box-body" id="content-list-phrase">
                            @if(count($phrases) > 0)
                            @foreach($phrases as $i => $phrase)
                            <div id="{{$phrase->id}}" class="row row-phrase">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Phrase <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="Title" name="phrase_fr[]" id="p-title" value="{{$phrase->phrase_fr}}"/>
                                            @if ($errors->has('title'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('title') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-1 col-md-1">
                                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                            <label>Membre </label>
                                            <div class="custom-selectbx" style="display: block; max-width: 250px !important;">
                                                <select id="utm_medium" name="type_membre[]">
                                                        <option value="3"  @if($phrase->type_membre == 3) selected @endif>Tous les membres</option>
                                                        <option value="0"  @if($phrase->type_membre == 0) selected @endif>Basic</option>
                                                        <option value="1"  @if($phrase->type_membre == 1) selected @endif>Premium</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-1 col-md-1">
                                        <label> </label>
                                        <div><a data-id="{{$phrase->id}}" href="javascript:" class="btn btn-default btn-remove-phrase">Remove</a></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                             <div id="{{$phrase->id}}" class="row row-phrase first-row">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Phrase <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="Title" name="phrase_fr[]" id="p-title"/>
                                            @if ($errors->has('title'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('title') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-1 col-md-1">
                                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                            <label>Membre </label>
                                            <div class="custom-selectbx" style="display: block; max-width: 250px !important;">
                                                <select id="utm_medium" name="type_membre[]">
                                                        <option value="3">Tous les membres</option>
                                                        <option value="0">Basic</option>
                                                        <option value="1">Premium</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-1 col-md-1">
                                        <label> </label>
                                        <div><a data-id="{{$phrase->id}}" href="javascript:" class="btn btn-default btn-remove-phrase">Remove</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-footer">
                                    <a href="{{route('admin.packageList')}}" class="btn btn-default">Cancel</a>
                                    <a id="add_phrase" href="javascript:" class="btn btn-default">Add Phrase</a>
                                    <button type="submit" class="btn btn-info" id="edit-profile-step-3">Submit</button>
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
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_phrase").on('click', function(){
            $('#content-list-phrase').append('<div class="row row-phrase">' + $('.first-row').html() +'</div>');
        });

        $(".btn-remove-phrase").on('click', function(){
            $('#' + $(this).attr('data-id')).remove();
        });
    });
</script> 
<style type="text/css">
    .row-phrase
    {
        padding : 15px;
    }
</style>  
@endsection