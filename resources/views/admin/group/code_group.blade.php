@extends('layouts.adminappinner')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Group<small>Access code</small>
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
							<form id="edit-pixel-id" method="POST"  action="{{ route('admin.edit_code_group') }}" role="form">
								{{ csrf_field() }}
								<div class="box box-primary ">
									<div class="box-header with-border">
										<h3 class="box-title">Edit Group access code</h3>
									</div>
									<div class="box-body">
										<div class="row">
											<div class="col-xs-12 col-sm-6 col-md-6">
												<div>
													<label>Code <sup>*</sup></label>
													<input type="text" class="form-control" placeholder="Code group" name="code_group" id="p-title"  value="{{$codeGroup}}" />
													
													@if (isset($errors) && $errors->has('code_group'))
													<span style="color:red;" class="help-block">
														<strong>{{ __('Required field') }}</strong>
													</span>
													@endif
													<input type="hidden" name="last_Id" value="{{$codeGroup}}"/>
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
									</div>
								</div>
							</form>
							<!-- /.box -->
						</div>
				</div>
            </div>
        </div>
    </section>
</div>    
@endsection