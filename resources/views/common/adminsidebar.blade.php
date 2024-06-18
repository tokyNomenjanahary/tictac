

@php
$modules = getAllModules();
@endphp
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                @if(Session::has('ADMIN_USER') && !empty(Session::get('ADMIN_USER')->user_profiles) && !empty(Session::get('ADMIN_USER')->user_profiles->profile_pic))
                <img src="{{URL::asset('uploads/profile_pics/' . Session::get('ADMIN_USER')->user_profiles->profile_pic)}}" class="img-circle" alt="Admin Image">
                @else
                <img src="{{URL::asset('images/profile_avatar.jpeg')}}" class="img-circle" alt="Admin Image" />
                @endif
            </div>
            <div class="pull-left info">
                <p>{{Session::get('ADMIN_USER')->first_name . ' ' . Session::get('ADMIN_USER')->last_name }}</p>
<!--                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>-->
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">

        <li class="treeview ">
            @if(is_Admin())
                <a  href="TESTT">
                    <i class="fa "></i> <span>ACTIVATION</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
            @endif
                <ul class="treeview-menu">
                   
                @if(isAdmin())
            <li>
                @if(getConfig("debug") == 1)
                <a title="Desactiver debug" href="{{route('admin.manage-debug', [0])}}" class="action-toggle-on menu-debug" href="javascript:"><span>Debug</span><i class="fa fa-toggle-on toogle-debug toogle-on-debug" value="0"></i></a>
                @else
                <a title="Activer debug" class="action-toggle-on menu-debug" href="{{route('admin.manage-debug', [1])}}"><span>Debug</span><i class="fa fa-toggle-off toogle-debug toogle-off-debug" value="1"></i></a>
                @endif
            </li>
            <li>
                @if(getConfig("stripe_checkout") == 1)
                <a title="Desactiver Paiement Stripe" class="action-toggle-on menu-debug" href="{{route('admin.stripe_checkout', [0])}}"><span>Paiement Stripe</span><i class="fa fa-toggle-on toogle-debug toogle-on-debug" value="0"></i></a>
                @else
                <a title="Activer Paiement Stripe" class="action-toggle-on menu-debug" href="{{route('admin.stripe_checkout', [1])}}"><span>Paiement Stripe</span><i class="fa fa-toggle-off toogle-debug toogle-off-debug" value="1"></i></a>
                @endif
            </li>
            <li>
                @if(isActivePayPal())
                <a title="Desactiver Paiement PayPal" class="action-toggle-on menu-debug" href="{{ route('admin.toggle_paypal',[0]) }}"><span>Paiement PayPal</span><i class="fa fa-toggle-on toogle-debug toogle-on-debug" value="0"></i></a>
                @else
                <a title="Activer Paiement PayPal" class="action-toggle-on menu-debug" href="{{ route('admin.toggle_paypal',[1]) }}"><span>Paiement PayPal</span><i class="fa fa-toggle-off toogle-debug toogle-off-debug" value="1"></i></a>
                @endif
            </li>
            <li>
                @if(getConfig("telegram_pub_acceuil") == 1)
                <a title="Desactiver lien telegram" class="action-toggle-on menu-debug" href="{{route('admin.telegram_pub', ['acceuil', 0])}}"><span><i class="fa fa-telegram"></i>Acceuil</span><i class="fa fa-toggle-on toogle-debug toogle-on-debug" value="0"></i></a>
                @else
                <a title="Activer lien telegram" class="action-toggle-on menu-debug" href="{{route('admin.telegram_pub', ['acceuil', 1])}}"><span><i class="fa fa-telegram"></i>Acceuil</span><i class="fa fa-toggle-off toogle-debug toogle-off-debug" value="1"></i></a>
                @endif
            </li>

            <li>
                @if(getConfig("verification_mail") == 1)
                <a title="Desactiver verification mail" class="action-toggle-on menu-debug" href="{{route('admin.manage-verification-mail', [0])}}"><span>Verification Mail</span><i class="fa fa-toggle-on  toogle-on-debug" value="0"></i></a>
                @else
                <a title="Activer verification mail" class="action-toggle-on menu-debug" href="{{route('admin.manage-verification-mail', [1])}}"><span>Verification Mail</span><i class="fa fa-toggle-off  toogle-off-debug" value="1"></i></a>
                @endif
            </li>
            <li>
                @if(getConfig("telegram_pub_email") == 1)
                <a title="Desactiver lien telegram" class="action-toggle-on menu-debug" href="{{route('admin.telegram_pub', ['email', 0])}}"><span><i class="fa fa-telegram"></i>Email</span><i class="fa fa-toggle-on toogle-debug toogle-on-debug" value="0"></i></a>
                @else
                <a title="Activer lien telegram" class="action-toggle-on menu-debug" href="{{route('admin.telegram_pub', ['email', 1])}}"><span><i class="fa fa-telegram"></i>Email</span><i class="fa fa-toggle-off toogle-debug toogle-off-debug" value="1"></i></a>
                @endif
            </li>

            <li>
                @if(getConfig("sponsorised_ads") == 1)
                <a title="Desactiver debug" class="action-toggle-on menu-debug" href="{{route('admin.manage-sponsorised-ads', [0])}}"><span>Ads Sponsorisés</span><i class="fa fa-toggle-on toogle-debug toogle-on-debug" value="0"></i></a>
                @else
                <a title="Activer debug" class="action-toggle-on menu-debug" href="{{route('admin.manage-sponsorised-ads', [1])}}"><span>Ads Sponsorisés</span><i class="fa fa-toggle-off toogle-debug toogle-off-debug" value="1"></i></a>
                @endif
            </li>

            <li>
                @if(getConfig("maintenance") == 1)
                <a title="Desactiver debug" class="action-toggle-on menu-debug" href="{{route('admin.maintenance', [0])}}"><span>Site Maintenance</span><i class="fa fa-toggle-on toogle-debug toogle-on-debug" value="0"></i></a>
                @else
                <a title="Activer debug" class="action-toggle-on menu-debug" href="{{route('admin.maintenance', [1])}}"><span>Site Maintenance</span><i class="fa fa-toggle-off toogle-debug toogle-off-debug" value="1"></i></a>
                @endif
            </li>

            <li>
                @if(getConfig("tva") == 1)
                <a title="Desactiver debug" class="action-toggle-on menu-debug" href="{{route('admin.tva', [0])}}"><span>TVA</span><i class="fa fa-toggle-on toogle-debug toogle-on-debug" value="0"></i></a>
                @else
                <a title="Activer debug" class="action-toggle-on menu-debug" href="{{route('admin.tva', [1])}}"><span>TVA</span><i class="fa fa-toggle-off toogle-debug toogle-off-debug" value="1"></i></a>
                @endif
            </li>

            <li>
                @if(getConfig("icone_fb") == 1)
                    <a title="Desactiver debug" class="action-toggle-on menu-debug" href="{{route('admin.icone_fb', [0])}}">
                        <span>Icone Facebook</span>
                        <i class="fa fa-toggle-on toogle-debug toogle-on-debug" value="0"></i>
                    </a>
                @else
                    <a title="Activer debug" class="action-toggle-on menu-debug" href="{{route('admin.icone_fb', [1])}}">
                        <span>Icone Facebook</span>
                        <i class="fa fa-toggle-off toogle-debug toogle-off-debug" value="1"></i>
                    </a>
                @endif
            </li>

            {{-- Google AdSense --}}
            <li>
                @if(getConfig("google_adsense") == 1)
                    <a title="Desactiver debug" class="action-toggle-on menu-debug" href="{{route('admin.google_adsense', [0])}}">
                        <span>Google Adsense</span>
                        <i class="fa fa-toggle-on toogle-debug toogle-on-debug" value="0"></i>
                    </a>
                @else
                    <a title="Activer debug" class="action-toggle-on menu-debug" href="{{route('admin.google_adsense', [1])}}">
                        <span>Google Adsense</span>
                        <i class="fa fa-toggle-off toogle-debug toogle-off-debug" value="1"></i>
                    </a>
                @endif
            </li>

            <li>
                @if(getConfig("langue") == 1)
                    <a title="Desactiver debug" class="action-toggle-on menu-debug" href="{{route('admin.multilangue', [0])}}">
                        <span>Multilangue</span>
                        <i class="fa fa-toggle-on toogle-debug toogle-on-debug" value="0"></i>
                    </a>
                @else
                    <a title="Activer debug" class="action-toggle-on menu-debug" href="{{route('admin.multilangue', [1])}}">
                        <span>Multilangue</span>
                        <i class="fa fa-toggle-off toogle-debug toogle-off-debug" value="1"></i>
                    </a>
                @endif
            </li>
            {{-- activer ou désactiver le toctoc auto    --}}
            <li>
                @if(getConfig("active_toctoc") == 1)
                    <a title="Desactiver Toctoc auto" href="{{route('admin.toctoc_auto', [0])}}" class="action-toggle-on menu-debug"><span>Toctoc auto</span><i class="fa fa-toggle-on toogle-debug toogle-on-debug" value="0"></i></a>
                @else
                    <a title="Activer Toctoc auto" href="{{route('admin.toctoc_auto', [1])}}"class="action-toggle-on menu-debug"><span>Toctoc auto</span><i class="fa fa-toggle-off toogle-debug toogle-off-debug" value="1"></i></a>
                @endif
            </li>

            <li>
                @if(is_active_notification_nbr_mail())
                    <a title="Desactiver l'envoi mail sur le max nombre de mail envoyé" href="{{route('admin.notification.mail', [0])}}" class="action-toggle-on menu-debug"><span>Notification reste du mail</span><i class="fa fa-toggle-on toogle-debug toogle-on-debug" value="0"></i></a>
                @else
                    <a title="Activer l'envoi mail sur le max nombre de mail envoyé" href="{{route('admin.notification.mail', [1])}}"class="action-toggle-on menu-debug"><span>Notification reste du mail</span><i class="fa fa-toggle-off toogle-debug toogle-off-debug" value="1"></i></a>
                @endif
            </li>

            <li>
                @if(guest_listing_page())
                    <a title="Desactiver la recherche sans être connecté" href="{{route('admin.search.guest.ads', [0])}}" class="action-toggle-on menu-debug"><span>Listing ads sans login</span><i class="fa fa-toggle-on toogle-debug toogle-on-debug" value="0"></i></a>
                @else
                    <a title="Activer la recherche sans être connecté" href="{{route('admin.search.guest.ads', [1])}}"class="action-toggle-on menu-debug"><span>Listing ads sans login</span><i class="fa fa-toggle-off toogle-debug toogle-off-debug" value="1"></i></a>
                @endif
            </li>

            @endif

                </ul>

        </li>


        <li class="treeview ">
            @if(isAdmin())
                <a  href="TESTT">
                    <i class="fa "></i> <span>Domaine</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
           
                <ul class="treeview-menu">
                    <li><a href="{{route('affiche_domaine')}}"><span>Tous les domaines</span></a>
                    <li><a href="{{route('ajout_variable')}}"><span>Ajouter domaine</span></a>
                    <li><a href="{{route('ajout_propriete')}}"><span>Ajouter valeur du propriéte</span></a>
             </li>     
                </ul>
                @endif
          </li>


            {{--<li>
                <a href="{{ route('google_tag_manager.index') }} ">
                    <i class="fa fa-google"></i> <span>Google Tag Manager</span>
                </a>
            </li>--}}

            {{--@php dd($modules) @endphp--}}
            <li @if(Route::currentRouteName() == 'admin.courbes')class="active" @endif>
                 <a href="{{route('admin.courbes')}}">
                    <i class="fa fa-chart-area"></i> <span>Courbes</span>
                </a>
    </li>
    <li>
        @if(isAdmin())
            <a href="{{route('admin.emploi_temp_comunity')}}">
                <i class="fa fa-chart-area"></i> <span>Emploi du temps</span>
            </a>
        @endif

    </li>
    <li>
        @if(isAdmin())
            <a href="{{route('admin.archivage')}}">
                <i class="fa fa-history" aria-hidden="true"></i> <span>Historique d'archivage</span>
            </a>
        @endif

    </li>

    @foreach($modules as $key => $module)

 
            @if(isUserModule($module))
            <li @if(empty($module->route) || is_null($module->route))
                class="treeview @if(isActiveModule(Route::currentRouteName(), $module)) active  @endif" @endif>
                 <a @if(!is_null($module->route)) href="{{route($module->route)}} @else href="#" @endif">
                    <i class="fa {{$module->icone}}"></i> <span>{{$module->nom}}</span>
                    @if((empty($module->route) || is_null($module->route)) && isset($module->pages))
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    @endif
                </a>
                @if(count($module->pages) > 0)
                <ul class="treeview-menu">
                    @foreach($module->pages as $key2 => $page)
                        @if(isAdmin())
                            <li @if(Route::currentRouteName() == $page->route){{"class=active"}} @endif>
                                <a href="{{url(getConfig('admin_prefix') . $page->url)}}"><i class="fa fa-circle-o"></i> {{$page->nom}}</a>
                            </li>
                        @elseif(!isAdmin() && $module->nom == 'Manage User')
                            @if($page->nom == 'User List')
                                <li @if(Route::currentRouteName() == $page->route){{"class=active"}} @endif>
                                    <a href="{{url(getConfig('admin_prefix') . $page->url)}}"><i class="fa fa-circle-o"></i> {{$page->nom}}</a>
                                </li>
                            @endif
                        @else
                            <li @if(Route::currentRouteName() == $page->route){{"class=active"}} @endif>
                                <a href="{{url(getConfig('admin_prefix') . $page->url)}}"><i class="fa fa-circle-o"></i> {{$page->nom}}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
                @endif
            </li>
            @endif
            @endforeach
        
        <li>
            <a href="{{route('admin.mailmaintenance')}}">
               <i class="fa fa-chart-area"></i> <span>Mail Maintenance</span>
           </a>
        </li>

    </ul>


    </section>
    <style type="text/css">
        .nb-notif
        {
            color: red;
            font-weight: bold;
            font-size: 18px;
        }

        .toogle-debug
        {
            margin-left: 15px;
        }

        .toogle-on-debug
        {
            color: green !important;
        }

        .toogle-off-debug
        {
            color: red !important;
        }

        .menu-debug
        {
            font-size: 22px;
            background-color: white;
        }
        .menu-debug:hover
        {
            background-color: white !important;
        }
        .menu-debug span
        {
            color: red !important;
        }
    </style>

    <!-- /.sidebar -->
</aside>
