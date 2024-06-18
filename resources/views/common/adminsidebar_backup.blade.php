

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
            <li class="header">MAIN NAVIGATION</li>
            @if(isAdmin())
            
            <li>
                @if(getConfig("debug") == 1)
                <a title="Desactiver debug" class="action-toggle-on menu-debug" href="javascript:"><span>Debug</span><i class="fa fa-toggle-on toogle-debug toogle-on-debug" value="0"></i></a>
                @else
                <a title="Activer debug" class="action-toggle-on menu-debug" href="javascript:"><span>Debug</span><i class="fa fa-toggle-off toogle-debug toogle-off-debug" value="1"></i></a>
                @endif
            </li>
            <li>
                @if(getConfig("telegram_pub_acceuil") == 1)
                <a title="Desactiver lien telegram" class="action-toggle-on menu-debug" href="{{url('/admin/telegram_pub/acceuil/0')}}"><span><i class="fa fa-telegram"></i>Acceuil</span><i class="fa fa-toggle-on toogle-debug toogle-on-debug" value="0"></i></a>
                @else
                <a title="Activer lien telegram" class="action-toggle-on menu-debug" href="{{url('/admin/telegram_pub/acceuil/1')}}"><span><i class="fa fa-telegram"></i>Acceuil</span><i class="fa fa-toggle-off toogle-debug toogle-off-debug" value="1"></i></a>
                @endif
            </li>

            <li>
                @if(getConfig("verification_mail") == 1)
                <a title="Desactiver verification mail" class="action-toggle-on menu-debug" href="{{url('/admin/manage-verification-mail/0')}}"><span>Verification Mail</span><i class="fa fa-toggle-on  toogle-on-debug" value="0"></i></a>
                @else
                <a title="Activer verification mail" class="action-toggle-on menu-debug" href="{{url('/admin/manage-verification-mail/1')}}"><span>Verification Mail</span><i class="fa fa-toggle-off  toogle-off-debug" value="1"></i></a>
                @endif
            </li>
            <li>
                @if(getConfig("telegram_pub_email") == 1)
                <a title="Desactiver lien telegram" class="action-toggle-on menu-debug" href="{{url('/admin/telegram_pub/email/0')}}"><span><i class="fa fa-telegram"></i>Email</span><i class="fa fa-toggle-on toogle-debug toogle-on-debug" value="0"></i></a>
                @else
                <a title="Activer lien telegram" class="action-toggle-on menu-debug" href="{{url('/admin/telegram_pub/email/1')}}"><span><i class="fa fa-telegram"></i>Email</span><i class="fa fa-toggle-off toogle-debug toogle-off-debug" value="1"></i></a>
                @endif
            </li>
            <li @if(Route::currentRouteName() == 'admin.parameters')class="active" @endif>
                 <a href="{{route('admin.parameters')}}">
                    <i class="fa fa-cog"></i> <span>Parameters</span>
                </a>
            </li>
            <li @if(Route::currentRouteName() == 'admin.dashboard')class="active" @endif>
                 <a href="{{route('admin.dashboard')}}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            @endif
            @if(isAdmin())
            <li @if(Route::currentRouteName() == 'admin.indicators')class="active" @endif>
                 <a href="{{route('admin.indicators')}}">
                    <i class="fa fa-dashboard"></i> <span>Indicators</span>
                </a>
            </li>
            @endif
            @if(isAdmin())
            <li @if(Route::currentRouteName() == 'admin.link_source')class="active" @endif>
                 <a href="{{route('admin.link_source')}}">
                    <i class="fa fa-dashboard"></i> <span>Lien sources Page de vente</span>
                </a>
            </li>
            <li @if(Route::currentRouteName() == 'admin.contact_show')class="active" @endif>
                 <a href="{{route('admin.contact_show')}}">
                    <i class="fa fa-dashboard"></i> <span>Affichage infos contact</span>
                </a>
            </li>
            @endif
            @if(isAdmin())
            <li @if(Route::currentRouteName() == 'admin.click_toctoc_mail')class="active" @endif>
                 <a href="{{route('admin.click_toctoc_mail')}}">
                    <i class="fa fa-dashboard"></i> <span>Click Lien Toctoc Mail</span>
                </a>
            </li>
            @endif
             @if(isSuperviseur() || isAdmin() || isComunity())
            <li @if(Route::currentRouteName() == 'admin.message_delete')class="active" @endif>
                 <a href="{{route('admin.message_delete')}}">
                    <span>Message compte désactivé @if(getNbMessageAccountDelete()) <span class="nb-notif">({{getNbMessageAccountDelete()}})</span> @endif</span>
                </a>
            </li>
            @endif
            @if(isSuperviseur() || isAdmin() || isComunity())
            <li class="treeview @if(Route::currentRouteName() == 'admin.users' || Route::currentRouteName() == 'admin.user.comunity_list' || Route::currentRouteName() == 'admin.user.add_user' || Route::currentRouteName() == 'admin.user.add_comunity' || Route::currentRouteName() == 'admin.user_avis' || Route::currentRouteName() == 'admin.professions' || Route::currentRouteName() == 'admin.user.parainage' || Route::currentRouteName() =='admin.block_ip'){{"active"}} @endif">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>Manage User</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(Route::currentRouteName() == 'admin.users'){{"class=active"}} @endif><a href="{{route('admin.users')}}"><i class="fa fa-circle-o"></i> User List</a></li>
                    <li @if(Route::currentRouteName() == 'admin.user.parainage'){{"class=active"}} @endif><a href="{{route('admin.user.parainage')}}"><i class="fa fa-circle-o"></i> Parrainage</a></li>
                    <li @if(Route::currentRouteName() == 'admin.professions'){{"class=active"}} @endif><a href="{{route('admin.professions')}}"><i class="fa fa-circle-o"></i> Ecole & Professions</a></li>
                    @if(isAdmin())
                    <li @if(Route::currentRouteName() == 'admin.admin.user_avis'){{"class=active"}} @endif><a href="{{route('admin.user_avis')}}"><i class="fa fa-circle-o"></i> User Avis</a></li>
                     <li @if(Route::currentRouteName() == 'admin.user.comunity_list'){{"class=active"}} @endif><a href="{{route('admin.user.comunity_list')}}"><i class="fa fa-circle-o"></i> Admin User List</a></li>
                    @endif
                    <li @if(Route::currentRouteName() == 'admin.user.add_user'){{"class=active"}} @endif><a href="{{route('admin.user.add_user')}}"><i class="fa fa-circle-o"></i> Add User</a></li>
                    @if(isAdmin())
                     <li @if(Route::currentRouteName() == 'admin.user.add_comunity'){{"class=active"}} @endif><a href="{{route('admin.user.add_comunity')}}"><i class="fa fa-circle-o"></i> New User Admin</a></li>
                    @endif
                    <li @if(Route::currentRouteName() == 'admin.admin.block_ip'){{"class=active"}} @endif><a href="{{route('admin.block_ip')}}"><i class="fa fa-circle-o"></i> Block IP</a></li>
                </ul>
            </li>
            @endif
            @if(isAdmin())
            <li class="treeview @if(Route::currentRouteName() == 'admin.featuredcity.addFeaturedCity' || Route::currentRouteName() == 'admin.featuredCityList'){{"active"}} @endif">
                <a href="#">
                    <i class="fa fa-building"></i>
                    <span>Manage Featured City</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(Route::currentRouteName() == 'admin.featuredcity.addFeaturedCity'){{"class=active"}} @endif><a href="{{route('admin.featuredcity.addFeaturedCity')}}"><i class="fa fa-circle-o"></i> Add Featured City</a></li>
                    <li @if(Route::currentRouteName() == 'admin.featuredCityList'){{"class=active"}} @endif><a href="{{route('admin.featuredCityList')}}"><i class="fa fa-circle-o"></i> Featured City List</a></li>
                </ul>
            </li>
            @endif
            @if(isAdmin())
            <li class="treeview @if(Route::currentRouteName() == 'proximity.index'){{"active"}} @endif">
                <a href="#">
                    <i class="fa fa-building"></i>
                    <span>Manage Point of Proximity</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(Route::currentRouteName() == 'proximity.index'){{"class=active"}} @endif><a href="{{route('proximity.index')}}"><i class="fa fa-circle-o"></i> Point of Proximity</a></li>
                </ul>
            </li>
            @endif

             @if(isSuperviseur() || isAdmin() || isComunity())
             <li class="treeview @if(Route::currentRouteName() == 'admin.create-ad' || Route::currentRouteName() == 'admin.list-ad-community' || Route::currentRouteName() == 'admin.edit_bitly_token' || Route::currentRouteName() == 'admin.manage_report_field' || Route::currentRouteName() == 'admin.manage_report_field' || Route::currentRouteName() == 'admin.all_report' || Route::currentRouteName() == 'admin.manage_task_category'|| 
             Route::currentRouteName() == 'admin.daily_report_list' ||
             Route::currentRouteName() == 'admin.manage_profile' || Route::currentRouteName() == 'admin.new_daily_report' || Route::currentRouteName() == 'admin.link_phrase') {{"active"}} @endif">
                <a href="#">
                    <i class="fa  fa-user"></i>
                    <span>{{__('Community Manager')}}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(Route::currentRouteName() == 'admin.create-ad'){{"class=active"}} @endif><a href="{{route('admin.create-ad')}}"><i class="fa fa-circle-o"></i>{{__('Create Ad')}}</a></li>
                    <li @if(Route::currentRouteName() == 'admin.new_daily_report'){{"class=active"}} @endif><a href="{{route('admin.new_daily_report')}}"><i class="fa fa-circle-o"></i> Daily Report</a></li>
                    <li @if(Route::currentRouteName() == 'admin.all_report'){{"class=active"}} @endif><a href="{{route('admin.all_report')}}"><i class="fa fa-circle-o"></i> All Report</a></li>

                    @if(isAdmin() && !isSuperviseur())
                    <li @if(Route::currentRouteName() == 'admin.manage_profile'){{"class=active"}} @endif><a href="{{route('admin.manage_profile')}}"><i class="fa fa-circle-o"></i> Manage Profile</a></li>
                    <li @if(Route::currentRouteName() == 'admin.manage_task_category'){{"class=active"}} @endif><a href="{{route('admin.manage_task_category')}}"><i class="fa fa-circle-o"></i> Manage task category</a></li>
                    <li @if(Route::currentRouteName() == 'admin.manage_report_field'){{"class=active"}} @endif><a href="{{route('admin.manage_report_field')}}"><i class="fa fa-circle-o"></i> Manage Tasks</a></li>
                    @endif
                    <li @if(Route::currentRouteName() == 'admin.edit_bitly_token'){{"class=active"}} @endif><a href="{{route('admin.edit_bitly_token')}}"><i class="fa fa-circle-o"></i>{{__('Bl.ink & short url')}}</a></li>
                    <li @if(Route::currentRouteName() == 'admin.link_phrase'){{"class=active"}} @endif><a href="{{route('admin.link_phrase')}}"><i class="fa fa-circle-o"></i>{{__('Phrases')}}</a></li>
                    
                </ul>
            </li>
            <li class="treeview @if(Route::currentRouteName() == 'admin.adList' || Route::currentRouteName() == 'admin.trakingAd' || Route::currentRouteName() == 'admin.popularAd' || Route::currentRouteName() == 'admin.signalAd'){{"active"}} @endif">
                <a href="#">
                    <i class="fa fa-buysellads"></i>
                    <span>Manage Ads</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(Route::currentRouteName() == 'admin.adList'){{"class=active"}} @endif><a href="{{route('admin.adList')}}"><i class="fa fa-circle-o"></i>Ads List</a></li>
                    <li @if(Route::currentRouteName() == 'admin.trakingAd'){{"class=active"}} @endif><a href="{{route('admin.trakingAd',['type'=>'clic'])}}"><i class="fa fa-circle-o"></i>Popular city</a></li>
                    <li @if(Route::currentRouteName() == 'admin.popularAd'){{"class=active"}} @endif><a href="{{route('admin.popularAd',['type'=>'clic'])}}"><i class="fa fa-circle-o"></i>Popular ads</a></li>
                    <li @if(Route::currentRouteName() == 'admin.signalAd'){{"class=active"}} @endif><a href="{{route('admin.signalAd',['type'=>'loue'])}}"><i class="fa fa-circle-o"></i>Signal ads</a></li>
                </ul>
            </li>
            @endif
            @if(isAdmin() || isSeoAdmin()  || isSuperviseur() || isComunity())
            <li class="treeview @if(Route::currentRouteName() == 'admin.addnewblog' || Route::currentRouteName() == 'admin.bloglisting'){{"active"}} @endif">
                <a href="#">
                    <i class="fa fa-wordpress"></i>
                    <span>Manage Blogs</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(Route::currentRouteName() == 'admin.addnewblog'){{"class=active"}} @endif><a href="{{route('admin.addnewblog')}}"><i class="fa fa-circle-o"></i> Add New Blog</a></li>
                    <li @if(Route::currentRouteName() == 'admin.bloglisting'){{"class=active"}} @endif><a href="{{route('admin.bloglisting')}}"><i class="fa fa-circle-o"></i> Blog Listing</a></li>
                </ul>
            </li>
            @endif
            @if(isAdmin())
            <li class="treeview @if(Route::currentRouteName() == 'admin.regexEdit'){{"active"}} @endif">
                <a href="#">
                    <i class="fa fa-wordpress"></i>
                    <span>Regex</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(Route::currentRouteName() == 'admin.regexEdit'){{"class=active"}} @endif><a href="{{route('admin.regexEdit')}}"><i class="fa fa-circle-o"></i>Regex</a></li>
                </ul>
            </li>
            @endif
            @if(isSuperviseur() || isAdmin() || isComunity())
            @if(isAdmin())
            <li class="treeview @if(Route::currentRouteName() == 'admin.addnewpage' || Route::currentRouteName() == 'admin.pagelisting' || Route::currentRouteName() == 'admin.pagetextlisting' || Route::currentRouteName() == 'admin.mot_cles' || Route::currentRouteName() == 'admin.add_new_mot_cles'){{"active"}} @endif">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Pages Seo</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <!-- <li @if(Route::currentRouteName() == 'admin.addnewpage'){{"class=active"}} @endif><a href="{{route('admin.addnewpage')}}"><i class="fa fa-circle-o"></i> Add New page</a></li> -->
                    <li @if(Route::currentRouteName() == 'admin.pagelisting'){{"class=active"}} @endif><a href="{{route('admin.pagelisting')}}"><i class="fa fa-circle-o"></i> Description & Title</a></li>
                    <li @if(Route::currentRouteName() == 'admin.pagetextlisting'){{"class=active"}} @endif><a href="{{route('admin.pagetextlisting')}}"><i class="fa fa-circle-o"></i> Pages Text</a></li>
                    <li @if(Route::currentRouteName() == 'admin.mot_cles' || Route::currentRouteName() == 'admin.add_new_mot_cles' ){{"class=active"}} @endif><a href="{{route('admin.mot_cles')}}"><i class="fa fa-circle-o"></i> Mot Cles</a></li>
                </ul>
            </li>
            @endif
            @if(isAdmin())
            <li class="treeview @if(Route::currentRouteName() == 'admin.edit_static_page'){{"active"}} @endif">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Pages Static</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <!-- <li @if(Route::currentRouteName() == 'admin.addnewpage'){{"class=active"}} @endif><a href="{{route('admin.addnewpage')}}"><i class="fa fa-circle-o"></i> Add New page</a></li> -->
                    <li @if(Route::currentRouteName() == 'admin.edit_static_page'){{"class=active"}} @endif><a href="{{route('admin.edit_static_page')}}"><i class="fa fa-circle-o"></i>Edit pages</a></li>
                </ul>
            </li>
            @endif
            <li class="treeview @if(Route::currentRouteName() == 'admin.signal'){{"active"}} @endif">
                <a href="#">
                    <i class="fa fa-bell"></i>
                    <span>Signal Ad</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(Route::currentRouteName() == 'admin.signal') class="active" @endif><a href="{{route('admin.signal')}}"><i class="fa fa-circle-o"></i> Signal list</a></li>
                </ul>
            </li>
            <li class="treeview @if(Route::currentRouteName() == 'admin.subscription_notif'){{"active"}} @endif">
                <a href="#">
                    <i class="fa fa-bell"></i>
                    <span>Notification</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(Route::currentRouteName() == 'admin.subscription_notif'){{"class=active"}} @endif><a href="{{route('admin.subscription_notif')}}"><i class="fa fa-circle-o"></i>Notif</a></li>
                </ul>
            </li>
            
            @if(isAdmin())
            <li class="treeview @if(Route::currentRouteName() == 'admin.packageList' || Route::currentRouteName() == 'admin.editPackage' ||
            Route::currentRouteName() == 'admin.upselling_list' ||
            Route::currentRouteName() == 'admin.new_upselling' ||
            Route::currentRouteName() == 'admin.boosted_ads' || Route::currentRouteName() == 'admin.user_package_list' 
            || Route::currentRouteName() == 'admin.premium_phrase' || Route::currentRouteName() == 'admin.payment_by_city'){{"active"}} @endif">
                <a href="#">
                    <i class="fa fa-eur"></i>
                    <span>Manage Sub. Plans</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(Route::currentRouteName() == 'admin.packageList'){{"class=active"}} @endif><a href="{{route('admin.packageList')}}"><i class="fa fa-circle-o"></i> Package List</a></li>

                    <li @if(Route::currentRouteName() == 'admin.user_package_list'){{"class=active"}} @endif><a href="{{route('admin.user_package_list')}}"><i class="fa fa-circle-o"></i> User Subscription </a></li>
                    <li @if(Route::currentRouteName() == 'admin.upselling_list'){{"class=active"}} @endif><a href="{{route('admin.upselling_list')}}"><i class="fa fa-circle-o"></i> Upselling </a></li>
                    <li @if(Route::currentRouteName() == 'admin.new_upselling'){{"class=active"}} @endif><a href="{{route('admin.new_upselling')}}"><i class="fa fa-circle-o"></i>New upselling</a></li>
                    <li @if(Route::currentRouteName() == 'admin.boosted_ads'){{"class=active"}} @endif><a href="{{route('admin.boosted_ads')}}"><i class="fa fa-circle-o"></i> Boosted Ads </a></li>
                    <li @if(Route::currentRouteName() == 'admin.premium_phrase'){{"class=active"}} @endif><a href="{{route('admin.premium_phrase')}}"><i class="fa fa-circle-o"></i> Subscription phrase </a></li>
                    <li @if(Route::currentRouteName() == 'admin.subscription_notif'){{"class=active"}} @endif><a href="{{route('admin.subscription_notif')}}"><i class="fa fa-circle-o"></i>Notif</a></li>
                    <li @if(Route::currentRouteName() == 'admin.payment_by_city'){{"class=active"}} @endif><a href="{{route('admin.payment_by_city')}}"><i class="fa fa-circle-o"></i> Payment by city </a></li>
                    <li @if(Route::currentRouteName() == 'admin.indicateur_vente'){{"class=active"}} @endif><a href="{{route('admin.indicateur_vente')}}"><i class="fa fa-circle-o"></i> Indicateur pourcentage </a></li>
                
                </ul>
            </li>
            <li class="treeview @if(Route::currentRouteName() == 'admin.user-badi'){{"active"}} @endif">
                <a href="#">
                    <i class="fa fa-buysellads"></i>
                    <span>Badi</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(Route::currentRouteName() == 'admin.user-badi'){{"class=active"}} @endif><a href="{{route('admin.user-badi')}}"><i class="fa fa-circle-o"></i>User list</a></li>
                    
                </ul>
            </li>
            @endif
            @if(isAdmin())
            <li class="treeview @if(Route::currentRouteName() == 'admin.fb_pixel' || Route::currentRouteName() == 'admin.edit_fb_pixel'){{"active"}} @endif">
                <a href="#">
                    <i class="fa fa-facebook"></i>
                    <span>Fb Pixel</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(Route::currentRouteName() == 'admin.fb_pixel'){{"class=active"}} @endif><a href="{{route('admin.fb_pixel')}}"><i class="fa fa-circle-o"></i> Pixel Info</a></li>
                </ul>
            </li>
            @endif
            @if(isAdmin())
            <li class="treeview @if(Route::currentRouteName() == 'admin.code_group' || Route::currentRouteName() == 'admin.edit_code_group'){{"active"}} @endif">
                <a href="#">
                    <i class="fa  fa-group"></i>
                    <span>Group</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(Route::currentRouteName() == 'admin.code_group'){{"class=active"}} @endif><a href="{{route('admin.code_group')}}"><i class="fa fa-circle-o"></i>Group access code</a></li>
                </ul>
            </li>
            @endif
            @if(isAdmin())
            <li class="treeview @if(Route::currentRouteName() == 'admin.botList'){{"active"}} @endif">
                <a href="#">
                    <i class="fa  fa-android"></i>
                    <span>{{__('admin.bot')}}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(Route::currentRouteName() == 'admin.botList'){{"class=active"}} @endif><a href="{{route('admin.botList')}}"><i class="fa fa-circle-o"></i>{{__("admin.list_bot")}}</a></li>
                </ul>
            </li>
            @endif
            @if(isAdmin())
            <li class="treeview @if(Route::currentRouteName() == 'admin.code-promo' || Route::currentRouteName() == 'admin.add-code-promo'){{"active"}} @endif">
                <a href="#">
                    <i class="fa  fa-barcode"></i>
                    <span>Promo Code</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                   <li @if(Route::currentRouteName() == 'admin.code-promo'){{"class=active"}} @endif><a href="{{route('admin.code-promo')}}"><i class="fa fa-circle-o"></i>List</a></li>
                    <li @if(Route::currentRouteName() == 'admin.add-code-promo'){{"class=active"}} @endif><a href="{{route('admin.add-code-promo')}}"><i class="fa fa-circle-o"></i>Add Promo Code</a></li>
                </ul>
            </li>

            <li class="treeview @if(Route::currentRouteName() == 'admin.active_deactivated_element'){{"active"}} @endif">
                <a href="#">
                    <i class="fa  fa-barcode"></i>
                    <span>Active/Deactive Elements</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                   <li @if(Route::currentRouteName() == 'admin.active_deactivated_element'){{"class=active"}} @endif><a href="{{route('admin.active_deactivated_element')}}"><i class="fa fa-circle-o"></i>List</a></li>
                </ul>
            </li>
            @endif
            <li class="treeview @if(Route::currentRouteName() == 'admin.daily_interaction'){{"active"}} @endif">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>Daily user interaction
                    </span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                   <li @if(Route::currentRouteName() == 'admin.daily_interaction'){{"class=active"}} @endif><a href="{{route('admin.daily_interaction')}}"><i class="fa fa-circle-o"></i>List</a></li>
                </ul>
            </li>

            <li class="treeview @if(Route::currentRouteName() == 'admin.user_alert'){{"active"}} @endif">
                <a href="#">
                    <i class="fa fa-bell"></i>
                    <span>Alert
                    </span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                   <li @if(Route::currentRouteName() == 'admin.user_alert'){{"class=active"}} @endif><a href="{{route('admin.user_alert')}}"><i class="fa fa-circle-o"></i>List</a></li>
                </ul>
            </li>
            @endif
            @if(isAdmin() || isPost())
            <li class="treeview @if(Route::currentRouteName() == 'admin.pub_community' || Route::currentRouteName() == 'admin.pub_community_list'){{"active"}} @endif">
                <a href="#">
                    <i class="fa fa-user"></i>
                    <span>Espace Posts</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(Route::currentRouteName() == 'admin.pub_community'){{"class=active"}} @endif><a href="{{route('admin.pub_community')}}"><i class="fa fa-circle-o"></i>{{__('Publications')}}</a></li>
                    <li @if(Route::currentRouteName() == 'admin.pub_community_list'){{"class=active"}} @endif><a href="{{route('admin.pub_community_list')}}"><i class="fa fa-circle-o"></i>{{__('Listes')}}</a></li>
                </ul>
            </li>
            @endif
            @if(isAdmin())
            <li class="treeview @if(Route::currentRouteName() == 'admin.add_module' || Route::currentRouteName() == 'admin.add_module_page'){{"active"}} @endif">
                <a href="#">
                    <i class="fa fa-user"></i>
                    <span>Gestion des modules</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(Route::currentRouteName() == 'admin.add_module'){{"class=active"}} @endif><a href="{{route('admin.add_module')}}"><i class="fa fa-circle-o"></i>{{__('Add Module')}}</a></li>
                    <li @if(Route::currentRouteName() == 'admin.add_module_page'){{"class=active"}} @endif><a href="{{route('admin.add_module_page')}}"><i class="fa fa-circle-o"></i>{{__('Add Page Modules')}}</a></li>
                </ul>
            </li>
            @endif
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
    <script type="text/javascript">
        $(document).ready(function(){
            $('.toogle-debug').on('click', function(){
                var value = $(this).attr('value');
                location.href = "/admin/manage-debug/" + value;
            });
        });
    </script>
    
    <!-- /.sidebar -->
</aside>