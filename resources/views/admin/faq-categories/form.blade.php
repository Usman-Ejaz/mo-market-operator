<div class="card-body">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="name">Name <span class="text-danger">*</span></label>
				<input type="input" autocomplete="off" class="form-control" id="name" placeholder="Enter Category Name" name="name" value="{{ old('name') ?? $faqCategory->name }}">
				<span class="form-text text-danger">{{ $errors->first('name') }} </span>
			</div>
		</div>
	</div>
</div>
<!-- /.card-body -->
@csrf