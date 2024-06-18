@if ($message = Session::get('action'))
    @if ($message == 'publiez_annonce')
        @push('scripts')
            <script src="/js/choosescen.js"></script>
        @endpush
    @endif
@endif

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-*********" crossorigin="anonymous" />
<style>
        /***************
    fa fa-bullhorn
    fa fa-pencil-square-o       color: #00f0ff;
    fa fa-envelope-open-text    color: #70ff00;
    fa fa-star-o                color: #ffe500;
    glyphicon glyphicon-comment color: #006ccc;
    fa-file-text                color: #e84900;
    fa-bell-o                   color: #446ae4;
                                transform: rotate(30deg);
    *****************/
    .icon-head{
        font-size: 2.6rem;
    }

    .icon-annonce{
        color: #ff00c7;
        transform: rotate(-22deg);
    }

    .icon-candidatures{
        color: #00f0ff;
    }

    .icon-messages{
        color: #70ff00;
    }

    .icon-favoris{
        color: #ffe500;
    }

    .icon-commentaires{
        color: #006ccc;
    }

    .icon-toctoc
    {
        color: #006ccc;
    }

    .icon-documents
    {
        color: #e84900;
    }

    .spaces{
        margin-right: -25px;
        margin-left: -25px;
    }

    i.flag-icon.flag-icon-fr {
        margin-right: 4px;
    }

    .space-nav {
        margin-left: 20px;
        padding-right: 20px;
        color: #333;
    }

    button#langue.btn.btn-default-nrh.dropdown-toggle {
        padding: 4px 7px;
        min-height: 32px;
        border-radius: 12px;
        /* padding: 7px 26px 0 8px; */
        border: 1px solid #979797;
    }

    ul.dropdown-menu {
        min-width: 50px !important;
        padding: 4px 2px;
        min-height: 29px;
        border-radius: 5px;
        /* padding: 7px 26px 0 8px; */
        border: 1px solid rgba(206, 202, 202, 0.6);
        padding-left: 10px;
    }

    .langue {
        position: absolute;
        right: 73px;
        top: 0px;
    }

    .dropdown-item-header-2-nrh {
        padding: 0px !important;
    }

    .nav-item:hover {
        background-color: #b5b2b2a8;
        border-radius: 5px;
    }

    @media screen and (min-width:587px) {
        .langue {
            position: absolute;
            right: 69px;
            top: 1px;
        }
    }

    @media screen and (max-width:587px) {
        .langue {
            position: absolute;
            right: 9px;
            top: 0px;
        }
    }

</style>
{{-- <link href="/css/toctoc.css" rel="stylesheet"> --}}
<link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649420273/Bailti/css/toctoc_k7yl4a.css" rel="stylesheet">
<header id="header-tictac">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span id="count-glyph-user-pic"
                        class="count-glyph-all-notif count-glyph-user-pic count-glyph-all-notif-user-pic glyph-button">0</span>
                </button>
                <a class="navbar-brand" href="/">
                    <img src="/img/blue-logo.png" alt="">
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav" style="width: 900px !important;">
                    <li class="nav-item first-menu annonces">
                        <a class="nav-link" href="{{ route('user.dashboard') }}">
                            <div class="row spaces">
                                <div class="col-md-3 col-xs-1">
                                    <i class="fa fa-bullhorn icon-head icon-annonce"></i>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <span>{{ __('header.pluriel') }}</span><br>
                                    <strong>{{ __('header.annonces') }}</strong>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item my_applications">
                        <a class="nav-link" href="{{ url('mes-candidatures/envoyes/tous') }}">
                            <div class="row spaces">
                                <div class="col-md-3 col-xs-1">
                                    <i class="fa fa-pencil-square-o icon-head icon-candidatures"></i>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <span>{{ __('header.pluriel') }}</span><br>
                                    <strong>{{ __('header.candidatures') }}</strong>
                                </div>
                            </div>
                        </a>
                        <span id="count-glyph-candidature"
                            class="count-glyph-all-notif count-glyph-all-notif-message"></span>

                    </li>
                    <li class="nav-item my_messages">
                        <a class="nav-link" href="{{ url('/messages-boite-reception') }}">
                            <div class="row spaces">
                                <div class="col-md-3 col-xs-1">
                                    <i class="fa fa-envelope-open icon-head icon-messages"></i>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <span>{{ __('header.pluriel') }}</span><br>
                                    <strong>{{ __('header.messages_nrh') }}</strong>
                                </div>
                            </div>
                        </a>
                        <span id="count-glyph-all-message" class="count-glyph-all-notif count-glyph-all-notif-message"></span>
                    </li>

                    <li class="my_favorites nav-item">
                        @php
                            $favourite_count = 0;
                            if (Auth::check()) {
                                $favourite_count = count(
                                    Auth::user()
                                        ->favorites()
                                        ->has('ads.user')
                                        ->whereHas('ads', function ($query) {
                                            $query->where('status', '1')->where('admin_approve', '1');
                                        })
                                        ->get(),
                                );
                            }

                        @endphp
                        <a class="nav-link" href="{{ route('favorites_list') }}">
                            <div class="row spaces">
                                <div class="col-md-3 col-xs-1">
                                    <i class="fa fa-star icon-head icon-favoris"></i>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <span>{{ __('header.pluriel') }}</span><br>
                                    <strong>{{ __('header.favoris') }}</strong>
                                </div>
                            </div>
                        </a>

                        @if ($favourite_count > 0)
                            <span class="count-glyph" style="display:block">{{ $favourite_count }}</span>
                        @endif

                    </li>
                    <li class="my_comments nav-item">
                        <a class="nav-link" href="{{ url('mes-commentaires') }}">
                            <div class="row spaces">
                                <div class="col-md-3 col-xs-1">
                                    <i class="fa fa-commenting icon-head icon-commentaires"></i>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <span>{{ __('header.pluriel') }}</span><br>
                                    <strong>{{ __('header.commentaires') }}</strong>
                                </div>
                            </div>
                        </a>
                        <span id="count-glyph-comment" class="count-glyph-all-notif count-glyph-all-notif-message"></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('mes-documents') }}">
                            <div class="row spaces">
                                <div class="col-md-3 col-xs-1">
                                    <i class="fa fa-file-text icon-head icon-documents"></i>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <span>{{ __('header.pluriel') }}</span><br>
                                    <strong>{{ __('header.documents') }}</strong>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="menu-toctoc" href="javascript:">
                            <div class="row spaces">
                                <div class="col-md-3 col-xs-1">
                                    <i class="fa fa-bell icon-head icon-toctoc"></i>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <span>{{ __('header.pluriel') }}</span><br><strong>TocTocs</strong>
                                </div>
                            </div>
                        </a>
                        <span id="count-glyph-coup-de-foudre" class="count-glyph-all-notif count-glyph-all-notif-message"></span>

                        <div class="toctoc-notif-content">
                            <div class="flash-div flash-div-popup">
                                <div class="row">
                                    <div class="div-englobe div-title-toc">
                                        <span class="spn-iln-block title">TocTocs</span>
                                    </div>
                                </div>
                            </div>
                            <div id="div-notif-content">
                                <div class="flash-div flash-div-popup">
                                    <div class="row">
                                        <div class="div-englobe-notif">
                                            <img class="img-load-notif" src="/images/loader-notif.gif" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="javascript:" class="close-notif-toctoc">
                                <i class="fa fa-window-close" aria-hidden="true"></i>
                            </a>
                        </div>
                    </li>



                </ul>

            </div><!-- /.navbar-collapse -->
            <div class="langue">
                @if (getConfig('langue') == 1)
                    <div class="dropdown " style="margin-right: 60px; top: 20px;display:absolute;">
                        <button class="btn btn-default-nrh dropdown-toggle" type="button" id="langue"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if (App::getLocale() == 'fr')
                                <i class="flag-icon flag-icon-fr"></i>
                            @else
                                <i class="flag-icon flag-icon-us"></i>
                            @endif
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" style="" aria-labelledby="langue">
                            @if (App::getLocale() == 'fr')
                                <a class="dropdown-item-header-2-nrh"
                                    href="{{ route('changement-langue', ['langue' => 'en']) }}"><i
                                        class="flag-icon flag-icon-us"></i></a>
                            @else
                                <a class="dropdown-item-header-2-nrh"
                                    href="{{ route('changement-langue', ['langue' => 'fr']) }}"><i
                                        class="flag-icon flag-icon-fr"></i> </a>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>
            <div class="dropdown user">

                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="true">
                    <table>
                        <tr>
                            <td>
                                <img @if (!empty(Auth::user()->user_profiles) && !empty(Auth::user()->user_profiles->profile_pic) && File::exists(storage_path('uploads/profile_pics/' . Auth::user()->user_profiles->profile_pic))) src="{{ URL::asset('uploads/profile_pics/' . Auth::user()->user_profiles->profile_pic) }}" style="transform: rotate({{Auth::user()->user_profiles->pdp_rotate}}deg);"
                        @else
                        src="{{ URL::asset('images/profile_avatar.jpeg') }}" @endif
                                    alt="">
                                <span id="count-glyph-user-pic"
                                    class="count-glyph-all-notif count-glyph-user-pic count-glyph-all-notif-user-pic">0</span>
                            </td>

                            <td><span>{{ __('acceuil.salut') }}</span><br> {{ Auth::user()->first_name }}</td>
                        </tr>
                    </table>
                </button>

                <ul class="dropdown-menu mt-4" style="margin-right: 10px!important; margin-top: 10px!important; background-color:rgba(255,255,255,0.91); box-shadow: none;" aria-labelledby="dropdownMenu1">
                    <form action="{{ route('user.toggle') }}" method="post">
                        {{ csrf_field() }}
                        @method('put')
                        <input type="text" value="1" name="role_id" hidden>
                        <li style="margin:5px!important; font-weight: 600; color: #363636;" onclick="event.preventDefault(); this.closest('form').submit();"><a href="#" class="space-nav"> <i class="fas fa-home" style="margin-right: 10px!important;"></i>Espace propriétaire</a></li>
                    </form>
                    <form action="{{ route('user.toggle') }}" method="post">
                        {{ csrf_field() }}
                        @method('put')
                        <input type="text" value="2" name="role_id" hidden>
                        <li style="margin:5px!important; margin-top: 7px!important; font-weight: 600; color: #363636;" onclick="event.preventDefault(); this.closest('form').submit();"><a style="text-align: right!important;" href="" class="space-nav"> <i class="fas fa-key" style="margin-right: 10px!important;"></i>Espace locataire</a></li>
                    </form>
                    <li style="margin:5px!important;font-weight: 600; color: #363636;" role="separator" class="divider"></li>
                    <li style="margin:5px!important;"><a style="font-weight: 600!important; color: #363636!important;" href="{{ route('edit.profile') }}"><i class="fas fa-user" style="margin-right: 10px!important;"></i>{{ __('header.profile') }}</a></li>
                    <li style="margin:5px!important; font-weight: 600; color: #363636;"><a style="font-weight: 600!important; color: #363636!important;" href="{{ route('user.dashboard') }}"><i class="fas fa-tachometer-alt" style="margin-right: 5px!important;"></i> {{ __('header.dashboard') }}</a></li>
                    <li style="margin:5px!important; font-weight: 600; color: #363636;"><a style="font-weight: 600!important; color: #363636!important;" href="{{ url('/mes-candidatures/envoyes/tous') }}"><i class="fas fa-file-alt" style="margin-right: 10px!important;"></i>{{ __('header.applications') }}</a>
                    </li>
                    <li style="margin:5px!important;font-weight: 600; color: #363636;" id="mes_notifications"><a style="font-weight: 600!important; color: #363636!important;" href="javascript:"><i class="fas fa-bell" style="margin-right: 10px!important;"></i>{{ __('notification.modal_notif_title') }} <span
                                id="nbNotifs"></span></a></li>
                    <li style="margin:5px!important;font-weight: 600; color: #363636;" role="separator" class="divider"></li>
                    <li style="margin:5px!important;font-weight: 600; color: #363636;">
                        <a style="font-weight: 600!important; color: #363636!important;" href="#" onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt" style="margin-right: 10px!important;"></i>
                            {{ __('header.logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </div>
        </div><!-- /.container-fluid -->
    </nav>
    <div class="bg-img">
        <div class="s_adh">
            <div class="s shadow"><a href="{{ getSearchUrl() }}"><span><span
                            class="mobile_none">{{ __('header.search') }}</span></span></a></div>
            @if (!isUserSubscribed())
                <div class="adh shadow"><a href="{{ route('subscription_plan') }}?type=1"><span><span
                                class="mobile_none">{{ __('header.ameliorer') }}</span></span></a></div>
            @endif
            @if (isUserSubscribed())
                <span class="dropdown" style="padding-bottom: 12px;cursor: pointer;">
                    <span class="adh shadow" data-toggle="dropdown">{{ __('header.choisir_design') }}</span>
                    <ul class="dropdown-menu">
                        <li class="{{ Auth::user()->type_design == 1 ? '' : 'active' }}"><a
                                href="{{ Auth::user()->type_design == 1 ? route('chooseDesign', ['type_design' => 0]) : '#' }}">Classique</a>
                        </li>
                        <li class="{{ Auth::user()->type_design == 1 ? 'active' : '' }}"><a
                                href="{{ Auth::user()->type_design == 1 ? '#' : route('chooseDesign', ['type_design' => 1]) }}">Premium</a>
                        </li>
                    </ul>
                </span>
            @endif
        </div>
    </div>
    @include('modal.toctocAdsModal')
</header>
@include("common.errorContact")
@if (isset($ad->scenario_id))
    <input type="hidden" id="typeUserSubscription" value="{{ typeUserSubscription($ad->scenario_id) }}" name="">
@elseif(isset($scenario_id))
    <input type="hidden" id="typeUserSubscription" value="{{ typeUserSubscription($scenario_id) }}" name="">
@endif
@include('coup_de_foudre.listing')
