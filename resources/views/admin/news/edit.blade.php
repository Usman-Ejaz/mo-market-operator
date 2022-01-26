@extends('admin.layouts.app')
@section('header', 'News')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item">News</li>
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

              @if( \Route::current()->getName() == 'admin.news.edit' )

                  @if($news->active == 'Active')
                    <button type="submit" class="btn btn-primary publish_button">Update</button>
                    <button type="submit" class="btn btn-danger draft_button">Unpublish</button>
                  @elseif($news->active == 'Draft')
                    <button type="submit" class="btn btn-primary draft_button">Update</button>
                    @if( Auth::user()->role->hasPermission('news', 'publish') )
                        <button type="submit" class="btn btn-success publish_button">Publish</button>
                    @endif
                  @endif
              @else
                    <button type="submit" class="btn btn-primary draft_button">Save</button>
                    @if( Auth::user()->role->hasPermission('news', 'publish') )
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

    $('#start_datetime, #end_datetime').datetimepicker({
        format:'{{ config('settings.datetime_format') }}',
    });
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
                    maxlength: 255
                },
                description:{
                    required: true,
                    maxlength: 50000
                },
                slug: {
                    required: true,
                    maxlength: 255
                },
                news_category: {
                    required: true,
                },
                image: {
                    extension: "jpg|jpeg|png",
                },
                start_datetime: {
                    required : false,
                },
                end_datetime: {
                    required : false,
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
                            alert('image deleted successfully');
                            $('.imageExists').remove();
                        }
                    }
                });
            }
        });

    });

  </script>

@endpush
