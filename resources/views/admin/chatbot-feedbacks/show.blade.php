@extends('admin.layouts.app')
@section('header', 'View Chatbot Feedback')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.chatbot-feedbacks.index') }}">Chatbot Feedback</a></li>
<li class="breadcrumb-item active">View Chatbot Feedback</li>
@endsection

@section('addButton')
@if(hasPermission('chatbot_feedback', 'delete'))
<button class="btn btn-danger deleteButton float-right">Delete</button>
@endif

@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">View Chatbot Feedback - {{ truncateWords($chatbotFeedback->feedback, 20) }}</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Email: </label>
                                <span>{{ $chatbotFeedback->owner->email }}</span>
                            </div>
                        </div>
                    </div>

					<div class="row">
						<div class="col-md-12">
                            <div class="form-group">
                                <label>Rating: </label>
                                <span>{{ $chatbotFeedback->rating }}</span>
                            </div>
                        </div>
					</div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Feedback: </label>
                                <span>{{ $chatbotFeedback->feedback }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->

</div>
@include('admin.includes.delete-popup')
@endsection

@push('optional-scripts')
    <script>
        let action = "";
        $(function () {
            $('body').on('click', '.deleteButton', (e) => {
                action = '{{ route("admin.chatbot-feedbacks.destroy", $chatbotFeedback->id) }}';
                $('#deleteModal').modal('toggle');
            });

            $('#deleteForm').submit(function (event) {
                $(this).attr('action', action);
            });
        });

    </script>
@endpush
