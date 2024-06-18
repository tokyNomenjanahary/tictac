@push('scripts')
<script src="/js/register_profil.js"></script>
@endpush

@if(!Auth::check()) 
@push('scripts')
<script src="/js/date-naissance.js"></script>
<script src="/js/register_profil.js"></script>
<script src="/js/intlTelInput/intlTelInput.js"></script>
 <script>
var messages_error = {"error_phone" : "{{i18n('register.error_phone')}}", "error_occured" : "{{__('backend_messages.error_occured')}}", "invalid_phone" : "{{i18n('register.invalid_phone')}}", "rectify_message" : "{{__('backend_messages.rectify_message')}}", "email_confirmation" : "{{__('backend_messages.email_confirmation')}}"};
</script>
@endpush
<div class="heading-underline m-t-0">
    <h6>{{ __('property.profil') }}</h6>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
            <label>{{__('register.first_name')}}  <sup>*</sup></label>
            <input type="text" class="form-control" placeholder="{{__('register.first_name')}}" name="first_name" id="first_name" />
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
            <label>{{i18n('register.last_name')}} <sup>*</sup></label>
            <input type="text" class="form-control" placeholder="{{i18n('register.last_name')}}" name="last_name" id="last_name" />
        </div>
    </div>
</div> 
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
            <label>{{__('register.sex')}} <sup>*</sup></label>
            <div class="custom-selectbx">
                <select class="selectpicker" name="sex" id="sex">
                    <option value="0">{{__('register.male')}}</option>
                    <option value="1">{{__('register.female')}}</option>
                </select>
            </div>

        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
            <label>{{__('register.phone')}} <sup>*</sup></label>
            <input type="tel" class="form-control" placeholder="{{ __('register.enter_phone') }}" name="mobile_no" id="mobile_no" />
            <input type="hidden" name="valid_number" id="valid_number" />
            <input type="hidden" name="iso_code" id="iso_code" />
            <input type="hidden" name="dial_code" id="dial_code" />
        </div>
    </div>
</div> 
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
            <label>{{i18n('register.postal_code')}} <sup>*</sup></label>
            <input type="text" class="form-control" placeholder="{{ i18n('register.enter_postal_code') }}" name="postal_code" id="postal_code" />
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
            <label>{{__('register.date_birth')}} <sup>*</sup></label>
            <div class="datepicker-outer">
                <div class="datepicker-outer">
                    <select id="date-jour" data-class="date" data-id="birth_date_registration" class="date-jour date-select">
                        <?php echo getJourOption();?>
                    </select>
                    <select id="date-mois" data-class="date" data-id="birth_date_registration" class="date-mois date-select">
                        <?php echo getMoisOption();?>
                    </select>
                    <select id="date-annee" data-class="date" data-id="birth_date_registration" class="date-annee date-select">
                        <?php echo getAnneeOption();?>
                    </select>
                    <input class="form-control datepicker" type="hidden" name="birth_date" value="01/01/{{date('Y')}}" id="birth_date_registration"/>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<div class="heading-underline m-t-0">
    <h6>{{ __('property.compte') }}</h6>
</div>
<div class="form-group">
        <div>
            <label>{{__('register.mail')}} <sup>*</sup></label>
            <input class="form-control" placeholder="{{__('register.mail_placeholder')}}" type="text" name="email" id="email" />
        </div>
        <div class="form-group">
            <label>{{__('register.pass')}} <sup>*</sup></label>
            <div class="input-with-icon"><i class="fa fa-eye show-hide-password" aria-hidden="true"></i><input class="form-control" placeholder="{{__('register.register_pass')}}" type="password" name="password" id="password" /></div>
        </div>
        <div class="form-check">
            <input type="checkbox" class="term_check" name="term_check" class="form-check-input"/>
            <label class="form-check-label" for="term_check">{!! __('register.term_condition') !!}</label>
         </div>
</div>
<div id="information-modal" class="modal">
    <div class="modal-dialog ">
        <div class="modal-content ">
            <div class="modal-body" >
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 style="font-size : 1.2em;" id="modal-information-text" class="modal-title text-center"> {{__('register.error_link_term_condition')}} </h5>
            </div>
        </div>
    </div>
</div>
@endif