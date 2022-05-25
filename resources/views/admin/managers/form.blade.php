<div class="card-body">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="name">Name <span class="text-danger">*</span></label>
				<input type="text" class="form-control" id="name" placeholder="Enter full name" name="name" value="{{ old('name') ?? $manager->name }}">
				<span class="form-text text-danger">{{ $errors->first('name') }} </span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="designation">Designation <span class="text-danger">*</span></label>
				<input type="text" class="form-control" id="designation" placeholder="Enter Designation" name="designation" value="{{ old('designation') ?? $manager->designation }}">
				<span class="form-text text-danger">{{ $errors->first('designation') }} </span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="description">Description <span class="text-danger">*</span></label>
				<textarea class="form-control ckeditor" id="description" placeholder="Enter Description" name="description" rows="400" cols="50">{{ old('description') ?? $manager->description }} </textarea>
				<span class="form-text text-danger">{{ $errors->first('description') }} </span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="order">Order <span class="text-danger">*</span></label>
				<input type="number" class="form-control" id="order" placeholder="Enter order" name="order" value="{{ old('order') ?? $manager->order }}" step="1" min="0">
				<span class="form-text text-danger">{{ $errors->first('order') }} </span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="image">Profile Image <small>(Max allowed size is 2MB. Allowed types are {{ str_replace("|", ", ", config('settings.image_file_extensions')) }})</small></label>
				<input type="file" class="form-control" id="image" name="image" onchange="handleFileChoose(event)">
				<span class="form-text text-danger">{{ $errors->first('image') }} </span>
				@if(isset($manager->image) && \Route::current()->getName() == 'admin.managers.edit')
					<small class="text-primary imageExists">
						<a href="{{ $manager->image }}" target="_blank">
							<img src="{{ $manager->image }}" target="_blank" class="img-thumbnail" style="height: 200px;">
						</a>
						<span class="btn-sm btn-danger float-right" id="deleteImage"><i class="fa fa-trash"></i></span> 
					</small>
				@endif
			</div>
		</div>
	</div>
</div>
<!-- /.card-body -->

@csrf