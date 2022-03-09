@extends('admin.layouts.app')
@section('header', 'Clients')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.clients.index') }}">Clients</a></li>
<li class="breadcrumb-item active">View</li>
@endsection

@section('addButton')
@if(hasPermission('clients', 'delete'))
<form method="POST" action="{{ route('admin.clients.destroy', $client->id) }}" class="float-right">
	@method('DELETE')
	@csrf
	<button class="btn btn-danger" onclick="return confirm('Are You Sure Want to delete this record?');">Delete</button>
</form>
@endif
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card card-primary">
				<div class="card-header">
					<h3 class="card-title">View Client - {{ $client->name }}</h3>
				</div>
				<!-- /.card-header -->
				<!-- form start -->
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Name</label>
								<span>{{ $client->name }}</span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Email</label>
								<span>{{$client->email}}</span>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Role</label>
								<br />
								<div>{{$client->role}}</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Department</label>
								<span>{{$client->department ?? 'None'}}</span>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Status</label>
								<span>{{$client->active}}</span>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Image</label>
								@if( isset($client->image) )
								<img src="{{ asset( config('filepaths.clientProfileImagePath.public_path') . $client->image ) }}" class="img-fluid">
								@else
								<span>None</span>
								@endif
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