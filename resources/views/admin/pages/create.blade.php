@extends('admin.layouts.app')
@section('header', 'Pages')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
  <li class="breadcrumb-item active">Create</li>
@endsection


@section('content')
  <div class="container-fluid">

              <form method="POST" action="{{ route('admin.pages.store') }}" enctype="multipart/form-data" id="create-page-form">
                <div class="row">
                  <div class="col-md-9">
                    <div class="card card-primary">
                      <div class="card-header">
                        <h3 class="card-title">Create Page</h3>
                      </div>
                      <!-- /.card-header -->
                      <!-- form start -->
                      @include('admin.pages.form')

                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card card-primary">
                      <div class="card-header">
                        <h3 class="card-title">Schedule Content</h3>
                      </div>
                        @include('admin.pages.publishform')
                    </div>

                    <!-- /.card-body -->
                    <div class="float-right">

                      <input type="hidden" name="active" id="status">
                      <input type="hidden" name="action" id="action">
                      
                      <button type="submit" class="btn btn-primary draft_button">Save</button>
                      @if( Auth::user()->role->hasPermission('pages', 'publish') )
                      <button type="submit" class="btn btn-success publish_button">Publish</button>
                      @endif
                    </div>

                  </div>
                </div>
              </form>


            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

    </div>
@endsection

@push('optional-scripts')
  <script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
  <script src="{{ asset('admin-resources/js/moment.min.js') }}"></script>
  <script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>

  <script>

    //Date and time picker
    $(document).ready(function(){
      $('#start_datetime, #end_datetime').datetimepicker({
        format:'{{ config("settings.datetime_format") }}',
        validateOnBlur: false,
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

      // Slug generator
      $("#title").keyup(function() {
        var Text = $(this).val();
        Text = Text.toLowerCase().trim();
        Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
        $("#slug").val(Text);
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
      
      $('#create-page-form').validate({
        ignore: [],
        errorElement: 'span',
        errorClass: "my-error-class",
        validClass: "my-valid-class",
        rules:{
          title: {
            required: true,
            minlength: 2,
            notNumericValues: true,            
          },
          description:{
            ckeditor_required: true,
            minlength: 5
          },
          slug: {
            required: true,
            minlength: 2,
            notNumericValues: true,                       
          },
          keywords: {
            minlength: 5,
            notNumericValues: true,                       
          },
          image: {
            extension: "jpg|jpeg|png|ico|bmp"
          },
          start_datetime: {
            required: false
          },
          end_datetime: {
            required: false,
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
          image: '{{ __("messages.valid_file_extension") }}'
        }
      });
    });
  </script>

@endpush
