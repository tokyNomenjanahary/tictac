@if ($message = Session::get('action'))
@if($message== "publiez_annonce")
@push('scripts')
{{-- <script src="/js/choosescen.js"></script> --}}
<script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649935703/Bailti/js/choosescen_lgxa72.js"></script>
@endpush
@endif
@endif
<header id="header-tictac" class="white-bg home-hdr custum-home-hdr">
    <div class="container wow fadeIn">
        <div class="row">
            <div class="col-xs-12 col-sm-3 col-md-3 home-logo">
                <a @if(!isSearchProfile()) href="{{ url('/') }}" @endif><img src="{{URL::asset('img/blue-logo.png')}}" alt="{{ config('app.name', 'BailFit') }}"></a>
            </div>
            <div class="col-xs-12 col-sm-8 col-md-8 home-nav-outer home-nav-outer-menuone">
                <nav class="navbar">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <i class="fa fa-bars" aria-hidden="true"></i> </button>
                    </div>
                    <div id="navbar" class="home-custom-navbar outer-home-custom-navbar  navbar-collapse collapse home-custom-navbar-menuone">
                 
                        <ul class="nav navbar-nav custum-navbar-nav-menuone">
                            @guest
                            <li class="login-btn return_handle_button"><a href="{{ route('login') }}">{{ __('header.login') }}</a></li>
                            @else
                            @if(!isSearchProfile() && !isAfterRegister())
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ __('header.hi') }},&nbsp{{ Auth::user()->first_name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu pers-dropdown-menu custum-style-dropdown-menu" role="menu">
                                    <li class="li-first">
                                        <a class="a-link" href="{{ route('user.dashboard') }}">{{ __('header.dashboard') }}</a>
                                    </li>
                                    <li>
                                        <a class="a-link" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                   document.getElementById('logout-form').submit();">
                                            {{ __('header.logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            @endif
                            @endguest
                            @if(!isSearchProfile() && !isAfterRegister())
                                @if (Route::currentRouteName() == "postAds")

                                @else
                                    <li class="post-job-btn post_an_ad"><a href="/publiez-annonce">{{ __('header.post_ad') }}</a></li>
                                @endif
                            @endif

                            <li>

                                <div class="dropdown dropdown-header-1-nrh" style="display:fixe;">
                                    <button class="btn btn-default dropdown-toggle" type="button"
                                            id="langue" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @if(App::getLocale() == 'fr')
                                            <i class="flag-icon flag-icon-fr"></i>
                                        @else
                                            <i class="flag-icon flag-icon-us"></i>
                                        @endif
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu  dropdown-menu-header-1-nrh" style="min-width: 60px !important;" aria-labelledby="langue">
                                        @if(App::getLocale() == 'fr')
                                            <a class="dropdown-item-header-1-nrh" href="{{ route('changement-langue', ['langue' => 'en']) }}" style="padding: 20px !important;"><i class="flag-icon flag-icon-us"></i></a>
                                        @else
                                            <a class="dropdown-item-header-1-nrh" href="{{ route('changement-langue', ['langue' => 'fr']) }}" style="padding: 20px !important;"><i class="flag-icon flag-icon-fr"></i> </a>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="col-xs-12 col-sm-1 col-md-1">
            </div>
        </div>
    </div>
</header>
@guest
@else
@include('common.review')
@include('common.code_promo')
@endguest
