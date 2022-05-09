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
				<label for="image">Profile Image</label>
				<input type="file" class="form-control" id="image" name="image">
				<span class="form-text text-danger">{{ $errors->first('image') }} </span>
				@if(isset($manager->image))
					<small class="text-primary imageExists">
						<a href="{{ $manager->image }}" target="_blank">
							<img src="{{ $manager->image }}" target="_blank" class="img-thumbnail" style="width: 23%;">
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