<link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648885163/css/notif.min_omyxa1.css" rel="stylesheet">
@stack('custom-scripts')
<script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648885275/js/message_notif_mmrxib.js"></script>
{{-- <script src="/js/message_notif.js"></script> --}}
@if(!empty(Route::currentRouteName()) && (Route::currentRouteName() == 'search.ad' || Route::currentRouteName() == 'searchdemande.ad'))
<!-- <div id="div-alert">
	<div class="modal-alert modal-body modal-body-notif modal-body-alert-success">
	@include("searchlisting.notif-alert-success")
	</div>
	<div id="modal-body-alert" style="display: block;" class="modal-alert modal-body modal-body-notif">
	@include("searchlisting.notif-alert")
	</div>
</div> -->
@endif

<div id="notif_directive_container" class="col-xs-12 col-sm-8 col-md-6">
	<input type="hidden" id="notif-visible" value="0"/>
	<div class="notif-content modal-notif-content">
		
		<div id="modal-body-notif-message" class="modal-body modal-body-notif">

		</div>
		<div id="modal-body-comment" class="modal-body modal-body-notif">

		</div>
		<div id="modal-body-notif-flash" class="modal-body modal-body-notif">

		</div>
		<div id="modal-body-visit" class="modal-body modal-body-notif">

		</div>
		<div id="modal-body-application" class="modal-body modal-body-notif">

		</div>
		<div id="modal-body-registration" class="modal-body modal-body-notif">

		</div>
		<div id="modal-body-subscription" class="modal-body modal-body-notif">

		</div>
	</div>
</div>

