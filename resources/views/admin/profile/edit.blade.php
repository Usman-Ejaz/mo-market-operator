@extends('admin.layouts.app')
@section('header', 'Profile')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Profile</li>
@endsection

@section('content')
<div class="container-fluid">

	<form method="POST" action="{{ route('admin.profile.update', $user->id) }}" enctype="multipart/form-data" id="update-users-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Update Profile - {{ $user->name }}</h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->
					@method('PATCH')
					@include('admin.profile.form')
					<input type="hidden" name="sendEmail" value="0" id="sendEmail">
					<div class="card-footer text-right">
						<button type="submit" class="btn btn-primary draft_button">Update</button>
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
					email: true,
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
					minlength: "{{ __('messages.min_characters', ['field' => 'Username', 'limit' => 3]) }}",
					required: "This field is required.",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Username', 'limit' => 64]) }}"
				}
			}
		});

		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		$("#deleteImage").click(function() {

			if (confirm('Are you sure you want to delete this image?')) {
				$.ajax({
					url: "{{ route('admin.profile.deleteImage') }}",
					type: 'POST',
					data: {
						_token: "{{ csrf_token() }}",
						user_id: "{{$user->id}}"
					},
					dataType: 'JSON',
					success: function(data) {
						if (data.success) {
							toast.success('{{ __("messages.record_deleted", ["module" => "Image"]) }}');
							$('.imageExists').remove();
						}
					}
				});
			}
		});


	});
</script>

@endpush
