@extends('admin.layouts.app')
@section('header', 'View Client')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.clients.index') }}">Clients</a></li>
<li class="breadcrumb-item active">View Client</li>
@endsection

@section('addButton')
@if(hasPermission('clients', 'delete'))
    <button class="btn btn-danger deleteButton float-right">Delete</button>
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

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Address Line One: </label>
								<span>{{ $client->address_line_one }}</span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Address Line Two: </label>
								<span>{{ $client->address_line_two }}</span>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>City: </label>
								<span>{{ $client->city }}</span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>State: </label>
								<span>{{ $client->state }}</span>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Zip Code: </label>
								<span>{{ $client->zipcode }}</span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Country: </label>
								<span>{{ $client->country }}</span>
							</div>
						</div>
					</div>

					@foreach ($client->details as $detail)
						<h4 class="mt-3">{{ ucfirst($detail->type) }} Details</h4>
						<hr />
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Name: </label>
									<span>{{ $detail->name }}</span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Email: </label>
									<span>{{ $detail->email }}</span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Address Line One: </label>
									<span>{{ $detail->address_line_one }}</span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Address Line Two: </label>
									<span>{{ $detail->address_line_two }}</span>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Designation: </label>
									<span>{{ $detail->designation }}</span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>City: </label>
									<span>{{ $detail->city }}</span>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>State: </label>
									<span>{{ $detail->state }}</span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Zip Code: </label>
									<span>{{ $detail->zipcode }}</span>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Telephone: </label>
									<span>{{ $detail->telephone }}</span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Facsimile Telephone: </label>
									<span>{{ $detail->facsimile_telephone }}</span>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Signature: </label>
									<img src="{{ $detail->signature }}" class="img-thumbnail" style="width: 50%; display: block;">
								</div>
							</div>
						</div>
					@endforeach


                    <h4 class="mt-3">Declaration of Conformity</h4>
                    <hr />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name: </label>
                                <span>{{ $client->dec_name }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date: </label>
                                <span>{{ \Carbon\Carbon::parse($client->dec_date)->format('m-d-Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Signature: </label>
                                <img src="{{ $client->dec_signature }}" class="img-thumbnail" style="width: 50%; display: block;">
                            </div>
                        </div>
                    </div>

					@if ($client->attachments->count() > 0)
						<h4 class="mt-3">Attachments</h4>
						<hr />
						<h5 class="mt-3 mb-2" style="font-weight: bold;">
                            {{ __("General Attachments") }}
                            <a style="font-size: 1rem;font-weight: 400;" href="{{ route('admin.clients.downloadBulkFiles', ['client' => $client->id, 'category' => 0]) }}">{{ __("Download All") }}</a>
                        </h5>
						<ol>
							@foreach($client->generalAttachments() as $attachment)
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<li>
												<p style="display: inline;">{{ $attachment->phrase_string }} </p>
												<a href="{{ route('admin.attachment.download', ["clients/attachments", basename($attachment->file)]) }}">{{ __("View") }}</a>
											</li>
										</div>
									</div>
								</div>
							@endforeach
						</ol>

						@foreach($client->categoryAttachments() as $categoryId => $attachments)
							<h5 class="mt-3 mb-2" style="font-weight: bold;">
                                {{ __('client.categories.' . $client->type . '.' . \App\Models\Client::REGISTER_CATEGORIES[$categoryId]) }}
                                <a style="font-size: 1rem;font-weight: 400;" href="{{ route('admin.clients.downloadBulkFiles', ['client' => $client->id, 'category' => $categoryId]) }}">{{ __("Download All") }}</a>
                            </h5>
							<ol>
								@foreach($attachments as $attachment)
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<li>
													<p style="display: inline;">{{ $attachment->phrase_string }} </p>
													<a href="{{ route('admin.attachment.download', ["clients/attachments", basename($attachment->file)]) }}">{{ __("View") }}</a>
												</li>
											</div>
										</div>
									</div>
								@endforeach
							</ol>
						@endforeach
					@endif
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
                action = '{{ route("admin.clients.destroy", $client->id) }}';
                $('#deleteModal').modal('toggle');
            });

            $('#deleteForm').submit(function (event) {
                $(this).attr('action', action);
            });
        });

    </script>
@endpush
