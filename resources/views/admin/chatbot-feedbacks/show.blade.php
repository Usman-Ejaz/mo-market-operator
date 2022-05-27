@extends('admin.layouts.app')
@section('header', 'Chatbot Feedback')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.chatbot-feedbacks.index') }}">Chatbot Feedback</a></li>
<li class="breadcrumb-item active">View</li>
@endsection

@section('addButton')
@if(hasPermission('chatbot_feedback', 'delete'))
<form method="POST" action="{{ route('admin.chatbot-feedbacks.destroy', $chatbotFeedback->id) }}" class="float-right">
    @method('DELETE')
    @csrf
    <button class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
</form>
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
@endsection
