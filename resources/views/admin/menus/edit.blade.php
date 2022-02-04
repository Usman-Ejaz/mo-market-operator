@extends('admin.layouts.app')
@section('header', 'Main Menus')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.menus.index') }}">Main Menus</a></li>
  <li class="breadcrumb-item active">Update</li>
@endsection

@section('content')
  <div class="container-fluid">

      <form method="POST" action="{{ route('admin.menus.update', $menu->id) }}" enctype="multipart/form-data" id="update-menus-form">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Editing Menu - {{ $menu->name }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              @method('PATCH')
              @include('admin.menus.form')

              <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary draft_button">Update</button>
              </div>
            </div>
          </div>

        </div>
      </form>

    </div>
@endsection

@push('optional-styles')

@endpush

@push('optional-scripts')
  <script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>

  <script>
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

        $('#update-menus-form').validate({
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
                theme: {
                    required: true,
                    maxlength: 255,
                    minlength: 1,
                },
                active: {
                    required: true,
                }
            }
        });

    });

  </script>

@endpush
