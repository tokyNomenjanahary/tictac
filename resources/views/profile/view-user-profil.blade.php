@extends( ($layout == 'inner') ? 'layouts.appinner' : 'layouts.app' )

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">

@endpush



@section('content')
<section class="inner-page-wrap">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 ad-details-outer">
                <div class="row m-t-2">
                    <div class="col-xs-12 col-sm-12 col-md-8">

                        <div class="request-bx-1 ad-details-roommate-bx-1 m-t-2">
                            <div class="request-bx-left">
                                <figure class="brdr-radi">
                                    <img @if(!empty($user->profile_pic) && !empty($user->profile_pic)) class="pic_available" src="{{URL::asset('uploads/profile_pics/' . $user->profile_pic)}}" alt="{{$user->profile_pic}}" @else class="no_pic_available" src="{{URL::asset('images/room_no_pic.png')}}" alt="{{ __('no pic') }}" @endif/>
                                </figure>
                            </div>
                            <div class="request-bx-right">
                                <div class="rb-hdr">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="rb-hdr-left"><h6><span>@if(!empty($user->first_name)){{getFirstWord($user->first_name)}}@if(!empty($user->last_name)){{' '.$user->last_name}}@endif @endif @if(!empty($user->birth_date)){{ ', ' . date_diff(date_create($user->birth_date), date_create('today'))->y . " " . __('profile.age')}}@endif</span> <i class="fa fa-check-circle" aria-hidden="true"></i></h6></div></div>
                                    </div>
                                </div>
                                <div class="ad-rmate-mid-detail">
                                    <ul>
                                        <li>
                                            @if(!empty($user->professional_category) && $user->professional_category == 2)
                                            <div class="ad-rmate-mid-icon">
                                                <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                            </div> Situation : {{ __('addetails.salaried') }}
                                            @elseif(!empty($user->professional_category) && $user->professional_category == 1)
                                            <div class="ad-rmate-mid-icon">
                                                <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                            </div>Situation : {{ __('addetails.freelancer') }}
                                            @else
                                            <div class="ad-rmate-mid-icon">
                                                <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                            </div> Situation : {{ __('addetails.student') }}
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                                @if(!empty($user->about_me))
                                <div class="rb-ftr-detail">
                                    <p>{{ __('addetails.profile_description') }} - {{$user->about_me}}</p>
                                </div>
                                @endif
                            </div>

                        </div>
                         <div style="margin-top:15px;" class="social_info white-bx-shadow m-b-1">
                         <div class="ad-bottom-detials-bx">
                                    <div class="icon-hdd">
                                        <div class="icon-left-hdd">
                                            <i class="glyphicon glyphicon-info-sign" aria-hidden="true"></i>
                                        </div>
                                        <h6>{{ __('addetails.about') }} {{getFirstWord($user->first_name)}}</h6>
                                    </div>
                            </div>
                        @if(!is_null($userProfiles) && !is_null($userProfiles->school) || !is_null($userProfiles->smoker))
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    @if(!is_null($userProfiles) && !is_null($userProfiles->school))  <i class="glyphicon glyphicon-education icon-size"></i>
                                        <span>{{$userProfiles->school}}</span>
                                    @endif
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <ul class="list-with-dot">
                                         @if(!is_null($userProfiles) && !is_null($userProfiles->smoker) && $userProfiles->smoker==0) <li>{{ __('addetails.smoker') }}</li>@endif
                                    </ul>
                                </div>
                            </div>
                            @endif
                            @if(!is_null($userProfiles) && !is_null($userProfiles->city_name) || !is_null($userProfiles->country_name))
                            <div style="margin-top:35px;" class="ad-bottom-detials-bx">
                                    <div class="icon-hdd">
                                        <div class="icon-left-hdd">
                                            <i class="fa fa-globe" aria-hidden="true"></i>
                                        </div>
                                        <h6>{{ __('addetails.origin') }}</h6>
                                    </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <ul class="list-with-dot">
                                         @if(!is_null($userProfiles) && !is_null($userProfiles->city_name)) <li>{{ __('addetails.from') . " " . $userProfiles->city_name }} / @if(!is_null($userProfiles->country_name)) {{$userProfiles->country_name}} @endif</li>@endif
                                    </ul>
                                </div>
                            </div>
                            @endif
                            </div>
                    </div>
                    @if(!is_null($socialInfo) || !is_null($lifeStyles))
                    <div class="col-xs-12 col-sm-12 col-md-4">
                         <div style="margin-top:15px;" class="social_info white-bx-shadow m-b-1">
                            @if(isset($adId) && !is_null($adId))						
                            <div class="porject-btn-1" style="width:100%;margin-top : 5px;">
                                <a href="javascript:void(0);" id="send_message">{{ __('addetails.contact_maj') }}</a>
                            </div>
                            @endif
							@if(!is_null($socialInfo) && count($socialInfo) > 0)
							<div style="margin-top:35px;" class="ad-bottom-detials-bx">
                                    <div class="icon-hdd">
                                        <div class="icon-left-hdd">
                                            <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                                        </div>
                                        <h6>{{ __('addetails.interests') }}</h6>
                                    </div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12">
									<ul class="list-with-dot">
										 @foreach($socialInfo as $interest)
											<li>{{ traduct_info_bdd($interest->interest_name) }}</li>
										 @endforeach
									</ul>
								</div>
							</div>
							@endif
							@if(!is_null($lifeStyles) && count($lifeStyles) > 0)
							<div style="margin-top:35px;" class="ad-bottom-detials-bx">
                                    <div class="icon-hdd">
                                        <div class="icon-left-hdd">
                                            <i class="fa fa-street-view" aria-hidden="true"></i>
                                        </div>
                                        <h6>{{ __('addetails.lifestyles') }}</h6>
                                    </div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12">
									<ul class="list-with-dot">
										 @foreach($lifeStyles as $lifeStyle)
											<li>{{ traduct_info_bdd($lifeStyle->lifestyle_name) }}</li>
										 @endforeach
									</ul>
								</div>
							</div>
							@endif
						</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- hidden item-->
<input type="hidden" id="is_user_premium" value="@if($is_user_premium) {{'true'}} @endif">
<input type="hidden" id="is_user_has_ad" value="@if($is_user_has_ad) {{'true'}} @endif">
<input type="hidden" id="is_autentified" value="@guest @else {{'true'}} @endguest">
<!-- hidden item-->

<div id="sendMessageModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center">{{ __('addetails.contact') }} {{getFirstWord($user->first_name)}}</h4>
            </div>
            <div class="modal-body">
                <form id="sendMessageForm" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    
                    <input type="hidden" name="ad_id" value="{{base64_encode($adId)}}">
                    <input type="hidden" name="sender_ad_id" value="{{base64_encode($adId)}}">
                    <input type="hidden" name="receiver_id" value="{{base64_encode($user->id)}}">
                    <div class="form-group">
                        <label class="control-label" for="note">{{ __('addetails.your_message') }}</label>
                        <textarea id="message" name="message" class="form-control" placeholder="{{ __('Message') }}" rows="6"></textarea>
                    </div>
                    <div class="ad-detail-ftr"><p>{{ __('addetails.max_message') }}</p></div>
                    <div class="pr-poup-ftr">
                        <div class="submit-btn-2"><a data-dismiss="modal" href="javascript:void(0);">{{ __('addetails.cancel') }}</a></div>
                        <div class="submit-btn-2 reg-next-btn"><a href="javascript:void(0);" id="submit-send-message">{{ __('addetails.send_message') }}</a></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="alertModalContactPremium" class="modal fade alert-modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="alrt-modal-body">
                <h3>{{ __('addetails.become_premium') }}</h3>
                <p>{{ __('addetails.to_contact') }} "{{getFirstWord($user->first_name)}}" {{ __('addetails.need_become_premium') }}.</p>
                <div class="porject-btn-1 project-btn-green"><a href="{{ route('subscription_plan') }}">{{__("addetails.upgrade_now_maj")}}</a></div>
            </div>
        </div>
    </div>
</div>
<div id="alertModalContactLogIn" class="modal fade alert-modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="alrt-modal-body">
                <h3>{{ __('addetails.identifiez_vous') }}</h3>
                <p>{{ __('addetails.to_contact') }} "{{getFirstWord($user->first_name)}}" {{ __('addetails.need_authenticate') }}.</p>
                <div class="porject-btn-1 project-btn-green"><a href="{{ route('login') }}">{{__("addetails.connexion")}}</a></div>
            </div>
        </div>
    </div>
</div>

<script>
    var messages = {'message_sent' : "{{__('addetails.message_sent')}}"};
    $(document).ready(function(){
        $(document).on("click", "#send_message", function(){
            if($('#is_autentified').val().trim() != "true"){
                 $('#alertModalContactLogIn').modal({show:true});
            } else {
               if($('#is_user_premium').val().trim() != "true"){
                    $('#alertModalContactPremium').modal({show:true});
                } else {
                    $('#sendMessageModal').modal({show:true});
                }
            }
            
        });

        $(document).on('click', "#submit-send-message", function() {
            
            $("#sendMessageModal").find(".has-error").each(function() {
                $(this).find('.help-block').remove(); 
            });
            
            $("#message").closest(".form-group").removeClass('has-error');
            
            $.ajax({
                type: "POST",
                url: '/save_message',
                data: $("#sendMessageForm").serialize(),
                dataType: 'json',
                success: function (data) {
                    if(data.error == 'yes') {
                        
                        $.each(data.errors , function (index, item) {
                            if (index == 'message') {
                                $("#message").closest(".form-group").addClass('has-error');
                                $("#message").after('<span class="help-block">' + item + '</span>');
                            }
                            
                        });
                        
                    } else {
                        $('#sendMessageModal').modal('hide');
                        $('#send_message').parent().before('<div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;margin-bottom:0;"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + messages.message_sent + '.</div>');
                        window.setTimeout(function () {
                            $(".alert").fadeTo(500, 0).slideUp(500, function () {
                                $(this).remove();
                            });
                        }, 3000);
                    }
                }
            });
        });
    });
     
    
</script>
@endsection