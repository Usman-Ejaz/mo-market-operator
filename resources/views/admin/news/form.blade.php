<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="title">Title</label>
        <input type="input" class="form-control" id="title" placeholder="Enter News Title" name="title" value="{{ old('title') ?? $news->title }}">
        <small class="form-text text-danger">{{ $errors->first('title') }} </small>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label for="slug">Slug</label>
        <input type="input" class="form-control" id="slug" placeholder="Enter News Slug"  name="slug" value="{{ old('slug') ?? $news->slug }}">
        <small class="form-text text-danger">{{ $errors->first('slug') }} </small>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label for="description">Description</label>
    <textarea class="form-control ckeditor" id="description" placeholder="Enter News Description" name="description" rows="400" cols="50">{{ old('description') ?? $news->description }}</textarea>
    <small class="form-text text-danger">{{ $errors->first('description') }} </small>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="keywords">Keywords</label>
        <input type="input" class="form-control" id="keywords" placeholder="Enter keywords" name="keywords" value="{{ old('keywords') ?? $news->keywords }}">
        <small class="form-text text-danger">{{ $errors->first('keywords') }} </small>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label>Category</label>
        <select class="custom-select" name="newscategory_id" id="newscategory_id">
          <option value="">Please select an option</option>
          @foreach($categories as $category)
            <option value="{{$category->id}}" {{ $news->newscategory_id == $category->id ? 'selected' : ''}} >{{$category->name}}</option>
          @endforeach
        </select>
        <small class="form-text text-danger">{{ $errors->first('category') }} </small>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="image">News Image</label>
        <div class="input-group">
          <div class="custom-file">
            <input type="file" class="custom-file-input" id="image"  name="image">
            <label class="custom-file-label" for="image">Choose file</label>
          </div>
        </div>
        <small class="form-text text-danger">{{ $errors->first('image') }} </small>
      </div>
    </div>
  </div>

</div>
<!-- /.card-body -->

@csrf