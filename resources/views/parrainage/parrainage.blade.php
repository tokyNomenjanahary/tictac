@extends( 'layouts.appinner' )

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="/css/compiledstyles/default.css" rel="stylesheet">
    <link href="/css/compiledstyles/account/parrainage.css" rel="stylesheet">
    <link href="/css/parainage.css" rel="stylesheet">
@endpush
@push('scripts')
<script src="/js/parainnage.js"></script>
@endpush

@section('content')
	<section class="parrainage-container d-flex flex-column align-items-center">
		<div class="parrainez-votre-entourage-container d-flex flex-wrap">
			<div class="parrainez-votre-entourage-column d-flex flex-column flex-one">
				<div class="parrainez-votre-entourage d-flex align-items-center flex-one">
					<img class="flex-one" src="/img/fond-danse.png">
					<div class="text-parrainez-votre-entourage d-flex flex-column justify-content-between">
						<p>{{ __('parrainage.Parrainez') }}</p>
						<p>{{ __('parrainage.votre') }}</p>
						<p>{{ __('parrainage.entourage') }}</p>
					</div>
				</div>
			</div>
			<div class="atout-parrainage d-flex flex-column">
				<p class="title-black"><strong>{{ __('parrainage.amie') }}</strong></p>
				<p class="title-black"><strong>=</strong></p>
				<p class="title-blue flex-one d-flex flex-column justify-content-center align-items-center"><strong>50% </strong>&nbsp;{{ __('parrainage.reduction') }}&nbsp;<strong> {{ __('parrainage.sur_abonnement') }}</strong></p>
			</div>
		</div>
		<div class="envoie-email d-flex">
			<div class="form-envoie-email d-flex flex-column">
				<label>{{ __('parrainage.parrain_pers') }}</label>
				<div class="custom-input-group d-flex">
					<input id="input-parainage" type="text" name="filleul-email" placeholder="Adresse mail de votre filleul" class="flex-one">
					<button id="btn-parainage" class="filleul-email-send"></button>
				</div>
				<div class="div-infos-parainage show-infos">{{ __('parrainage.success') }}</div>
				<div class="div-error-parainage show-infos"></div>
			</div>
			<div class="comment-ca-marche">
				<h3>{{ __('parrainage.comment_marche') }}</h3>
				<p>{{ __('parrainage.how') }}</p>
			</div>
		</div>
	</section>
@endsection

