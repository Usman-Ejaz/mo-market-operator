@extends('admin.layouts.app')
@section('header', 'Jobs')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.jobs.index') }}">Jobs</a></li>
  <li class="breadcrumb-item active">Update</li>
@endsection

@section('content')
  <div class="container-fluid">
      <form method="POST" action="{{ route('admin.jobs.update', $job->id) }}" enctype="multipart/form-data" id="update-job-form">
        <div class="row">
          <div class="col-md-9">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Editing Job - {{ $job->title }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              @method('PATCH')
              @include('admin.jobs.form')

            </div>
          </div>
          <div class="col-md-3">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Schedule Content</h3>
              </div>

                @include('admin.jobs.publishform')

            </div>

            <!-- /.card-body -->
            <div class="float-right">

              <input type="hidden" name="active" id="status">
              <input type="hidden" name="action" id="action">

              @if($job->active == 'Active')
                <button type="submit" class="btn width-120 btn-primary publish_button">Update</button>
                @if( Auth::user()->role->hasPermission('jobs', 'publish') )
                  <button type="submit" class="btn width-120 btn-danger unpublish_button">Unpublish</button>
                @endif
              @elseif($job->active == 'Draft')
                <button type="submit" class="btn width-120 btn-primary draft_button">Update</button>
                @if( Auth::user()->role->hasPermission('jobs', 'publish') )
                  <button type="submit" class="btn width-120 btn-success publish_button">Publish</button>
                @endif
              @endif
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
  <!-- <script src="{{ asset('admin-resources/js/tempusdominus-bootstrap-4.min.js') }}"></script> -->
  <script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>

  <script>

    $(document).ready(function(){

      CKEDITOR.instances.description.on('blur', function(e) {
        var messageLength = CKEDITOR.instances.description.getData().replace(/<[^>]*>/gi, '').length;
        if (messageLength !== 0) {
          $('#cke_description').next().hasClass("my-error-class") && $('#cke_description').next().remove();
        }
      });
      
      //Date and time picker
      $('#start_datetime').datetimepicker({
        format: '{{ config("settings.datetime_format") }}',
        step: 30,
        roundTime: 'ceil',
        minDate: new Date(),
        validateOnBlur: false,
        onChangeDateTime: function (dp, $input) {
          let endDate = $("#end_datetime").val();
          if (endDate.trim().length > 0 && $input.val() > endDate) {
            $input.val("");
            $input.parent().next().text("Start Date cannot be less than end date");
          } else {
            $input.parent().next().text("");
          }
        } 
      });

      $('#end_datetime').datetimepicker({
        format: '{{ config("settings.datetime_format") }}',
        step: 30,
        roundTime: 'ceil',
        minDate: new Date(),
        validateOnBlur: false,
        onChangeDateTime: function (dp, $input) {
          let startDate = $("#start_datetime").val();
          if (startDate.trim().length > 0 && $input.val() < startDate) {
            $input.val("");
            $input.parent().next().text("{{ __('messages.min_date') }}");
          } else {
            $input.parent().next().text("");
          }
        }
      });

      // Set hidden fields based on button click
      $('.draft_button').click(function(e) {
        $('#status').val("0");
        $('#action').val("Updated");
      });

      $('.publish_button').click(function(e) {
        $('#status').val("1");
        $('#action').val("Published");
      });

      $('.unpublish_button').click(function(e) {
        $('#status').val("0");
        $('#action').val("Unpublished");
      });


      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $("#deleteImage").click(function(){
          if (confirm('Are you sure you want to this image?')) {
            $.ajax({
              url: "{{ route('admin.jobs.deleteImage') }}",
              type: 'POST',
              data: {_token: "{{ csrf_token() }}", job_id: "{{$job->id}}"},
              dataType: 'JSON',
              success: function (data) {
                if(data.success){
                  alert('Image Deleted Successfully');
                  $('.imageExists').remove();
                }
              }
            });
          }
        });
        
      $.validator.addMethod("notNumericValues", function (value, element) {
          return this.optional(element) || isNaN(Number(value));
      }, '{{ __("messages.not_numeric") }}');

      $.validator.addMethod("greaterThan", function (value, element, params) {
        // if there is no date in both fields, then bypass the validation
        if (value.trim().length === 0 && $(params).val().trim().length === 0) return true;
        
        if (!/Invalid|NaN/.test(new Date(value))) {
            return new Date(value) > new Date($(params).val());
        }
        return isNaN(value) && isNaN($(params).val()) || (Number(value) > Number($(params).val())); 

        // Error Message for this field | Should put on the single quotes given below.
        // {{ __("messages.valid_date", ["first" => "End", "second" => "Start"]) }}
      }, '');

      $.validator.addMethod("ckeditor_required", function(value, element) {        
        var editorId = $(element).attr('id');
        var messageLength = CKEDITOR.instances[editorId].getData().replace(/<[^>]*>/gi, '').length;
        return messageLength !== 0;
      }, '{{ __("messages.ckeditor_required") }}');

      $('#update-job-form').validate({
        ignore: [],
        errorElement: 'span',
        errorClass: "my-error-class",
        validClass: "my-valid-class",
        ignore: [],
        rules:{
          title: {
            required: true,
            minlength: 3,
            maxlength: 255,
            notNumericValues: true,            
          },
          description:{
            ckeditor_required: true,
            minlength: 5
          },
          qualification: {
            required: true,
            minlength: 5,
            notNumericValues: true,                      
          },
          experience: {
            required: true,
            minlength: 2,
            notNumericValues: true,                       
          },
          location: {
            required: true,
            minlength: 5,
            notNumericValues: true,                       
          },
          total_positions: {
            required: true,
            number: true,
            min:1,
            maxlength: 4
          },
          image: {
            extension: "jpg|jpeg|png|ico|bmp"
          },
          enable: {
            required: false,
          },
          start_datetime: {
          },
          end_datetime: {
            greaterThan: "#start_datetime"
          }
        },
        errorPlacement: function (error, element) {
          if (element.attr("id") == "description") {
            element = $("#cke_" + element.attr("id"));
          }
          error.insertAfter(element);
        },
        messages: {
          image: '{{ __("messages.valid_file_extension") }}',
          title: {
            minlength: "{{ __('messages.min_characters', ['field' => 'Title', 'limit' => 3]) }}",
            required: "This field is required.",
            maxlength: "{{ __('messages.max_characters', ['field' => 'Title', 'limit' => 255]) }}"
          }
        }
      });
    });
  </script>

@endpush
