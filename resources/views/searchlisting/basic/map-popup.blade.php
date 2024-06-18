<div class="image-ad ad-infos-content">
    <div class="image-content">
        <img @if(!empty($ad->ad_files) && count($ad->ad_files) > 0) src="{{'/uploads/images_annonces/' . $ad->ad_files[0]->filename}}" @else class="no_pic_available" src="/images/room_no_pic.png" alt="{{ __('no pic') }}" @endif width="50" height="50">
        <div class="price-popup">
            @if(!empty($ad) && !empty($ad->min_rent)){{'€'.$ad->min_rent}}<small>{{ __('searchlisting.par_mois') }}</small>@endif
            @if(!empty($ad) && (empty($ad->min_rent) || $ad->min_rent == 0))
            {{__('searchlisting.a_negocier')}}
            @endif
        </div>
    </div>
</div>
<div class="ad-infos-content other-infos">
    <div class="adress-ad-popup infos-popup"><span class="glyphicon glyphicon-map-marker"></span>{{$ad->address}}</div>
    <div class="infos-popup">
        @if(!empty($ad->min_surface_area))
        <img width="18" height="18" src="{{URL::asset('images/ft-squar-icon.png')}}" alt="" />
        <span class="property-infos">
        {{$ad->min_surface_area}} {{ __('searchlisting.sq_meter') }}
        </span>
        @endif
        @if(!empty($ad->bedrooms))
        <img class="img-bedroom" width="18" height="18" src="{{URL::asset('images/bed-icon.png')}}" alt="" />
        <span class="property-infos">{{$ad->bedrooms}}</span>
        @endif
        @if(!empty($ad->bathrooms))
        <img class="img-bathroom" width="18" height="18" src="{{URL::asset('images/bathroom-icon.png')}}" alt="" />
        <span class="property-infos">{{$ad->bathrooms}}</span>
        @endif
    </div> 
    <div class="infos-popup">@if($ad->furnished == 1){{__("searchlisting.furnished")}} @else {{__("searchlisting.not_furnished")}} @endif</div>
    <div class="posted-info infos-popup">{{ __('searchlisting.posted') }} {{translateDuration($ad->updated_at)}}.</div>
</div>
<a href="javascript:" class="close-mobile-popup">x</a>