<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="title">Title <span class="text-danger">*</span></label>
                <input type="input" class="form-control" id="title" placeholder="Enter training title" name="title"
                    value="{{ old('title') ?? $training->title }}">
                <span class="form-text text-danger">{{ $errors->first('title') }} </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="short_description">Short Description</label>
                <textarea class="form-control" id="short_description" placeholder="Enter short description" name="short_description" cols="50" rows="3">{{ old('short_description') ?? $training->short_description }}</textarea>
                <span class="form-text text-danger">{{ $errors->first('short_description') }} </span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control ckeditor" id="description" placeholder="Enter training description" name="description"
            rows="400" cols="50">{{ old('description') ?? $training->description }}</textarea>
        <span class="form-text text-danger">{{ $errors->first('description') }} </span>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="target_audience">Target Audience <span class="text-danger">*</span></label>
                <input type="input" class="form-control" id="target_audience" placeholder="Enter target audience"
                    name="target_audience" value="{{ old('target_audience') ?? $training->target_audience }}" data-role="tagsinput">
                <span class="form-text text-danger">{{ $errors->first('target_audience') }} </span>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="topics">Topics <span class="text-danger">*</span></label>
                <input type="input" class="form-control" id="topics" placeholder="Enter topics"
                    name="topics" value="{{ old('topics') ?? $training->topics }}" data-role="tagsinput">
                <span class="form-text text-danger">{{ $errors->first('topics') }} </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="location">Location <span class="text-danger">*</span></label>
                <input type="input" class="form-control" id="location" placeholder="Enter location" name="location"
                    value="{{ old('location') ?? $training->location }}" data-role="tagsinput">
                <span class="form-text text-danger">{{ $errors->first('location') }} </span>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select class="custom-select" name="status" id="status">
                    <option value="">Please select a status</option>
                    @if(Request::route()->getName() == "admin.trainings.create")
                        <option value="open">Open</option>
                        <option value="closed">Closed</option>
					@else
                        <option value="open" {{ ($training->status === "open" || old('status') === "open") ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ ($training->status === "closed" || old('status') === "closed") ? 'selected' : '' }}>Closed</option>
					@endif
                </select>
                <span class="form-text text-danger">{{ $errors->first('status') }} </span>
            </div>
        </div>
    </div>	

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="attachments[]">Attechments <small>(Max allowed size is 5MB. Allowed types are doc, docx, pdf)</small> </label>
                <input type="file" class="form-control" id="attachments[]" name="attachments[]" multiple>
                <span class="form-text text-danger">{{ $errors->first('attachments') }} </span>                
                @if(isset($training->attachment_links) && count($training->attachment_links) > 0)
                    @foreach ($training->attachment_links as $file)
                    <small class="text-primary fileExists" style="display: block; margin-bottom: 15px;">
                        <a href="{{ $file }}" target="_blank">
                            {{ getFileOriginalName($file) }}
                        </a>
                        <span class="btn-sm btn-danger float-right remove-file" data-file="{{ basename($file) }}" id="deleteFile"><i class="fa fa-trash"></i></span>
                    </small>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
<!-- /.card-body -->

@csrf
