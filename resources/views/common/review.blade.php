
<?php $userAvis = getUserAvis();?>
<div id="reviewModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center">{{ __('header.avis_title') }}</h4>
            </div>
            <div class="modal-body">
					<input type="hidden" id="avis_action" @if(!is_null($userAvis)) value="edit" @endif/>
					<div id="div_avis" class="avis_stars" style="padding-bottom : 20px;width : 250px;margin : auto;">
						@if(is_null($userAvis))
						<a  href="javascript:" data-toggle="tooltip" data-placement="top" title="{{__('header.tooltip_mauvais')}}" >
							<img data-id="1" class="stars_avis" width="40" height="40" src="/img/empty_stars_avis.png">
						</a>
						<a href="javascript:" data-toggle="tooltip" data-placement="top" title="{{__('header.tooltip_acceptable')}}" >
							<img data-id="2" class="stars_avis" width="40" height="40" src="/img/empty_stars_avis.png">
						</a>
						<a  href="javascript:" data-toggle="tooltip" data-placement="top" title="{{__('header.tooltip_correct')}}">
							<img data-id="3" class="stars_avis" width="40" height="40" src="/img/empty_stars_avis.png">
						</a>
						<a  href="javascript:" data-toggle="tooltip" data-placement="top" title="{{__('header.tooltip_tres_bien')}}" >
							<img data-id="4" class="stars_avis" width="40" height="40" src="/img/empty_stars_avis.png">
						</a>
						<a  href="javascript:" data-toggle="tooltip" data-placement="top" title="{{__('header.tooltip_excellent')}}" >
							<img data-id="5" class="stars_avis" width="40" height="40" src="/img/empty_stars_avis.png">
						</a>
						@else
							@for($i=1; $i <= 5; $i++)
								@if($i < $userAvis->notes)
									<a  href="javascript:" data-toggle="tooltip" data-placement="top" title="{{getToolTipAvis($i)}}" >
										<img data-id="{{$i}}" class="stars_avis" width="40" height="40" src="/img/filled_stars_avis.png">
									</a>
								@endif
								@if($i == $userAvis->notes)
									<a  href="javascript:" data-toggle="tooltip" data-placement="top" title="{{getToolTipAvis($i)}}" >
										<img data-id="{{$i}}" class="stars_avis selected" width="40" height="40" src="/img/filled_stars_avis.png">
									</a>
								@endif
								@if($i > $userAvis->notes)
									<a  href="javascript:" data-toggle="tooltip" data-placement="top" title="{{getToolTipAvis($i)}}" >
										<img data-id="{{$i}}" class="stars_avis" width="40" height="40" src="/img/empty_stars_avis.png">
									</a>
								@endif
							@endfor
						@endif
					</div>
                    <div class="form-group">
                        <textarea id="avisComment" class="form-control" placeholder="{{ __('header.avis_placeholder') }}" rows="5" id="comment" style="max-width:100%;">@if(!is_null($userAvis)) {{$userAvis->comments}} @endif</textarea>
					</div>
                    <div class="pr-poup-ftr">
                        <div class="submit-btn-2"><a data-dismiss="modal" href="javascript:void(0);">{{ __('header.cancel') }}</a></div>
                        <div class="submit-btn-2"><input id="okAvis" type="submit" class="submit-btn-2 reg-next-btn" value="{{ __('header.ok') }}"></div>
                    </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
	$("#my_review_button").on('click', function(){
		$('#reviewModal').modal("show");
	});
	
	$(".my_review_button").on('click', function(){
		$('#reviewModal').modal("show");
	});
	
	$('.stars_avis').hover(function() {
		var i = $(this).attr("data-id");
	   $("#div_avis").find(".stars_avis").each(function(index, item){
		   if($(this).attr("data-id") <= i) {
				$(this).attr("src", "{{URL::asset('img/filled_stars_avis.png')}}");
		   } else {
				$(this).attr("src", "{{URL::asset('img/empty_stars_avis.png')}}");
		   }
	   });
	});
	
	$('.stars_avis').mouseout(function() {
		var i = $(this).attr("data-id");
		if(!$(this).hasClass("selected")) {
			$("#div_avis").find(".stars_avis").each(function(index, item){
			   if($(this).attr("data-id") <= i) {
					$(this).attr("src", "{{URL::asset('img/empty_stars_avis.png')}}");
			   }
		   });
		   
		   i = $(".selected").attr("data-id");
		   if(i!=null) {
				$("#div_avis").find(".stars_avis").each(function(index, item){
				   if($(this).attr("data-id") <= i) {
						$(this).attr("src", "{{URL::asset('img/filled_stars_avis.png')}}");
				   }
			   });
		   }
		}
	   
	});
	
	$('.stars_avis').on("click",function() {
	   var i = $(this).attr("data-id");
	   $("#div_avis").find(".stars_avis").each(function(index, item){
		   $(this).removeClass("selected");
		   if($(this).attr("data-id") <= i) {
				$(this).attr("src", "{{URL::asset('img/filled_stars_avis.png')}}");
		   }
	   });
	   $(this).addClass("selected");
	});
	
	$('#okAvis').on("click",function() {
		var notes = $(".selected").attr("data-id");
		if(notes != null) {
			$.ajaxSetup({
			  headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			  }
			});
			$.ajax({
				url: '/donner_avis',
				type: "post",
				dataType: "json",
				data: {"action" : $("#avis_action").val(),"notes" : $(".selected").attr("data-id"), 'comment' : $("#avisComment").val()}
			}).done(function (data){
				$('#reviewModal').modal("hide");
				location.reload();
			}).fail(function (jqXHR, ajaxOptions, thrownError){
				alert('No response from server');
			});
		} else {
			alert("{{__('header.no_notes_message')}}");
		}
	    
	});
	
});
</script>