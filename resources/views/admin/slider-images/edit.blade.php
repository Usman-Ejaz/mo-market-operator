@extends('admin.layouts.app')
@section('header', 'Edit Slider Image')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.slider-images.index') }}">Slider Images</a></li>
<li class="breadcrumb-item active">Edit Slider Image</li>
@endsection

@section('content')
<div class="container-fluid">

	<form method="POST" action="{{ route('admin.slider-images.update', $sliderImage->id) }}" enctype="multipart/form-data" id="update-slider-images-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Edit Slider Image - {{ $sliderImage->slot_one }}</h3>
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
@include('admin.includes.confirm-popup')
@endsection

@push('optional-styles')

@endpush

@push('optional-scripts')
<script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/form-custom-validator-methods.js') }}"></script>

<script>
	let oldFiles = [];
	$(document).ready(function() {

		$('#update-slider-images-form').validate({
			ignore: [],
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
            onfocusout: false,
            onkeyup: false,
			rules: {
				slot_one: {
					required: true,
					maxlength: 64,
					minlength: 3,
					notNumericValues: true,
                    // prevent_special_characters: true
				},
				slot_two: {
					required: true,
					maxlength: 64,
					minlength: 3,
					notNumericValues: true,
                    // prevent_special_characters: true
				},
				url: {
					required: true,
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
					required: "{{ __('messages.required') }}",
					minlength: "{{ __('messages.min_characters', ['field' => 'Slot one', 'limit' => 3]) }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Slot one', 'limit' => 64]) }}"
				},
				slot_two: {
					required: "{{ __('messages.required') }}",
					minlength: "{{ __('messages.min_characters', ['field' => 'Slot two', 'limit' => 3]) }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Slot two', 'limit' => 64]) }}"
				},
				image: {
					required: "{{ __('messages.required') }}",
					extension: "{{ __('messages.valid_image_extension') }}"
				}
			}
		});

		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		$("#deleteImage").click(function() {
            $('#msg_heading').text('Delete record?');
            $('#msg_body').text('Are you sure you want to delete this image?');
            $('#confirmModal').modal('toggle');
            $('body').on('click', '#confirm', (e) => {
                $('.imageExists').remove();
                $('#confirmModal').modal('toggle');
            });
		});

		$(document).on('focusin', 'input[type="file"]', function(e){
			oldFiles = e.target.files;
		});
	});

	function handleFileChoose (e)
	{
		if (e.target.files.length === 0) {
			e.preventDefault();
			e.target.files = oldFiles;
			return false;
		}
	}
</script>

@endpush
