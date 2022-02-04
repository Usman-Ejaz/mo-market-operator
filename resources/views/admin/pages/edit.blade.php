@extends('admin.layouts.app')
@section('header', 'Pages')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
  <li class="breadcrumb-item active">Update</li>
@endsection

@section('content')
  <div class="container-fluid">

      <form method="POST" action="{{ route('admin.pages.update', $page->id) }}" enctype="multipart/form-data" id="update-page-form">
        <div class="row">
          <div class="col-md-9">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Editing - {{ $page->title }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              @method('PATCH')
              @include('admin.pages.form')

            </div>
          </div>
          <div class="col-md-3">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Schedule Content</h3>
              </div>

                @include('admin.pages.publishform')

            </div>

            <!-- /.card-body -->
            <div class="float-right">

              <input type="hidden" name="active" id="status">

              @if( \Route::current()->getName() == 'admin.pages.edit' )

                  @if($page->active == 'Active')
                    <button type="submit" class="btn btn-primary publish_button">Update</button>
                    @if( Auth::user()->role->hasPermission('pages', 'publish') )
                      <button type="submit" class="btn btn-danger draft_button">Unpublish</button>
                    @endif
                  @elseif($page->active == 'Draft')
                    <button type="submit" class="btn btn-primary draft_button">Update</button>
                    @if( Auth::user()->role->hasPermission('pages', 'publish') )
                      <button type="submit" class="btn btn-success publish_button">Publish</button>
                    @endif
                  @endif
              @else
                    <button type="submit" class="btn btn-primary draft_button">Save</button>
                    @if( Auth::user()->role->hasPermission('pages', 'publish') )
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
  <script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
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

      $('#update-page-form').validate({
        errorElement: 'span',
        errorClass: "my-error-class",
        validClass: "my-valid-class",
        rules:{
          title: {
            required: true,
            minlength: 5
          },
          description:{
            required: true,
            minlength: 5
          },
          slug: {
            required: true,
            minlength: 5
          },
          keywords: {
            minlength: 5
          },
          image: {
            extension: "jpg|jpeg|png|ico|bmp"
          },
          start_datetime: {
            date:true,
            dateLessThan : '#end_datetime'
          },
          end_datetime: {
            date:true
          }
        },
        messages: {
          image: "Please Attach a file with valid extension"
        }
      });

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $("#deleteImage").click(function(){

            if (confirm('Are you sure you want to this image?')) {
                $.ajax({
                    url: "{{ route('admin.pages.deleteImage') }}",
                    type: 'POST',
                    data: {_token: "{{ csrf_token() }}", page_id: "{{$page->id}}"},
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
