@extends('admin.layouts.app')
@section('header', 'Users')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">

	<form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data" id="update-users-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Edit User - {{ $user->name }}</h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->
					@method('PATCH')
					@include('admin.users.form')
					<input type="hidden" name="sendEmail" value="0" id="sendEmail">
					<input type="hidden" name="removeImage" value="0" id="removeImage">
					<div class="card-footer text-right">
						<button type="submit" class="btn btn-primary draft_button width-120">Update</button>
						{{-- <button type="submit" class="btn btn-success mr-2 save-with-email">Update & Send Email</button> --}}
					</div>
				</div>
			</div>

		</div>
	</form>

</div>
@endsection

@push('optional-scripts')
<script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/form-custom-validator-methods.js') }}"></script>

<script>
	let oldFiles = [];
	$(document).ready(function() {

		$(".save-with-email").click(function() {
			$("#sendEmail").val("1");
		});

		$('#update-users-form').validate({
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
				email: {
					required: true,
					// validEmailAddress: true,
					notNumericValues: true,
				},
				role_id: {
					required: true,
					number: true
				},
				department: {
					required: true,
					number: true
				},
				designation: {
					required: true,
					maxlength: 64,
					minlength: 3,
					notNumericValues: true,
                    // prevent_special_characters: true
				},
				image: {
					extension: "{{ config('settings.image_file_extensions') }}"
				},
				active: {
					required: true,
				}
			},
			errorPlacement: function(error, element) {
				if (element.attr("id") == "image") {
					element.next().text('');
				}
				error.insertAfter(element);
			},
			messages: {
				image: '{{ __("messages.valid_image_extension") }}',
				name: {
					minlength: "{{ __('messages.min_characters', ['field' => 'Name', 'limit' => 3]) }}",
					required: "{{ __('messages.required') }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Name', 'limit' => 64]) }}"
				}
			}
		});

		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		$("#deleteImage").click(function() {

			if (confirm('Are you sure you want to this image?')) {
				$('#removeImage').val('1');
				$('.imageExists').remove();
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
