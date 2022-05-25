@extends('admin.layouts.app')
@section('header', 'Feedback Ratings')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.feedback-ratings.index') }}">Feedback Ratings</a></li>
<li class="breadcrumb-item active">View</li>
@endsection

@section('addButton')
@if(hasPermission('feedback_ratings', 'delete'))
<form method="POST" action="{{ route('admin.feedback-ratings.destroy', $feedbackRating->id) }}" class="float-right">
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
                    <h3 class="card-title">View Feedback Ratings - {{ truncateWords($feedbackRating->feedback, 20) }}</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Email: </label>
                                <span>{{ $feedbackRating->owner->email }}</span>
                            </div>
                        </div>						
                    </div>

					<div class="row">
						<div class="col-md-12">
                            <div class="form-group">
                                <label>Rating: </label>
                                <span>{{ $feedbackRating->rating }}</span>
                            </div>
                        </div>
					</div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Feedback: </label>
                                <span>{{ $feedbackRating->feedback }}</span>
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
