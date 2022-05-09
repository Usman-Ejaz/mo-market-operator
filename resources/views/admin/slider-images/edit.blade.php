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
						<h3 class="card-title">Edit Slider Image - {{ truncateWords($sliderImage->slot_one, 30) }}</h3>
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

		$('#update-slider-images-form').validate({
			ignore: [],
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			rules: {
				slot_one: {
					required: true,
					maxlength: 255,
					minlength: 3,
					notNumericValues: true
				},
				slot_two: {
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
					required: {
						depends: () => {
							return $(".imageExists").length > 0 ? false : true;
						}
					},
					extension: "{{ config('settings.image_file_extensions') }}"
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

		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		$("#deleteImage").click(function() {

			if (confirm('Are you sure you want to delete this image?')) {
				$('.imageExists').remove();
				return;
				// $.ajax({
				// 	url: "{{ route('admin.slider-images.deleteImage') }}",
				// 	type: 'POST',
				// 	data: {
				// 		_token: "{{ csrf_token() }}",
				// 		slider_id: "{{ $sliderImage->id }}"
				// 	},
				// 	dataType: 'JSON',
				// 	success: function(data) {
				// 		if (data.success) {
				// 			toastr.success(data.message);							
				// 			$('.imageExists').remove();
				// 		}
				// 	}
				// });
			}
		});
	});
</script>

@endpush