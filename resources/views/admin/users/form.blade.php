<div class="card-body">
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="name">Name <span class="text-danger">*</span></label>
				<input type="input" class="form-control" id="name" placeholder="Enter full name" name="name" value="{{ old('name') ?? $user->name }}">
				<span class="form-text text-danger">{{ $errors->first('name') }} </span>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="email">Email <span class="text-danger">*</span></label>
				<input type="input" class="form-control" id="email" placeholder="Enter Email" name="email" value="{{ old('email') ?? $user->email }}">
				<span class="form-text text-danger">{{ $errors->first('email') }} </span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label>Role <span class="text-danger">*</span></label>
				<select class="custom-select" name="role_id" id="role_id">
					<option value="">Please select an option</option>
					@foreach($user->roles() as $role)
						@if(old('role_id') == $role->id)
							<option value="{{ $role->id }}" selected> {{ $role->name }} </option>
						@else
							<option value="{{ $role->id }}" {{ ( isset($user->role->id) && $user->role->id === $role->id) ? 'selected' : '' }}>{{$role->name}}</option>
						@endif
					@endforeach
				</select>
				<span class="form-text text-danger">{{ $errors->first('role_id') }} </span>
			</div>
		</div>
		<input type="hidden" class="form-control" id="department" placeholder="Enter Department" name="department" value="1">

		<!-- <div class="col-md-6">
		<div class="form-group">
			<label for="department">Department <span class="text-danger">*</span> </label>
			<input type="input" class="form-control" id="department" placeholder="Enter Department" name="department" value="{{ old('department') ?? $user->department }}">
			<span class="form-text text-danger">{{ $errors->first('department') }} </span>
		</div>
		</div> -->

		<div class="col-md-6">
			<div class="form-group">
				<label for="designation">Designation <span class="text-danger">*</span></label>
				<input type="input" class="form-control" id="designation" placeholder="Enter Designation" name="designation" value="{{ old('designation') ?? $user->designation }}">
				<span class="form-text text-danger">{{ $errors->first('designation') }} </span>
			</div>
		</div>
	</div>

	<div class="row">
		<!-- <div class="col-md-6">
            <div class="form-group">
                <label>Status <span class="text-danger">*</span></label>
                <select class="custom-select" name="active" id="active">
                    <option value="">Please select a status</option>
                    @foreach($user->activeOptions() as $statusId => $statusValue)
                        <option value="{{$statusId}}" {{ ($user->active === $statusValue) ? 'selected' : '' }}>{{$statusValue}}</option>
                    @endforeach
                </select>
                <span class="form-text text-danger">{{ $errors->first('active') }} </span>
            </div>
        </div> -->
		<div class="col-md-6">
			<div class="form-group">
				<label for="image" class="form-label">User Profile Image <small>(Allowed max size is 2MB. Allowed types are jpg, jpeg, png)</small></label>
				<input class="form-control" type="file" id="image" name="image">
				<span class="form-text text-danger">{{ $errors->first('image') }} </span>
				@if( isset($user->image) )
				<span class="text-primary imageExists"><a href="{{ $user->image }}" target="_blank"><img src="{{ $user->image }}" target="_blank" class="img-thumbnail" style="width: 23%;"></a><span class="btn-sm btn-danger float-right" id="deleteImage"><i class="fa fa-trash"></i></span> </span>
				@endif
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Status <span class="text-danger">*</span></label>
				<select class="custom-select" name="active" id="active">
					<option value="">Please select a status</option>
					@if(Request::route()->getName() == "admin.users.create") 
						@foreach($user->activeOptions() as $statusId => $statusValue)
							<option value="{{$statusId}}">{{$statusValue}}</option>
						@endforeach
					@else
						@foreach($user->activeOptions() as $statusId => $statusValue)
							<option value="{{$statusId}}" {{ ($user->active === $statusValue) ? 'selected' : '' }}>{{$statusValue}}</option>
						@endforeach
					@endif
					
				</select>
				<span class="form-text text-danger">{{ $errors->first('active') }} </span>
			</div>
		</div>		
	</div>

	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<div class="form-check">
					<input type="checkbox" class="form-control-custom form-check-input" id="notifications" name="notifications" {{ $user->show_notifications === 1 ? 'checked' : '' }} value="1">
					<label for="notifications" class="form-check-label">Send Notifications</label>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /.card-body -->

@csrf