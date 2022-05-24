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
								<label>Name: </label>
								<span>{{ $client->name }}</span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Business: </label>
								<span>{{ $client->business }}</span>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Type: </label>
								<span>{{ __('client.registration_types.' . $client->type) }}</span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Categories: </label>
								<span>{{ $client->category_labels }}</span>
							</div>
						</div>
					</div>

					<h4 class="mt-3">Primary Details</h4>
					<hr />
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Name</label>
								<span>{{ $client->pri_name }}</span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Email</label>
								<span>{{ $client->pri_email }}</span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Address</label>
								<span>{{ $client->pri_address }}</span>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Telephone</label>
								<span>{{ $client->pri_telephone }}</span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Facsimile Telephone</label>
								<span>{{ $client->pri_facsimile_telephone }}</span>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Signature: </label>
								<img src="{{ $client->pri_signature }}" class="img-thumbnail" style="width: 23%; display: block;">
							</div>
						</div>
					</div>

					<h4 class="mt-3">Secondary Details</h4>
					<hr />
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Name</label>
								<span>{{ $client->sec_name }}</span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Email</label>
								<span>{{ $client->sec_email }}</span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Address</label>
								<span>{{ $client->sec_address }}</span>
							</div>
						</div>
					</div>					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Telephone</label>
								<span>{{ $client->sec_telephone }}</span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Facsimile Telephone</label>
								<span>{{ $client->sec_facsimile_telephone }}</span>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Signature: </label>
								<img src="{{ $client->sec_signature }}" class="img-thumbnail" style="width: 23%; display: block;">
							</div>
						</div>
					</div>

					@if ($client->attachments->count() > 0)
						<h4 class="mt-3">Attachments</h4>
						<hr />
						<h5 class="mt-3 mb-2" style="font-weight: bold;">{{ __("General Attachments") }}</h5>
						<ul>
							@foreach($client->generalAttachments() as $attachment)
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<li>
												<p style="display: inline;">{{ $attachment->phrase }} </p>
												<a href="{{ route('admin.attachment.download', ["clients/attachments", basename($attachment->file)]) }}">{{ __("View") }}</a>
											</li>
										</div>
									</div>
								</div>
							@endforeach
						</ul>						

						@foreach($client->categoryAttachments() as $categoryId => $attachments)
							<h5 class="mt-3 mb-2" style="font-weight: bold;">{{ ucwords(\App\Models\Client::REGISTER_CATEGORIES[$categoryId]) }}</h5>
							<ul>
								@foreach($attachments as $attachment)
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<li>
													<p style="display: inline;">{{ $attachment->phrase }} </p>
													<a href="{{ route('admin.attachment.download', ["clients/attachments", basename($attachment->file)]) }}">{{ __("View") }}</a>
												</li>
											</div>
										</div>
									</div>
								@endforeach
							</ul>							
						@endforeach
					@endif
				</div>
			</div>
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->

</div>
@endsection