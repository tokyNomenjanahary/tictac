
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

         
    </head>
    <body  class="body-list">

      
        @include('common.header_login')
       

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
       
        <form id="registerStep" method="POST" id="formLoginCaptcha-popup"   action=" {{route('save_mail')}}">
        @csrf 
             
            <div class="popup-social-icons text-center">
                <div class="entete-login">
                <div class="pop-hdr">
                    <span class="find-colocation" style="font-weight:bold;">{{ __('register.change_usermaile') }}</span>
                </div>
                <hr>
                   <h5 class="login-h5">
                       {{ __('register.change_usermail') }}
                     </h5>
                </div>
  
                <div class="form-group">
                    <label class="control-label">{{ __('login.mail') }} <sup>*</sup></label>
                    <input class="form-control" placeholder="{{__('register.mail_placeholder')}}" type="email" name="email" id="email_address"  required />
                </div>
                <br>

                         <div class="form-group">
                                 <button type="submit" class="btn btn-primary"> 
                                    {{ __('register.register') }}
                                </button>
                         </div>

                    <!-- <div class="submit-btn-1 save-nxt-btn">
                    <button class="submit-btn-2 btn-info" id="catTimer" type="submit"> enregistrer </button>
                    </div> -->
            </div>
             
        </form>
    </div>
</section>
 
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
            
            },
            messages: {
            email: {
                required:  "{{__('validator.required')}}",
                email: "{{__('validator.email')}}"
            },
            

            }
        })
        }

</script>
    <script>
         
          var messages = {
              "registration_successful": "{{__('login.registration_successful')}}"
          };
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
 
@endpush

        @if(!empty(Route::currentRouteName()) && Route::currentRouteName() == 'searchadlocation.map')
        <!--No Footer -->
        @else
        @include('common.footer')
        @endif
        @stack('scripts')
        
    </body>
</html>



