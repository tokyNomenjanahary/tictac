<a href="javascript:" class="close-notif"><span>x</span></a>
<div class="row">
	<p class="notif-alert-title">
		 <img width="30" height="20" src="/img/icons/notification-cloche1.png">{{ __('searchlisting.rate_auccun_annonce') }}
	</p>
	</div>
	<div class="row">
	<div class="text-notif-div">
		<input id="isAlert" type="hidden" value="0"/>
		<div class="notif-alert-text">
			<div class="txt-alert">{{ __('searchlisting.receive_annonce_mail') }}</div>
			<div>
				@if(!is_null(getParameter("idAlert")))
				<a id="button-alert-filter" class="create-alert-button" href="javascript:">{{ __('searchlisting.modif_alert') }}</a>
				@else
				<a id="button-alert-filter" class="create-alert-button" href="javascript:">{{ __('searchlisting.create_alert') }}</a>
				@endif
				<a id="button-alert-filter" class="create-alert-button" href="{{route('user.alerts')}}">{{ __('searchlisting.alert_list') }}</a>
			</div>
		</div>
	</div>
</div>