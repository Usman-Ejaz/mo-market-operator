@extends('admin.layouts.app')
@section('header', 'Documents')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Documents</a></li>
  <li class="breadcrumb-item active">Create</li>
@endsection


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
              <input type="hidden" name="action" id="action">
              
              <button type="submit" class="btn width-120 btn-primary draft_button">Save</button>
              @if (Auth::user()->role->hasPermission('documents', 'publish'))
                <button type="submit" class="btn width-120 btn-success publish_button">Publish</button>
              @endif
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
    $(document).ready(function(){

      // Set hidden fields based on button click
      $('.draft_button').click(function(e) {
        $('#action').val("Added");
      });

      $('.publish_button').click(function(e) {
        $('#action').val("Published");
      });

      $.validator.addMethod("notNumericValues", function(value, element) {
          return this.optional(element) || isNaN(Number(value));
      }, '{{ __("messages.not_numeric") }}');

      $('#create-document-form').validate({
        errorElement: 'span',
        errorClass: "my-error-class",
        validClass: "my-valid-class",
        rules:{
          title: {
            required: true,
            minlength: 2,
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
            required:true,
            extension: "doc|docx|txt|ppt|pptx|csv|xls|xlsx|pdf|odt"
          }
        },
        messages: {
          file: '{{ __("messages.valid_file_extension") }}',
          title: {
            required: "This field is required.",
            minlength: "{{ __('messages.min_characters', ['field' => 'Title', 'limit' => 3]) }}",
            maxlength: "{{ __('messages.max_characters', ['field' => 'Title',  'limit' => 255]) }}"
          }
        }
      });

    });

    function validateFileExtension(e) {
      console.log(e.target.checked);
    }
  </script>

@endpush
