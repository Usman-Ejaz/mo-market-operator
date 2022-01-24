@extends('admin.layouts.app')
@section('header', 'Documents')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Documents</a></li>
  <li class="breadcrumb-item active">Update</li>
@endsection

@section('content')
  <div class="container-fluid">
    <form method="POST" action="{{ url('/admin/documents/'.$document->id)}}" enctype="multipart/form-data" id="update-document-form">
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

@push('optional-styles')
  <link rel="stylesheet" href="{{ asset('admin/css/app.css') }}">
@endpush

@push('optional-scripts')
  <script src="{{ asset('admin/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('admin/js/additional-methods.min.js') }}"></script>

  <script>
    $(document).ready(function(){

      $('#update-document-form').validate({
        errorElement: 'span',
        errorClass: "my-error-class",
        validClass: "my-valid-class",
        rules:{
          title: {
            required: true,
            minlength: 8
          },
          keywords: {
            minlength: 3
          },
          file: {
            required: true,
            extension: "doc|docx|txt|ppt|csv|xls|xlsx|pdf|odt"
          }
        },
        messages: {
          title: {
            required: "Title is required",
            minlength: "Title cannot be less than 8 characters"
          },
          keywords: {
            minlength: "Keywords cannot be less than 3 characters"
          },
          file: {
            required: "File is required",
            extension: "This type of file is not accepted"
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
                            alert('File deleted successfully');
                            $('.fileExists').remove();
                        }
                    }
                });
            }
        });

    });

  </script>

@endpush
