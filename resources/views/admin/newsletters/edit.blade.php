@extends('admin.layouts.app')
@section('header', 'NewsLetters')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.newsletters.index') }}">Newsletters</a></li>
  <li class="breadcrumb-item active">Update</li>
@endsection

@section('content')
  <div class="container-fluid">

      <form method="POST" action="{{ route('admin.newsletters.update', $newsletter->id) }}" enctype="multipart/form-data" id="update-newsletter-form">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Editing - {{ $newsletter->subject }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              @method('PATCH')
              @include('admin.newsletters.form')
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
<link rel="stylesheet" href="{{ asset('admin-resources/css/tempusdominus-bootstrap-4.min.css') }}">
@endpush

@push('optional-scripts')
  <script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
  <script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>
  <script src="{{ asset('admin-resources/js/moment.min.js') }}"></script>
  <script src="{{ asset('admin-resources/js/tempusdominus-bootstrap-4.min.js') }}"></script>
  <script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>

  <script>
    CKEDITOR.replace('editor1', {
      height: 800,
      baseFloatZIndex: 10005,
      removeButtons: 'PasteFromWord'
    });

    //Date and time picker
    $(document).ready(function(){

      $.validator.addMethod(
        "notNumericValues",
        function(value, element) {
          return this.optional(element) || isNaN(Number(value));
        },
        "String cannot be numeric"
      );

      $.validator.addMethod("noSpace", function(value) { 
        this.value = $.trim(value);
        return this.value;
      });

        $('#update-newsletter-form').validate({
            errorElement: 'span',
            errorClass: "my-error-class",
            validClass: "my-valid-class",
            rules:{
                subject: {
                    required: true,
                    minlength: 2,
                    notNumericValues: true,
                    noSpace: true
                },
                description:{
                    minlength: 1,
                    required: true,
                }
            }
        });
    });

  </script>

@endpush
