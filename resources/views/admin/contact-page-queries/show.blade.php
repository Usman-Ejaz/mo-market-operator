@extends('admin.layouts.app')
@section('header', 'Contact Page Query')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.contact-page-queries.index') }}">Contact Page Queries</a></li>
<li class="breadcrumb-item active">Details</li>
@endsection
@section('addButton')
@if(hasPermission('contact-page-queries', 'delete'))
<form method="POST" action="{{ route('admin.contact-page-queries.destroy', $contactPageQuery->id) }}" class="float-right">
	@method('DELETE')
	@csrf
	<button class="btn btn-danger" onclick="return confirm('Are You Sure Want to delete this record?')">Delete</button>
</form>
@endif
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card card-primary">
				<div class="card-header">
					<h3 class="card-title">Viewing Query - {{ $contactPageQuery->subject }}</h3>
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
				</div>
			</div>
		</div>
	</div>
</div>
@endsection