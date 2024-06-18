<div id="flashModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center"> <a href="javascript:" id="list-flash-button"> <img style="margin-right: 10px;margin-top : -5px;" width="40" height="30" src="/img/message-flash.png">{{__('header.coup_de_foudre')}}
                          (<span id="nb_total_flash">0</span>)</a></h4>
            </div>
            <div class="modal-body">
            	<div id="all-flash-content">
            		
            	</div>	
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
	$('#list-flash-button').on("click", function(){
		$.ajax({
            type: "POST",
            url: '/flash/get_all_message_flash',
            success: function (data) {
            	$(".count-glyph-flash").hide();
            	if(!$.isEmptyObject(data)) {
            		$("#all-flash-content").html(data.html);
            		$('#nb_total_flash').text(data.nb);
            	} 
            	
                $('#flashModal').modal("show");
            },
            complete: function () {
            }
        });
		
	});


});
</script>