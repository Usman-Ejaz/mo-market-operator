@extends('admin.layouts.app')
@section('header', 'Pages')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
  <li class="breadcrumb-item active">Update</li>
@endsection

@section('content')
  <div class="container-fluid">

      <form method="POST" action="{{ route('admin.pages.update', $page->id) }}" enctype="multipart/form-data" id="update-page-form">
        <div class="row">
          <div class="col-md-9">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Editing - {{ $page->title }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              @method('PATCH')
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

              @if($page->active == 'Active')
                <button type="submit" class="btn btn-primary publish_button">Update</button>
                @if( Auth::user()->role->hasPermission('pages', 'publish') )
                  <button type="submit" class="btn btn-danger unpublish_button">Unpublish</button>
                @endif
              @elseif($page->active == 'Draft')
                <button type="submit" class="btn btn-primary draft_button">Update</button>
                @if( Auth::user()->role->hasPermission('pages', 'publish') )
                  <button type="submit" class="btn btn-success publish_button">Publish</button>
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
  <script src="{{ asset('admin-resources/js/tempusdominus-bootstrap-4.min.js') }}"></script>
  <script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>

  <script>
    // CKEDITOR.replace('editor1', {
    //   height: 800,
    //   baseFloatZIndex: 10005,
    //   removeButtons: 'PasteFromWord'
    // });

    //Date and time picker
    $(document).ready(function(){
      $('#start_datetime, #end_datetime').datetimepicker({
        format:'{{ config("settings.datetime_format") }}',
        validateOnBlur: false,
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
        if (!/Invalid|NaN/.test(new Date(value))) {
            return new Date(value) > new Date($(params).val());
        }
        return isNaN(value) && isNaN($(params).val()) || (Number(value) > Number($(params).val())); 

        // Error Message for this field | Should put on the single quotes given below.
        // {{ __("messages.valid_date", ["first" => "End", "second" => "Start"]) }}
      }, '');

      $.validator.addMethod("noSpace", function(value) { 
        this.value = $.trim(value);
        return this.value;
      });

      $('#update-page-form').validate({
        errorElement: 'span',
        errorClass: "my-error-class",
        validClass: "my-valid-class",
        rules:{
          title: {
            required: true,
            minlength: 2,
            notNumericValues: true,
            noSpace: true
          },
          description:{
            required: true,
            minlength: 5
          },
          slug: {
            required: true,
            minlength: 2,
            notNumericValues: true,
            noSpace: true           
          },
          keywords: {
            minlength: 5,
            notNumericValues: true,
            noSpace: true             
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
        messages: {
          image: '{{ __("messages.valid_file_extension") }}'
        }
      });

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $("#deleteImage").click(function(){

            if (confirm('Are you sure you want to this image?')) {
                $.ajax({
                    url: "{{ route('admin.pages.deleteImage') }}",
                    type: 'POST',
                    data: {_token: "{{ csrf_token() }}", page_id: "{{$page->id}}"},
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

    });

  </script>

@endpush
