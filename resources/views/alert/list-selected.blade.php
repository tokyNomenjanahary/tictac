<div class="request-bx-1 m-t-2"  id="document_content">
	<div style="width:100%;margin:auto;">
    @if(isset($savedAlerts) && !empty($savedAlerts))
		@foreach($savedAlerts as $key => $savedAlert)
			<a href="javascript:void(0);" class="saved_document">
				<div class="card card-alert" style="margin-bottom:10px;">
				  <div style="position:relative;">
						<span class="badge shoppingBadge shoppingBadge-custom" style="background-color:rgb(51,122,183);position:absolute;right:5px;top:0px;">{{ __('alert.alerte') }} {{$key+1}}</span>
						<input id="doc-{{$savedAlert->id}}" data-id="{{$savedAlert->id}}" type="checkbox" class="checkDocument" style="display:none;width:20px;height:20px;position:absolute;left:5px;top:0px;"/>
						<img class="card-img-top img-list-alert" width="80" height="80" src="/img/alert-img.png" alt="Card image cap">
				 </div>
				 <div class="card-body" style="margin-top:5px;">
				 Le {{date("d/m/Y", strtotime(adjust_gmt($savedAlert->date)))}} à {{date("H:i", strtotime(adjust_gmt($savedAlert->date)))}}
				 </div>
				 <div class="card-body" style="margin-top:5px;">

				 	{{getScenarioAlert(getFilterElement("scenario_id", $savedAlert->filtres))}} à {{getFilterElement("address", $savedAlert->filtres)}}
				 </div>
				  <div class="card-body" style="margin-top:5px;">
					 <a target="_blank" href="/view-alertes/{{$savedAlert->id}}" class="btn btn-primary view-card-alert"><span class="glyphicon glyphicon-eye-open" style="font-size:15px;"></a>
					 <a data-href="/delete-alert/{{$savedAlert->id}}" href="javascript:" class="btn btn-danger delete-card-alert delete-alert"><span class="glyphicon glyphicon-trash" style="font-size:15px;;"></a>
					@if($savedAlert->is_email == 1)
				  	<a title="Désactiver l'alerte par email" class="action-toggle-on action-email" href="/desactiver-email/{{$savedAlert->id}}"><i class="fa fa-toggle-on"></i> {{ __('alert.alert_par_mail') }}</a>
				  	@else
				  	<a title="Activer l'alerte par email" class="action-toggle-off action-email" href="/activer-email/{{$savedAlert->id}}"><i class="fa fa-toggle-off"></i> {{ __('alert.alert_par_mail') }}</a>
				  	@endif
				  </div>
				  
				</div>
			</a>
		@endforeach
	@endif
	</div>
</div>
<style>
	.card-alert
	{
		padding: 10px;
    	border: 1px rgb(68,106,228) solid;
    	width: 100%;
	}

	.img-list-alert
	{
		margin-top: 18px;
	}
	.view-card-alert
	{
		display: inline-block;
		width: 100px;
	}

	.delete-card-alert
	{
		display: inline-block;
		width: 100px;
		margin-right: 20px;
	}

	.action-toggle-on i
	{
		color: green;
	}

	.action-toggle-off i
	{
		color: red;
	}

	@media screen and (max-width: 400px) {
		.action-email
		{
			display: block;
			margin-top : 10px;
		}
	}
</style>
