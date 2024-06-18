@extends('layouts.adminappinner')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Bitly<small>Token</small>
        </h1>
    </section>
    
    <!-- Main content -->
    <section class="content">
        @if ($message = Session::get('error'))
        <div class="alert alert-danger fade in alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            {{ $message }}
        </div>

        @endif
        @if ($message = Session::get('status'))
        <div class="alert alert-success fade in alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            {{ __('Information updated succesfully') }}
        </div>
        @endif
        <div class="row">
            <div class="col-xs-12">
                    <div class="row">
						<div class="col-md-12 show-message">
							<!-- general form elements -->
							<form id="edit-pixel-id" method="POST"  action="{{ route('admin.edit_bitly_token') }}" role="form">
								{{ csrf_field() }}
								<div class="box box-primary ">
									<div class="box-header with-border">
										<h3 class="box-title">Bl.ink</h3>
									</div>
									<div class="box-body">
										<div class="row">
											<div class="col-xs-6 col-sm-6 col-md-6">
												<div>
													<label>Nombre de phrase :</label>
													<input type="text" class="form-control" placeholder="Nombre" name="token" id="nb-url"  value="{{$nb}}" />
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="box-footer">
													<a id="btn-nb-url" href="javascript:" class="btn btn-default">{{ __('Ok') }}</a>
												</div>
											</div>
										</div>
										@foreach($phrases_link as $i => $shortUrl)
										<div id="div-{{$i}}" class="row url-content">
											<div class="col-xs-12 col-sm-6 col-md-6">
												<div>
													<span id="url-{{$i}}" class="blink_url">{{$shortUrl}}</span>
													<input type="button" data-id="{{$i}}" class="copy-button" value="Copier" name="">
												</div>
											</div>
										</div>
										@endforeach
									</div>
									<!-- <div class="box-body">
										<div class="row">
											<div class="col-xs-12 col-sm-6 col-md-6">
												<div>
													<label>Token <sup>*</sup></label>
													<input type="text" class="form-control" placeholder="token" name="token" id="p-title"  value="{{$token}}" />
													
													@if (isset($errors) && $errors->has('token'))
													<span style="color:red;" class="help-block">
														<strong>{{ __('Required field') }}</strong>
													</span>
													@endif
													<input type="hidden" name="last_Id" value="{{$token}}"/>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="box-footer">
												<a href="{{route('admin.packageList')}}" class="btn btn-default">{{ __('Cancel') }}</a>
												<button type="submit" class="btn btn-info">{{ __('Save') }}</button>
											</div>
										</div>
									</div> -->
								</div>
							</form>
							<!-- /.box -->
						</div>
				</div>
            </div>
        </div>
    </section>
</div> 
<style type="text/css">
	.blink_url
	{
		color: red;
	}

	.url-content
	{
		margin-top: 10px;
	}
</style>   
<script type="text/javascript">
	$(document).ready(function(){
		$('.copy-button').on('click', function(){
			var id = $(this).attr('data-id');
			var $temp = $("<input>");
			$("body").append($temp);
			$temp.val($('#url-' + id).text()).select();
			document.execCommand("copy");
			$temp.remove();
			$('#div-' + id).remove();
		});

		$('#btn-nb-url').on('click', function() {
			location.href = "?nb=" + $('#nb-url').val();
		});
	});
</script>
@endsection