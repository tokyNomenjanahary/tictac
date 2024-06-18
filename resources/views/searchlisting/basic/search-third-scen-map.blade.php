@extends( ($layout == 'inner') ? 'layouts.appinner' : 'layouts.app' )

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="/css/new-filter.css" rel="stylesheet">
    <link href="/css/exception.css" rel="stylesheet">
@endpush

<script type="text/javascript">
    var appSettings = {};
    @if (!empty($jsonResponse))
        appSettings['map_data'] = {!! $jsonResponse !!};
    @endif
</script>

<!-- Push a script dynamically from a view -->

@push('scripts')
    <script type="text/javascript">
        var messages = {
            "a_negocier": "{{ __('searchlisting.a_negocier') }}",
            "error_num": "{{ __('searchlisting.error_num') }}",
            "budget_max_error": "{{ __('searchlisting.budget_max_error') }}",
            "surface_max_error": "{{ __('searchlisting.surface_max_error') }}"
        };
        var current_user_id = "{{ Auth::id() }}";
    </script>
    <!-- <script src="/js/ad_and_user_subscription.js"></script> -->
    <script src="{{ asset('js/searchroomsmap.min.js') }}"></script>
    <!-- <script src="{{ asset('js/social_filter_map.js') }}"></script> -->
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDUBhW1coDqA6E5JdXruNEMwfVNY7fhL_4&libraries=places" async
             defer></script> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/leaflet/1/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/leaflet/1/leaflet.js"></script>
    <!-- <script src="{{ asset('js/facility_search.js') }}"></script> -->
    <script src="/js/facility_search.js"></script>
    <script src="/js/return_handler.min.js"></script>
@endpush
@section('content')
    <section class="section-map">
        @include('searchlisting.basic.new-filter')
    </section>

    <section class="map-search-wrap">
        <div>
            <div class="map-search-bx" id="map-dessin">
                <div id="EmplacementDeMaCarte" class="google_map">
                </div>
                <a href="javascript:" class="btn-dessin-control" id="btn-left-map">
                    <span class="glyphicon glyphicon-hand-left"></span>
                </a>
                <a href="javascript:" class="btn-dessin-control" id="btn-right-map">
                    <span class="glyphicon glyphicon-hand-right"></span>
                </a>
                <a href="javascript:" class="btn-dessin-control" id="btn-top-map">
                    <span class="glyphicon glyphicon-hand-up"></span>
                </a>
                <a href="javascript:" class="btn-dessin-control" id="btn-bottom-map">
                    <span class="glyphicon glyphicon-hand-down"></span>
                </a>
                <div class="div-tooltip">
                    <a href="javascript:" class="tooltip-dessin" id="tooltip-dessin-draw">
                        {{ __('searchlisting.dessin_tooltip') }}
                        <span id="close-tooltip">x</span>
                    </a>
                </div>
                <div class="div-btn-dessin">
                    <a href="javascript:" class="btn-dessin" id="btn-retour-dessin">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                    </a>
                    <a href="javascript:" class="btn-dessin"
                        id="btn-appliquer-dessin">{{ __('searchlisting.appliquer') }}</a>
                    <a href="javascript:" class="btn-dessin" id="btn-remove-dessin">
                        <span class="glyphicon glyphicon-remove"></span>
                    </a>
                </div>
            </div>
        </div>
        <div id="map-ads" class="container-fluid">
            <div class="row">

                {{-- par ici --}}

                <div class="col-xs-12 col-lg-12 room-grid-bx-outer-row roommate-grid-bx-outer-row d-flex flex-wrap " style="margin-bottom: 10px;">
                    <div class="gird-options-icon-outer pull-right">
                        <span id= "grille-normale" class="listing-view-icon option-mobile active"><i class="fa-option fa fa-bars" aria-hidden="true"></i>{{__("searchlisting.liste")}}</span>
                        <!-- <span class="grid-view-icon option-mobile search-option-middle"><i class="fa-option fa fa-th" aria-hidden="true"></i>{{__("searchlisting.grid")}}</span> -->
                        <span id="grille-classe" class="grid-view-icon option-mobile listing-view-icon-map"><i class="fa-option fa fa-th" aria-hidden="true"></i>{{__("searchlisting.grid")}}</span>
                        {{-- <span class="listing-view-ico
                         @endiflisting-view-icon-map"><a href="{{enleveInscriptionPath() . '?map=true'}}@if($id!=0)&id={{$id}}@endif"><i class="fa-option fas fa-map-marked-alt" aria-hidden="true"></i>{{__("searchlisting.carte")}}</a></span> --}}
                    </div>
                </div>


                <aside class="col-xs-12 col-sm-12 col-md-6 leftside-filter-outer left-search-map-col">
                    <div class="search-map-left-hdr white-bg">
                        <div class="sm-filter-more">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 text-right">
                                    <span class="search-list-view-icn">
                                        @if ($layout == 'outer')
                                            <a
                                                href="{{ route('search.ad', [config('customConfig.scenarioUrl')[$scenario_id - 1],app('request')->segment(2),app('request')->segment(3)]) }}@if ($id != 0) ?id={{ $id }} @endif"><i
                                                    class="fa fa-bars" aria-hidden="true"></i>
                                                {{ __('searchlisting.list_view') }}</a>
                                        @else
                                            <a
                                                href="{{ route('search.ad', [config('customConfig.scenarioUrl')[$scenario_id - 1],app('request')->segment(2),app('request')->segment(3)]) }}@if ($id != 0) ?id={{ $id }} @endif"><i
                                                    class="fa fa-bars" aria-hidden="true"></i>
                                                {{ __('searchlisting.list_view') }}</a>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="search-map-left-cont">
                        <div class="search-map-showing">
                            <div class="row">
                                <div class="col-xs-12 col-sm-8">
                                    <h5>{{ __('searchlisting.showing') }} <span
                                            class="count-from">{{ $countFrom }}</span>
                                        {{ __('searchlisting.to') }} <span
                                            class="count-to">{{ $countTo }}</span>
                                        {{ __('searchlisting.of') }} <span
                                            class="count-total">{{ $totalResults }}</span>
                                        {{ __('searchlisting.scenario_' . $scenario_id) }}</h5>
                                    <p>{{ __('searchlisting.monthly_prices') }}</p>
                                </div>
                                <div class="col-xs-12 col-sm-4 text-right">
                                    <div class="custom-selectbx search-dropdown-filter">
                                        <select id="sort" name="sort" class="selectpicker">
                                            <option value="1">{{ __('searchlisting.recent') }}</option>
                                            <option value="2">{{ __('searchlisting.increase_rent') }}</option>
                                            <option value="3">{{ __('searchlisting.decrease_rent') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="listing_container2">
                            @if (!empty($ads) && count($ads) > 0)
                                @include(
                                    'searchlisting.basic.third-scen-data-map'
                                )
                            @endif
                        </div>
                    </div>
                </aside>
                <div class="col-xs-12 col-sm-12 col-md-6 rightside-map-search">
                    <div class="rightside-map-search-in">
                        <div class="rightside-map-hdr">
                            <div class="search-move white-bx-shadow">
                                <input class="custom-checkbox" id="move-checkbox-1" type="checkbox" value="1" checked="">
                                <label for="move-checkbox-1">{{ __('searchlisting.search_as_move') }}</label>
                            </div>
                            <!--                        <div class="porject-btn-1"><a href="javascript:void(0);"><i class="fa fa-star" aria-hidden="true"></i> {{ __('SEARCH THIS AREA') }}</a></div>-->
                        </div>
                        <div class="main-serch-map">
                            <div id="gmapsearch2"></div>
                        </div>
                        <div class="rightside-map-ftr text-center">{{ __('searchlisting.showing') }} <span
                                class="count-current">{{ $count }}</span> {{ __('searchlisting.of') }} <span
                                class="count-total">{{ $totalResults }}</span>
                            {{ __('searchlisting.scenario_' . $scenario_id) }}, {{ __('searchlisting.zoom') }}</div>
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
                        <div class="porject-btn-1"><a
                                href="{{ route('register') }}">{{ __('addetails.post_your_ad') }}</a></div>
                    </div>
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
                        <div class="porject-btn-1"><a
                                href="/subscription_plan?type=message-flash-button">{{ __('searchlisting.savoir_plus') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <form id="home-search-sc2" method="POST" action="{{ route('searchadScenId') }}?map=true">
        {{ csrf_field() }}
        <div class="adress-slider">
            <label>
                <input type="hidden" id="first_latitude" name="latitude"
                    value="@if (isset($lat)) {{ $lat }} @endif">
                <input type="hidden" id="first_longitude" name="longitude"
                    value="@if (isset($long)) {{ $long }} @endif">
                <input type="hidden" id="search_scenario_id" name="scenario_id"
                    value="@if (isset($scenario_id)) {{ $scenario_id }} @endif">
                <input type="hidden" id="address_search" name="address"
                    value="@if (isset($address)) {{ urldecode($address) }} @endif">
                <input type="hidden" id="address_search" name="map" value="true">
            </label>
        </div>
    </form>
    <input type="hidden" id="input_click_map_marker" value="0" />

    <div id="ad-mobile-windows">

    </div>
    <input type="hidden" id="lat" />
    <input type="hidden" id="lang" />
    <script>
        history.replaceState ?
            history.replaceState(null, null, window.location.href.split("#")[0]) :
            window.location.hash = "";
    </script>
    <script>
             $('body').on('click', '.grid-view-icon', function () {
             $(".room-grid-bx-outer, .roommate-grid-bx-outer").removeClass("list-view-time");
             $(".room-grid-bx-outer, .roommate-grid-bx-outer").addClass("grid-view-time");
             $(".room-grid-bx-outer-row").addClass("grid-view-row-on");
             });
              $('body').on('click', '.listing-view-icon', function () {

             $(".room-grid-bx-outer, .roommate-grid-bx-outer").removeClass("grid-view-time");
             $(".room-grid-bx-outer, .roommate-grid-bx-outer").addClass("list-view-time");
             $(".room-grid-bx-outer-row").removeClass("grid-view-row-on");
            });
         </script>
@endsection
