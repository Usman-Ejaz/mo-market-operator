@extends('admin.layouts.app')
@section('header', 'Roles')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item">Roles</li>
  <li class="breadcrumb-item active">Create</li>
@endsection


@section('content')
  <div class="container-fluid">

              <form method="POST" action="{{ route('admin.roles.store') }}" enctype="multipart/form-data" id="create-roles-form">
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
          name: {
            required: true,
            maxlength: 255
          }
        }
      });

    });
  </script>

@endpush
