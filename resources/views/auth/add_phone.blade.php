@extends('layouts.master')


@section('content')

<section class="validOTPForm">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('add_phone') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>{{ __('register.phone') }} <sup>*</sup></label>
                                <input type="tel" class="form-control only_num"
                                    placeholder="{{ __('register.enter_phone') }}"
                                    name="mobile_no" id="mobile_no" />
                            </div>
                            @if (isset($erreur))
                            <div class="alert alert-danger">
                               {{ $erreur }}
                            </div>
                           @endif
                            <button class="btn btn-primary" id="test">{{ __('register.valider') }}</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>

var messages_error = {
                "error_phone": "{{ __('login.error_phone') }}",
                "invalid_phone": "{{ __('login.invalid_phone') }}",
                "error_contact": "{{ __('backend_messages.error_contact') }}",
            };


function isMobileValid()
{
	var intlNumber = $("#mobile_no").intlTelInput("getNumber");
    var countryData = $("#mobile_no").intlTelInput("getSelectedCountryData");
    var isValid = $("#mobile_no").intlTelInput("isValidNumber");
        
    $("#mobile_no").closest(".form-group").removeClass('has-error');
    $("#mobile_no").parent().next('.help-block').remove();
    
    if(isValid){
        
        $("#valid_number").val(intlNumber);
        $("#dial_code").val(countryData.dialCode);
        $("#iso_code").val(countryData.iso2);
        return true;
    } else {
    	 $("#mobile_no").closest(".form-group").addClass('has-error');
         $("#mobile_no").parent().after('<span class="help-block text-danger">'+ messages_error.invalid_phone +'</span>');
            
    }
    return false;

}

$("#test").click(function(e) {
  if(!isMobileValid())
  {
    e.preventDefault();
  }
});
</script>
@push('scripts')
<script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623734/bootstrap.min_lpirue.js"></script>
<script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623652/common_nanhue.js"></script>
<script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623564/bootstrap-select_cio7qn.js"></script>
<script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623486/wow.min_zyw8vv.js"></script>
<script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623363/owl.carousel_xjecmj.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
<script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649402901/Bailti/js/bootstrap-slider.min_vlv61y.js"></script>
<script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623300/bootstrap-datepicker.min_r9iu0u.js"></script>
<script type="text/javascript" src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623205/jquery.validate.min_j1mjcb.js"></script>
<script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649320248/js/jquery.easy-autocomplete.min_wzadpl.js"></script>
<script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648721735/js/jquery.sumoselect.min_l4aopu.js"></script>
<script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648716885/js/countrySelect_cbw9nq.js"></script>
<script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648716669/js/mask_gelzej.js"></script>
<script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648716542/css/jquery.validate_bgwq1d.js"></script>
<script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648628890/js/jquery.sumoselect.min_yny39b.js"></script>
<script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648624801/jquery.timepicker_wqeuw6.js"></script>
<script type="text/javascript" src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648625951/slick.min_gfbzgh.js"></script>

<script src="/js/register.js"></script>

<script src="/js/date-naissance.min.js"></script>
<script src="/js/intlTelInput/intlTelInput.min.js"></script>

@endpush
<style>
h5.text-center {
    margin-top: 20px;
    font-size: 11px;
}
.clique{
    color: #0260c1;
    font-weight: bold;
}


.bg-dark {
    background-color: #ffffff!important;
}
.btn-dark {
    color: #fff;
    background-color: #2a8bb7;
    border-color: #fff;
}
h7.text-center{
    vertical-align: inherit;
    color: #2f2828;
}
h7.text-center2{
    vertical-align: inherit;
    color: #59d07b;
}

</style>

@endsection
