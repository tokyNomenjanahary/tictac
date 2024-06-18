@foreach($requests as $request)
<div class="request-bx-1 m-t-2">
    <div class="request-bx-left">
        <figure class="brdr-radi">
            <img @if(!empty($request->sender_ads->user->user_profiles) && !empty($request->sender_ads->user->user_profiles->profile_pic)) class="pic_available" src="{{URL::asset('uploads/profile_pics/' . $request->sender_ads->user->user_profiles->profile_pic)}}" alt="{{$request->sender_ads->user->user_profiles->profile_pic}}" @else class="no_pic_available" src="{{URL::asset('images/room_no_pic.png')}}" alt="{{ __('no pic') }}" @endif/>
        </figure>
        @if(!empty($request->sender_ads->ad_uploaded_guarantees) && count($request->sender_ads->ad_uploaded_guarantees) > 0)
        <h6>{{ __('request.guarantee') }}</h6>
        <ul>
            @foreach($request->sender_ads->ad_uploaded_guarantees as $uploaded_guarantees)
            <li><a href="#"><i class="fa fa-file-text" aria-hidden="true"></i> {{$uploaded_guarantees->guarantees->guarantee}}</a></li>
            @endforeach
        </ul>
        @endif
    </div>
    <div class="request-bx-right">
        <div class="rb-hdr">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="rb-hdr-left">
                        <h6><span>@if(!empty($request->sender_ads->user) && !empty($request->sender_ads->user->first_name)){{$request->sender_ads->user->first_name}}@if(!empty($request->sender_ads->user->last_name)){{' '.$request->sender_ads->user->last_name}}@endif,@endif @if(!empty($request->sender_ads->user->user_profiles) && !empty($request->sender_ads->user->user_profiles->birth_date)){{date_diff(date_create($request->sender_ads->user->user_profiles->birth_date), date_create('today'))->y}}@endif</span> <i class="fa fa-check-circle" aria-hidden="true"></i></h6>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 rs-time text-right">{{ __('request.sent_on') }} - {{dateLocale($request->created_at)}} </div>
            </div>
        </div>
        <div class="rb-mid-detail">
            @if(!empty($request->slot_id))
            <h6>@if(!empty($request->slots->end_time)){{date("h:i a", strtotime($request->slots->start_time))}} {{ __('request.to') }} {{date("h:i a", strtotime($request->slots->end_time))}}@else{{date("h:i a", strtotime($request->slots->start_time))}}@endif{{','}} {{dateLocale($request->slots->visiting_date)}} <small> (@if(!empty($request->sender_ads->user) && !empty($request->sender_ads->user->first_name)){{$request->sender_ads->user->first_name}} @endif {{ __('request.has_request_visit') }})</small></h6>
            @if(!empty($request->slots->notes))
            <blockquote>{{$request->slots->notes}}</blockquote>
            @endif
            @else
            <h6>@if(!empty($request->end_time)){{date("h:i a", strtotime($request->start_time))}} {{ __('to') }} {{date("h:i a", strtotime($request->end_time))}}@else{{date("h:i a", strtotime($request->start_time))}}@endif{{','}} {{dateLocale($request->visiting_date)}} <small> (@if(!empty($request->sender_ads->user) && !empty($request->sender_ads->user->first_name)){{$request->sender_ads->user->first_name}} @endif {{ __('request.has_request_visit') }})</small></h6>
            @if(!empty($request->notes))
            <blockquote>{{$request->notes}}</blockquote>
            @endif
            @endif
        </div>
        <div class="rb-ftr-detail">
            <div class="roomate-grid-price-av">
                <div class="roomate-grid-price-only"><h4>&euro;{{$request->sender_ads->min_rent}} @if(!empty($request->sender_ads->max_rent)) {{'- &euro;'. $request->sender_ads->max_rent}} @endif<sub> {{ __('request.per_month') }}</sub></h4></div>
                <div class="looking-now active">@if(date_create($request->sender_ads->available_date) > date_create('today')) {{ __('request.looking_from') }} {{date('j M', strtotime($request->sender_ads->available_date))}} @else {{ __('request.looking_now') }} @endif</div>
            </div>
            @if(!empty($request->sender_ads->user->user_profiles) && !empty($request->sender_ads->user->user_profiles->about_me))
            <p>{{ __('Profile description') }} - {{$request->sender_ads->user->user_profiles->about_me}}</p>
            @endif
            <div class="rb-btns-outer">
                @if($type == 'accepted')
                <div class="porject-btn-1 btn-disable">
                    <a href="javascript:void(0);">{{ __('request.accept_request_sing') }}</a>
                </div>
                @else
                <div class="porject-btn-1">
                    <a href="javascript:void(0);" class="accept_request" request_id="{{base64_encode($request->id)}}" ad_id="{{base64_encode($request->ad_id)}}">{{ __('request.accept') }}</a>
                </div>
                @endif
                <div class="porject-btn-1"><a href="#">{{ __('request.message') }}</a></div>
                @if($type == 'declined')
                <div class="porject-btn-gray btn-right btn-disable">
                    <a href="javascript:void(0);">{{ __('request.decline') }} </a>
                </div>
                @else
                <div class="porject-btn-gray btn-right">
                    <a href="javascript:void(0);" class="decline_request" request_id="{{base64_encode($request->id)}}" ad_id="{{base64_encode($request->ad_id)}}">{{ __('request.decline') }}</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach