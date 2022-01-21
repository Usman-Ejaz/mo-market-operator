@extends('admin.layouts.app')
@section('header', 'Jobs')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item">Jobs</li>
  <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
  <div class="container-fluid">
              <form method="POST" action="{{ route('admin.jobs') }}" enctype="multipart/form-data" id="create-job-form">
                <div class="row">
                  <div class="col-md-9">
                    <div class="card card-primary">
                      <div class="card-header">
                        <h3 class="card-title">Editing Job - {{ $job->title }}</h3>
                      </div>
                      <!-- /.card-header -->
                      <!-- form start -->
                      @include('admin.jobs.form')

                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card card-primary">
                      <div class="card-header">
                        <h3 class="card-title">Schedule Content</h3>
                      </div>
                        @include('admin.jobs.publishform')
                    </div>

                    <!-- /.card-body -->
                    <div class="float-right">

                      <input type="hidden" name="active" id="status">

                      @if( \Route::current()->getName() == 'admin.jobs.edit' )
                          @if($job->active == 'Active')
                            <button type="submit" class="btn btn-primary publish_button">Update</button>
                            <button type="submit" class="btn btn-danger draft_button">Unpublish</button>
                          @elseif($job->active == 'Draft')
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
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

    </div>
@endsection


@push('optional-styles')
  <link rel="stylesheet" href="{{ asset('admin-resources/css/tempusdominus-bootstrap-4.min.css') }}">
  <style>
  .my-error-class {
    color:#FF0000;  /* red */
    font-size: none;
    font-weight: none!important;
  }
  .my-valid-class {
    color:#00CC00; /* green */
  }
  </style>
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
      $('#starttime').datetimepicker({ icons: { time: 'far fa-clock' } });
      $('#endtime').datetimepicker({ icons: { time: 'far fa-clock' } });
      // Set hidden fields based on button click
      $('.draft_button').click(function(e) {
        $('#status').val("0");
      });

      $('.publish_button').click(function(e) {
        $('#status').val("1");
      });

      $('#create-job-form').validate({
        errorElement: 'span',
        errorClass: "my-error-class",
        validClass: "my-valid-class",
        rules:{
          title: {
            required: true,
            maxlength: 500
          },
          description:{
            required: true,
            maxlength: 50000
          },
          qualification: {
            required: true,
            maxlength: 2000
          },
          experience: {
            required: true,
            maxlength: 500
          },
          location: {
            required: true,
            maxlength: 500
          },
          total_positions: {
            required: true,
            number: true,
            min:1,
            maxlength: 4
          },
          image: {
            extension: "jpg|jpeg|png|ico|bmp"
          },
          enable: {
            required: false,
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
            maxlength: "Title cannot be more than 500 characters"
          },
          description: {
            required: "Description is required",
            maxlength: "Description cannot be more than 50000 characters"
          },
          qualification: {
            required: "Qualification is required",
            maxlength: "Qualification cannot be more than 2000 characters",
          },
          experience: {
            required: "Experience is required",
            maxlength: "Experience cannot be more than 500 characters"
          },
          location: {
            required: "Location is required",
            maxlength: "Location cannot be more than 500 characters"
          },
          total_positions: {
            required: "Total positions is required",
            number:"Total positions should be a number",
            min:"Total positions cannot be negative",
            maxlength: "Total positions cannot be more than 4 digits"
          },
          image: {
            extension: "This type of file is not accepted"
          },
          enable: {
            required: "Enable is not required"
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
