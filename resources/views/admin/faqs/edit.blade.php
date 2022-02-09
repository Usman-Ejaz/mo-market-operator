@extends('admin.layouts.app')
@section('header', 'FAQ')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.faqs.index') }}">FAQ</a></li>
  <li class="breadcrumb-item active">Update</li>
@endsection

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('admin.faqs.update', $faq->id) }}" enctype="multipart/form-data" id="update-faq-form">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Editing FAQ # {{$faq->id}}</h3>
                        </div>
                        <!-- form start -->
                        @method('PATCH')
                        @include('admin.faqs.form')
                        <div class="card-footer">
                            <div class="float-right">
                                <input type="hidden" name="active" id="status">
                                <input type="hidden" name="action" id="action">
                                
                                @if($faq->active == 'Active')
                                  <button type="submit" class="btn btn-primary publish_button">Update</button>
                                  <button type="submit" class="btn btn-danger unpublish_button">Unpublish</button>
                                @elseif($faq->active == 'Draft')
                                  <button type="submit" class="btn btn-primary draft_button">Update</button>
                                  @if( Auth::user()->role->hasPermission('faqs', 'publish') )
                                    <button type="submit" class="btn btn-success publish_button">Publish</button>
                                  @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>     
    </div>   
@endsection

@push('optional-scripts')
  <script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
  <script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>

  <script>
    $(document).ready(function(){
      // Set hidden fields based on button click
      $('.draft_button').click(function(e) {
        $('#status').val("0");
        $('#action').val("Updated");
      });

      $('.publish_button').click(function(e) {
        $('#status').val("1");  
        $('#action').val("Published");
      }); 

      $('.unpublish_button').click(function(e) {
        $('#status').val("0");  
        $('#action').val("Unpublished");
      }); 

      $.validator.addMethod("notNumericValues", function(value, element) {
          return this.optional(element) || isNaN(Number(value));
      }, '{{ __("messages.not_numeric") }}');

      $.validator.addMethod("ckeditor_required", function(value, element) {        
        var editorId = $(element).attr('id');
        var messageLength = CKEDITOR.instances[editorId].getData().replace(/<[^>]*>/gi, '').length;
        return messageLength !== 0;
      }, '{{ __("messages.ckeditor_required") }}');

      $('#update-faq-form').validate({
        ignore: [],
        errorElement: 'span',
        errorClass: "my-error-class",
        validClass: "my-valid-class",
        ignore: [],
        rules:{
          question: {
            required: true,
            minlength: 2,
            notNumericValues: true,            
          },
          answer:{
            ckeditor_required: true,
            minlength: 5
          }
        },
        errorPlacement: function (error, element) {
          if (element.attr("id") == "answer") {
            element = $("#cke_" + element.attr("id"));
          }
          error.insertAfter(element);
        }
      });
    });
  </script>

@endpush
