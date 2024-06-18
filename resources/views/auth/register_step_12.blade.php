@extends('layouts.app')

@section('content')
@push('styles')
	<link href="/css/register.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/sumoselect.min.css">
    <link href="/css/intlTelInput/intlTelInput.min.css" rel="stylesheet">
    <link href="/css/country/countrySelect.css" rel="stylesheet">
@endpush
@push('scripts')
<script src="/js/easyautocomplete/jquery.easy-autocomplete.min.js"></script>
<script src="/js/register.js"></script>
<script src="/js/date-naissance.min.js"></script>
<script src="/js/intlTelInput/intlTelInput.min.js"></script>
<script src="/js/jquery.sumoselect.min.js"></script>
<script src="/js/country/countrySelect.js"></script>
<script src="/js/mask.js"></script>
<script src="/js/jquery.validate.js"></script>
<script>
    jQuery.extend(jQuery.validator.messages, {
        required: "{{__('validator.required')}}",
        email: "{{__('validator.email')}}",
        number : "{{__('validator.number')}}"
      });
    jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
    }, "{{__('validator.required')}}");
</script>
<script>
var messages_error = {
    "error_phone"        : "{{__('login.error_phone')}}",
    "error_occured"      : "{{__('backend_messages.error_occured')}}",
    "invalid_phone"      : "{{__('login.invalid_phone')}}",
    "rectify_message"    : "{{__('backend_messages.rectify_message')}}",
    "error_contact"      : "{{__('backend_messages.error_contact')}}",
    "email_confirmation" : "{{__('backend_messages.email_confirmation')}}"
};
var messages = {"registration_successful" : "{{__('login.registration_successful')}}"};

</script>
@endpush
    @if ($message = Session::get('success'))

        <div class="alert alert-success fade in alert-dismissable" style="margin-top:20px;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
            {{ $message }}
        </div>

    @endif
<section class="inner-page-wrap section-center section-login">
    <div id="popup-modal-body-login" class="register-form-x rent-property-form-content project-form edit-pro-content-1 white-bg m-t-20">
    <a class="change_place" href="/" data-dismiss="modal">< {{__('register.change_place')}}</a>
        <div class="modal-body">
            <div class="pop-hdr">
            <h6 class="fb-registration-message register-to-search">

                {{__("register.entamons")}} <span id="ville-register">{{getVilleHome()}}</span></h6>
            </div>
            <div class="pop-hdr">
                <span class="find-colocation">{{__('register.pour_acceder')}}</span>
            </div>
            <div class="pop-hdr">
                <div class="find-colocation-text">
                    <ul>
                        <li>
                            {{__('register.consultez')}}
                        </li>
                        <li>
                            {{__('register.faites_demandes')}}
                        </li>
                        <li>
                            {{__('register.creez_alertes')}}
                        </li>
                        <li>
                            {{__('register.filtrez_annonces')}}
                        </li>
                        <li>
                            {{__('register.accedez_map')}}

                        </li>
                        <li>
                            {{__('register.filtrez_annonces_affinites')}}

                        </li>
                        <li>
                            {{__('register.plus_fonctionnalite')}}

                        </li>
                    </ul>
                </div>
            </div>
            <!-- <div class="pop-hdr">
                <h6 class="fb-registration-message">{{__('login.register_fb_message')}}</h6>
            </div> -->


            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#login-tab"><i class="fa fa-address-card" aria-hidden="true"></i> {{__('register.new_user')}}?</a></li>
                <li><a href="{{ route('login') }}"><i class="fa fa-user" aria-hidden="true"></i> {{__('register.existing_user')}}?</a></li>
            </ul>

            <div class="tab-content">
                <div id="login-tab" class="tab-pane fade in active">

                    <form id="registerStep" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="register-first-step @if(isset($step)) hide @endif">
                            @if(getConfig("icone_fb") == 1)
                            <div class="popup-social-icons text-center">
                                <h6>{{__('register.register_with')}}:</h6>
                                <ul>
                                    <li class="fb-social"><a class="provider-social" href="{{ url('/login/facebook') }}">
                                        <i class="fa fa-facebook" aria-hidden="true"></i>
                                        <span>{{ __('register.facebook') }}</span></a>
                                    </li>
                                </ul>
                            </div>

                            @endif

                           <div class="form-group">
                                <label>{{__('register.mail')}} <sup>*</sup></label>
                                <input class="form-control" placeholder="{{__('register.mail_placeholder')}}" type="text" name="email" id="email_register" />
                            </div>
                            <div class="form-group">
                                <label>{{__('register.pass')}} <sup>*</sup></label>
                                <div class="input-with-icon"><i class="fa fa-eye show-hide-password" aria-hidden="true"></i><input class="form-control" placeholder="{{__('register.register_pass')}} " type="password" name="password" id="password_register" /></div>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="term_check" name="term_check" class="form-check-input"/>
                                <label class="form-check-label" for="term_check">{!! __('register.term_condition') !!}</label>
                             </div>

                            <div class="pr-poup-ftr">
                                <div class="submit-btn-2"><a data-dismiss="modal" href="/">{{__('register.cancel')}}</a></div>
                                <div class="submit-btn-2 button_condition"><a href="javascript:void(0);" id="submit-reg-step-1">{{__('register.next')}}</a></div>
                            </div>

                        </div>
                        <div class="@if(!isset($step) || $step != 2) hide @endif">
                            <!--add this class when only one step is there "single-step" -->
                           <!-- <div class="register-second-hdr text-center single-step">
                                <div class="step-back-arrow"><i class="fa fa-arrow-left" aria-hidden="true"></i></div>
                                <ul>
                                    <li class="current"><a href="javascript:void(0);"><span>1</span><h6>{{__('login.about')}}</h6></a></li>
                                </ul>
                            </div> -->


                             <div class="popup-social-icons text-center">
                                <h6>{{__('register.register_with')}}:</h6>
                                <ul>
                                    <li class="fb-social"><a href="{{ url('/login/facebook') }}"><i class="fa fa-facebook" aria-hidden="true"></i><span>{{ __('register.facebook') }}</span></a></li>
                                </ul>
                            </div>
                            <div class="or-divider"><span>{{__('register.or')}}</span></div>


                            <div class="register-second-content">
                                <input type="hidden" id="scenario_id" name="scenario_id" value="1">


                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('register.first_name')}} <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="{{__('register.first_name')}}" name="first_name" id="first_name" />
                                        </div>
                                    </div><!--
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('login.last_name')}} <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="{{__('login.last_name')}}" name="last_name" id="last_name" />
                                        </div>
                                    </div> -->
                                    @if(isset($typeMusics) && getInfoRegister('scenario_register') != 3 )
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('register.type_music') }} <sup>*</sup></label>
                                            <div class="msc-desc">{{__('register.same_music_desc')}}</div>
                                            <div class="custom-selectbx">
                                                <select class="sumo-select" sumo-required="true" placeholder="{{__('filters.no_selected')}}" name="type_musics[]" id="type_musics" multiple="">
                                                    @foreach($typeMusics as $data)
                                                    <option value="{{$data->id}}">{{traduct_info_bdd($data->label)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <label id="type_musics-custom_error" class="custom-error" for="social_interests" style="">{{__('validator.required')}}</label>
                                        </div>
                                    </div>
                                    @endif

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
                                <div class="row">
                                    @if(getInfoRegister('scenario_register') != 3)
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('register.situation_pro')}} <sup>*</sup></label>
                                            <div class="custom-selectbx">
                                                <select class="selectpicker" title="{{ __('profile.choisir_professional') }}" name="professional_category" id="professional_category">
                                                <option value="" selected></option>
                                                <option value="0">{{ __('register.student') }}</option>
                                                <option value="1">{{ __('register.freelancer') }}</option>
                                                <option value="2">{{ __('register.salaried') }}</option>
                                                <option value="3">{{ __('register.cadre') }}</option>
                                                <option value="4">{{ __('register.retraite') }}</option>
                                                <option value="5">{{ __('register.chomage') }}</option>
                                                <option value="6">{{ __('register.rentier') }}</option>
                                                <option value="7">{{ __('register.situationPro7') }}</option>
                                            </select>
                                            </div>

                                        </div>
                                    </div>
                                    @endif
                                    @if(getInfoRegister('scenario_register') == 3)
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('register.location_par')}} <sup>*</sup></label>
                                            <div class="custom-selectbx">
                                                <select class="selectpicker" name="accept_as" id="accept_as">
                                                    <option value="1">{{ucfirst(__('register.landlord'))}}</option>
                                                    <option value="2">{{ucfirst (__('register.agent'))}}</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('register.phone')}} <sup>*</sup></label>
                                            <input type="tel" class="form-control only_num" placeholder="{{ __('register.enter_phone') }}" name="mobile_no" id="mobile_no" />
                                            <input type="hidden" name="valid_number" id="valid_number" />
                                            <input type="hidden" name="iso_code" id="iso_code" />
                                            <input type="hidden" name="dial_code" id="dial_code" />
                                            <label id="phone_custom_error" class="custom-error">{{__('validator.required')}}</label>
                                        </div>
                                    </div>

                                  <!--
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label> <sup>*</sup></label>
                                            <div class="custom-selectbx">
                                                <select class="selectpicker" name="accept_as" id="accept_as">
                                                    <option value="1">{{ucfirst(__('register.landlord'))}}</option>
                                                    <option value="2">{{ucfirst (__('register.agent'))}}</option>
                                                </select>
                                            </div>

                                        </div>

                                        <div class="col-md-2">
					                            <label class="control-label" for="budget">Votre budget : *</label>
					                            <input type="text" class="form-control" id="rent_per_month_standard" placeholder="&euro;" name="rent_per_month"  value="">
				                            </div>
                                    </div>  -->

                                    <!-- <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('profile.revenu') }} <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="€" name="revenus" id="revenus" />
                                        </div>
                                    </div> -->


                                @if(getInfoRegister('scenario_register') != 3)
                                <div class="row" style="margin :-6px">
                                    <div id="register-school" class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('register.school')}}</label>
                                            <input type="text" class="form-control" placeholder="{{__('register.school')}}" name="school" id="school" />
                                        </div>
                                    </div>
                                    <div id="register-profession" class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('register.profession') }} <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="{{ __('register.profession_placeholder') }}" name="profession" id="profession"/>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if(getInfoRegister('scenario_register') != 1 && getInfoRegister('scenario_register') != 3)
                                <div  style="padding: 10px;margin :-6px">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('register.origin_country_label')}} <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="{{ __('register.origin_country') }}" name="origin_country" id="origin_country" />
                                            <input type="hidden" name="origin_country_code" id="origin_country_code" />
                                            <label id="origin_country_error" class="custom-error">{{__('validator.required')}}</label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if(getInfoRegister('scenario_register') == 1 || getInfoRegister('scenario_register') == 2  || getInfoRegister('scenario_register') == 5 )

                                <div class="col-xs-12 col-sm-6 col-md-6">
                                  <div class="form-group" for="budget">
                                   <label style="margin-bottom:20px;">{{ __('register.budget') }} <sup> *</sup></label>
                                   <br>
                                   <input type="number" class="form-control" placeholder="&euro;" name="budget" id="budget" min="1"/>
                                  </div>
                                </div>
                                 @endif


                                <!-- <div class="row">
                                    <div id="register-school" class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('login.school')}}</label>
                                            <input type="text" class="form-control" placeholder="{{__('login.school')}}" name="school" id="school" />
                                        </div>
                                    </div>
                                    <div id="register-profession" class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('profile.profession') }} <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="{{ __('profile.profession_placeholder') }}" name="profession" id="profession"/>
                                        </div>
                                    </div>
                                </div> -->

                            </div>

                            <div class="pr-poup-ftr">
                                <div class="submit-btn-2"><a data-dismiss="modal" href="/">{{__('register.cancel')}}</a></div>
                                <div class="submit-btn-2 reg-next-btn"><a href="javascript:void(0);" id="submit-reg-step-2">{{__('register.register_button')}}</a></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="information-modal" class="modal ">
    <div class="modal-dialog ">
        <div class="modal-content ">
            <div class="modal-body" >
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 style="font-size : 1.2em;" id="modal-information-text" class="modal-title text-center"> {{__('register.error_link_term_condition')}} </h5>
            </div>
        </div>
    </div>
</div>


@endsection
<style>
    .hide
    {
        display: none;
    }
</style>

 @if(Session::get('stepAccount') == 2)
 @push('scripts')
 <script type="text/javascript">

$(document).ready(function(){ /*code here*/
    $('.sumo-select').SumoSelect();

        if ($("#registerStep").length >= 0) {
            $("#registerStep").validate({

            rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
            },
            budget: {
                required: true,

            }
            },
            messages: {
            email: {
                required:  "{{__('validator.required')}}",
                email: "{{__('validator.email')}}"
            },
            password: {
                required: "{{__('validator.required')}}"
            },
            budget: {
                required:  "{{__('validator.required')}}"
            }

            }
        })
    }
})

</script>
 @endpush
 @endif

