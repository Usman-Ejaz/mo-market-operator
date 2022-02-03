@extends('admin.layouts.app')
@section('header', 'FAQs')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.faqs.index') }}">FAQ</a></li>
  <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('admin.faqs.store') }}" enctype="multipart/form-data" id="create-faq-form">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Creating FAQ</h3>
                        </div>
                        <!-- form start -->
                        @include('admin.faqs.form')
                        <div class="card-footer">
                            <div class="float-right">
                                <input type="hidden" name="active" id="status">
                                <button type="submit" class="btn btn-primary draft_button">Save</button>
                                @if( Auth::user()->role->hasPermission('faqs', 'publish') )
                                  <button type="submit" class="btn btn-success publish_button">Publish</button>
                                @endif
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
    CKEDITOR.replace('editor1', {
      height: 800,
      baseFloatZIndex: 10005,
      removeButtons: 'PasteFromWord'
    });

    $(document).ready(function(){
      // Set hidden fields based on button click
      $('.draft_button').click(function(e) {
        $('#status').val("0");
      });

      $('.publish_button').click(function(e) {
        $('#status').val("1");
      });

      $('#create-faq-form').validate({
        errorElement: 'span',
        errorClass: "my-error-class",
        validClass: "my-valid-class",
        rules:{
          question: {
            required: true,
            minlength: 5
          },
          answer:{
            required: true,
            minlength: 5
          }
        },
      });

      $('#create-faq-form').on("focusout", "input", function() {
        if ($(this).val().trim().length > 0) {
          $(this).hasClass("my-error-class") && $(this).removeClass("my-error-class");
          $(this).next().hasClass("my-error-class") && $(this).next().remove();
        }
      });
    });    
  </script>
  
@endpush