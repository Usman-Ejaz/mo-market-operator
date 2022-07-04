@extends('admin.layouts.app')
@section('header', 'Documents')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Documents</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@push('optional-styles')
<link rel="stylesheet" href="{{ asset('admin-resources/css/bootstrap-tagsinput.css') }}" />
<style type="text/css">
        .bootstrap-tagsinput{
            width: 100%;
            padding: 7px 6px !important;
        }
        .label-info{
            background-color: #17a2b8;
        }
        .label {
            display: inline-block;
            padding: .25em .4em;
            font-size: 85%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,
            border-color .15s ease-in-out,box-shadow .15s ease-in-out;
            white-space: break-spaces !important;
            max-width: 63em;
            margin: 0px 0px 5px 0px;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
	<form method="POST" action="{{ route('admin.documents.update', $document->id) }}" enctype="multipart/form-data" id="update-document-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Edit Document - {{ $document->title }}</h3>
					</div>
					@method('PATCH')
					@include('admin.documents.form')

					<div class="card-footer">
						<div class="float-right">

							<input type="hidden" name="action" id="action">
							<input type="hidden" name="removeFile" id="removeFile">

							@if ($document->isPublished())
								<button type="submit" class="btn width-120 btn-primary update_button">Update</button>
								@if (hasPermission('documents', 'publish'))
									<button type="submit" class="btn width-120 btn-danger unpublish_button">Unpublish</button>
								@endif
							@else
								<button type="submit" class="btn width-120 btn-primary draft_button">Update</button>
								@if (hasPermission('documents', 'publish'))
									<button type="submit" class="btn width-120 btn-success publish_button">Publish</button>
								@endif
							@endif
						</div>
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
<script src="{{ asset('admin-resources/js/bootstrap-tagsinput.js') }}"></script>
<script src="{{ asset('admin-resources/js/form-custom-validator-methods.js') }}"></script>
<script>
	let oldFiles = [];
	$(document).ready(function() {

		$('.draft_button').click(function(e) {
			$('#action').val("Updated");
		});

		$('.publish_button').click(function(e) {
			$('#action').val("Published");
		});

		$('.update_button').click(function(e) {
			$('#action').val("Updated");
		});

		$('.unpublish_button').click(function(e) {
			$('#action').val("Unpublished");
		});

		$('#update-document-form').validate({
			ignore: [],
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			rules: {
				title: {
					required: true,
					minlength: 3,
					maxlength: 255,
					notNumericValues: true,
                    prevent_special_characters: true
				},
				category_id: {
					required: true,
				},
				keywords: {
                    maxlength: {
                        depends: () => {
                            let tags = $('#keywords').val().split(',');
                            return tags.filter(tag => tag.length > 64).length > 0 ? 64 : 0;
                        }
                    }
                },
				image: {
					required: {
						depends: function () {
							return $(".imageExists").length > 0 ? false : true;
						}
					},
					extension: '{{ config("settings.image_file_extensions") }}'
				},
				'file[]': {
					required: {
						depends: function() {
							return $(".fileExists").length > 0 ? false : true;
						}
					},
					docx_extension: 'doc|docx|txt|ppt|pptx|csv|xls|xlsx|pdf|odt',
				}
			},
			messages: {
				'file[]': {
					required: "{{ __('messages.required') }}",
					docx_extension: '{{ __("messages.valid_file_extension") }}'
				},
				image: {
					required: "{{ __('messages.required') }}",
					extension: '{{ __("messages.valid_image_extension") }}'
				},
				title: {
					required: "{{ __('messages.required') }}",
					minlength: "{{ __('messages.min_characters', ['field' => 'Title', 'limit' => 3]) }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Title',  'limit' => 255]) }}"
				},
                keywords: {
					maxlength: "{{ __('messages.max_characters', ['field' => 'Keywords', 'limit' => 64]) }}"
				}
			}
		});

		let paths = [];
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		$(".remove-file").on('click', function() {
			let { path } = $(this).data();

			if (confirm('Are you sure you want to delete this file?')) {
				paths.push(path);
				$(this).parent().parent().remove();
				// $('.fileExists').remove();
				$("#removeFile").val(`${paths.toString()}`);
			}
		});

		$('#deleteImage').on("click", function () {
			if (confirm('Are you sure you want to delete this image?')) {
				$(this).parent().remove();
				// $("#removeFile").val(`${paths.toString()}`);
			}
		});

		$('.bootstrap-tagsinput > input').on('blur keypress', function (e) {
			if ((e.which === 13 && $(this).val().trim().length > 0) || document.getElementsByClassName('label-info').length > 0) {
				$(this).attr('placeholder', '');
				return;
			}

			$(this).attr('placeholder', '{{ __("Enter keywords") }}');
		});

		if (document.getElementsByClassName('label-info').length > 0) {
			$('.bootstrap-tagsinput > input').attr('placeholder', '');
		}

		$(document).on('focusin', 'input[type="file"]', function(e){
			oldFiles = e.target.files;
		});
	});

	function validateFileExtension(e) {

		if (!e.target.checked) return;

		const convertableExtensions = ['doc', 'docx', 'txt', 'ppt', 'pptx', 'odt'];

		const uploadedFiles = $("#file").get(0).files;

		if (uploadedFiles.length > 0) {
			let invalidFiles = [];
			let pdfFiles = [];

			for (let file of uploadedFiles) {
				var ext = getFileExtension(file);

				if (ext.toLowerCase() === "pdf") {
					pdfFiles.push(file.name);
				} else if (!convertableExtensions.includes(ext)) {
					invalidFiles.push(file.name);
				}
			}

			if (pdfFiles.length > 0) {
				let msg = pdfFiles.length === 1
                    ? `${pdfFiles.toString()} file is already in PDF.`
                    : `${pdfFiles.toString()} files are already in PDF.`;

				alert(msg);
				e.target.checked = false;
			}

			if (invalidFiles.length > 0) {
                let msg = invalidFiles.length === 1
                    ? `${invalidFiles.toString()} document extension is not allowed for conversion.`
                    : `${invalidFiles.toString()} document extensions are not allowed for conversion.`;

				alert(msg);
				e.target.checked = false;
			}
		} else {
			alert('Please select the document first.');
			e.target.checked = false;
		}

		// if ($(".fileExists").length > 0) {
		// 	let filename = '$document->file';
		// 	extension = filename.split(".");
		// 	extension = extension[extension.length - 1];
		// }
	}

	function resetConvertCheckbox(e) {
		if (e.target.files.length === 0) {
			e.preventDefault();
			e.target.files = oldFiles;
			return false;
		}

		const allowedUploedFiles = ['doc', 'docx', 'txt', 'ppt', 'pptx', 'csv', 'xls', 'xlsx', 'pdf', 'odt'];
		const invalidFiles = [];

		let uploadedFiles = e.target.files;

		for (let file of uploadedFiles) {
			if (!allowedUploedFiles.includes(getFileExtension(file))) {
				invalidFiles.push(file.name);
			}
		}

		if (invalidFiles.length > 0) {
			message = invalidFiles.length === 1
                ? `${invalidFiles.toString()} file is not allowed for upload.`
                : `${invalidFiles.toString()} files are not allowed for upload.`

			$(`#${e.target.id}`).append(`<span class="my-error-class">${message}</span>`);
		}

		document.getElementById('convert').checked = false;
	}

	function handleFileChoose (e)
	{
		if (e.target.files.length === 0) {
			e.preventDefault();
			e.target.files = oldFiles;
			return false;
		}
	}

	function getFileExtension(file) {
		let uploadedFilename = file.name;
		return uploadedFilename.split(".").at(-1);
	}
</script>

@endpush
