@extends('admin.layouts.app')
@section('header', 'Users')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item">Users</li>
  <li class="breadcrumb-item active">Create</li>
@endsection


@section('content')
  <div class="container-fluid">

              <form method="POST" action="{{ url('/admin/users')}}" enctype="multipart/form-data" id="create-users-form">
                <div class="row">
                  <div class="col-md-12">
                    <div class="card card-primary">
                      <div class="card-header">
                        <h3 class="card-title">Create User</h3>
                      </div>
                      <!-- /.card-header -->
                      <!-- form start -->
                      @include('admin.users.form')

                    </div>
                  </div>


                    <!-- /.card-body -->
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Save</button>
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
  <script src="{{ asset('admin/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('admin/js/additional-methods.min.js') }}"></script>

  <script>

    //Date and time picker
    $(document).ready(function(){

      $('#create-users-form').validate({
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
