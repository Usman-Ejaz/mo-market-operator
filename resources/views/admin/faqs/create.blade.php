@extends('admin.layouts.app')
@section('header', 'FAQs')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.faqs.index') }}">FAQs</a></li>
<li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="container-fluid">
	<form method="POST" action="{{ route('admin.faqs.store') }}" enctype="multipart/form-data" id="create-faq-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Create FAQ</h3>
					</div>
					<!-- form start -->
					@include('admin.faqs.form')
					<div class="card-footer">
						<div class="float-right">
							<input type="hidden" name="active" id="status">
							<input type="hidden" name="action" id="action">
							<button type="submit" class="btn width-120 btn-primary draft_button">Save</button>
							@if( hasPermission('faqs', 'publish') )
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
<script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>
<script>
	$(document).ready(function() {

		CKEDITOR.instances.answer.on('blur', function(e) {
			var messageLength = CKEDITOR.instances.answer.getData().replace(/<[^>]*>/gi, '').length;
			if (messageLength !== 0) {
				$('#cke_answer').next().hasClass("my-error-class") && $('#cke_answer').next().remove();
			}
		});			

		// Set hidden fields based on button click
		$('.draft_button').click(function(e) {
			$('#status').val("0");
			$('#action').val("Added");
		});

		$('.publish_button').click(function(e) {
			$('#status').val("1");
			$('#action').val("Published");
		});

		$.validator.addMethod("notNumericValues", function(value, element) {
			return this.optional(element) || isNaN(Number(value));
		}, '{{ __("messages.not_numeric") }}');

		$.validator.addMethod("ckeditor_required", function(value, element) {
			var editorId = $(element).attr('id');
			var messageLength = CKEDITOR.instances[editorId].getData().replace(/<[^>]*>/gi, '').length;
			return messageLength !== 0;
		}, '{{ __("messages.ckeditor_required") }}');

		$.validator.addMethod("noSpace", function(value) {
			this.value = $.trim(value);
			return this.value;
		});

		$('#create-faq-form').validate({
			ignore: [],
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			rules: {
				question: {
					required: true,
					minlength: 5,
					maxlength: 255,
					notNumericValues: true
				},
				category_id: {
					required: true,
				},
				answer: {
					ckeditor_required: true,
					minlength: 5
				}
			},
			errorPlacement: function(error, element) {
				if (element.attr("id") == "answer") {
					element = $("#cke_" + element.attr("id"));
				}
				error.insertAfter(element);
			},
			messages: {
				question: {
					required: "{{ __('messages.required') }}",
					minlength: "{{ __('messages.min_characters', ['field' => 'Question', 'limit' => 5]) }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Question', 'limit' => 255]) }}"
				},
				category_id: {
					required: "{{ __('messages.required') }}",
				}
			}
		});

		CKEDITOR.editorConfig = function( config ) {
			config.toolbarGroups = [
				{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
				{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
				{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
				{ name: 'forms', groups: [ 'forms' ] },
				{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
				{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
				{ name: 'links', groups: [ 'links' ] },
				{ name: 'insert', groups: [ 'insert' ] },
				'/',
				{ name: 'styles', groups: [ 'styles' ] },
				{ name: 'colors', groups: [ 'colors' ] },
				{ name: 'tools', groups: [ 'tools' ] },
				{ name: 'others', groups: [ 'others' ] },
				{ name: 'about', groups: [ 'about' ] }
			];

			config.removeButtons = 'Save,NewPage,ExportPdf,Preview,Print,Source,Templates,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField';
		};
	});
</script>

@endpush