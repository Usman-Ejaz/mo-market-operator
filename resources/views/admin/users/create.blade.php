@extends('admin.layouts.app')
@section('header', 'Users')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item">Users</li>
  <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
  <div class="container-fluid">

              <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" id="create-users-form">
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


@push('optional-scripts')
  <script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>

  <script>

    //Date and time picker
    $(document).ready(function(){

      $('#create-users-form').validate({
        errorElement: 'span',
        errorClass: "my-error-class",
        validClass: "my-valid-class",
        rules:{
          name: {
            required: true,
            maxlength: 255
          },
          email:{
            required: true,
            email: true
          },
          role_id: {
            required: true,
            number: true
          },
          department: {
            required: true,
            number: true
          },
          image: {
            extension: "jpg|jpeg|png"
          },
          status: {
            required : true,
          }
        }
      });

    });
  </script>

@endpush
