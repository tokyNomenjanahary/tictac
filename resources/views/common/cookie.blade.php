
<link href="/css/cookie.min.css" rel="stylesheet">
@stack('custom-scripts')
<script src="/js/jquery.cookie.min.js"></script>
<script src="/js/cookie.min.js"></script>

<div id="cookie_directive_container" class="modal-dialog  col-xs-12 col-sm-8 col-md-6">  
	<div class="modal-content modal-cookie-content">
	<div class="modal-body">
		<p>{{__("cookie.cookie_message")}} <a href="javascript:">{{ __("cookie.learn") }}</a></p>
		<div id="wrapper"><a href="/politique-de-confidentialite" id="button_cookie" class="btn">{{ __("cookie.ok") }}</a>
		</div>
	</div>
	</div>
</div>