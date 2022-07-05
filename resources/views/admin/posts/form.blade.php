<div class="card-body">
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="title">Title <span class="text-danger">*</span></label>
				<input type="input" class="form-control" id="title" autocomplete="off" placeholder="Enter Post Title" name="title" value="{{ old('title') ?? $post->title }}">
				<span class="form-text text-danger">{{ $errors->first('title') }} </span>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="slug">Slug <span class="text-danger">*</span></label>
				<input type="input" class="form-control" id="slug" autocomplete="off" placeholder="Post Slug" name="slug" value="{{ old('slug') ?? $post->slug }}" readonly>
				<span class="form-text text-danger">{{ $errors->first('slug') }} </span>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label for="description">Description <span class="text-danger">*</span></label>
		<textarea class="form-control ckeditor" id="description" placeholder="Enter Post Description" name="description" rows="400" cols="50">{{ old('description') ?? $post->description }}</textarea>
		<span class="form-text text-danger">{{ $errors->first('description') }} </span>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="keywords">Keywords</label>
				<input type="text" class="form-control" id="keywords" autocomplete="off" placeholder="Enter keywords" name="keywords" value="{{ old('keywords') ?? $post->keywords }}" data-role="tagsinput">
				<span class="form-text text-danger">{{ $errors->first('keywords') }} </span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label>Category <span class="text-danger">*</span></label>
				<select class="custom-select" name="post_category" id="post_category">
					<option value="">Please select an option</option>
					@foreach($post->postCategoryOptions() as $categoryId => $categoryValue)
						@if(old('post_category') == $categoryId)
							<option value="{{ $categoryId }}" selected>{{ $categoryValue }}</option>
						@else
							<option value="{{ $categoryId }}" {{ ($post->post_category === $categoryValue) ? 'selected' : '' }}>{{ $categoryValue }}</option>
						@endif
					@endforeach
				</select>
				<span class="form-text text-danger">{{ $errors->first('post_category') }} </span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="post_image" class="form-label">Post Image <small>(Allowed max size is 2MB. Allowed types are {{ str_replace("|", ", ", config('settings.image_file_extensions')) }}. Recommended Image dimensions are 1365 X 450)</small></label>
				<input class="form-control" type="file" id="post_image" name="image" onchange="handleFileChoose(event)">
				<span class="form-text text-danger">{{ $errors->first('image') }} </span>
				@if( isset($post->image) )
				<small class="text-primary imageExists">
					<a href="{{ $post->image }}" target="_blank">
						<img src="{{ $post->image }}" target="_blank" class="img-thumbnail" style="height: 200px;">
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
