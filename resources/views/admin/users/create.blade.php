@extends('admin.layouts.app')
@section('header', 'Users')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
<li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="container-fluid">

	<form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" id="create-users-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Create User</h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->
					@include('admin.users.form')
					
					<input type="hidden" name="sendEmail" value="0" id="sendEmail">
					<!-- /.card-body -->
					<div class="card-footer text-right">
						<button type="submit" class="btn btn-primary width-120">Save</button>
						<button type="submit" class="btn btn-success mr-2 save-with-email">Save & Send Email</button>
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

<script>
	//Date and time picker
	let oldFiles = [];
	$(document).ready(function() {

		$.validator.addMethod("notNumericValues", function(value, element) {
			return this.optional(element) || isNaN(Number(value)) || value.indexOf('e') !== -1;
		}, '{{ __("messages.not_numeric") }}');

		$(".save-with-email").click(function() {
			$("#sendEmail").val("1");
		});

		$('#create-users-form').validate({
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			rules: {
				name: {
					required: true,
					maxlength: 64,
					minlength: 3,
					notNumericValues: true
				},
				email: {
					required: true,
					email: true,
					notNumericValues: true
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
					notNumericValues: true
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