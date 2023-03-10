@extends('admin.layouts.app')
@section('header', 'Edit Training')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.trainings.index') }}">Trainings</a></li>
<li class="breadcrumb-item active">Edit Training</li>
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
	<form method="POST" action="{{ route('admin.trainings.update', $training->id) }}" enctype="multipart/form-data" id="update-training-form">
		<div class="row">
			<div class="col-md-9">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Edit Training - {{ $training->title }}</h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->
					@method('PATCH')
					@include('admin.trainings.form')

				</div>
			</div>
			<div class="col-md-3">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">{{ __('Schedule Date & Time') }}</h3>
					</div>

					@include('admin.trainings.publishform')

				</div>

				<!-- /.card-body -->
				<div class="float-right">

					<input type="hidden" name="active" id="status">
					<input type="hidden" name="action" id="action">
					<input type="hidden" name="removeFile" id="removeFile">

					<button type="submit" class="btn width-120 btn-primary draft_button">Update</button>
				</div>
			</div>
		</div>
	</form>
</div>
@include('admin.includes.confirm-popup')
@endsection

@push('optional-styles')
<link rel="stylesheet" href="{{ asset('admin-resources/css/tempusdominus-bootstrap-4.min.css') }}">
@endpush

@push('optional-scripts')
<script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('admin-resources/js/moment.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/bootstrap-tagsinput.js') }}"></script>
<script src="{{ asset('admin-resources/js/form-custom-validator-methods.js') }}"></script>

<script>
	let oldFiles = [];
	$(document).ready(function() {

		$('#start_datetime').datetimepicker({
			format: '{{ config("settings.datetime_format") }}',
			step: 5,
			roundTime: 'ceil',
			validateOnBlur: false,
			onChangeDateTime: function(selectedDateTime, $input) {

				// let todaysDate = (new Date()).setHours(0, 0, 0, 0);

				// if (selectedDateTime >= todaysDate) {
					// let currentDateTime = (new Date()).setSeconds(0, 0);
					// if (selectedDateTime >= currentDateTime) {
						$('#start_date').val(mapDate(selectedDateTime));

						let endDate = new Date($("#end_date").val()).setSeconds(0, 0);
						selectedDateTime = selectedDateTime.setSeconds(0, 0);

						if (selectedDateTime >= endDate) {
							$input.val("");
							$('#start_date').val("");
							$input.parent().next().text("{{ __('messages.min_date', ['first' => 'Start', 'second' => 'end']) }}");
						} else {
							$input.parent().next().text("");
						}
					// } else {
					// 	$input.val("");
					// 	$('#start_date').val("");
					// 	$input.parent().next().text("{{ __('messages.current_system_datetime') }}");
					// }
				// } else {
				// 	$input.val("");
				// 	$('#start_date').val("");
				// 	$input.parent().next().text("{{ __('messages.todays_date') }}");
				// }

                $('#start_datetime').datetimepicker('hide');
			},
			onShow: function () {
				this.setOptions({
					maxDate: $('#end_date').val() ? $('#end_date').val() : false
				})
			}
		});

		$('#end_datetime').datetimepicker({
			format: '{{ config("settings.datetime_format") }}',
			step: 5,
			roundTime: 'ceil',
			validateOnBlur: false,
			onChangeDateTime: function(selectedDateTime, $input) {

				// let todaysDate = (new Date()).setHours(0, 0, 0, 0);

				// if (selectedDateTime >= todaysDate) {
					$('#end_date').val(mapDate(selectedDateTime));

					let startDate = new Date($("#start_date").val()).setSeconds(0, 0);
					selectedDateTime = selectedDateTime.setSeconds(0, 0);

					if (selectedDateTime <= startDate) {
						$input.val("");
						$('#end_date').val("");
						$input.parent().next().text("{{ __('messages.max_date', ['first' => 'End', 'second' => 'start']) }}");
					} else {
						$input.parent().next().text("");
					}
				// } else {
				// 	$input.val("");
				// 	$('#end_date').val("");
				// 	$input.parent().next().text("{{ __('messages.todays_date') }}");
				// }

                $('#end_datetime').datetimepicker('hide');
			},
			onShow: function () {
				this.setOptions({
					minDate: $('#start_date').val() ? $('#start_date').val() : new Date()
				})
			}
		});

		let attachments = [];
		$(".remove-file").on('click', function() {
			let { file } = $(this).data();

            $('#msg_heading').text('Delete record?');
            $('#msg_body').text('Are you sure you want to delete this file?');
            $('#confirmModal').modal('toggle');
            $('body').on('click', '#confirm', (e) => {
                attachments.push(file);
				$(this).parent().remove();
				$("#removeFile").val(`${attachments.toString()}`);
                $('#confirmModal').modal('toggle');
            });
		});

		$('#update-training-form').validate({
			ignore: [],
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			rules: {
				title: {
					required: true,
					minlength: 3,
					maxlength: 150,
					notNumericValues: true,
                    prevent_special_characters: true
				},
				topics: {
					required: true,
					minlength: 3,
					maxlength: 255,
					notNumericValues: true,
                    // prevent_special_characters: true
				},
				target_audience: {
					required: true,
					minlength: 3,
					maxlength: 255,
					notNumericValues: true,
                    // prevent_special_characters: true
				},
				location: {
					required: true,
					minlength: 3,
					maxlength: 64
				},
				status: {
					required: true,
				},
				'attachments[]': {
					docx_extension: "doc|docx|pdf|pptx|ppt|pptm|potx|potm|pot|ppsx|ppsm|pps|ppam|ppa|odp"
				},
				start_datetime: {
					required: true
				},
				end_datetime: {
					required: true
				}
			},
			errorPlacement: function(error, element) {
				if (element.attr("id") == "description") {
					element = $("#cke_" + element.attr("id"));
				}
				if (element.attr('id') === "start_datetime" || element.attr('id') === "end_datetime") {
					element = (element).parent();
				}
				error.insertAfter(element);
			},
			messages: {
				'attachments[]': {
					docx_extension: '{{ __("messages.valid_file_extension") }}'
				},
				title: {
					minlength: "{{ __('messages.min_characters', ['field' => 'Title', 'limit' => 3]) }}",
					required: '{{ __("messages.required") }}',
					maxlength: "{{ __('messages.max_characters', ['field' => 'Title', 'limit' => 150]) }}"
				},
                topics: {
					required: '{{ __("messages.required") }}',
					minlength: "{{ __('messages.min_characters', ['field' => 'Topics', 'limit' => 3]) }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Topics', 'limit' => 255]) }}"
				},
                target_audience: {
					required: '{{ __("messages.required") }}',
					minlength: "{{ __('messages.min_characters', ['field' => 'Target audience', 'limit' => 3]) }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Target audience', 'limit' => 255]) }}"
				},
                location: {
					required: '{{ __("messages.required") }}',
					minlength: "{{ __('messages.min_characters', ['field' => 'Location', 'limit' => 3]) }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Location', 'limit' => 64]) }}"
				}
			}
		});

		$('.bootstrap-tagsinput > input').on('blur keypress', function (e) {
			if ((e.which === 13 && $(this).val().trim().length > 0) || $(this).parent().children("span").length > 0) {
				$(this).attr('placeholder', '');
				return;
			}

			var placeholder = 'Enter ' + $(this).parent().parent().find('> label').text().toLowerCase().replace('*', '');
			$(this).attr('placeholder', placeholder);
		});

		if (document.getElementsByClassName('label-info').length > 0) {
			$('.bootstrap-tagsinput > input').attr('placeholder', '');
		}

		$(document).on('focusin', 'input[type="file"]', function(e){
			oldFiles = e.target.files;
		});
	});

	function mapDate(date) {
		return `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()} ${date.getHours()}:${date.getMinutes()}:00`;
	}

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
