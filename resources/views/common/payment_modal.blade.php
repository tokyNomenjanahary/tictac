<style>
    .creditly-wrapper input.has-error {
        outline: none;
        border-color: #ff7076;
        border-top-color: #ff5c61;
        -webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,0.2),0 1px 0 rgba(255,255,255,0),0 0 4px 0 rgba(255,0,0,0.5);
        -moz-box-shadow: inset 0 1px 2px rgba(0,0,0,0.2),0 1px 0 rgba(255,255,255,0),0 0 4px 0 rgba(255,0,0,0.5);
        -ms-box-shadow: inset 0 1px 2px rgba(0,0,0,0.2),0 1px 0 rgba(255,255,255,0),0 0 4px 0 rgba(255,0,0,0.5);
        -o-box-shadow: inset 0 1px 2px rgba(0,0,0,0.2),0 1px 0 rgba(255,255,255,0),0 0 4px 0 rgba(255,0,0,0.5);
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.2),0 1px 0 rgba(255,255,255,0),0 0 4px 0 rgba(255,0,0,0.5);
    }
</style>
<div id="paymentFormModal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center">{{ __('payment.payment') }}</h4>
            </div>
            <div class="modal-body creditly-wrapper">
                <div class="row">
                    <form method="post" id="payment-form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="col-xs-12 col-md-12">
                            <!-- CREDIT CARD FORM STARTS HERE -->
                            <div class="panel panel-default credit-card-box">
                                <div class="panel-heading display-table" >
                                    <div class="row display-tr" >
                                        <h3 class="panel-title display-td" >{{ __('payment.payment_details') }}</h3>
                                        <div class="display-td" >                            
                                            <img class="img-responsive pull-right" src="/img/accepted_c22e0.png">
                                        </div>
                                    </div>                    
                                </div>
                                <div class="panel-body">
                                    <form role="form" id="payment-form">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="form-group">
                                                    <label for="cardNumber">{{ __('payment.card_number') }}&nbsp;&nbsp;&nbsp;&nbsp;<span class="card-type"></span></label>
                                                    <div class="input-group">
                                                        <input 
                                                            type="tel"
                                                            class="form-control number credit-card-number"
                                                            name="cardNumber"
                                                            placeholder="{{ __('Valid Card Number') }}"
                                                            autocomplete="cc-number"
                                                            autofocus
                                                            />
                                                        <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                                    </div>
                                                </div>                            
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-7 col-md-7">
                                                <div class="form-group">
                                                    <label for="cardExpiry"><span class="hidden-xs">{{ __('payment.valid_card_number') }}</span><span class="visible-xs-inline">{{ __('payment.exp') }}</span> {{ __('payment.date') }}</label>
                                                    <input 
                                                        id="card-validity""
                                                        type="tel" 
                                                        class="form-control expiration-month-and-year" 
                                                        name="cardExpiry"
                                                        placeholder="MM / YY"
                                                        autocomplete="cc-exp"
                                                        required 
                                                        />
                                                </div>
                                            </div>
                                            <div class="col-xs-5 col-md-5 pull-right">
                                                <div class="form-group">
                                                    <label for="cardCVC">{{ __('payment.cv_code') }}</label>
                                                    <input 
                                                        type="tel" 
                                                        class="form-control security-code"
                                                        name="cardCVC"
                                                        placeholder="CVC"
                                                        autocomplete="cc-csc"
                                                        required
                                                        />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="form-group">
                                                    <label for="nameOnCard">{{ __('payment.name_on_card') }}</label>
                                                    <input type="text" class="form-control billing-address-name" name="nameOnCard" required maxlength="20" placeholder="{{ __('Name on Card') }}"/>
                                                </div>
                                            </div>                        
                                        </div>
                                        @if(!empty(Route::currentRouteName()) && Route::currentRouteName() == 'subscription_plan')
                                            <input type="hidden" name="packageId" id="packageId"/>
                                        @elseif(!empty(Route::currentRouteName()) && Route::currentRouteName() == 'boost_ad')
                                            <input type="hidden" name="adId" id="adId"/>
                                        @endif
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <button class="btn btn-success btn-lg btn-block" type="submit" id="paymentSubmit" href="javascript:void(0);">{{ __('Pay') }} &euro;<span id="showAmount"></span></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>            
                            <!-- CREDIT CARD FORM ENDS HERE -->
                        </div> 
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>