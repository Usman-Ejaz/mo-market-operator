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

                    <input type="hidden" name="sendEmail" value="0" id="sendEmail">
                    <!-- /.card-body -->
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="submit" class="btn btn-success mr-2 save-with-email">Save & Send Email</button>
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
        '{{ __("messages.not_numeric") }}'
      );

      $.validator.addMethod("noSpace", function(value) { 
        this.value = $.trim(value);
        return this.value;
      });

      $(".save-with-email").click(function () {
        $("#sendEmail").val("1");
      });


      $('#create-users-form').validate({
        errorElement: 'span',
        errorClass: "my-error-class",
        validClass: "my-valid-class",
        rules:{
          name: {
            required: true,
            maxlength: 64,
            minlength: 3,
            notNumericValues: true
          },
          email:{
            required: true,
            email: true,
            notNumericValues: true,
            noSpace: true
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
          active: {
            required : true,
          }
        },
        messages: {
          image: '{{ __("messages.valid_file_extension") }}',
          name: {
            minlength: "{{ __('messages.min_characters', ['field' => 'Username', 'limit' => 3]) }}",
            required: "This field is required.",
            maxlength: "{{ __('messages.max_characters', ['field' => 'Username', 'limit' => 64]) }}"
          }
        }
      });

    });
  </script>

@endpush
