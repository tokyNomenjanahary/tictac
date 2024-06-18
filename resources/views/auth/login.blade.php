
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" @if(!empty(Route::currentRouteName()) && Route::currentRouteName() == 'searchadlocation.map') class="search-map-page-html" @endif>
    <head>
        @if((isset($ad) && $ad->user_id != 435) || isNoIndexPage())
        <meta name="robots" content="noindex">
        @else
        <meta name="robots" content="index,follow">
        @endif

        {{google_tag_manager_head()}}

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="{{generatePageDescription()}}">
        <meta id="e_metaKeywords" name="KEYWORDS" content="{{__('seo.keywords')}}">

        <!--titre du page -->
                <title>{{generatePageTitle()}}</title>


        {{-- <link href="{{ asset('css/include.css') }}" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651134861/Bailti/css/include_m6j05r.css" rel="stylesheet">


        <!-- Styles -->
        @stack('styles')



        <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649487192/Bailti/js/jquery-3.2.1.min_gdx1kd.js"></script>
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623734/bootstrap.min_lpirue.js"></script>
        {{-- <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623652/common_nanhue.js"></script> --}}

        <!-- Dump all dynamic scripts into template -->

        <meta name="httpcs-site-verification" content="HTTPCS9083HTTPCS" />

            <style>
                @media (max-width: 768px){
                    .none-display {
                        display: none;
                    }

                    .mb {
                        margin-bottom: 10px;
                    }

                    .mt {
                        margin-top: 20px;
                    }
                }
                .link-back:hover{
                    text-decoration: underline;
                }
            </style>

            {{-- Google Adsense --}}
            @if(Auth::check())
                @if(getConfig('google_adsense') && !isUserSubscribed(Auth::id()))
                    {{ google_adsense_code() }}
                @endif
            @endif
    </head>
    <body  class="body-list">

        {{google_tag_manager_body()}}

        {{serverInfos()}}
        <input type="hidden" id="changeLang" value="fr" name="">
        <div class='loader-icon' style="display:none;"><img src='/images/rolling-loader.apng' alt="rolling-loader.apng"></div>

        @if(!empty(Route::currentRouteName()) && (Route::currentRouteName() == 'login_popup' || Route::currentRouteName() == 'register' || Route::currentRouteName() == 'registerStep2' || Route::currentRouteName() == 'fb_register'))
            @include('common.header_login')
        @else
            @include('common.header1')
        @endif

@push('styles')
    <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648888215/css/custom_seek_annhlt.css" rel="stylesheet">
@endpush
@push('scripts')
{{-- <script src="/js/popup-login.js"></script> --}}
<script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649321175/js/popup-login_g86top.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
@endpush
<section class="inner-page-wrap">
    <div id="popup-modal-body-login" class="login-form-x rent-property-form-content project-form edit-pro-content-1 white-bg m-t-20">
        @if ($message = Session::get('success'))

        <div class="alert alert-success fade in alert-dismissable" style="margin-top:20px;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
            {{ $message }}
        </div>

        @endif
        @if ($message = Session::get('error'))

        <div class="alert alert-danger fade in alert-dismissable" style="margin-top:20px;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
            <?php echo $message; ?>
            @if ($message = Session::get('email_verif'))
            <a  style="display: block; text-decoration: underline !important;" data-id="{{Session::get('email_verif')}}" class="resend-verification-mail" href="javascript:">{{__('login.resend_mail')}}</a>
            @endif
        </div>

        @endif
        @if (Session::has('verify_email'))

        <div class="alert alert-success fade in alert-dismissable" style="margin-top:20px;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
            <?php echo Session::get('verify_email'); ?>
            @if ($message = Session::get('email_verif'))
            <a  style="display: block; text-decoration: underline !important;" data-id="{{Session::get('email_verif')}}" class="resend-verification-mail" href="javascript:">{{__('login.resend_mail')}}</a>
            @endif
        </div>
        <?php Session::forget('verify_email');?>

        @endif
        <!-- @if ($message = Session::get('success'))

        <div class="alert alert-success fade in alert-dismissable" style="margin-top:20px;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
            {{ $message }}
        </div>

        @endif
        @if ($message = Session::get('error'))

        <div class="alert alert-danger fade in alert-dismissable" style="margin-top:20px;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
            <?php echo $message; ?>
            @if ($message = Session::get('email_verif'))
            <a  style="display: block; text-decoration: underline !important;" data-id="{{Session::get('email_verif')}}" class="resend-verification-mail" href="javascript:">{{__('login.resend_mail')}}</a>
            @endif
        </div>

        @endif
        @if (Session::has('verify_email'))

        <div class="alert alert-success fade in alert-dismissable" style="margin-top:20px;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
            <?php echo Session::get('verify_email'); ?>
            @if ($message = Session::get('email_verif'))
            <a  style="display: block; text-decoration: underline !important;" data-id="{{Session::get('email_verif')}}" class="resend-verification-mail" href="javascript:">{{__('login.resend_mail')}}</a>
            @endif
        </div>
        <?php Session::forget('verify_email');?>

        @endif -->
        <form method="POST" id="formLoginCaptcha-popup" enctype="multipart/form-data" action="{{ route('loginPopup') }}">
            {{ csrf_field() }}
            <input type="hidden" name="timezone" id="timezone">


            <div class="popup-social-icons text-center">
                <div class="entete-login">
                    <h2 class="bjr">{{ __('login.bonjour') }}</h2>
                    <h5 class="login-h5">
                        {{ __('login.connectez_vous') }}
                    </h5>
                </div>

                @if(getConfig("icone_fb") == 1)
                <h6>{{ __('login.log_with') }} :</h6>
                <ul>
                    <li class="fb-social"><a class="facebook-connection" href="javascript:" data-id="{{ url('/login/facebook') }}"><i class="fa fa-facebook" aria-hidden="true"></i><span>{{ __('Facebook') }}</span></a></li>
                </ul>
            </div>
            @endif


            <div class="or-divider"><span>{{ __('login.or') }}</span></div>


            <div class="">
                <div class="form-group">
                    <label class="control-label">{{ __('login.mail') }} <sup>*</sup></label>
                    <input class="form-control" placeholder="{{ __('login.mail') }}" type="text" name="email" id="email_address" value="{{ old('email') }}" autofocus />
                </div>
                <div class="form-group">
                    <label class="control-label">{{ __('login.pass') }} <sup>*</sup></label>
                    <input class="form-control" placeholder="{{ __('login.pass') }}" type="password" name="password" id="password" />
                </div>
            </div>

            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('login.remember') }}
                    </label>
                </div>
            </div>
            <div class="submit-btn-1 save-nxt-btn">
                <input type="submit"
                id="loginButton"
                data-callback="onSubmitForm" value={{ __("acceuil.login_acceuil") }}>
            </div>
            <a href=""></a>
            <br>
            <div style="margin-top:10px;">
            {{ __('login.no_compte') }} <a href="/creer-compte/etape/1" class="inscr">{{ __('login.register') }}</a>
            </div>
            <br>
            <a class="btn btn-link" href="{{ route('password.request') }}">
                {{ __('login.forgot') }}
            </a>
        </form>
    </div>
</section>
@if ($message = Session::get('email_verif'))
<div id="mail-verif-modal" class="modal ">
    <div class="modal-dialog ">
        <div class="modal-content ">
            <div class="modal-body" >
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 style="font-size : 1.2em;" id="modal-information-text" class="modal-title text-center"> {{__('login.verif_mail_sent', ['mail' => Session::get('email_verif')])}} </h5>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
$(document).ready(function(){ /*code here*/ })
        if ($("#formLoginCaptcha-popup").length >= 0) {
            $("#formLoginCaptcha-popup").validate({

            rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
            }
            },
            messages: {
            email: {
                required:  "{{__('validator.required')}}",
                email: "{{__('validator.email')}}"
            },
            password: {
                required: "{{__('validator.required')}}"
            }

            }
        })
        }

</script>
<style>
a.btn.btn-link{
    margin-top: -32px;
    margin-left: -11px;

}
a.inscr{
    text-decoration: underline;
    color:#337ab7;
}
</style>
<script type="text/javascript">
    $(document).ready(function(){
        $('.facebook-connection').on('click', function(){
            var href = $(this).attr('data-id');
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            $.ajax({
                url: '/login_facebook',
                type: 'post',
            }).done(function(data){
                location.href = href;
            }).fail(function (jqXHR, ajaxOptions, thrownError){
                alert('No response from server');
            });
        });
    });
</script>
@endpush

        @if(!empty(Route::currentRouteName()) && Route::currentRouteName() == 'searchadlocation.map')
        <!--No Footer -->
        @else
        @include('common.footer')
        @endif
        @stack('scripts')
        <script>
            $(document).ready(function() {
                $('#btn-reset-popup').on('click', function(){
                    $('#LoginModal').modal('hide');
                    $('#ReinitialisePasswordModal').modal('show');
                });
                $('#btn-login-popup').on('click', function(){
                    $('#LoginModal').modal('show');
                });
            });
        </script>
    </body>
</html>



