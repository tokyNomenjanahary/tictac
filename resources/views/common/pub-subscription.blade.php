<!-- @if(!isUserSubscribed())
<div class="room-grid-ad-content col-xs-12 col-sm-6 col-md-6 col-lg-4 room-grid-bx-outer list-view-time">
    <div data-url="{{route('subscription_plan') . '?type=recherche'}}" class="room-grid-listing-bx-inside new-room-grid-listing-bx-inside pub-room-grid">
        <div class="premium-pub-recherche">
            <div class="pub-abonnez">
                <h4>Abonnez-vous pour débloquer toutes les fonctionnalités, Trouvez votre chambre idéale plus rapidement</h4>
            </div>
            <div class="pub-abonnez">
                <a href="{{route('subscription_plan') . '?type=recherche'}}" class="btn-pub-premium">{{__('subscription.abonnez_maintenant')}}</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('.pub-room-grid').on('click', function(){
            location.href = $(this).attr('data-url');
        });
    });
</script>
@endpush
@endif -->
