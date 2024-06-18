
@extends('layouts.adminappinner')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Manage Upselling
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
                <form class="form-horizontal" action="{{ route('admin.save_upselling') }}" method="POST" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    
                    <div class="box box-primary ">
                        <div class="box-header with-border">
                            <h3 class="box-title">Task Info</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                    <label class="col-sm-2 control-label">Label *</label>
                                    <div class="col-sm-6">
                                        <input type="hidden" name="id" class="form-control" value="@if(isset($upsel)) {{$upsel->id}} @endif" id="id_field" >
                                        <input type="text" name="label" class="form-control" value="@if(isset($upsel)) {{$upsel->label}} @endif" id="label" >
                                        @if ($errors->has("label"))
                                        <span class="help-block">
                                            <strong>{{ $errors->first("label") }}</strong>
                                        </span>
                                        @endif                                    
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label class="col-sm-2 control-label">Fr title *</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="fr_title" class="form-control" value="@if(isset($upsel)) {{$upsel->fr_title}} @endif" >
                                        @if ($errors->has("fr_title"))
                                        <span class="help-block">
                                            <strong>{{ $errors->first("fr_title") }}</strong>
                                        </span>
                                        @endif                                    
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label class="col-sm-2 control-label">English title  *</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="en_title" class="form-control" value="@if(isset($upsel)) {{$upsel->en_title}} @endif" >
                                        @if ($errors->has("en_title"))
                                        <span class="help-block">
                                            <strong>{{ $errors->first("en_title") }}</strong>
                                        </span>
                                        @endif                                    
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label class="col-sm-2 control-label">French Description *</label>
                                    <div class="col-sm-6">
                                        <textarea id="fr_description" name="fr_description" class="form-control" placeholder="Description" rows="6">
                                            @if(isset($upsel)) {{$upsel->fr_description}} @endif
                                        </textarea>
                                        @if ($errors->has("fr_description"))
                                        <span class="help-block">
                                            <strong>{{ $errors->first("fr_description") }}</strong>
                                        </span>
                                        @endif                                    
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label class="col-sm-2 control-label">English Description *</label>
                                    <div class="col-sm-6">
                                        <textarea id="en_description" name="en_description" class="form-control" placeholder="Description" rows="6">
                                            @if(isset($upsel)) {{$upsel->en_description}} @endif
                                        </textarea>
                                        @if ($errors->has("en_description"))
                                        <span class="help-block">
                                            <strong>{{ $errors->first("en_description") }}</strong>
                                        </span>
                                        @endif                                    
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label class="col-sm-2 control-label">Tarifs *</label>
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <button type="button" id="btn_add_tarif" class="btn btn-primary">Add tarif</button>
                                        </div>
                                    </div>
                            </div>
                            <div id="div-tarif" class="form-group">
                                @if(isset($upsel))
                                @foreach($upsel->tarifs as $key => $tarif)
                                <label class="col-sm-2 control-label"></label>
                                <div class="row row-tarif">
                                    <div class="col-sm-2">
                                        <input type="text" name="duration[]" class="form-control" value="{{$tarif->duration}}" placeholder="duration">
                                        @if ($errors->has("duration." . $key))
                                        <span class="help-block">
                                            <strong>{{ $errors->first("duration." . $key) }}</strong>
                                        </span>
                                        @endif                        
                                    </div>
                                    <div class="col-sm-1">
                                        <select class="unit-select" name="unit[]">
                                            <option @if($tarif->unit == "day") selected @endif value="day">Day</option>
                                            <option @if($tarif->unit == "week") selected @endif value="week">Week</option>
                                            <option @if($tarif->unit == "month") selected @endif value="month">Month</option>
                                        </select>                           
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" name="price[]" class="form-control" value="{{$tarif->price}}" placeholder="price" >
                                        @if ($errors->has("price." . $key))
                                        <span class="help-block">
                                            <strong>{{ $errors->first("duration." . $key) }}</strong>
                                        </span>
                                        @endif                         
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <label class="col-sm-2 control-label"></label>
                                <div class="row row-tarif">
                                    <div class="col-sm-2">
                                        <input type="text" name="duration[]" class="form-control" placeholder="duration">
                                        @if ($errors->has("duration.0"))
                                        <span class="help-block">
                                            <strong>{{ $errors->first("duration.0") }}</strong>
                                        </span>
                                        @endif                        
                                    </div>
                                    <div class="col-sm-1">
                                        <select class="unit-select" name="unit[]">
                                            <option value="day">Day</option>
                                            <option value="week">Week</option>
                                            <option value="month">Month</option>
                                        </select>                           
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" name="price[]" class="form-control" placeholder="price" >
                                        @if ($errors->has("price.0"))
                                        <span class="help-block">
                                            <strong>{{ $errors->first("duration.0") }}</strong>
                                        </span>
                                        @endif                         
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="">
                                <div class="col-sm-2"></div>
                                <div class="box-footer col-sm-10">
                                    <a href="" class="btn btn-default">Cancel</a>
                                    <input type="submit" id="btn-add" @if(isset($upsel)) value="Edit" @else value="Add" @endif class="btn btn-info">
                                </div>   
                            </div>
                        </div>
                    </div>
                </form>
            </div> 

        </div>
    </section>
</div>
<div id="div-hidden-tarif" style="display: none;">
    <label class="label-tarif col-sm-2 control-label"></label>
    <div class="row row-tarif">
        <div class="col-sm-2">
            <input type="text" name="duration[]" class="form-control" placeholder="duration">
            @if ($errors->has("duration"))
            <span class="help-block">
                <strong>{{ $errors->first("duration") }}</strong>
            </span>
            @endif                            
        </div>
        <div class="col-sm-1">
            <select class="unit-select" name="unit[]">
                <option value="day">Day</option>
                <option value="week">Week</option>
                <option value="month">Month</option>
            </select>                           
        </div>
        <div class="col-sm-2">
            <input type="text" name="price[]" class="form-control" placeholder="price" >
            @if ($errors->has("price"))
            <span class="help-block">
                <strong>{{ $errors->first("price") }}</strong>
            </span>
            @endif                          
        </div>
    </div>
</div>
<style>
    .unit-select
    {
        height: 34px;
        width: 100%;
    }

    .row-tarif
    {
        margin-bottom: 20px;
    }
</style>  
<script>
    $(document).ready(function(){
        $('#btn_add_tarif').on('click', function(){
            $('#div-tarif').append($('#div-hidden-tarif').html());
        });
    });
</script>  
@endsection