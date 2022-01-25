<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="input" class="form-control" id="name" placeholder="Enter full name" name="name" value="{{ old('name') ?? $role->name }}">
                <small class="form-text text-danger">{{ $errors->first('name') }} </small>
            </div>
        </div>
    </div>

</div>
<!-- /.card-body -->

@csrf
