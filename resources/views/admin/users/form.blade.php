<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
          <label for="name">Name <span class="text-danger">*</span></label>
        <input type="input" class="form-control" id="name" placeholder="Enter full name" name="name" value="{{ old('name') ?? $user->name }}">
        <small class="form-text text-danger">{{ $errors->first('name') }} </small>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label for="email">Email <span class="text-danger">*</span></label>
        <input type="input" class="form-control" id="email" placeholder="Enter Email"  name="email" value="{{ old('email') ?? $user->email }}">
        <small class="form-text text-danger">{{ $errors->first('email') }} </small>
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
            <option value="{{$role->id}}" {{ ( isset($user->role->id) && $user->role->id === $role->id) ? 'selected' : '' }}>{{$role->name}}</option>
          @endforeach
        </select>
        <small class="form-text text-danger">{{ $errors->first('role_id') }} </small>
      </div>
    </div>
    <input type="hidden" class="form-control" id="department" placeholder="Enter Department" name="department" value="1">

    <!-- <div class="col-md-6">
      <div class="form-group">
          <label for="department">Department <span class="text-danger">*</span> </label>
          <input type="input" class="form-control" id="department" placeholder="Enter Department" name="department" value="{{ old('department') ?? $user->department }}">
          <small class="form-text text-danger">{{ $errors->first('department') }} </small>
      </div>
    </div> -->

    <div class="col-md-6">
            <div class="form-group">
                <label>Status <span class="text-danger">*</span></label>
                <select class="custom-select" name="active" id="active">
                    <option value="">Please select a status</option>
                    @foreach($user->activeOptions() as $statusId => $statusValue)
                        <option value="{{$statusId}}" {{ ($user->active === $statusValue) ? 'selected' : '' }}>{{$statusValue}}</option>
                    @endforeach
                </select>
                <small class="form-text text-danger">{{ $errors->first('active') }} </small>
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
                <small class="form-text text-danger">{{ $errors->first('active') }} </small>
            </div>
        </div> -->

            <div class="col-md-6">
                <div class="form-group">
                    <label for="news_image" class="form-label" >User Profile Image</label>
                    <input class="form-control" type="file" id="image" name="image">
                    <small class="form-text text-danger">{{ $errors->first('image') }} </small>
                    @if( isset($user->image) )
                        <small class="text-primary imageExists"><a href="{{ $user->image }}" target="_blank"><img src="{{ $user->image }}" target="_blank" class="img-thumbnail" style="width: 23%;"></a><span class="btn-sm btn-danger float-right" id="deleteImage"><i class="fa fa-trash"></i></span> </small>
                    @endif
                </div>
            </div>
        </div>
</div>
<!-- /.card-body -->

@csrf
