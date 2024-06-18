
<link href="{{ asset('css/pub.min.css') }}" rel="stylesheet">
@push('scripts')
<script src="{{ asset('js/soiree.min.js') }}"></script>
@endpush
<div id="pub_directive_container" class="modal-dialog  col-xs-12 col-sm-8 col-md-6">

	<div class="modal-content modal-cookie-content">
	<div class="modal-body">

		<a target="_blank" data-id="6" href="<?php echo i18n('url_telegram'); ?>"><img class="img-soiree" src="/img/telegram.png" height="200"/></a>
		<p><?php echo __("pub.pub_message") ?></p>
		<div id="wrapper">
			<a target="_blank" data-id="6" href="<?php echo i18n('url_telegram'); ?>" id="register_button" class="btn button_pub">{{ __("pub.inscrire") }}</a>
			<a href="javascript:" id="pub_close_button" class="btn button_pub">{{ __("pub.close") }}</a>
			
		</div>

	</div>
	</div>
</div>