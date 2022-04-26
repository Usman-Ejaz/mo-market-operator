@extends('admin.layouts.app')
@section('header', 'View Query')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.contact-page-queries.index') }}">Contact Page Queries</a></li>
<li class="breadcrumb-item active">View Query</li>
@endsection
@section('addButton')
@if(hasPermission('contact_page_queries', 'delete'))
<form method="POST" action="{{ route('admin.contact-page-queries.destroy', $contactPageQuery->id) }}" class="float-right">
	@method('DELETE')
	@csrf
	<button class="btn btn-danger" onclick="return confirm('{{ __('messages.record_delete') }}')">Delete</button>
</form>
@endif
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
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