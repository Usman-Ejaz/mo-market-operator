@extends('admin.layouts.app')
@section('header', 'Create Manager')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.managers.index') }}">Managers</a></li>
<li class="breadcrumb-item active">Create Manager</li>
@endsection

@section('content')
<div class="container-fluid">

	<form method="POST" action="{{ route('admin.managers.store') }}" enctype="multipart/form-data" id="create-managers-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Create Manager</h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->
					@include('admin.managers.form')

					<!-- /.card-body -->
					<div class="card-footer text-right">
						<button type="submit" class="btn btn-primary width-120">Save</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection


@push('optional-scripts')
<script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/form-custom-validator-methods.js') }}"></script>

<script>
	//Date and time picker
	let oldFiles = [];
	$(document).ready(function() {

		$('#create-managers-form').validate({
			ignore: [],
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			rules: {
				name: {
					required: true,
					maxlength: 64,
					minlength: 3,
					notNumericValues: true,
                    // prevent_special_characters: true
				},
				description: {
					ckeditor_required: true
				},
				designation: {
                    required: true,
                    notNumericValues: true,
                    maxlength: 64,
                    // prevent_special_characters: true,
				},
				order: {
					required: true,
					number: true
				},
				image: {
					// required: true,
					extension: "{{ config('settings.image_file_extensions') }}"
				}
			},
			errorPlacement: function(error, element) {
				if (element.attr("id") == "image") {
					element.next().text('');
				}
				if (element.attr("id") == "description") {
					element = $("#cke_" + element.attr("id"));
				}
				error.insertAfter(element);
			},
			messages: {
				image: '{{ __("messages.valid_image_extension") }}',
				name: {
					required: "{{ __('messages.required') }}",
					minlength: "{{ __('messages.min_characters', ['field' => 'Name', 'limit' => 3]) }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Name', 'limit' => 64]) }}"
				},
                designation: {
                    required: "{{ __('messages.required') }}",
                    maxlength: "{{ __('messages.max_characters', ['field' => 'Designation', 'limit' => 64]) }}"
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
