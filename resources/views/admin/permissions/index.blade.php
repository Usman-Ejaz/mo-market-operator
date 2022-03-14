@extends('admin.layouts.app')
@section('header', 'Permissions')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Permissions</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="name">Select Role <span class="text-danger">*</span></label>
				<select class="custom-select" name="role" id="role">
					<option value="">Please select a role</option>
					@foreach($roles as $role)
					<option value="{{$role->id}}">{{$role->name}}</option>
					@endforeach
				</select>

				<small class="form-text text-danger">{{ $errors->first('name') }} </small>
			</div>
		</div>
		<!-- /.row -->
	</div>

	<div class="row">
		<div class="col-md-12">
			<!-- /.card-header -->
			<form method="POST" action="{{ route('admin.permissions.store')}}" enctype="multipart/form-data" id="role-permission-form" style="display:none;">
				<table class="table table-bordered" style="background-color: var(--white)">
					<thead>
						<tr>
							<th style="width:25%;">Module / Feature</th>
							<th style="width:75%;">Capabilities</th>
						</tr>
					</thead>
					<tbody>

						@foreach( $permissions as $permission)
						<tr>
							<td>
								<div class="row">
									<div class="col-md-10">
										{{ $permission['display_name'] }}
									</div>
									<div class="col-md-2">
										<input type="checkbox" name="select_all_permissions" class="select-permissions" id="{{ $permission['name'] }}-permissions" />
									</div>
								</div>
							</td>
							<td>
								<div class='row'>
									@php $count = 0; @endphp
									@foreach( $permission['capabilities'] as $capability_name => $capability_display_name)
									@php $count++; @endphp
									@if( $count == 1)
									<div class='col-md-4'>
										@endif
										<div>
											<input type="checkbox" id="{{ $permission['name'].'-'.$capability_name }}" class="single-permission" name="{{ 'permissions['.$permission['name'].']['.$capability_name.']' }}">
											<label for="{{ $permission['name'].'-'.$capability_name }}">{{ $capability_display_name }}</label>
										</div>
										@if ($count == 2)
									</div>
									@php $count = 0 @endphp
									@endif
									@endforeach
								</div>

							</td>
						</tr>
						@endforeach

					</tbody>
				</table>

				@if( hasPermission('permissions', 'edit') )
				<div class="text-right mb-5">
					<input type="submit" class="btn btn-primary width-120" value="Update" />
				</div>
				@endif

				<input type="hidden" id="role_id" name="role_id" value="">
				@csrf
			</form>
		</div>
	</div>
	<!-- /.container-fluid -->
</div>
@endsection

@push('optional-styles')

@endpush

@push('optional-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script type="text/javascript">
	$(function() {
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		$("body").on('change', '#role', function() {
			var role_id = $('#role').val();

			if (role_id) {
				$('#role-permission-form input:checkbox').prop('checked', false);
				$('#role_id').val(role_id);
				$('#role-permission-form').show();
				$.ajax({
					url: "{{ route('admin.permissions.getpermissions') }}",
					type: 'POST',
					data: {
						_token: "{{ csrf_token() }}",
						role_id: role_id
					},
					dataType: 'JSON',
					success: function(data) {
						if (data.success) {
							data.data.forEach(function(permission) {
								$('#' + permission['name'] + '-' + permission['capability']).prop('checked', true);
							});
							let permissions = @json($permissions);
							permissions.forEach(module => {								
								let totalCapabilities = Object.keys(module.capabilities).length;
								let markedCounter = 0;
								Object.keys(module.capabilities).forEach(per => {
									if (document.getElementById(`${module.name}-${per}`).checked) {
										markedCounter++;
									}
								});

								if (markedCounter === totalCapabilities) {
									document.getElementById(`${module.name}-permissions`).checked = true;
								}
							})
						}
					}
				});
			} else {
				$('#role_id').val("0");
				$('#role-permission-form input:checkbox').prop('checked', false);
				$('#role-permission-form').hide();
			}
		});

		$('#role').val('{{ session()->get("role_id") }}').change();
		//   let searchParams = new URLSearchParams(window.location.search)
		//   if( searchParams.has('role_id') ){
		//   }

		$('.select-permissions').on('change', function(e) {
			let targetModule = e.target.id.split('-')[0];
			let permissions = @json($permissions);
			let targetModulePermissions = permissions.filter(permission => permission.name === targetModule)[0].capabilities;
			Object.keys(targetModulePermissions).forEach(key => {
				document.getElementById(`${targetModule}-${key}`).checked = e.target.checked;
			});
		});

		$('.single-permission').on('change', function (e) {
			let permissions = @json($permissions);
			permissions.forEach(module => {								
				let totalCapabilities = Object.keys(module.capabilities).length;
				let markedCounter = 0;
				Object.keys(module.capabilities).forEach(per => {
					if (document.getElementById(`${module.name}-${per}`).checked) {
						markedCounter++;
					}
				});
				
				document.getElementById(`${module.name}-permissions`).checked = markedCounter === totalCapabilities;
			});
		});
	});
</script>
@endpush