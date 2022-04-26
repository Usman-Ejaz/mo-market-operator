@extends('admin.layouts.app')
@section('header', 'Slider Settings')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.slider-images.index') }}">Slider Images</a></li>
<li class="breadcrumb-item active">Slider Settings</li>
@endsection

@section('content')
<div class="container-fluid">

	<form method="POST" action="{{ route('admin.slider-settings.update', $sliderSetting->id) }}" enctype="multipart/form-data" id="update-slider-settings-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Edit Slider Settings</h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->
					@method('PATCH')
					@include('admin.slider-settings.form')

					<div class="card-footer text-right">
						<button type="submit" class="btn btn-primary draft_button width-120">Update</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection

@push('optional-styles')

@endpush

@push('optional-scripts')
<script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>

<script>
	$(document).ready(function() {

		$.validator.addMethod("notNumericValues", function(value, element) {
			return this.optional(element) || isNaN(Number(value)) || value.indexOf('e') !== -1;
		}, '{{ __("messages.not_numeric") }}');

		$('#update-slider-settings-form').validate({
			ignore: [],
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			rules: {
				transition: {
					required: true,
				},
				speed: {
					required: true,
					maxlength: 255,
					minlength: 3,
					// notNumericValues: true
				},
			},
		});
	});
</script>

@endpush