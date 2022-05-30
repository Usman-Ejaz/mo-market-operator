@extends('admin.layouts.app')
@section('header', 'Trainings')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.trainings.index') }}">Trainings</a></li>
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
						<h3 class="card-title">{{ __('Date & Time') }}</h3>
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

<script>
	let oldFiles = [];
	$(document).ready(function() {

		//Date and time picker
		$('#start_date').datetimepicker({
			format: '{{ config("settings.datetime_format") }}',
			step: 30,
			roundTime: 'ceil',
			minDate: new Date(),
			validateOnBlur: false,
			onChangeDateTime: function(dp, $input) {
				let endDate = new Date($("#end_date").val());
				if (dp >= endDate) {
					$input.val("");
					$input.parent().next().text("Start date cannot be less than end date");
				} else {
					$input.parent().next().text("");
				}
			},
			onShow: function () {
				this.setOptions({
					maxDate: $('#end_date').val() ? $('#end_date').val() : false
				})
			}
		});

		$('#end_date').datetimepicker({
			format: '{{ config("settings.datetime_format") }}',
			step: 30,
			roundTime: 'ceil',
			minDate: new Date(),
			validateOnBlur: false,
			onChangeDateTime: function(dp, $input) {
				let startDate = new Date($("#start_date").val());
				if (dp <= startDate) {
					$input.val("");
					$input.parent().next().text("{{ __('messages.min_date') }}");
				} else {
					$input.parent().next().text("");
				}
			},
			onShow: function () {
				this.setOptions({
					minDate: $('#start_date').val() ? $('#start_date').val() : false
				})
			}
		});

		let attachments = [];		
		$(".remove-file").on('click', function() {
			let { file } = $(this).data();

			if (confirm('Are you sure you want to this file?')) {
				attachments.push(file);
				$(this).parent().remove();
				$("#removeFile").val(`${attachments.toString()}`);
			}
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

		$('#update-training-form').validate({
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
				},
				topics: {
					required: true,
					minlength: 3,
					maxlength: 255,
					notNumericValues: true,
				},
				target_audience: {
					required: true,
					minlength: 3,
					maxlength: 255,
					notNumericValues: true,
				},
				location: {
					required: true,
					minlength: 3,
					maxlength: 255,
					notNumericValues: true,
				},
				status: {
					required: true,
				},
				'attachments[]': {
					docx_extension: "doc|docx|pdf"
				},
				start_date: {
					required: true
				},
				end_date: {
					required: true
				}
			},
			errorPlacement: function(error, element) {
				if (element.attr("id") == "description") {
					element = $("#cke_" + element.attr("id"));
				}
				if (element.attr('id') === "start_date" || element.attr('id') === "end_date") {
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
					maxlength: "{{ __('messages.max_characters', ['field' => 'Title', 'limit' => 255]) }}"
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