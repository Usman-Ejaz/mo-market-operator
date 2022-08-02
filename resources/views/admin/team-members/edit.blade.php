@extends('admin.layouts.app')
@section('header', 'Team Members')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.managers.index') }}">Managers</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.team-members.index') }}">Team Members</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">

	<form method="POST" action="{{ route('admin.team-members.update', $teamMember->id) }}" enctype="multipart/form-data" id="update-team-members-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Edit Team Member - {{ $teamMember->name }}</h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->
					@method('PATCH')
					@include('admin.team-members.form')

                    <input type="hidden" name="removeImage" id="removeImage" value="0">

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

@push('optional-scripts')
<script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/form-custom-validator-methods.js') }}"></script>

<script>
	let oldFiles = [];
	$(document).ready(function() {

		$('#update-team-members-form').validate({
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
				manager_id: {
					required: true
				},
				order: {
					required: true,
					number: true
				},
				image: {
					// required: {
					// 	depends: function () {
					// 		return $(".imageExists").length > 0 ? false : true;
					// 	}
					// },
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
				},
                designation: {
					required: "{{ __('messages.required') }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Designation', 'limit' => 64]) }}"
				}
			}
		});

		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		$("#deleteImage").click(function() {

            $('#msg_heading').text('Delete record?');
            $('#msg_body').text('Are you sure you want to delete this image?');
            $('#confirmModal').modal('toggle');
            $('body').on('click', '#confirm', (e) => {
                $('#removeImage').val('1');
                $('#confirmModal').modal('toggle');
                $('.imageExists').remove();
                // $.ajax({
				// 	url: "{{ route('admin.team-members.deleteImage') }}",
				// 	type: 'POST',
				// 	data: {
				// 		_token: "{{ csrf_token() }}",
				// 		id: "{{ $teamMember->id }}"
				// 	},
				// 	dataType: 'JSON',
				// 	success: function(data) {
				// 		if (data.success) {
                //             $('#confirmModal').modal('toggle');
				// 			toastr.success(data.message);
				// 			$('.imageExists').remove();
				// 		}
				// 	}
				// });
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
