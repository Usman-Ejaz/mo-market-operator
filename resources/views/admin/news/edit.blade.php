@extends('admin.layouts.app')
@section('header', 'News')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">News</a></li>
  <li class="breadcrumb-item active">Update</li>
@endsection

@section('content')
  <div class="container-fluid">

      <form method="POST" action="{{ route('admin.news.update', $news->id) }}" enctype="multipart/form-data" id="update-news-form">
        <div class="row">
          <div class="col-md-9">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Editing - {{ $news->title }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              @method('PATCH')
              @include('admin.news.form')

            </div>
          </div>
          <div class="col-md-3">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Schedule Content</h3>
              </div>

                @include('admin.news.publishform')

            </div>

            <!-- /.card-body -->
            <div class="float-right">

              <input type="hidden" name="active" id="status">
              <input type="hidden" name="action" id="action">              
              @if($news->active == 'Active')
                <button type="submit" class="btn width-120 btn-primary publish_button">Update</button>
                <button type="submit" class="btn width-120 btn-danger unpublish_button">Unpublish</button>
              @elseif($news->active == 'Draft')
                <button type="submit" class="btn width-120 btn-primary draft_button">Update</button>
                @if( Auth::user()->role->hasPermission('news', 'publish') )
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
  <script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>

  <script>

    //Date and time picker
    $(document).ready(function(){

      CKEDITOR.instances.description.on('blur', function(e) {
        var messageLength = CKEDITOR.instances.description.getData().replace(/<[^>]*>/gi, '').length;
        if (messageLength !== 0) {
          $('#cke_description').next().hasClass("my-error-class") && $('#cke_description').next().remove();
        }
      });

    $('#start_datetime, #end_datetime').datetimepicker({
      format:'{{ config("settings.datetime_format") }}',
      validateOnBlur: false
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

      $.validator.addMethod("notNumericValues", function(value, element) {
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

        $('#update-news-form').validate({
            ignore: [],
            errorElement: 'span',
            errorClass: "my-error-class",
            validClass: "my-valid-class",
            ignore: [],
            rules:{
                title: {
                    required: true,
                    maxlength: 255,
                    minlength: 3,
                    notNumericValues: true
                },
                description:{
                    ckeditor_required: true,
                    maxlength: 50000
                },
                slug: {
                    required: true,
                    notNumericValues: true,                    
                },
                news_category: {
                    required: true,
                },
                image: {
                    extension: "jpg|jpeg|png",
                },
                start_datetime: {
                    required : false
                },
                end_datetime: {
                    required : false,
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
                required: "This field is required.",
                minlength: "{{ __('messages.min_characters', ['field' => 'Title', 'limit' => 3]) }}",
                maxlength: "{{ __('messages.max_characters', ['field' => 'Title', 'limit' => 255]) }}"
              }
            }
        });

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $("#deleteImage").click(function(){

            if (confirm('Are you sure you want to this image?')) {
                $.ajax({
                    url: "{{ route('admin.news.deleteImage') }}",
                    type: 'POST',
                    data: {_token: "{{ csrf_token() }}", news_id: "{{$news->id}}"},
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
