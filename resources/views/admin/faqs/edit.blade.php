@extends('admin.layouts.app')
@section('header', 'FAQ')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item">FAQ</li>
  <li class="breadcrumb-item active">Update</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="flash-message">
            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-' . $msg))
                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                @endif
            @endforeach
        </div>

        <form method="POST" action="{{ url('/admin/faqs/'.$faq->id)}}" enctype="multipart/form-data" id="update-faq-form">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Editing FAQ {{$faq->id}}</h3>
                        </div>
                        <!-- form start -->
                        @method('PATCH')
                        @include('admin.faqs.form')
                        <div class="card-footer">
                            <div class="float-right">
                                <input type="hidden" name="active" id="status">

                                @if( \Route::current()->getName() == 'admin.faqs.edit' )

                                    @if($faq->active == 'Active')
                                    <button type="submit" class="btn btn-primary publish_button">Update</button>
                                    <button type="submit" class="btn btn-danger draft_button">Unpublish</button>
                                    @elseif($faq->active == 'Draft')
                                    <button type="submit" class="btn btn-primary draft_button">Update</button>
                                    <button type="submit" class="btn btn-success publish_button">Publish</button>
                                    @endif
                                @else 
                                    <button type="submit" class="btn btn-primary draft_button">Save</button>
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

@push('optional-styles')
  <style>
  .my-error-class {
    color:#FF0000;  /* red */
  }
  .my-valid-class {
    color:#00CC00; /* green */
  } 
  </style>
@endpush

@push('optional-scripts')
  <script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>
  <script src="{{ asset('admin/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('admin/js/additional-methods.min.js') }}"></script>

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

      $('#update-faq-form').validate({
        errorElement: 'span',
        errorClass: "my-error-class",
        validClass: "my-valid-class",
        rules:{
          question: {
            required: true,
            maxlength: 50000
          },
          answer:{
            required: true,
            maxlength: 50000
          }
        },
        messages: {
          question: {
            required: "Question is required",
            maxlength: "Question cannot be more than 50000 characters"
          },
          answer: {
            required: "Answer is required",
            maxlength: "Answer cannot be more than 50000 characters"
          }
        }
      });
    });
  </script>

@endpush
