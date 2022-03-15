<div class="card-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="question">Question <span class="text-danger">*</span></label>
        <input type="input" class="form-control" id="question" placeholder="Enter Question" name="question" value="{{ old('question') ?? $faq->question }}">
        <span class="form-text text-danger">{{ $errors->first('question') }} </span>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="category">Category <span class="text-danger">*</span></label>
        <select class="custom-select" name="category_id" id="category_id">
                    <option value="">Please select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ ($category->id === $faq->category_id) ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
        <span class="form-text text-danger">{{ $errors->first('category_id') }} </span>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label for="answer">Answer <span class="text-danger">*</span></label>
    <textarea class="form-control ckeditor" id="answer" placeholder="Enter Answer" name="answer" rows="30" cols="50">{{ old('answer') ?? $faq->answer }}</textarea>
    <span class="form-text text-danger">{{ $errors->first('answer') }} </span>
  </div>
</div>
<!-- /.card-body -->

@csrf