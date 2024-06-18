<!-- Push a stye dynamically from a view -->
@push('styles')
<link href="{{ asset('bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
<link href="{{ asset('bootstrap-fileinput/themes/explorer-fa/theme.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script>
    var messagess = {"browse" : "{{__('profile.browse')}}","cancel" : "{{__('profile.cancel')}}","remove" : "{{__('profile.remove')}}","upload" : "{{__('profile.upload')}}"}
</script>
<script src="{{ asset('bootstrap-fileinput/js/fileinput.min.js') }}"></script>
<script src="{{ asset('bootstrap-fileinput/themes/explorer-fa/theme.min.js') }}"></script>
<script src="{{ asset('bootstrap-fileinput/themes/fa/theme.min.js') }}"></script>
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<script src="{{ asset('js/admin/add_edit_blog.js') }}"></script>
@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper clearfix">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @if(!empty($id)){{'Edit Report'}}@else{{'New daily report'}}@endif
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        </ol>
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
                <form class="form-horizontal" action="{{ route('admin.save-report') }}" method="POST" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    <input type="hidden" value="@if(isset($id)){{$id}}@endif" name="id">
                    <div class="box box-primary ">
                        <div class="box-header with-border">
                            <h3 class="box-title">Report Info</h3>
                        </div>
                        <div class="box-body">
                            <div id="accordion">
                            <div class="card">
                                <div class="card-header" id="heading0">
                                  <h5 class="mb-0">
                                    <a href="javascript:">
                                    <div data-id="0" class="btn-accordion collapsed" data-toggle="collapse" data-target="#collapse0" aria-expanded="true" aria-controls="collapse0">
                                        <span style="display: none;" id="icon-plus-0" class="glyphicon glyphicon-plus icon-accordion icon-accordion-plus">
                                        </span>
                                        <span id="icon-minus-0" class="glyphicon glyphicon-minus icon-accordion icon-accordion-minus">
                                        </span>
                                        <span class="label-category-accordeon">Basics Infos</span>
                                    </div>
                                    </a>
                                  </h5>
                                </div>
                                <div id="collapse0" class="collapse in" aria-labelledby="heading" data-parent="#accordion">
                                  <div class="card-body">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Report Date <sup>*</sup></label>
                                            <div class="col-sm-6">
                                                <div class="datepicker-outer">
                                                    <div class="custom-datepicker">
                                                        <input class="form-control date_field" type="text" id="date_report" name="date_report" readonly value="@if(isset($date_report)){{$date_report}}@else{{date('d/m/Y')}}@endif" placeholder="dd/mm/yyyy">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                  </div>
                                </div>
                              </div>
                            @foreach($fields as $key => $kids) 
                              <div class="card">
                                <div class="card-header" id="heading{{$key}}">
                                  <h5 class="mb-0">
                                    <a href="javascript:">
                                    <div data-id="{{$key}}" class="btn-accordion collapsed" data-toggle="collapse" data-target="#collapse{{$key}}" aria-expanded="false" aria-controls="collapse{{$key}}">
                                        <span id="icon-plus-{{$key}}"  class="glyphicon glyphicon-plus icon-accordion icon-accordion-plus">
                                        </span>
                                        <span id="icon-minus-{{$key}}" style="display: none;" class="glyphicon glyphicon-minus icon-accordion icon-accordion-minus">
                                        </span>
                                        <span class="label-category-accordeon">{{$kids['login']}}</span>
                                    </div>
                                    </a>
                                  </h5>
                                </div>
                                <div id="collapse{{$key}}" class="collapse" aria-labelledby="heading" data-parent="#accordion">
                                  <div class="card-body">
                                @foreach($kids['fields'] as $key2 => $field)
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{$field->label}} <sup>@if($field->required == 1)*@endif</sup></label>
                                        <div class="col-sm-6">
                                            <input type="text" name="{{$field->name}}_{{$key}}" class="form-control" id="{{$field->name}}" placeholder="{{$field->label}}" value="@if(isset($field->value)){{$field->value}} @endif">
                                            @if ($errors->has($field->name . "_" . $key))
                                            <span class="help-block">
                                                <strong>{{ $errors->first($field->name . "_" . $key) }}</strong>
                                            </span>
                                            @endif                                    
                                        </div>
                                    </div>
                                    @endforeach
                                  </div>
                                </div>
                              </div>

                            @endforeach
                            </div>
                            
                            <div class="">
                                <div class="col-sm-2"></div>
                                <div class="box-footer col-sm-10">
                                    <a href="{{route('admin.bloglisting')}}" class="btn btn-default">Cancel</a>
                                    <input type="submit" value="Submit" class="btn btn-info">
                                </div>   
                            </div>
                        </div>
                    </div>
                </form>
            </div>            
        </div>
    </section>
</div>
<style type="text/css">
    .btn-accordion
    {
        width: 100%;
        background-color: rgb(224,224,224);
        padding: 15px;
    }

    .icon-accordion
    {
        margin-right: 15px;
    }

    .label-category-accordeon
    {
            font-weight: bold;
            font-size: 18px;
    }
</style>
<script>
    $(document).ready(function(){
        $(".date_field").datepicker({
            format: "dd/mm/yyyy",
            minDate: "-0d",
            setDate : new Date()
        });

        $('.btn-accordion').on('click', function(){
            var key = $(this).attr('data-id');
            if($('#icon-minus-' + key).css('display') == "none")
            {
                $('#icon-minus-' + key).show();
                $('#icon-plus-' + key).hide();
            } else {
                $('#icon-minus-' + key).hide();
                $('#icon-plus-' + key).show();
            }
           
        });
    });
    

</script>
@endsection