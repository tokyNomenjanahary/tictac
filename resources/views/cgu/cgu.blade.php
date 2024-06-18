@extends('layouts.app')

@section('content')
<section class="inner-page-wrap">
    <div class="container">
        <div class="row">
            <?php echo $page->description; ?>
        </div>
    </div>
</section>

<div id="persoDataModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center">{{ __('cgu.title_modal') }}</h4>
            </div>
            <div class="modal-body">
                    <div class="form-group">
						<label class="control-label">{{ __('login.mail') }} <sup>*</sup></label>
                        <input class="form-control" placeholder="{{ __('login.mail') }}" type="text" name="email" id="email_address" value="" autofocus />
                    </div>

					<div class="pr-poup-ftr">
                        <div class="submit-btn-2"><a data-dismiss="modal" href="javascript:void(0);">{{ __('header.cancel') }}</a></div>
                        <div class="submit-btn-2"><input id="sendPersoData" type="submit" class="submit-btn-2 reg-next-btn" value="{{ __('header.ok') }}"></div>
                    </div>
            </div>
        </div>
    </div>
</div>
<div id="infos-modal" class="modal ">
    <div class="modal-dialog ">
			<div class="modal-content ">
				<div class="modal-body" >
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h5 style="font-size : 1.2em;" id="modal-news-text" class="modal-title text-center">{{__('cgu.alert')}} <span class="mail_send"></span></h5>
				</div>
			</div>
		</div>
	</div>
<script>
$("#btn_download").on("click", function(){
	$("#persoDataModal").modal("show");
 });

$('#sendPersoData').on("click",function() {
	$.ajaxSetup({
	  headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	});
	$.ajax({
		url: '/get-personal-data',
		type: "post",
		dataType: "json",
		data: {"mail" : $("#email_address").val()}
	}).done(function (data){
		$(".mail_send").text($("#email_address").val());
		$('#persoDataModal').modal("hide");
		$('#infos-modal').modal("show");

	}).fail(function (jqXHR, ajaxOptions, thrownError){
		alert('No response from server');
	});
});

</script>
@endsection

