@extends('admin.layouts.app')
@section('header', 'Slider Images')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.slider-images.index') }}">Slider Images</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">

	<form method="POST" action="{{ route('admin.slider-images.update', $sliderImage->id) }}" enctype="multipart/form-data" id="update-slider-images-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Edit Slider Image - {{ truncateWords($sliderImage->block_one, 30) }}</h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->
					@method('PATCH')
					@include('admin.slider-images.form')

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
				block_one: {
					required: true,
					maxlength: 255,
					minlength: 3,
					notNumericValues: true
				},
				block_two: {
					required: true,
					maxlength: 255,
					minlength: 3,
					notNumericValues: true
				},
				url: {
					required: true,
					maxlength: 255,
					minlength: 3,
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
				name: {
					minlength: "{{ __('messages.min_characters', ['field' => 'Name', 'limit' => 3]) }}",
					required: "{{ __('messages.required') }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Name', 'limit' => 64]) }}"
				},
				contents: {
					minlength: "{{ __('messages.min_characters', ['field' => 'Contents', 'limit' => 3]) }}",
					ckeditor_required: "{{ __('messages.required') }}",
				}
			}
		});
	});
</script>

@endpush