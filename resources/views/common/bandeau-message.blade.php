<link href="/css/bandeau-message.css" rel="stylesheet">
<div class="bandeau-message">
    <!--<div class="message-demande">{{__("bandeau.message")}}<a href="javascript:" class="button-close-bandeau" class="btn">x</a></div>-->
    <div class="message-demande">
    {{ __('property.standard_version_message') }}
    <div class="div-standard-post"><a class="standard-post-button" href="{{enleveInscriptionPath()}}?standard=true">{{__('property.use_standard')}}</a></div>
    <a href="javascript:" class="button-close-bandeau" class="btn">x</a>
    </div>
</div>
@push("scripts")
<script>
	$(document).ready(function(){
		$(".bandeau-message").animate({
            height: 'toggle'
        });

		$(".button-close-bandeau").on('click', function(){
			$(".bandeau-message").animate({
	            height: 'toggle'
	        });
		});
	});
</script>
@endpush
