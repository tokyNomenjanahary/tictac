@foreach($requests as $request)
<div class="request-bx-1 m-t-2">
    <div class="request-bx-left">
        <figure class="brdr-rect">
            <img @if(!empty($request->receiver_ads->ad_files) && count($request->receiver_ads->ad_files) > 0) class="pic_available" src="{{URL::asset('uploads/images_annonces/' . $request->receiver_ads->ad_files[0]->filename )}}" alt="{{$request->receiver_ads->ad_files[0]->user_filename}}" @else class="no_pic_available" src="{{URL::asset('images/room_no_pic.png')}}" alt="{{ __('no pic') }}" @endif />
        </figure>
    </div>
    <div class="request-bx-right">
        <div class="rb-hdr">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="rb-hdr-left">
                        <h6><span>@if(!empty($request->receiver_ads) && !empty($request->receiver_ads->title)){{$request->receiver_ads->title}}@endif</span></h6>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 rs-time text-right">{{ __('request.sent_on') }} -  {{dateLocale($request->created_at)}}</div>
            </div>
        </div>
        <div class="rsent-mid">
            <p><i class="fa fa-info-circle" aria-hidden="true"></i> {{ __('request.request_sent_to_ad') }}</p>
        </div>
        <div class="rb-mid-detail">
            @if(!empty($request->slot_id))
            <h6>@if(!empty($request->slots->end_time)){{date("h:i a", strtotime($request->slots->start_time))}} {{ __('request.to') }} {{date("h:i a", strtotime($request->slots->end_time))}}@else{{date("h:i a", strtotime($request->slots->start_time))}}@endif{{','}} {{dateLocale($request->slots->visiting_date)}} </h6>
            @if(!empty($request->slots->notes))
            <blockquote>{{$request->slots->notes}}</blockquote>
            @endif
            @else
            <h6>@if(!empty($request->end_time)){{date("h:i a", strtotime($request->start_time))}} {{ __('request.to') }} {{date("h:i a", strtotime($request->end_time))}}@else{{date("h:i a", strtotime($request->start_time))}}@endif{{','}} {{dateLocale($request->visiting_date)}} </h6>
            @if(!empty($request->notes))
            <blockquote>{{$request->notes}}</blockquote>
            @endif
            @endif
        </div>
        <div class="rb-ftr-detail">
            <div class="roomate-grid-price-av">
                <div class="roomate-grid-price-only"><h4>&euro;{{$request->receiver_ads->min_rent}} <sub> {{ __('request.per_month') }}</sub></h4></div>
                <div class="looking-now active">@if(date_create($request->receiver_ads->available_date) > date_create('today')) {{ __('request.available_from') }} {{date('j M', strtotime($request->receiver_ads->available_date))}} @else {{ __('request.available_now') }} @endif</div>
            </div>
            @if(!empty($request->receiver_ads) && !empty($request->receiver_ads->description))
            <p>{{ __('request.ad_description') }} - {{$request->receiver_ads->description}}</p>
            @endif
            <div class="rb-btns-outer">
                <div class="porject-btn-1 btn-disable"><a href="javascript:void(0);">{{ __('request.request_sent_sing') }}</a></div>
                <div class="porject-btn-1"><a href="#">{{ __('request.request_sent_sing') }}</a></div>
                @if($request->accepted == '3')
                <div class="porject-btn-gray btn-right btn-disable">
                    <a href="javascript:void(0);">{{ __('request.cancel_request') }} </a>
                </div>
                @else
                <div class="porject-btn-gray btn-right">
                    <a href="javascript:void(0);" class="cancel_request" request_id="{{base64_encode($request->id)}}" sender_ad_id="{{base64_encode($request->sender_ad_id)}}">{{ __('request.cancel_request') }}</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach