@foreach($requests as $request)
<div class="request-bx-1 m-t-2">
    <div class="request-bx-left">
        <figure class="brdr-rect">
            <img @if(!empty($request->sender_ads->ad_files) && count($request->sender_ads->ad_files) > 0) class="pic_available" src="{{URL::asset('uploads/ads_images/' . $request->sender_ads->ad_files[0]->filename )}}" alt="{{$request->sender_ads->ad_files[0]->user_filename}}" @else class="no_pic_available" src="{{URL::asset('images/room_no_pic.png')}}" alt="{{ __('no pic') }}" @endif />
        </figure>
    </div>
    <div class="request-bx-right">
        <div class="rb-hdr">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="rb-hdr-left">
                        <h6><span>@if(!empty($request->sender_ads) && !empty($request->sender_ads->title)){{$request->sender_ads->title}}@endif</span></h6>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 rs-time text-right">{{ __('request.sent_on') }} - {{dateLocale($request->created_at)}} </div>
            </div>
        </div>
        <div class="rb-mid-detail">
            @if(!empty($request->slot_id))
            <h6>@if(!empty($request->slots->end_time)){{date("h:i a", strtotime($request->slots->start_time))}} {{ __('request.to') }} {{date("h:i a", strtotime($request->slots->end_time))}}@else{{date("h:i a", strtotime($request->slots->start_time))}}@endif{{','}} {{dateLocale($request->slots->visiting_date)}} <small> (@if(!empty($request->sender_ads->user) && !empty($request->sender_ads->user->first_name)){{$request->sender_ads->user->first_name}} @endif {{ __('request.has_requested') }})</small></h6>
            @if(!empty($request->slots->notes))
            <blockquote>{{$request->slots->notes}}</blockquote>
            @endif
            @else
            <h6>@if(!empty($request->end_time)){{date("h:i a", strtotime($request->start_time))}} {{ __('to') }} {{date("h:i a", strtotime($request->end_time))}}@else{{date("h:i a", strtotime($request->start_time))}}@endif{{','}} {{dateLocale($request->visiting_date)}} <small> (@if(!empty($request->sender_ads->user) && !empty($request->sender_ads->user->first_name)){{$request->sender_ads->user->first_name}} @endif {{ __('request.has_requested') }})</small></h6>
            @if(!empty($request->notes))
            <blockquote>{{$request->notes}}</blockquote>
            @endif
            @endif
        </div>
        <div class="rb-ftr-detail">
            <div class="roomate-grid-price-av">
                <div class="roomate-grid-price-only"><h4>&euro;{{$request->sender_ads->min_rent}} <sub> {{ __('request.per_month') }}</sub></h4></div>
                <div class="looking-now active">@if(date_create($request->sender_ads->available_date) > date_create('today')) {{ __('request.available_from') }} {{date('j M', strtotime($request->sender_ads->available_date))}} @else {{ __('request.available_now') }} @endif</div>
            </div>
            @if(!empty($request->sender_ads) && !empty($request->sender_ads->description))
            <p>{{ __('request.description') }} - {{$request->sender_ads->description}}</p>
            @endif
            <div class="rb-btns-outer">
                @if($type == 'accepted')
                <div class="porject-btn-1 btn-disable">
                    <a href="javascript:void(0);">{{ __('request.accept_request') }}</a>
                </div>
                @else
                <div class="porject-btn-1">
                    <a href="javascript:void(0);" class="accept_request" request_id="{{base64_encode($request->id)}}" ad_id="{{base64_encode($request->ad_id)}}">{{ __('request.accept_request') }}</a>
                </div>
                @endif
                <div class="porject-btn-1"><a href="#">{{ __('request.message') }}</a></div>
                @if($type == 'declined')
                <div class="porject-btn-gray btn-right btn-disable">
                    <a href="javascript:void(0);">{{ __('request.decline') }} </a>
                </div>
                @else
                <div class="porject-btn-gray btn-right">
                    <a href="javascript:void(0);" class="decline_request" request_id="{{base64_encode($request->id)}}" ad_id="{{base64_encode($request->ad_id)}}">{{ __('request.decline') }} </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>                            
@endforeach