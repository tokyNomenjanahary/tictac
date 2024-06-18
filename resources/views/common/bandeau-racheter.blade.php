<link href="/css/bandeau-message.css" rel="stylesheet">
<div class="bandeau-message">
    <!--<div class="message-demande">{{__("bandeau.message")}}<a href="javascript:" class="button-close-bandeau" class="btn">x</a></div>-->
    <div class="message-demande">
    {{ __('property.rachat_message') }}
     <div class="div-standard-post"><a data-dismiss="bandeau-message" class="button-close-message-bandeau standard-post-button" href="javascript:">{{__('property.close')}}</a></div>
    <a href="javascript:" class="button-close-message-bandeau button-close-bandeau" class="btn">x</a>
    </div>
</div>
<script>
	$(document).ready(function(){
		$(".bandeau-message").animate({
            height: 'toggle'
        });

		$(".button-close-message-bandeau").on('click', function(){
			$(".bandeau-message").animate({
	            height: 'toggle'
	        });
		});
	});
</script>
