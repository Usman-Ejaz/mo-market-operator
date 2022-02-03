@extends('admin.layouts.app')
@section('header', 'Roles')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
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
@endpush

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

      $('#create-roles-form').validate({
        errorElement: 'span',
        errorClass: "my-error-class",
        validClass: "my-valid-class",
        rules: {
          name: {
            required: true,
            minlength: 3,
            maxlength: 255,
            minlength: 2,
            notNumericValues: true,
            noSpace:true,
          }
        }
      });

      // removing error labels when someone put value inside the input and leave the focus
      $('#create-roles-form').on("focusout", "input", function() {
        if ($(this).val().trim().length > 0) {
          $(this).hasClass("my-error-class") && $(this).removeClass("my-error-class");
          $(this).next().hasClass("my-error-class") && $(this).next().remove();
        }
      });
    });
  </script>

@endpush