@foreach($apps as $app)
<div class="request-bx-1 m-t-2">
    <div class="request-bx-left">
        <figure class="brdr-rect">
            <img class="pic_available" src=""/>
        </figure>
    </div>
    <div class="request-bx-right">
        <div class="rb-hdr">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="rb-hdr-left">
                        <h6><span>@if(!empty($app->user)){{$app->user->first name}} {{$app->user->last_name}}@endif</span></h6>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 rs-time text-right">{{__('application.sent_on')}} -  {{dateLocale($app->created_at)}}</div>
            </div>
        </div>
        <div class="rb-mid-detail">     
        </div>
        <div class="rb-ftr-detail">
            <div class="roomate-grid-price-av">
                <div class="roomate-grid-price-only">
                    <h4>
                        @if(!empty($app->sender_ads->min_rent) && $app->sender_ads->min_rent != 0)
                        &euro;{{$app->sender_ads->min_rent}} 
                        <sub> {{__('application.per_month')}}</sub>
                        @else
                        {{__('searchlisting.a_negocier')}}
                        @endif
                    </h4>
                </div>
                <div class="looking-now active">@if(date_create($app->sender_ads->available_date) > date_create('today')) {{ __('Available from') }} {{date('j M', strtotime($app->sender_ads->available_date))}} @else {{ __('Available Now') }} @endif</div>
            </div>
            @if(!empty($app->sender_ads) && !empty($app->sender_ads->description))
            <p>{{ __('application.ad_description') }} - {{$app->sender_ads->description}}</p>
            @endif
            @if(empty($app->documents) || count($app->documents) == 0)
                <div class="">
                    {{__('application.no_document')}} 
                </div>
            @else
                <div class="">
                    {{count($app->documents)}} {{__('application.document')}}
                </div>
            @endif
            <div class="rb-btns-outer">
                <div class="porject-btn-1">
                    <a href="javascript:void(0);">{{ __('application.more_detail') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>                            
@endforeach