@extends('admin.layouts.app')
@section('header', 'Chatbot Knowledge Base')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.knowledge-base.index') }}">Chatbot Knowledge Base</a></li>
<li class="breadcrumb-item active">Edit</li>
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
	<form method="POST" action="{{ route('admin.knowledge-base.update', $knowledge_base->id ) }}" enctype="multipart/form-data" id="update-knowledge-base-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Edit Chatbot Knowledge Base - {{ truncateWords($knowledge_base->question, 50) }}</h3>
					</div>
					<!-- form start -->
					@method('PATCH')
					@include('admin.chatbot-knowledge-base.form')
					<div class="card-footer">
						<div class="float-right">
							<input type="hidden" name="active" id="status">
							<input type="hidden" name="action" id="action">

							@if($knowledge_base->published_at !== null)
								<button type="submit" class="btn width-120 btn-primary update_button">Update</button>
								@if(hasPermission('knowledge_base', 'publish'))
									<button type="submit" class="btn width-120 btn-danger unpublish_button">Unpublish</button>
								@endif
							@else
								<button type="submit" class="btn width-120 btn-primary draft_button">Update</button>
								@if(hasPermission('knowledge_base', 'publish'))
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
<script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/bootstrap-tagsinput.js') }}"></script>
<script src="{{ asset('admin-resources/js/form-custom-validator-methods.js') }}"></script>

<script>
	$(document).ready(function() {

		// Set hidden fields based on button click
		$('.draft_button').click(function(e) {
			$('#status').val("0");
			$('#action').val("Updated");
		});

		$('.publish_button').click(function(e) {
			$('#status').val("1");
			$('#action').val("Published");
		});

		$('.update_button').click(function(e) {
			$('#status').val("1");
			$('#action').val("Updated");
		});

		$('.unpublish_button').click(function(e) {
			$('#status').val("0");
			$('#action').val("Unpublished");
		});

		$('#update-knowledge-base-form').validate({
			ignore: [],
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			rules: {
				question: {
					required: true,
					minlength: 5,
					maxlength: 64,
					notNumericValues: true,
                    prevent_special_characters: true
				},
				answer: {
					ckeditor_required: true,
					minlength: 5
				},
				keywords: {
					required: true,
					notNumericValues: true,
                    maxlength: {
                        depends: () => {
                            let tags = $('#keywords').val().split(',');
                            return tags.filter(tag => tag.length > 64).length > 0 ? 64 : 0;
                        }
                    }
				},
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
					maxlength: "{{ __('messages.max_characters', ['field' => 'Question', 'limit' => 64]) }}"
				},
                keywords: {
                    required: "{{ __('messages.required') }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Keywords', 'limit' => 64]) }}"
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
	});
</script>

@endpush
