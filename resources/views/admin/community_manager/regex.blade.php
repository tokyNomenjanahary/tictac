@extends('layouts.adminappinner')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Regex
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
                <form id="edit-package" method="POST" action="{{ route('admin.manage-regex')}}" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    
                    <div class="box box-primary ">
                        <div class="box-header with-border">
                            <h3 class="box-title">Regex</h3>
                        </div>
                        <div class="box-body" id="content-list-phrase">
                            @if(count($phrases) > 0)
                            @foreach($phrases as $i => $phrase)
                            <div id="{{$phrase->id}}" class="row row-phrase">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label>{{$phrase->name}} <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="Title" name="regex[]" value="{{$phrase->regex}}"/>
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
                                        <label> </label>
                                        <div><a data-id="{{$phrase->id}}" href="javascript:" class="btn btn-default btn-remove-phrase">Remove</a></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-info" id="edit-profile-step-3">Submit</button>
                                    <button type="button" class="btn btn-primary" href="" id="add-regex-btn">Add Regex</button>
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

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="regex-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">New Regex</h4>
            </div>
            <form id="edit-package" method="POST" action="{{ route('admin.manage-regex')}}" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="div-form-modal">
                        <label class="control-label" for="property_type">Nom *</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="nom_regex" placeholder="Nom" autofocus>
                        </div>
                    </div>
                    <div class="div-form-modal">
                        <label class="control-label" for="property_type">Regex *</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="text_regex" placeholder="Regex" autofocus>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" href="" id="modal-deactive-btn-yes">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div> 
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="regex-delete-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Delete Regex?</h4>
            </div>
            <form id="edit-package" method="POST" action="{{ route('admin.manage-regex')}}" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="div-form-modal">
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="delete_regex" name="delete_regex" placeholder="Nom" autofocus>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" href="" id="modal-deactive-btn-yes">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#add-regex-btn').on('click', function(){
            $('#regex-modal').modal("show");
        });
        $(".btn-remove-phrase").on('click', function(){
            $('#delete_regex').val($(this).attr('data-id'));
            $('#regex-delete-modal').modal('show');
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