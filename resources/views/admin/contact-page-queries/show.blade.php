@extends('admin.layouts.app')
@section('header', 'View Query')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.contact-page-queries.index') }}">Contact Page Queries</a></li>
    <li class="breadcrumb-item active">View Query</li>
@endsection
@section('addButton')
    @if (hasPermission('contact_page_queries', 'delete'))
        <button class="btn btn-danger deleteButton float-right">Delete</button>
    @endif
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('admin.contact-page-queries.add-reply', $contactPageQuery->id) }}" method="POST"
                    enctype="multipart/form-data" id="update-form">
                    @csrf
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">View Query - {{ $contactPageQuery->subject }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <span> {{ $contactPageQuery->name }} </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <span> {{ $contactPageQuery->email }} </span>
                                    </div>
                                </div>
                            </div>
                            @if ($isChatbotQuery)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <span> {{ $contactPageQuery->phone }} </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Company</label>
                                            <span> {{ $contactPageQuery->company }} </span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Subject</label>
                                        <span> {{ $contactPageQuery->subject }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Message</label>
                                        <span> {{ $contactPageQuery->message }} </span>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="reply">Reply <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="reply" placeholder="Enter your reply here" name="reply" rows="10"
                                            cols="50">{{ old('reply') ?? $contactPageQuery->comments }}</textarea>
                                        <span class="form-text text-danger">{{ $errors->first('reply') }} </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="float-right">
                                <button type="submit" class="btn width-120 btn-primary draft_button">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('admin.includes.delete-popup')
@endsection

@push('optional-scripts')
    <script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('admin-resources/js/form-custom-validator-methods.js') }}"></script>

    <script>
        let action = "";
        $(document).ready(function() {

            $('#update-form').validate({
                errorElement: 'span',
                errorClass: "my-error-class",
                validClass: "my-valid-class",
                rules: {
                    reply: {
                        required: true,
                        minlength: 3,
                        maxlength: 255,
                        notNumericValues: true
                    }
                },
                messages: {
                    reply: {
                        required: '{{ __('messages.required') }}',
                        minlength: '{{ __('messages.min_characters', ['field' => 'Name', 'limit' => 3]) }}',
                        maxlength: '{{ __('messages.max_characters', ['field' => 'Name', 'limit' => 255]) }}'
                    }
                }
            });

            $('body').on('click', '.deleteButton', (e) => {
                action = '{{ route('admin.contact-page-queries.destroy', $contactPageQuery->id) }}';
                $('#deleteModal').modal('toggle');
            });

            $('#deleteForm').submit(function(event) {
                $(this).attr('action', action);
            });
        });
    </script>
@endpush
