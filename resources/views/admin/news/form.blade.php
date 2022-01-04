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
    <textarea class="form-control ckeditor" id="description" placeholder="Enter News Description" name="description">{{ old('description') ?? $news->description }}</textarea>
    <small class="form-text text-danger">{{ $errors->first('description') }} </small>
  </div>

  <div class="form-group">
    <label for="image">News Image</label>
    <div class="input-group">
      <div class="custom-file">
        <input type="file" class="custom-file-input" id="image"  name="image">
        <label class="custom-file-label" for="image">Choose file</label>
      </div>
      <div class="input-group-append">
        <span class="input-group-text">Upload</span>
      </div>
    </div>
    <small class="form-text text-danger">{{ $errors->first('image') }} </small>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="keywords">Keywords</label>
        <input type="input" class="form-control" id="keywords" placeholder="Enter keywords" name="keywords" value="{{ old('keywords') ?? $news->keywords }}">
        <small class="form-text text-danger">{{ $errors->first('keywords') }} </small>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label>Category</label>
        <select class="custom-select" name="newscategory_id">
          <option value="">Please select an option</option>
          @foreach($categories as $category)
            <option value="{{$category->id}}" {{ $news->newscategory_id == $category->id ? 'selected' : ''}} >{{$category->name}}</option>
          @endforeach
        </select>
        <small class="form-text text-danger">{{ $errors->first('category') }} </small>
      </div>
    </div>
  </div>  

  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label>Start DateTime:</label>
          <div class="input-group date" id="starttime" data-target-input="nearest">
              <input type="text" class="form-control datetimepicker-input" data-target="#starttime"  value="{{ old('start_datetime') ?? $news->start_datetime }}">
              <div class="input-group-append" data-target="#starttime" data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
              </div>
              <small class="form-text text-danger">{{ $errors->first('start_datetime') }} </small>
          </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label for="endtime">End DateTime</label>
        <input type="input" class="form-control" id="endtime" placeholder="Schedual End Time" name="end_datetime" value="{{ old('end_datetime') ?? $news->end_datetime }}">
        <small class="form-text text-danger">{{ $errors->first('end_datetime') }} </small>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <div class="form-group">
          <label>Status</label>
          <select class="custom-select" name="active">
            <option value="" disabled>Please select an option</option>
            @foreach($news->activeOptions() as $activeOptionKey => $activeOptionValue)
              <option value="{{$activeOptionKey}}" {{ $news->active == $activeOptionValue ? 'selected' : ''}} >{{$activeOptionValue}}</option>
            @endforeach
          </select>
          <small class="form-text text-danger">{{ $errors->first('active') }} </small>
        </div>
      </div>
    </div>    
  </div>

</div>
<!-- /.card-body -->

@csrf