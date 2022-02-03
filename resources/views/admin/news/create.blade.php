@extends('admin.layouts.app')
@section('header', 'News')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">News</a></li>
  <li class="breadcrumb-item active">Create</li>
@endsection


@section('content')
  <div class="container-fluid">

              <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" id="create-news-form">
                <div class="row">
                  <div class="col-md-9">
                    <div class="card card-primary">
                      <div class="card-header">
                        <h3 class="card-title">Create News</h3>
                      </div>
                      <!-- /.card-header -->
                      <!-- form start -->
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
                            @if( Auth::user()->role->hasPermission('news', 'publish') )
                                <button type="submit" class="btn btn-danger draft_button">Unpublish</button>
                            @endif
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
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
@endsection

@push('optional-scripts')
  <script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
  <script src="{{ asset('admin-resources/js/moment.min.js') }}"></script>
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

      $('#create-news-form').validate({
        errorElement: 'span',
        errorClass: "my-error-class",
        validClass: "my-valid-class",
        rules:{
          title: {
            required: true,
            maxlength: 255,
            minlength: 2,
            notNumericValues: true,
            noSpace:true,
          },
          description:{
            required: true,
            maxlength: 50000
          },
          slug: {
            required: true,
            maxlength: 255,
            noSpace:true,
            notNumericValues: true,
          },
          news_category: {
            required: true,
          },
          image: {
            extension: "jpg|jpeg|png",
          },
          start_datetime: {
            required : false,
            dateLessThan : '#end_datetime'
          },
          end_datetime: {
            required : false,
          }
        }
      });

    });
  </script>

@endpush
