<div id="alertPayment" class="modal fade alert-modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="alrt-modal-body">
                <h3>{{ __('addetails.become_premium') }}</h3>
                <p id="modal-alert-body">
                    @if(isset($typeSubscription) && !empty($typeSubscription))
                    {{$alert}}
                    @else
                    {{ __('subscription.default_subcription') }}
                    @endif
                </p>
                <div class="porject-btn-1 project-btn-green">
                <a href="javascript:" data-dismiss="modal">OK</a>
                </div>
            </div>
        </div>
    </div>
</div>
@if(isset($typeSubscription) && !empty($typeSubscription))
@stack('script')
<script>
    $(document).ready(function(){
        $('#alertPayment').modal('show');
    });
</script>
@endif