@extends('admin.layouts.app')
@section('header', 'Documents')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Documents</a></li>
  <li class="breadcrumb-item active">Update</li>
@endsection

@section('content')
  <div class="container-fluid">
    <form method="POST" action="{{ route('admin.documents.update', $document->id) }}" enctype="multipart/form-data" id="update-document-form">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Editing - {{ $document->title }}</h3>
            </div>
            @method('PATCH')
            @include('admin.documents.form')

            <div class="card-footer">
              <div class="float-right">
                <button type="submit" class="btn btn-primary draft_button">Update</button>
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
    $(document).ready(function(){
      $.validator.addMethod("notNumericValues", function(value, element) {
          return this.optional(element) || isNaN(Number(value));
      }, '{{ __("messages.not_numeric") }}');

      $('#update-document-form').validate({
        errorElement: 'span',
        errorClass: "my-error-class",
        validClass: "my-valid-class",
        rules:{
          title: {
            required: true,
            minlength: 2,
            maclength: 255,
            notNumericValues: true,            
          },
          keywords: {
            minlength: 2,
            notNumericValues: true,                      
          },
          category_id: {
            required: true,
          },
          file: {
            required: true,
            extension: "doc|docx|txt|ppt|csv|xls|xlsx|pdf|odt"
          }
        },
        messages: {
          file: '{{ __("messages.valid_file_extension") }}',
          title: {
            required: "This field is required.",
            minlength: "{{ __('messages.min_characters', ['field' => 'Title', 'limit' => 3]) }}",
            maxlength: "{{ __('messages.max_characters', ['field' => 'Title', 'limit' => 255]) }}"
          }
        }
      });

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $("#deleteFile").click(function(){

            if (confirm('Are you sure you want to this file?')) {
                $.ajax({
                    url: "{{ route('admin.documents.deleteFile') }}",
                    type: 'POST',
                    data: {_token: "{{ csrf_token() }}", document_id: "{{$document->id}}"},
                    dataType: 'JSON',
                    success: function (data) {
                        if(data.success){
                            alert('File Deleted Successfully');
                            $('.fileExists').remove();
                        }
                    }
                });
            }
        });

    });

  </script>

@endpush
