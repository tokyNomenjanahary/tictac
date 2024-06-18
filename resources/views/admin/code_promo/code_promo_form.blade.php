<!-- Push a stye dynamically from a view -->
@push('styles')
<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
<link href="{{ asset('bootstrap-fileinput/themes/explorer-fa/theme.min.css') }}" rel="stylesheet">
@endpush
@push('scripts')
<script src="/js/date-naissance.min.js"></script>
@endpush
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper clearfix">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Add Promo Code
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
                <form id="codePromoForm" method="POST" action="{{route('admin.save-code-promo')}}" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    <div class="box box-primary ">
                        <div class="box-body">
                            <div class="row">
                                <input type="hidden" name="promo_id" @if(isset($promotion)) value="{{$promotion->id}}" @endif >
                                <div class="row">
                                <div class="col-xs-6 col-sm-3 col-md-3">
                                <label class="control-label" for="property_type">Type *</label>
                                <div class="custom-selectbx" style="display: block; max-width: 250px !important;">
                                    <select id="type_promo" name="type_promo" class="selectpicker">
                                        @foreach($types as $type)
                                         @if(isset($promotion) && $promotion->type_promo == $type->id)
                                         <option selected value="{{$type->id}}">{{$type->libelle_type}}</option>
                                         @else
                                         <option value="{{$type->id}}">{{$type->libelle_type}}</option>
                                         @endif
                                         
                                        @endforeach
                                    </select>
                                </div>
                                </div>
                                <div class="col-xs-6 col-sm-3 col-md-3">
                                    <div class="form-group">
                                        <label>Label <sup>*</sup></label>
                                        <input type="text" class="form-control" placeholder="Label"  name="libelle" id="libelle" @if(isset($promotion)) value="{{$promotion->libelle}}" @endif/>
                                    </div>
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-3 col-md-3">
                                        <div class="form-group">
                                            <label>Code <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="Code" name="code" id="code" @if(isset($promotion)) value="{{$promotion->code}}" @endif/>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3 col-md-3">
                                        <div class="form-group">
                                            <label>Value 
                                                <span @if(isset($promotion)) @if($promotion->type_promo == 2) style="display:iniline-bock;" @else style="display:none;" @endif @else style="display: none" @endif class="comment-value" id="comment-value-2">(% Reduction of the promotion)</span>
                                                <span @if(isset($promotion)) @if($promotion->type_promo == 1) style="display:iniline-bock;" @else style="display:none;" @endif @endif class="comment-value" id="comment-value-1">(Number of day  free promo)</span>
                                            <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="Value" name="value" id="value" @if(isset($promotion)) value="{{$promotion->value}}" @endif/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-3 col-md-3">
                                        <div class="form-group">
                                            <label>End Date Validity <sup>*</sup></label>
                                            <div class="datepicker-outer">
                                                <div class="datepicker-outer">
                                                    <select id="date-jour" data-class="date" data-id="birth_date_registration" class="date-jour date-select">
                                                        <?php echo getJourOption((isset($promotion)) ? getDateElement($promotion->end_date_validity, "d") : date('d'));?>
                                                    </select>
                                                    <select id="date-mois" data-class="date" data-id="birth_date_registration" class="date-mois date-select">
                                                        <?php echo getMoisOption((isset($promotion)) ? getDateElement($promotion->end_date_validity, "m") : date('m'));?>
                                                    </select>
                                                    <select id="date-annee" data-class="date" data-id="birth_date_registration" class="date-annee date-select">
                                                        <?php echo getAnneeOptionPromo((isset($promotion)) ? getDateElement($promotion->end_date_validity, "Y") : date('Y'));?>
                                                    </select>

                                                    <input class="form-control datepicker" type="hidden"  name="valid_date" id="birth_date_registration" @if(isset($promotion)) value="{{date('d/m/Y', strtotime($promotion->end_date_validity))}}" @else value="{{date('d/m/Y')}}" @endif /> 
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                 <!-- <div class="col-xs-6 col-sm-3 col-md-3">
                                    <div class="form-group">
                                        <label>End Date Validity * <sup>*</sup></label>
                                        <div class="datepicker-outer">
                                            <div id="datepicker-1" class="custom-datepicker">
                                                <input class="form-control datepicker" type="text" placeholder="mm/dd/yyyy" readonly name="valid_date" id="valid_date" @if(isset($promotion)) value="{{date('Y/d/m', strtotime($promotion->end_date_validity))}}" @endif >
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label>Comment <sup>*</sup></label>
                                        <textarea id="comment" name="comment" class="form-control" placeholder="Comment" rows="6">@if(isset($promotion)) {{$promotion->commentaire}} @endif</textarea>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-footer">
                                       <button id="btnSaveAd" type="submit" class="btn btn-info">{{ __('Save') }}</button>
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
<!-- /.row -->
<style>
    .error
    {
        color: red;
    }
    .row
    {
        margin-left: 10px !important;
    }
</style>
@push('scripts')
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
<script>
    var messagess = {"browse" : "{{__('profile.browse')}}","cancel" : "{{__('profile.cancel')}}","remove" : "{{__('profile.remove')}}","upload" : "{{__('profile.upload')}}"}
</script>
<script src="{{ asset('bootstrap-fileinput/js/fileinput.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
       $( "#valid_date" ).datepicker({
            dateFormat: "mm/dd/yy",
            changeMonth: true,
            changeYear: true,
            yearRange: "1950:c",
            maxDate: "-0d"
        }); 

       jQuery("#codePromoForm").validate({
            rules: {
                 "comment":{
                    "required": true,
                 },
                 "libelle": {
                    "required": true
                 },
                 "code": {
                    "required": true
                 },
                 "value": {
                    "required": true
                 },
                 "valid_date": {
                    "required": true
                 }
            }                
        });
       $("#codePromoForm").validate();

       $("#btnSaveAd").on("click", function(e){
            e.preventDefault();
            if(!$('#codePromoForm').valid()) return;
            $('#codePromoForm').submit();
        });

       $('#type_promo').on("change", function(){
            $('.comment-value').hide();
            $('#comment-value-'+ $(this).val()).show();
       });
    });
   
</script>
@endpush