<div id="LoginModal" class="modal fade">
    <div class="modal-dialog modal-lg ad-senario-popup">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title modal-title-annonce text-center">{{ __('login.connecter') }}</h4>
            </div>

            <div class="modal-body">
                <div id="popup-modal-body-login" class="rent-property-form-content project-form edit-pro-content-1 white-bg m-t-20">
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
                            <h6>{{ __('login.log_with') }} :</h6>
                            <ul>

                                <li class="fb-social"><a class="facebook-connection" href="javascript:" data-id="{{ url('/login/facebook') }}"><i class="fa fa-facebook" aria-hidden="true"></i><span>{{ __('Facebook') }}</span></a></li>
                                <!-- <li class="in-social"><a href="{{ url('/login/linkedin') }}"><i class="fa fa-linkedin" aria-hidden="true"></i><span>{{ __('Linkedin') }}</span></a></li> -->
                                <!-- <li class="google-social"><a href="{{ url('/login/google') }}"><i class=" fa fa-google" aria-hidden="true"></i><span>{{ __('Google') }}</span></a></li> -->
                            </ul>
                        </div>
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
                            data-callback="onSubmitForm" value="Se connecter">
                        </div>
                        <!-- <div class="submit-btn-1 save-nxt-btn">
                            <input type="submit"
                            class="g-recaptcha"
                            id="loginButton"
                            data-sitekey="6Ldfd3IUAAAAAOUONqREj3aSYlxTxsP_3SCMPA3z"
                            data-callback="onSubmitForm" data-size="invisible" value="Se connecter">
                        </div> -->
                        
                        <a class="btn btn-link" href="{{ route('reset_password_popup') }}">
                            {{ __('login.forgot') }}
                        </a>
                        

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.13/moment-timezone-with-data.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var timezone = moment.tz.guess();
        $('#timezone').val(timezone);
        console.log($('#timezone').val());
    });
</script>
@endpush

