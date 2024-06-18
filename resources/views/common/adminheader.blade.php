<header class="main-header">    
    <!-- Logo -->
    <a href="{{route('admin.dashboard')}}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>TT</b> H</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>{{config("app.name")}}</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        @if(Session::has('ADMIN_USER') && !empty(Session::get('ADMIN_USER')->user_profiles) && !empty(Session::get('ADMIN_USER')->user_profiles->profile_pic))
                        <img src="{{URL::asset('uploads/profile_pics/' . Session::get('ADMIN_USER')->user_profiles->profile_pic)}}" class="user-image" alt="Admin Image">
                        @else
                        <img src="{{URL::asset('images/profile_avatar.jpeg')}}" class="user-image" alt="Admin Image" />
                        @endif
                        <span class="hidden-xs">{{Session::get('ADMIN_USER')->first_name . ' ' . Session::get('ADMIN_USER')->last_name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            @if(Session::has('ADMIN_USER') && !empty(Session::get('ADMIN_USER')->user_profiles) && !empty(Session::get('ADMIN_USER')->user_profiles->profile_pic))
                            <img src="{{URL::asset('uploads/profile_pics/' . Session::get('ADMIN_USER')->user_profiles->profile_pic)}}" class="img-circle" alt="Admin Image">
                            @else
                            <img src="{{URL::asset('images/profile_avatar.jpeg')}}" class="img-circle" alt="Admin Image" />
                            @endif

                            <p>
                                {{Session::get('ADMIN_USER')->first_name . ' ' . Session::get('ADMIN_USER')->last_name }} - Admin
<!--                                <small>Member since Nov. 2012</small>-->
                            </p>
                        </li>

                        <!-- Menu Footer-->
                        <li class="user-footer">
<!--                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                            </div>-->
                            <div class="pull-right">
                                <a href="{{ route('admin.logout') }}"
                                    onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">
                                     Sign out
                                </a>

                                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                     {{ csrf_field() }}
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>