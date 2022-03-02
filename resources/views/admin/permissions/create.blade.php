@extends('admin.layouts.app')
@section('header', 'Permissions')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Permissions</a></li>
  <li class="breadcrumb-item active">Create</li>
@endsection


@section('content')
  <div class="container-fluid">

              <form method="POST" action="{{ route('admin.roles') }}" enctype="multipart/form-data" id="create-roles-form">
                <div class="row">
                  <div class="col-md-12">
                    <div class="card card-primary">
                      <div class="card-header">
                        <h3 class="card-title">Create Role</h3>
                      </div>
                      <!-- /.card-header -->
                      <!-- form start -->
                      @include('admin.roles.form')

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                  </div>


                    <!-- /.card-body -->


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
@endpush

@push('optional-scripts')
  <script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>

  <script>

    //Date and time picker
    $(document).ready(function(){

      $('#create-roles-form').validate({
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
        }
      });

    });
  </script>

@endpush
