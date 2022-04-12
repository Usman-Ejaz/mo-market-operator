@extends('admin.layouts.app')
@section('header', 'Slider Images')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.slider-images.index') }}">Slider Images</a></li>
<li class="breadcrumb-item active">Create</li>
@endsection


@section('content')
<div class="container-fluid">
	<form method="POST" action="{{ route('admin.slider-images.store') }}" enctype="multipart/form-data" id="create-slider-images-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Create Slider Image</h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->
					@include('admin.slider-images.form')

					<div class="card-footer text-right">
						<button type="submit" class="btn btn-primary width-120">Save</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection


@push('optional-styles')
<link rel="stylesheet" href="{{ asset('admin-resources/css/tempusdominus-bootstrap-4.min.css') }}">
@endpush

@push('optional-scripts')
<script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>

<script>
	//Date and time picker
	$(document).ready(function() {

		$.validator.addMethod("notNumericValues", function(value, element) {
			return this.optional(element) || isNaN(Number(value)) || value.indexOf('e') !== -1;
		}, '{{ __("messages.not_numeric") }}');

		$.validator.addMethod("ckeditor_required", function(value, element) {
			var editorId = $(element).attr('id');
			var messageLength = CKEDITOR.instances[editorId].getData().replace(/<[^>]*>/gi, '').length;
			return messageLength !== 0;
		}, '{{ __("messages.ckeditor_required") }}');

		$('#create-slider-images-form').validate({
			ignore: [],
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			rules: {
				slot_one: {
					required: true,
					maxlength: 64,
					minlength: 3,
					notNumericValues: true
				},
				slot_two: {
					required: true,
					maxlength: 100,
					minlength: 3,
					notNumericValues: true
				},
				url: {
					required: true,
					notNumericValues: true
				},
				order: {
					required: true,
				},
				image: {
					required: true,
					extension: "jpg|jpeg|png",
				}
			},
			messages: {
				slot_one: {
					minlength: "{{ __('messages.min_characters', ['field' => 'slot one', 'limit' => 3]) }}",
					required: "{{ __('messages.required') }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'slot one', 'limit' => 64]) }}"
				},
				slot_two: {
					minlength: "{{ __('messages.min_characters', ['field' => 'slot two', 'limit' => 3]) }}",
					required: "{{ __('messages.required') }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'slot two', 'limit' => 100]) }}"
				},
			}
		});
	});
</script>

@endpush