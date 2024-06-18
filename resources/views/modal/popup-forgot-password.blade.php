<div id="ReinitialisePasswordModal" class="modal fade">
    <div class="modal-dialog modal-lg ad-senario-popup">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title modal-title-annonce text-center">{{ __('reset.reset_pass') }}</h4>
            </div>

            <div class="modal-body">
                <div id="modal-password-modal" class="rent-property-form-content project-form edit-pro-content-1 white-bg">    

                                     
                    <form id="forgot-password" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}
                        
                        <div class="">
                            <div class="form-group">
                                <label for="email_address" class="control-label">{{ __('reset.email_adresse') }} <sup>*</sup></label>
                                <input class="form-control" placeholder="{{ __('reset.email_adresse') }}" type="text" name="email" id="email_address" value="{{ old('email') }}" autofocus />
                                
                            </div>
                            <div class="submit-btn-1 save-nxt-btn btn-reset-psswd"><input type="submit" name="resetpassword" value="{{ __('reset.button_send_link') }}" /></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>