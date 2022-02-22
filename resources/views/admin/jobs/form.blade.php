<div class="card-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="title">Title <span class="text-danger">*</span></label>
        <input type="input" class="form-control" id="title" placeholder="Enter Job Title" name="title" value="{{ old('title') ?? $job->title }}">
        <small class="form-text text-danger">{{ $errors->first('title') }} </small>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label for="description">Description <span class="text-danger">*</span></label>
    <textarea class="form-control ckeditor" id="description" placeholder="Enter Job Description" name="description" rows="400" cols="50">{{ old('description') ?? $job->description }}</textarea>
    <small class="form-text text-danger">{{ $errors->first('description') }} </small>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="qualification">Qualification <span class="text-danger">*</span></label>
        <input type="input" class="form-control" id="qualification" placeholder="Enter Qualification For Job"  name="qualification" value="{{ old('qualification') ?? $job->qualification }}">
        <small class="form-text text-danger">{{ $errors->first('qualification') }} </small>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label for="experience">Experience <span class="text-danger">*</span></label>
        <input type="input" class="form-control" id="experience" placeholder="Enter Experience For Job"  name="experience" value="{{ old('experience') ?? $job->experience }}">
        <small class="form-text text-danger">{{ $errors->first('experience') }} </small>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="location">Location <span class="text-danger">*</span></label>
        <input type="input" class="form-control" id="location" placeholder="Enter Job Location"  name="location" value="{{ old('location') ?? $job->location }}">
        <small class="form-text text-danger">{{ $errors->first('location') }} </small>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label for="total_positions">Total Positions <span class="text-danger">*</span></label>
        <input type="number" class="form-control" id="total_positions" placeholder="Enter Total Positions For Job"  name="total_positions" value="{{ old('total_positions') ?? $job->total_positions }}">
        <small class="form-text text-danger">{{ $errors->first('total_positions') }} </small>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
          <label for="image">Job Image</label>
            <input type="file" class="form-control" id="image"  name="image">
            <small class="form-text text-danger">{{ $errors->first('image') }} </small>
            @if( isset($job->image) )
            <small class="text-primary imageExists"><a href="{{ $job->image }}" target="_blank"><img src="{{ $job->image }}" target="_blank" class="img-thumbnail" style="width: 23%;"></a><span class="btn-sm btn-danger float-right" id="deleteImage"><i class="fa fa-trash"></i></span> </small>
            @endif
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="enable" name="enable" value="1"  @if($job->enable == 1 || old('enable')) checked='checked' @endif/>
          <label class="form-check-label" for="enable"> Enable/Disable Application Form</label>
        </div>    
      </div>
    </div>
  </div>

</div>
<!-- /.card-body -->

@csrf