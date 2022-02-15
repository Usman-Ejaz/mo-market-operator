<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
          <label for="title">Title <span class="text-danger">*</span></label>
        <input type="input" class="form-control" id="title" placeholder="Enter Page Title" name="title" value="{{ old('title') ?? $page->title }}">
        <span class="form-text text-danger">{{ $errors->first('title') }} </span>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label for="slug">Slug <span class="text-danger">*</span></label>
        <input type="input" class="form-control" id="slug" placeholder="Enter Page Slug"  name="slug" value="{{ old('slug') ?? $page->slug }}">
        <span class="form-text text-danger">{{ $errors->first('slug') }} </span>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label for="description">Description <span class="text-danger">*</span></label>
    <textarea class="form-control ckeditor" id="description" placeholder="Enter Page Description" name="description" rows="400" cols="50">{{ old('description') ?? $page->description }}</textarea>
    <small class="form-text text-danger">{{ $errors->first('description') }} </small>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="keywords">Keywords</label>
        <input type="input" class="form-control" id="keywords" placeholder="Enter keywords" name="keywords" value="{{ old('keywords') ?? $page->keywords }}">
        <small class="form-text text-danger">{{ $errors->first('keywords') }} </small>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="page_image" class="form-label" >Page Image</label>
            <input class="form-control" type="file" id="page_image" name="image">
            <small class="form-text text-danger">{{ $errors->first('image') }} </small>
            @if( isset($page->image) )
                <small class="text-primary imageExists"><a href="{{ asset( config('filepaths.pageImagePath.public_path') .$page->image) }}" target="_blank"><img src="{{ asset( config('filepaths.pageImagePath.public_path') .$page->image) }}" target="_blank" class="img-thumbnail" style="width: 23%;"></a><span class="btn-sm btn-danger float-right" id="deleteImage"><i class="fa fa-trash"></i></span> </small>
            @endif
        </div>
    </div>
  </div>

</div>
<!-- /.card-body -->

@csrf
