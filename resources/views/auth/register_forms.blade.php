<link href="/css/register.min.css" rel="stylesheet">
<link rel="stylesheet" href="/css/sumoselect.min.css">
@push('scripts')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script> 
@endpush

@if(isset($ajax))
<script src="/js/register.min.js"></script>
<script src="/js/date-naissance.min.js"></script>
<script src="/js/intlTelInput/intlTelInput.min.js"></script>
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
var messages_error = {"error_phone" : "{{__('login.error_phone')}}", "error_occured" : "{{__('backend_messages.error_occured')}}", "invalid_phone" : "{{__('login.invalid_phone')}}", "rectify_message" : "{{__('backend_messages.rectify_message')}}", "error_contact" : "{{__('backend_messages.error_contact')}}", "email_confirmation" : "{{__('backend_messages.email_confirmation')}}"};
var messages = {"registration_successful" : "{{__('login.registration_successful')}}"};
</script>
@else
@push('scripts')
<script src="/js/register.min.js"></script>
<script src="/js/date-naissance.min.js"></script>
<script src="/js/intlTelInput/intlTelInput.min.js"></script>
<script src="/js/jquery.sumoselect.min.js"></script>
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
var messages_error = {"error_phone" : "{{__('login.error_phone')}}", "error_occured" : "{{__('backend_messages.error_occured')}}", "invalid_phone" : "{{__('login.invalid_phone')}}", "rectify_message" : "{{__('backend_messages.rectify_message')}}", "error_contact" : "{{__('backend_messages.error_contact')}}", "email_confirmation" : "{{__('backend_messages.email_confirmation')}}"};
var messages = {"registration_successful" : "{{__('login.registration_successful')}}"};
</script>
@if(isset($step) && !is_null($step))
<script>
    $(document).ready(function(){
        $('#publish-popup').modal('show');
        $('.register-first-step').hide();
        $('.register-second-step').show();
     });  
</script>
@endif
@endpush
@endif
<div class="modal fade project-popup-1 project-login-form-outer" id="publish-popup" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content project-form">
            <a class="change_place" href="#" data-dismiss="modal">< {{__('login.change_place')}}</a>
            <button type="button" class="close1" data-dismiss="modal">&times;</button>
            <div class="modal-body">
                <div class="pop-hdr">
                    <h6 class="fb-registration-message register-to-search">

                    {{__("acceuil.entamons")}} <span id="ville-register"> @if(getParameter("etape_creer_compte")==2){{getVilleHome()}} @else Paris @endif</span></h6>
                </div>
                <div class="pop-hdr">
                    <span class="find-colocation">{{i18n('pour_acceder')}}</span>
                </div>
                <div class="pop-hdr">
                    <div class="find-colocation-text">
                        <ul>
                            <li>
                                {{i18n('consultez')}}
                            </li>
                            <li>
                                {{i18n('faites_demandes')}}
                            </li>
                            <li>
                                {{i18n('creez_alertes')}}
                            </li>
                            <li>
                                {{i18n('filtrez_annonces')}}
                            </li>
                            <li>
                                {{i18n('accedez_map')}}
                                 
                            </li>
                            <li>
                                {{i18n('filtrez_annonces_affinites')}}
                                  
                            </li>
                            <li>
                                {{i18n('plus_fonctionnalite')}}
                                
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- <div class="pop-hdr">
                    <h6 class="fb-registration-message">{{__('login.register_fb_message')}}</h6>
                </div> -->
                

                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#login-tab"><i class="fa fa-address-card" aria-hidden="true"></i> {{__('login.new_user')}}?</a></li>
                    <li><a href="{{ route('login') }}"><i class="fa fa-user" aria-hidden="true"></i> {{__('login.existing_user')}}?</a></li>
                </ul>

                <div class="tab-content">
                    <div id="login-tab" class="tab-pane fade in active">

                        <form id="registerStep" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            
                            <div class="register-first-step">
                                 <div class="popup-social-icons text-center">
                                    <h6>{{__('login.register_with')}}:</h6>
                                    <ul>
                                        <li class="fb-social"><a class="provider-social" href="{{ url('/login/facebook') }}"><i class="fa fa-facebook" aria-hidden="true"></i><span>{{ __('Facebook') }}</span></a></li>
                                        <!-- <li class="in-social provider-social"><a href="{{ url('/login/linkedin') }}"><i class="fa fa-linkedin" aria-hidden="true"></i><span>{{ __('Linkedin') }}</span></a></li> -->
                                        <!-- <li class="google-social"><a class="provider-social" href="{{ url('/login/google') }}"><i class="fa fa-google" aria-hidden="true"></i><span>{{ __('Google') }}</span></a></li> -->
                                    </ul>
                                </div>
                                <div class="or-divider"><span>{{__('login.or')}}</span></div>
                               <div class="form-group">
                                    <label>{{__('login.mail')}} <sup>*</sup></label>
                                    <input class="form-control" placeholder="{{__('login.mail_placeholder')}}" type="text" name="email" id="email_register" />
                                </div>
                                <div class="form-group">
                                    <label>{{__('login.pass')}} <sup>*</sup></label>
                                    <div class="input-with-icon"><i class="fa fa-eye show-hide-password" aria-hidden="true"></i><input class="form-control" placeholder="{{__('login.register_pass')}} " type="password" name="password" id="password_register" /></div>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="term_check" name="term_check" class="form-check-input"/>
                                    <label class="form-check-label" for="term_check">{{__('login.term_condition_accepte')}} <a class="lien" href="/condition-generale-utilisation">{{__('login.link_term_condition')}}</a>  {{__('login.de')}} {{config('app.name', 'TicTacHouse')}}</label>
                                 </div>
                                 
                                <div class="pr-poup-ftr">
                                    <div class="submit-btn-2"><a data-dismiss="modal" href="javascript:void(0);">{{__('login.cancel')}}</a></div>
                                    <div class="submit-btn-2 button_condition"><a href="javascript:void(0);" id="submit-reg-step-1">{{__('login.next')}}</a></div>
                                </div>
                            </div>
                            <div class="register-second-step">
                                <!--add this class when only one step is there "single-step" -->
                               <!-- <div class="register-second-hdr text-center single-step">
                                    <div class="step-back-arrow"><i class="fa fa-arrow-left" aria-hidden="true"></i></div>
                                    <ul>
                                        <li class="current"><a href="javascript:void(0);"><span>1</span><h6>{{__('login.about')}}</h6></a></li>
                                    </ul>
                                </div> -->
                                
                                <div class="popup-social-icons text-center">
                                    <h6>{{__('login.register_with')}}:</h6>
                                    <ul>
                                        <li class="fb-social"><a href="{{ url('/login/facebook') }}"><i class="fa fa-facebook" aria-hidden="true"></i><span>{{ __('Facebook') }}</span></a></li>
                                        <!-- <li class="in-social"><a href="{{ url('/login/linkedin') }}"><i class="fa fa-linkedin" aria-hidden="true"></i><span>{{ __('Linkedin') }}</span></a></li>
                                        <li class="google-social"><a href="{{ url('/login/google') }}"><i class="fa fa-google-plus" aria-hidden="true"></i><span>{{ __('Google+') }}</span></a></li> -->
                                    </ul>
                                </div>
                                <div class="or-divider"><span>{{__('login.or')}}</span></div>
                                

                                <div class="register-second-content">
                                    <input type="hidden" id="scenario_id" name="scenario_id" value="1">
                                    

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>{{__('login.first_name')}} <sup>*</sup></label>
                                                <input type="text" class="form-control" placeholder="{{__('login.first_name')}}" name="first_name" id="first_name" />
                                            </div>
                                        </div><!-- 
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>{{__('login.last_name')}} <sup>*</sup></label>
                                                <input type="text" class="form-control" placeholder="{{__('login.last_name')}}" name="last_name" id="last_name" />
                                            </div>
                                        </div> -->
                                        @if(isset($typeMusics) && getInfoRegister('scenario_register') != 3 && getInfoRegister('scenario_register') != 4 )
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">{{ __('profile.type_music') }} <sup>*</sup></label>
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
                                                <label>{{__('login.sex')}} <sup>*</sup></label>
                                                <div class="custom-selectbx">
                                                    <select class="selectpicker" name="sex" id="sex">
                                                        <option value="0">{{__('login.male')}}</option>
                                                        <option value="1">{{__('login.female')}}</option>
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>{{__('login.date_birth')}} <sup>*</sup></label>
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
                                                <label>{{__('login.situation_pro')}} <sup>*</sup></label>
                                                <div class="custom-selectbx">
                                                    <select class="selectpicker" title="{{ __('profile.choisir_professional') }}" name="professional_category" id="professional_category">
                                                    <option value="" selected></option>
                                                    <option value="0">{{ __('profile.student') }}</option>
                                                    <option value="1">{{ __('profile.freelancer') }}</option>
                                                    <option value="2">{{ __('profile.salaried') }}</option>
                                                    <option value="3">{{ __('profile.cadre') }}</option>
                                                    <option value="4">{{ __('profile.retraite') }}</option>
                                                    <option value="5">{{ __('profile.chomage') }}</option>
                                                    <option value="6">{{ __('profile.rentier') }}</option>
                                                    <option value="7">{{ __('profile.situationPro7') }}</option>
                                                </select>
                                                </div>

                                            </div>
                                        </div>
                                        @endif
                                        @if(getInfoRegister('scenario_register') == 3)
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>{{__('login.location_par')}} <sup>*</sup></label>
                                                <div class="custom-selectbx">
                                                    <select class="selectpicker" name="accept_as" id="accept_as">
                                                        <option value="1">{{ucfirst(__('login.landlord'))}}</option>
                                                        <option value="2">{{ucfirst (__('login.agent'))}}</option>
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>{{__('login.phone')}} <sup>*</sup></label>
                                                <input type="tel" class="form-control" placeholder="{{ __('login.enter_phone') }}" name="mobile_no" id="mobile_no" />
                                                <input type="hidden" name="valid_number" id="valid_number" />
                                                <input type="hidden" name="iso_code" id="iso_code" />
                                                <input type="hidden" name="dial_code" id="dial_code" />
                                                <label id="phone_custom_error" class="custom-error">{{__('validator.required')}}</label>
                                            </div>
                                        </div>
                                        
                                        <!-- <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('profile.revenu') }} <sup>*</sup></label>
                                                <input type="text" class="form-control" placeholder="€" name="revenus" id="revenus" />
                                            </div>
                                        </div> -->
                                        <!-- <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>{{__('login.budget')}}<sup>*</sup></label>
                                                <input type="text" class="form-control" placeholder="{{__('login.budget')}}" name="budget" id="budget" />
                                            </div>
                                        </div> -->
                                    </div>
                                    @if(getInfoRegister('scenario_register') != 3)
                                    <div class="row">
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
                                <input type="hidden" id="address_register" name="address_register">
                                <input type="hidden" id="latitude_register" name="latitude_register">
                                <input type="hidden" id="longitude_register" name="longitude_register">
                                <input type="hidden" id="scenario_register" name="scenario_register">


                                <div class="pr-poup-ftr">
                                    <div class="submit-btn-2"><a data-dismiss="modal" href="javascript:void(0);">{{__('login.cancel')}}</a></div>
                                    <div class="submit-btn-2 reg-next-btn"><a href="javascript:void(0);" id="submit-reg-step-2">{{__('login.register_button')}}</a></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="information-modal" class="modal ">
    <div class="modal-dialog ">
        <div class="modal-content ">
            <div class="modal-body" >
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 style="font-size : 1.2em;" id="modal-information-text" class="modal-title text-center"> {{__('login.error_link_term_condition')}} </h5>
            </div>
        </div>
    </div>
</div>
<script>
//$(document).ready(function(){ /*code here*/ }) 

        if ($("#registerStep").length >= 0) {
            $("#registerStep").validate({
            
            rules: {
            email: {
                required: true,
                email: true
            },
            password: {
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
            }
            
            }
        })
        }


</script>

<style>
    @if(Session::get('stepAccount') == 2)
    .register-second-step
    {
        display: block;
    }
    .register-first-step
    {
        display: none;
    }

    @else

    .register-second-step
    {
        display: none;
    }
    .register-first-step
    {
        display: block;
    }

    @endif
</style>

 @if(Session::get('stepAccount') == 2)
 @push('scripts')
 <script type="text/javascript">
     $(document).ready(function(){
        $('.sumo-select').SumoSelect();
        $('#publish-popup').modal("show");
     });
 </script>
 @endpush
 @endif
