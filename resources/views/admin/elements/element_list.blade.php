@extends('layouts.adminappinner')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Active/Deactive Elements<small>Manage Package</small>
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
                <form id="edit-package" method="POST" action="{{ route('admin.manage-elements')}}" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    
                    <div class="box box-primary ">
                        <div class="box-header with-border">
                            <h3 class="box-title">Active/Deactive Elements</h3>
                        </div>
                        <div class="box-body" id="content-list-phrase">
                            @if(count($elements) > 0)
                            @foreach($elements as $i => $element)
                            <div id="{{$element->id}}" class="row row-phrase">
                                <div class="row">
                                    <div class="col-xs-6 col-sm-4 col-md-4">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Selector <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="Title" name="selector[]" id="p-title" value="{{$element->selector}}"/>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3 col-md-3">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Comment <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="Title" name="comments[]" id="p-title" value="{{$element->comment}}"/>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-1 col-md-1">
                                        <label>Cacher</label>
                                        <div>
                                            <input type="checkbox" class="check_hide" @if($element->hide==1) checked @endif name=check_hide[]"/>
                                            <input type="hidden" name="hide[]" class="input-hide" value="{{$element->hide}}" />
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-1 col-md-1">
                                        <label></label>
                                        <div><a data-id="{{$element->id}}" href="javascript:" class="btn btn-default btn-remove-phrase"><i class="fa fa-remove"></i></a></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                             <div class="row row-phrase first-row">
                                <div class="row">
                                    <div class="col-xs-6 col-sm-4 col-md-4">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Selector <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="Title" name="selector[]" id="p-title"/>
                                            @if ($errors->has('title'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('title') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3 col-md-3">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>Comment <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="Title" name="comments[]" id="p-title"/>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-1 col-md-1">
                                        <label></label>
                                        <div>
                                            <input type="checkbox" class="check_hide"/>
                                            <input type="hidden" name="hide[]" class="input-hide" value="0" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-footer">
                                    <a href="{{route('admin.packageList')}}" class="btn btn-default">Cancel</a>
                                    <a id="add_phrase" href="javascript:" class="btn btn-default">Add Element</a>
                                    <button type="submit" class="btn btn-info" id="edit-profile-step-3">Save</button>
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

        $('.check_hide').click(function(){
            if($(this).is(":checked")) {
                $(this).parent().find(".input-hide").val(1);
            } else {
                $(this).parent().find(".input-hide").val(0);
            }
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