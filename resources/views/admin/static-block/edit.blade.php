@extends('admin.layouts.app')
@section('header', 'Static Block')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.static-block.index') }}">Static Block</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">

  <form method="POST" action="{{ route('admin.static-block.update', $staticBlock->id) }}" enctype="multipart/form-data" id="update-static-block-form">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Edit Static Block - {{ truncateWords($staticBlock->contents, 30) }}</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          @method('PATCH')
          @include('admin.static-block.form')

          <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary draft_button width-120">Update</button>
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
  $(document).ready(function() {
    $.validator.addMethod("notNumericValues", function(value, element) {
      return this.optional(element) || isNaN(Number(value));
    }, '{{ __("messages.not_numeric") }}');

    $('#update-static-block-form').validate({
      errorElement: 'span',
      errorClass: "my-error-class",
      validClass: "my-valid-class",
      rules: {
        contents: {
          required: true,
          maxlength: 255,
          minlength: 2,
          notNumericValues: true
        }
      },
      messages: {
        contents: {
          minlength: "{{ __('messages.min_characters', ['field' => 'Contents', 'limit' => 3]) }}",
          required: "This field is required.",
          maxlength: "{{ __('messages.max_characters', ['field' => 'Contents', 'limit' => 64]) }}"
        }
      }
    });
  });
</script>

@endpush