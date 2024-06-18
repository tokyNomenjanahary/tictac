
<style>
button#navbarDropdownMenuLink78.btn.btn-default.dropdown-toggle{
    border-radius: 16px;
   /* margin-top: 6px;*/
    margin-top: 14px;
}
div.dropdown-menu.dropdown-menu-login-nrh{
    border-radius: 16px;
}
a.dropdown-item-login-nrh{
    padding-left: 13px;
}
.dropdown-menu-login-nrh {
    min-width: 58px;
}
i.flag-icon.flag-icon-fr{
    margin-right: 2px;
}
@media only screen and (max-width:992px){
	button#navbarDropdownMenuLink78.btn.btn-default.dropdown-toggle{

        margin-top: 0px;

}
}
</style>
<header id="header-tictac" class="white-bg home-hdr custum-home-hdr hdr-login">
    <div class="container wow fadeIn">
        <div class="row">
            <div class="home-logo">
                <a @if(!isSearchProfile()) href="{{ url('/') }}" @endif>
                    <img class="logo-bailti" src="{{URL::asset('img/blue-logo.png')}}" alt="{{ config('app.name', 'BailFit') }}"></a>
                <div class="hdr-cnx-text">
                    <img class="img" src="/images/verified.png" alt="login">
                    <span class="txt">Authentification</span>
                </div>

                <div class="dropdown dropdown-login-nrh" style="display:none;">
                    <button type="button" class="btn btn-default dropdown-toggle" href="#" id="navbarDropdownMenuLink78"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        @if(App::getLocale() == 'fr')
                            <i class="flag-icon flag-icon-fr"></i>
                        @else
                            <i class="flag-icon flag-icon-us"></i>
                        @endif
                        <span class="caret"></span>

                    </button>
                    <div class="dropdown-menu dropdown-menu-login-nrh" aria-labelledby="navbarDropdownMenuLink78">
                        @if(App::getLocale() == 'fr')
                            <a class="dropdown-item-login-nrh" href="{{ route('changement-langue', ['langue' => 'en']) }}"><i class="flag-icon flag-icon-us"></i></a>
                        @else
                            <a class="dropdown-item-login-nrh" href="{{ route('changement-langue', ['langue' => 'fr']) }}"><i class="flag-icon flag-icon-fr"></i> </a>
                        @endif
                    </div>
                </div>
            </div>


        </div>


    </div>
</header>
@guest
@else
@include('common.review')
@include('common.code_promo')
@endguest
