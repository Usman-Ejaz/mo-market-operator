@extends('admin.layouts.app')
@section('header', 'Documents')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Documents</a></li>
<li class="breadcrumb-item active">Create</li>
@endsection

@push('optional-styles')
<link rel="stylesheet" href="{{ asset('admin-resources/css/bootstrap-tagsinput.css') }}" />
<style type="text/css">
        .bootstrap-tagsinput{
            width: 100%;
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
        }
    </style>
@endpush


@section('content')
<div class="container-fluid">
	<form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data" id="create-document-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Create Document</h3>
					</div>

					@include('admin.documents.form')
					<div class="card-footer">
						<div class="float-right">
							<input type="hidden" name="action" id="action">

							<button type="submit" class="btn width-120 btn-primary draft_button">Save</button>
							@if (hasPermission('documents', 'publish'))
							<button type="submit" class="btn width-120 btn-success publish_button">Publish</button>
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

<script>
	let oldFiles = [];
	$(document).ready(function() {

		// Set hidden fields based on button click
		$('.draft_button').click(function(e) {
			$('#action').val("Added");
		});

		$('.publish_button').click(function(e) {
			$('#action').val("Published");
		});

		$.validator.addMethod("notNumericValues", function(value, element) {
			return this.optional(element) || isNaN(Number(value)) || value.indexOf('e') !== -1;
		}, '{{ __("messages.not_numeric") }}');

		$.validator.addMethod('docx_extension', function (value, element, param) {
			let files = Array.from(element.files);
			param = param.split('|');
			let invalidFiles = files.filter(file => !param.includes(file.name.split('.').at(-1)));
			return this.optional(element) || invalidFiles.length === 0;
		}, '{{ __("messages.valid_file_extension") }}');

		$('#create-document-form').validate({
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			rules: {
				title: {
					required: true,
					minlength: 3,
					maxlength: 255,
					notNumericValues: true,
				},
				category_id: {
					required: true,
				},
				keywords: {
					notNumericValues: true
				},
				image: {
					required: true,
					extension: '{{ config("settings.image_file_extensions") }}'
				},
				'file[]': {
					required: true,
					docx_extension: 'doc|docx|txt|ppt|pptx|csv|xls|xlsx|pdf|odt'
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
				}
			}
		});

		$('.bootstrap-tagsinput > input').on('blur keypress', function (e) {
			if ((e.which === 13 && $(this).val().trim().length > 0) || document.getElementsByClassName('label-info').length > 0) {
				$(this).attr('placeholder', '');
				return;
			}

			$(this).attr('placeholder', '{{ __("Enter Keywords") }}');
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
				alert(`${pdfFiles.toString()} file(s) are already in PDF.`);
				e.target.checked = false;
			}

			if (invalidFiles.length > 0) {
				alert(`${invalidFiles.toString()} document(s) extension is not allowed for conversion.`);
				e.target.checked = false;
			}
		} else {
			alert('Please select the document first.');
			e.target.checked = false;
		}
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
			message = `${invalidFiles.toString()} file(s) are not allowed for upload.`
			$(`#${e.target.id}`).append(`<span class="my-error-class">${message}</span>`);
		}

		document.getElementById('convert').checked = false;
	}

	function getFileExtension(file) {
		let uploadedFilename = file.name;
		return uploadedFilename.split(".").at(-1);
	}
</script>

@endpush