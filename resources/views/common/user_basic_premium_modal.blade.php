<div id="premium-remember-modal" class="modal fade">
    <div class="modal-dialog modal-lg ad-senario-popup body-premium-avantage">
        <div class="modal-content">
            <div class="modal-header modal-header-avantage">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <div class="heading-one"><h2>{{ __('subscription.avantage_pass_premium') }}</h2></div>
            </div>

            
            <div class="modal-body">
                <div class="go-premium">
                    <div class="white-bg premium-listing">
                        <div class="div-scroll-table">
                            <table class="table scroll-table">
                                <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>{{ __('Basic') }}</th>
                                        <th>{{ __('Premium') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $phrases = getPremiumPhrases();?>
                                    @foreach($phrases as $phrase)
                                    <tr>
                                        <td>{{ $phrase->phrase_fr }}</td>
                                        <td>@if($phrase->type_membre == 0 || $phrase->type_membre == 3)<div class="gray-check"><i class="fa fa-check" aria-hidden="true"></i>@else - @endif</div></td>
                                        <td>@if($phrase->type_membre == 1 || $phrase->type_membre == 3)<div class="green-check"><i class="fa fa-check" aria-hidden="true"></i>@else - @endif</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="detail-paiement button-premium-avantage">
                                <a href="javascript:" data-dismiss="modal" class="btn-acceuil stay_basic">{{__('subscription.stay_basic')}}</a>
                                <a href="{{route('subscription_plan') . '?type=popup'}}" class="btn-acceuil">{{__('subscription.pass_to_premium')}}</a>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@guest
@else
@if(!isUserSubscribed() && !isViewPremiumModal())
@push("scripts")
<script type="text/javascript">
    $(document).ready(function(){ 
        $('#premium-remember-modal').modal("show");
    });
</script>
@endpush
@endif
@endguest