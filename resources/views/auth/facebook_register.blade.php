<link href="/css/register.css" rel="stylesheet">
<link rel="stylesheet" href="/css/sumoselect.min.css">
@push('scripts')
<script src="/js/register_fb.js"></script>
<script src="/js/intlTelInput/intlTelInput.min.js"></script>
<script src="/js/metier_autocompletion.js"></script>
<script src="/js/school_autocomplete.js"></script>
<script src="/js/jquery.sumoselect.min.js"></script>
 <script>
     jQuery.extend(jQuery.validator.messages, {
        required: "{{__('validator.required')}}",
        email: "{{__('validator.email')}}"
      });
    jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
    }, "{{__('validator.required')}}");
    jQuery("#registerStepFb").validate({
        rules: {
            /*"revenus": {
                "required": true,
                "number": true,
                "greaterThanZero" : true
            },*/
            /*"budget" : {
                "required" : true,
                "number" : true
            },*/
            "school" : {
                required: {
                    depends: function(element) {
                        return ($('#professional_category').val() == 0);
                    }
                }
            },
            "professional_category" : {
                "required": true
            },
            "profession" : {
                required: {
                    depends: function(element) {
                        return ($('#professional_category').val() == 1 || $('#professional_category').val() == 2 || $('#professional_category').val() == 3);
                    }
                }
            }
        }
    });
    $("#newsletter-form").validate();
 </script>

@endpush
<div class="modal fade project-popup-1 project-login-form-outer" id="register-fb" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content project-form">
            <button type="button" class="close1" data-dismiss="modal">&times;</button>
            <div class="modal-body">
                <div class="pop-hdr">
                    <h6 class="fb-registration-message register-to-search">Bonjour <span id="ville-register">{{getNomUserFb()}}</span>!</h6>
                </div>
                <div class="pop-hdr">
                    <span class="find-colocation">On y est presque ! Tu vas bientôt pouvoir accéder à des milliers d'annonces de qualité comme la tienne</span>
                </div>

                <div class="tab-content">
                    <div id="login-tab" class="tab-pane fade in active">

                        <form id="registerStepFb" action="{{route('save_fb_loyer')}}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="register-first-step">
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
                                            <label>{{__('login.budget')}}</label>
                                            <input type="text" class="form-control" placeholder="{{__('login.budget')}}" name="budget" id="budget" />
                                        </div>
                                    </div> -->
                                </div>
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
                                    @if(getInfoRegister('scenario_register') == 3)
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('login.location_par')}} <sup>*</sup></label>
                                            <div class="custom-selectbx">
                                                <select class="selectpicker" name="accept_as" id="accept_as">
                                                    <option value="1">{{ucfirst(__('login.landlord'))}}</option>
                                                    <option value="2">{{ucfirst(__('login.agent'))}}</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    @endif


                                </div>
                                @if(!is_null(getInfoRegister('scenario_register')) && getInfoRegister('scenario_register') != 1 && getInfoRegister('scenario_register') != 3)
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('profile.interest') }} <sup>*</sup></label>
                                            <div class="custom-selectbx">
                                                <select class="sumo-select" sumo-required="true" placeholder="{{__('filters.no_selected')}}" name="social_interests[]" id="social_interests" multiple="">
                                                    @foreach($socialInterests as $data)
                                                    <option value="{{$data->id}}">{{traduct_info_bdd($data->interest_name)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <label id="social_interests-custom_error" class="custom-error" for="social_interests" style="">{{__('validator.required')}}</label>
                                        </div>
                                    </div>
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
                                </div>
                                <div class="row">
                                    <div class="sport-select col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">{{ i18n('user_sport') }} <sup>*</sup></label>
                                            <div class="custom-selectbx">
                                                <select  class="sport-sumo-select sumo-select" sumo-required="false" placeholder="{{__('filters.no_selected')}}" name="sports[]" id="sports" multiple="">
                                                    @foreach($sports as $data)
                                                    <option value="{{$data->id}}">{{$data->label}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <label id="sports-custom_error" class="custom-error" for="social_interests" style="">{{__('validator.required')}}</label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="form-check">
                                    <input type="checkbox" class="term_check" name="term_check" class="form-check-input"/>
                                    <label class="form-check-label" for="term_check">{{__('login.term_condition_accepte')}} <a class="lien" href="/condition-generale-utilisation">{{__('login.link_term_condition')}}</a>  {{__('login.de')}} {{config('app.name', 'TicTacHouse')}}</label>
                                </div>


                                <div class="pr-poup-ftr">
                                    <div class="submit-btn-2"><a data-dismiss="modal" href="javascript:void(0);">{{__('login.cancel')}}</a></div>
                                    <div class="submit-btn-2 reg-next-btn button_condition"><a href="javascript:void(0);" id="submit-rent">{{__('login.register_button')}}</a></div>
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

<style>
    .sport-select
    {
        display: none;
    }
    .fb-registration-message
    {
        color : rgb(247, 100, 38);
        font-weight : bold !important;
    }
    .SumoSelect {
        width:100%;
        margin-bottom: 20px;
    }
</style>


@if ($message = Session::get('action') && $message="fb_register")
@push('scripts')
 <script>
        history.replaceState
            ? history.replaceState(null, null, window.location.href.split("#")[0])
            : window.location.hash = "";

</script>
 <script type="text/javascript">
     $(document).ready(function(){
        $('#register-fb').modal("show");
     });
 </script>
 @endpush
 @endif

