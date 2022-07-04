@extends('admin.layouts.app')
@section('header', 'Managers')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.managers.index') }}">Managers</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">

	<form method="POST" action="{{ route('admin.managers.update', $manager->id) }}" enctype="multipart/form-data" id="update-managers-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Edit Manager Profile - {{ $manager->name }}</h3>
					</div>

					@method('PATCH')
					@include('admin.managers.form')

					<div class="card-footer text-right">
						<button type="submit" class="btn btn-primary draft_button width-120">Update</button>
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
	let oldFiles = [];
	$(document).ready(function() {

		$('#update-managers-form').validate({
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
                    // prevent_special_characters: true
				},
				order: {
					required: true,
					number: true
				},
				image: {
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

		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		$("#deleteImage").click(function() {
			if (confirm('Are you sure you want to this image?')) {
				$.ajax({
					url: "{{ route('admin.managers.deleteImage') }}",
					type: 'POST',
					data: {
						_token: "{{ csrf_token() }}",
						id: "{{ $manager->id }}"
					},
					dataType: 'JSON',
					success: function(data) {
						if (data.success) {
							toastr.success(data.message);
							$('.imageExists').remove();
						}
					}
				});
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
