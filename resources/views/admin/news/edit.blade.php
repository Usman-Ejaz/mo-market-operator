@extends('admin.layouts.app')
@section('header', 'News')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item">News</li>
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

      <form method="POST" action="{{ url('/admin/news/'.$news->id)}}" enctype="multipart/form-data" id="update-news-form">
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

              @if( \Route::current()->getName() == 'admin.news.edit' )

                  @if($news->active == 'Active')
                    <button type="submit" class="btn btn-primary publish_button">Update</button>
                    <button type="submit" class="btn btn-danger draft_button">Unpublish</button>
                  @elseif($news->active == 'Draft')
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
      </form>
      
         
    </div>   
@endsection

@push('optional-styles')
<link rel="stylesheet" href="{{ asset('admin/css/tempusdominus-bootstrap-4.min.css') }}">
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
  <script src="{{ asset('admin/js/moment.min.js') }}"></script>
  <script src="{{ asset('admin/js/tempusdominus-bootstrap-4.min.js') }}"></script>
  <script src="{{ asset('admin/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('admin/js/additional-methods.min.js') }}"></script>

  <script>
    CKEDITOR.replace('editor1', {
      height: 800,
      baseFloatZIndex: 10005,
      removeButtons: 'PasteFromWord'
    });

    //Date and time picker
    $(document).ready(function(){
      // $('#starttime').datetimepicker({ icons: { time: 'far fa-clock' } });

      // Set hidden fields based on button click
      $('.draft_button').click(function(e) {
        $('#status').val("0");
      });

      $('.publish_button').click(function(e) {
        $('#status').val("1");  
      });

      // Slug generator
      $("#title").keyup(function() {
        var Text = $(this).val();
        Text = Text.toLowerCase();
        Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
        $("#slug").val(Text);        
      });   
      
      $('#update-news-form').validate({
        errorElement: 'span',
        errorClass: "my-error-class",
        validClass: "my-valid-class",
        rules:{
          title: {
            required: true,
            maxlength: 5000
          },
          description:{
            required: true,
            maxlength: 50000
          },
          slug: {
            required: true,
            maxlength: 2000
          },
          keywords: {
            required: true,
            maxlength: 500
          },
          newscategory_id: {
            required: true,
          },
          image: {
            extension: "jpg|jpeg|png|ico|bmp"
          },
          starttime: {
            required : false,
            date:true,
            dateLessThan : '#endtime'
          },
          endtime: {
            required : false,
            date:true
          }
        },
        messages: {
          title: {
            required: "Title is required",
            maxlength: "Title cannot be more than 5000 characters"
          },
          description: {
            required: "Description is required",
            maxlength: "Description cannot be more than 50000 characters"
          },
          slug: {
            required: "Slug is required",
            maxlength: "Slug cannot be more than 2000 characters",
          },
          keywords: {
            required: "Keywords is required",
            maxlength: "Keywords cannot be more than 500 characters"
          },
          newscategory_id: {
            required: "Newscategory id is required"
          },
          image: {
            extension: "This type of file is not accepted"
          },
          starttime: {
            required: "Start time is not required",
            date:"Start time must be date time",
            dateLessThan: "Start time must less than end time"
          },
          endtime: {
            required: "End time is not required",
            date:"End time must be date time",
          }
        }
      });

    });
  </script>

@endpush
