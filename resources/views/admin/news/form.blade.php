<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
          <label for="title">Title <span class="text-danger">*</span></label>
        <input type="input" class="form-control" id="title" placeholder="Enter News Title" name="title" value="{{ old('title') ?? $news->title }}">
        <span class="form-text text-danger">{{ $errors->first('title') }} </span>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label for="slug">Slug <span class="text-danger">*</span></label>
        <input type="input" class="form-control" id="slug" placeholder="Enter News Slug"  name="slug" value="{{ old('slug') ?? $news->slug }}">
        <span class="form-text text-danger">{{ $errors->first('slug') }} </span>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label for="description">Description <span class="text-danger">*</span></label>
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
        <label>Category <span class="text-danger">*</span></label>
        <select class="custom-select" name="news_category" id="news_category">
          <option value="">Please select an option</option>
          @foreach($news->newsCategoryOptions() as $categoryId => $categoryValue)
            @if(old('news_category') == $categoryId)
              <option value="{{$categoryId}}" selected>{{ $categoryValue }}</option>
            @else
              <option value="{{$categoryId}}" {{ ($news->news_category === $categoryValue) ? 'selected' : '' }}>{{$categoryValue}}</option>
            @endif
          @endforeach
        </select>
        <small class="form-text text-danger">{{ $errors->first('news_category') }} </small>
      </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="news_image" class="form-label" >News Image</label>
            <input class="form-control" type="file" id="news_image" name="image">
            <small class="form-text text-danger">{{ $errors->first('image') }} </small>
            @if( isset($news->image) )
                <small class="text-primary imageExists"><a href="{{ $news->image }}" target="_blank"><img src="{{ $news->image }}" target="_blank" class="img-thumbnail" style="width: 23%;"></a><span class="btn-sm btn-danger float-right" id="deleteImage"><i class="fa fa-trash"></i></span> </small>
            @endif
        </div>
    </div>
  </div>

</div>
<!-- /.card-body -->

@csrf
