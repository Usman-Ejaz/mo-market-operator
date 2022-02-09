@extends('admin.layouts.app')
@section('header', 'NewsLetters')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.newsletters.index') }}">Newsletters</a></li>
  <li class="breadcrumb-item active">Create</li>
@endsection


@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('admin.newsletters.store') }}" enctype="multipart/form-data" id="create-newsletter-form">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Create Newsletter</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->

                        @include('admin.newsletters.form')

                        <div class="card-footer">
                            <div class="float-right">
                                <button type="submit" class="btn btn-primary draft_button">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('optional-scripts')
  <script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
  <script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>

  <script>
    $(document).ready(function(){

      $.validator.addMethod("notNumericValues", function (value, element) {
        return this.optional(element) || isNaN(Number(value));
      }, '{{ __("messages.not_numeric") }}');

      $.validator.addMethod("noSpace", function(value) { 
        this.value = $.trim(value);
        return this.value;
      });

      $('#create-newsletter-form').validate({
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        errorElement: 'span',
        errorClass: "my-error-class",
        validClass: "my-valid-class",
        ignore: [],
        rules:{
          subject: {
            required: true,
            minlength: 2,
            notNumericValues: true,
            noSpace:true,
          },
          description:{
            required:  function() 
                        {
                         CKEDITOR.instances.description.updateElement();
                        },
            minlength: 1
          },
        }
      });
    });
  </script>

@endpush
