@extends('admin.layouts.app')
@section('header', 'Managers')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.managers.index') }}">Managers</a></li>
<li class="breadcrumb-item active">Create</li>
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

<script>
	//Date and time picker
	let oldFiles = [];
	$(document).ready(function() {

		$.validator.addMethod("notNumericValues", function(value, element) {
			return this.optional(element) || isNaN(Number(value)) || value.indexOf('e') !== -1;
		}, '{{ __("messages.not_numeric") }}');

		CKEDITOR.instances.description.on('blur', function(e) {
			var messageLength = CKEDITOR.instances.description.getData().replace(/<[^>]*>/gi, '').length;
			if (messageLength !== 0) {
				$('#cke_description').next().hasClass("my-error-class") && $('#cke_description').next().remove();
			}
		});	

		$.validator.addMethod("ckeditor_required", function(value, element) {
			var editorId = $(element).attr('id');
			var messageLength = CKEDITOR.instances[editorId].getData().replace(/<[^>]*>/gi, '').length;
			return messageLength !== 0;
		}, '{{ __("messages.ckeditor_required") }}');

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
					notNumericValues: true
				},
				description: {
					ckeditor_required: true
				},
				designation: {
					required: true
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