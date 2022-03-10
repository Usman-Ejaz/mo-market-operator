@extends('admin.layouts.app')
@section('header', 'Documents')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Documents</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

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

							@if ($document->published_at !== null)
							<button type="submit" class="btn width-120 btn-primary update_button">Update</button>
							@if (hasPermission('documents', 'publish'))
							<button type="submit" class="btn width-120 btn-danger unpublish_button">Unpublish</button>
							@endif
							@else
							<button type="submit" class="btn width-120 btn-primary draft_button">Update</button>
							@if( hasPermission('documents', 'publish'))
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

<script>
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

		$.validator.addMethod("notNumericValues", function(value, element) {
			return this.optional(element) || isNaN(Number(value));
		}, '{{ __("messages.not_numeric") }}');

		$('#update-document-form').validate({
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
				file: {
					required: {
						depends: function() {
							return $(".fileExists").length > 0 ? false : true;
						}
					},
					extension: "doc|docx|txt|ppt|pptx|csv|xls|xlsx|pdf|odt"
				}
			},
			errorPlacement: function(error, element) {
				if (element.attr("id") == "file") {
					element.next().text('');
				}
				error.insertAfter(element);
			},
			messages: {
				file: {
					required: "{{ __('messages.required') }}",
					extension: '{{ __("messages.valid_file_extension") }}'
				},
				title: {
					required: "{{ __('messages.required') }}",
					minlength: "{{ __('messages.min_characters', ['field' => 'Title', 'limit' => 3]) }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Title',  'limit' => 255]) }}"
				}
			}
		});

		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		$("#deleteFile").click(function() {

			if (confirm('Are you sure you want to this file?')) {
				$.ajax({
					url: "{{ route('admin.documents.deleteFile') }}",
					type: 'POST',
					data: {
						_token: "{{ csrf_token() }}",
						document_id: "{{$document->id}}"
					},
					dataType: 'JSON',
					success: function(data) {
						if (data.success) {
							alert('File Deleted Successfully');
							window.location.reload();
							// $('.fileExists').remove();
						}
					}
				});
			}
		});

	});

	function validateFileExtension(e) {

		if (!e.target.checked) return;

		const convertableExtensions = ['doc', 'docx', 'txt', 'ppt', 'pptx', 'odt'];
		let extension = "";

		if ($("#file").get(0).files.length > 0) {
			let filename = $("#file").get(0).files[0].name;
			extension = filename.split(".");
			extension = extension[extension.length - 1];			
		} else if ($(".fileExists").length > 0) {
			let filename = '{{ $document->file }}';
			extension = filename.split(".");
			extension = extension[extension.length - 1];
		} else {
			alert("Please upload the file first.");
			e.target.checked = false;
			return;
		}

		if (!convertableExtensions.includes(extension)) {
			alert("This document extension is not allowed for conversion.");
			e.target.checked = false;
			return;
		}		
	}
</script>

@endpush