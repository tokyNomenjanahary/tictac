@push("styles")
<link rel="stylesheet" href="https://cdn.jsdelivr.net/leaflet/1/leaflet.css" />
@endpush
@extends( ($layout == 'inner') ? 'layouts.appinner' : 'layouts.app' )
 <script>
 var messages = {"rectify" : "{{__('property.rectify')}}", "add_saved_approb" : "{{__('backend_messages.ad_success_posted')}}" , "data_saved" : "{{__('property.data_saved')}}", "error_address" : "{{__('property.error_address')}}", "login_success" : "{{__('property.login_success')}}", "phone_error" : "{{__('validator.phone_error')}}"}
</script>
<!-- Push a script dynamically from a view -->
@push('scripts')
    <script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="/js/adresse-step.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/places.js@1.16.1"></script>
    <script src="https://cdn.jsdelivr.net/leaflet/1/leaflet.js"></script>
@endpush
@section('content')
<section class="inner-page-wrap">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 rent-property-form-outer rent-property-step-1">
                <div class="rent-property-form-hdr">
                    
                    <div class="rent-property-form-heading">
                        <h6>{{ __('property.title-' . $type)}}</h6>
                        <div class="div-deposez">{{ __('property.deposez') }}</div>
                    </div>
                </div>
                <div class="step-content step-1-content rent-property-form-content project-form rp-step-1-content white-bg m-t-20">
                    <form id="firstStep" method="POST" enctype="multipart/form-data" action="{{ route('save.adresse_annonce') }}">
                        {{ csrf_field() }}
                        
                        
                        <div class="heading-underline m-t-0">
                            <h6>{{ __('property.adresse_titre') }}</h6>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="address">{{ __('property.address-' . $type) }} *</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                <input id="address" name="address" class="form-control" type="text" placeholder="{{ __('property.placeholder_adress') }}" @if(!empty($ad_details)) value="{{$ad_details->address}}" @endif @if(!empty($AdInfo)) value="{{$AdInfo->address}}" @endif @if(!empty($address)) value="{{$address}}" @endif>
                                <input type="hidden" id="latitude" name="latitude" @if(!empty($ad_details)) value="{{$ad_details->latitude}}" @endif @if(!empty($AdInfo)) value="{{$AdInfo->latitude}}" @endif @if(!empty($latitude)) value="{{$latitude}}" @endif>
                                <input type="hidden" id="longitude" name="longitude" @if(!empty($ad_details)) value="{{$ad_details->longitude}}" @endif @if(!empty($AdInfo)) value="{{$AdInfo->longitude}}" @endif @if(!empty($longitude)) value="{{$longitude}}" @endif>
                                <input id="actual_address" name="actual_address" type="hidden" @if(!empty($ad_details)) value="{{$ad_details->address}}" @endif @if(!empty($AdInfo)) value="{{$AdInfo->address}}" @endif @if(!empty($address)) value="{{$address}}" @endif>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="ad-det-nearby">
                                <div id="ad-det-nearby-map" class="ad-det-nearby-map">
                                    
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="url" value="/{{$type}}"/>
                        
                        <input type="hidden" name="button_clicked" id="button_clicked" value="">
                        <div class="pr-form-ftr">
                            <div id="btnConfirmAdress" class="submit-btn-1 save-nxt-btn"><input type="submit" name="Save" value="{{ __('property.confirm_address') }}" id="confirm_address_btn" /></div>
                            
                        </div>
                    </form>
                </div>
                
                
            </div>
        </div>
    </div>
</section>

@endsection