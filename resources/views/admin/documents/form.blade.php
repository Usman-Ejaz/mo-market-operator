<div class="card-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
          <label for="title">Title <span class="text-danger">*</span></label>
        <input type="input" class="form-control" id="title" placeholder="Enter Document Title" name="title" value="{{ old('title') ?? $document->title }}">
        <small class="form-text text-danger">{{ $errors->first('title') }} </small>
      </div>
    </div>
  </div>
  <div class="row">
  <div class="col-md-12">
            <div class="form-group">
                <label>Category <span class="text-danger">*</span></label>
                <select class="custom-select" name="category_id" id="category">
                    <option value="">Please select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ ($category->id === $document->category_id) ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                <small class="form-text text-danger">{{ $errors->first('category') }} </small>
            </div>
        </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="keywords">Keywords</label>
        <input type="input" class="form-control" id="keywords" placeholder="Enter keywords" name="keywords" value="{{ old('keywords') ?? $document->keywords }}">
        <small class="form-text text-danger">{{ $errors->first('keywords') }} </small>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="file" class="form-label" >Document File <span class="text-danger">*</span></label>
            <input class="form-control" type="file" id="file" name="file">
            <small class="form-text text-danger">{{ $errors->first('file') }} </small>
            @if( isset($document->file) )
                <small class="fileExists">
                  <p>
                    Open Attachment Of - 
                    <a href="{{ asset( config('filepaths.documentsFilePath.internal_path') .$document->file) }}" target="_blank">
                      {{$document->title}}
                    </a>
                    <span class="btn-sm btn-danger float-right" id="deleteFile" title="Delete File"><i class="fa fa-trash"></i></span>
                  </p>
                </small>
            @endif
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="convert" name="convert" value="1">
          <label class="form-check-label" for="convert"> Convert File To PDF</label>
        </div>    
      </div>
    </div>
  </div>
</div>
<!-- /.card-body -->

@csrf
