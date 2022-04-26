<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="input" class="form-control" id="name" placeholder="Enter full name" name="name" value="{{ old('name') ?? $mediaLibrary->name }}">
                <span class="form-text text-danger">{{ $errors->first('name') }} </span>
            </div>
        </div>
    </div>
    <div class="form-group">
		<label for="description">Description </label>
		<textarea class="form-control ckeditor" id="description" placeholder="Enter Description" name="description" rows="400" cols="50">{{ old('description') ?? $mediaLibrary->description }}</textarea>
		<span class="form-text text-danger">{{ $errors->first('description') }} </span>
	</div>
</div>

@csrf
