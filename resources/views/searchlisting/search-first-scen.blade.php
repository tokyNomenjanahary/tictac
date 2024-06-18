@extends( ($layout == 'inner') ? 'layouts.appinner' : 'layouts.app' )

<!-- Push a script dynamically from a view -->
@push('styles')
       {{-- <link href="/css/new-filter.css" rel="stylesheet"> --}}
        {{-- <link href="/css/exception.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649074563/css/new-filter_owe5wr.css" rel="stylesheet">
        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649074531/css/exception_byrbop.css" rel="stylesheet">
        <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
        <link href="/css/compiledstyles/default.css" rel="stylesheet">
        <link href="/css/compiledstyles/account/roommate-ad-list.css" rel="stylesheet">
        <link href="/css/compiledstyles/account/room-ad-filter-list.css" rel="stylesheet">
        <link href="/css/compiledstyles/account/roommate-ad-list-responsive.css" rel="stylesheet">
        <link href="/css/compiledstyles/account/roboto.css" rel="stylesheet">
        <link href="/css/font-awesome.min.css" rel="stylesheet">
@endpush

<!-- Push a script dynamically from a view -->
@push('scripts')
<!-- <script src="{{ asset('js/visit.js') }}"></script>
<script src="{{ asset('js/social_filter.js') }}"></script>-->
<script src="/js/ad_and_user_subscription.js"></script>
<script src="{{ asset('js/searchrooms.js') }}"></script>
<script src="/js/slick-img.js"></script>
<script src="/js/facility_search.js"></script>
<script src="/js/return_handler.min.js"></script>
@endpush
@section('content')
<section class="inner-page-wrap">
    @include('searchlisting.new-filter')
    <div class="container container-result custum-row">
        <div class="row-fluid d-flex">
            <div class="rightside-filter-search">
                @include('searchlisting.first-scen-data-all')
            </div>
        </div>

    </div>

    <div id="modalQuotaToctoc" class="modal fade alert-modal" role="dialog">
        <div class="modal-dialog">
            <a href="javascript:" class="closeModalBtn" data-dismiss="modal"><span>x</span></a>
            <!-- Modal content-->
            <div class="modal-content">
                <div class="alrt-modal-body">
                    <h3>{{ __('searchlisting.abonnez_vous') }}</h3>
                    <p>{{ __('searchlisting.vous_avez_atteint') }}.</p>
                    <div class="porject-btn-1"><a href="/subscription_plan?type=message-flash-button">{{__("searchlisting.savoir_plus")}}</a></div>
                </div>
            </div>
        </div>
    </div>
    <div id="phoneModal" class="modal fade alert-modal" role="dialog">
        <div class="modal-dialog">
            <a href="javascript:" class="closeModalBtn" data-dismiss="modal"><span>x</span></a>
            <!-- Modal content-->
            <div class="modal-content">
                <div class="alrt-modal-body">
                    <h3>{{ __('addetails.contact') }}</h3>
                    <span class="glyphicon glyphicon-earphone glyph-phone"></span>
                    <a href="javascript:" id="phone-number-search"></a>
                </div>
            </div>
        </div>
    </div>
    <div id="alertModalInscrire" class="modal fade alert-modal" role="dialog">
        <div class="modal-dialog">
            <a href="javascript:" class="closeModalBtn" data-dismiss="modal"><span>x</span></a>
            <!-- Modal content-->
            <div class="modal-content">
                <div class="alrt-modal-body">
                    <h3>{{ __('addetails.post_ad_first') }}</h3>
                    <p>{{ __('addetails.need_post_ad_first') }}.</p>
                    <div class="porject-btn-1"><a href="{{route('login')}}">{{__("addetails.post_your_ad")}}</a></div>
                </div>
            </div>
        </div>
    </div>
</section>
<form id="home-search-sc2" style="display: none;" method="POST" action="{{ route('searchadScenId') }}">
    {{ csrf_field() }}
    <div class="adress-slider">
        <label>
            <input type="hidden" id="first_latitude" name="latitude" value="@if(isset($lat)) {{$lat}} @endif">
            <input type="hidden" id="first_longitude" name="longitude" value="@if(isset($long)) {{$long}} @endif">
            <input type="hidden" id="search_scenario_id" name="scenario_id" value="@if(isset($scenario_id)) {{$scenario_id}} @endif">
            <input type="hidden" id="address_search" name="address" value="@if(isset($address)){{urldecode($address)}}@endif">
        </label>
    </div>

</form>

<script>
        history.replaceState
            ? history.replaceState(null, null, window.location.href.split("#")[0])
            : window.location.hash = "";

</script>

<script>
    var messages = {"error_num" : "{{__('searchlisting.error_num')}}", "budget_max_error" : "{{__('searchlisting.budget_max_error')}}", "surface_max_error" : "{{__('searchlisting.surface_max_error')}}"};
    var current_user_id = "{{Auth::id()}}";
</script>
@endsection

