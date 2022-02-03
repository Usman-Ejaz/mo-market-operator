@extends('admin.layouts.app')
@section('header', 'Users')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
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

      $('#create-users-form').validate({
        errorElement: 'span',
        errorClass: "my-error-class",
        validClass: "my-valid-class",
        rules:{
          name: {
            required: true,
            maxlength: 255,
            minlength: 2,
            notNumericValues: true,
            noSpace:true,
          },
          email:{
            required: true,
            email: true,
            notNumericValues: true,
            noSpace:true,
          },
          role_id: {
            required: true,
            number: true
          },
          department: {
            required: true,
            number: true,
            noSpace:true,
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
