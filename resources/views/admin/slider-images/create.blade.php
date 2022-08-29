@extends('admin.layouts.app')
@section('header', 'Create Slider Image')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.slider-images.index') }}">Slider Images</a></li>
<li class="breadcrumb-item active">Create Slider Image</li>
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
<script src="{{ asset('admin-resources/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/form-custom-validator-methods.js') }}"></script>

<script>
	// Date and time picker
	let oldFiles = [];
	$(document).ready(function() {

		$('#create-slider-images-form').validate({
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
					required: true,
					extension: "{{ config('settings.image_file_extensions') }}"
				}
			},
			messages: {
				slot_one: {
					minlength: "{{ __('messages.min_characters', ['field' => 'Slot one', 'limit' => 3]) }}",
					required: "{{ __('messages.required') }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Slot one', 'limit' => 64]) }}"
				},
				slot_two: {
					minlength: "{{ __('messages.min_characters', ['field' => 'Slot two', 'limit' => 3]) }}",
					required: "{{ __('messages.required') }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Slot two', 'limit' => 64]) }}"
				},
				image: {
					required: "{{ __('messages.required') }}",
					extension: "{{ __('messages.valid_image_extension') }}"
				}
			}
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
