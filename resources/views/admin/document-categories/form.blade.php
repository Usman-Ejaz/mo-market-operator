<div class="card-body">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="name">Name <span class="text-danger">*</span></label>
				<input type="input" class="form-control" id="name" autocomplete="off" placeholder="Enter Category Name" name="name" value="{{ old('name') ?? $documentCategory->name }}">
				<span class="form-text text-danger">{{ $errors->first('name') }} </span>
			</div>
		</div>
	</div>

    <div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label>Parent Category </label>
				<select class="custom-select" name="parent_id" id="parent_id">
					<option value=""> --- Please select a category --- </option>
					@foreach($categories as $category)
						@if(old('parent_id') == $category->id)
							<option value="{{ $category->id }}" selected>{{ truncateWords($category->name, 35) }}</option>
						@else
							<option value="{{ $category->id }}" {{ ($category->id === $documentCategory->parent_id) ? 'selected' : '' }}>{{ truncateWords($category->name, 35) }}</option>
						@endif
					@endforeach
				</select>
				<span class="form-text text-danger">{{ $errors->first('category') }} </span>
			</div>
		</div>
	</div>
</div>
<!-- /.card-body -->
@csrf
