<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="title">Title <span class="text-danger">*</span></label>
                <input type="input" class="form-control" autocomplete="off" id="title" placeholder="Enter title" name="title"
                    value="{{ old('title') ?? $job->title }}">
                <span class="form-text text-danger">{{ $errors->first('title') }} </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="short_description">Short Description <span class="text-danger">*</span></label>
                <textarea class="form-control" id="short_description" placeholder="Enter short description" name="short_description" cols="50" rows="3">{{ old('short_description') ?? $job->short_description }}</textarea>
                <span class="form-text text-danger">{{ $errors->first('short_description') }} </span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="description">Description <span class="text-danger">*</span></label>
        <textarea class="form-control ckeditor" id="description" placeholder="Enter description" name="description"
            rows="400" cols="50">{{ old('description') ?? $job->description }}</textarea>
        <span class="form-text text-danger">{{ $errors->first('description') }} </span>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="qualification">Qualification <span class="text-danger">*</span></label>
                <input type="input" class="form-control" id="qualification" autocomplete="off" placeholder="Enter qualification"
                    name="qualification" value="{{ old('qualification') ?? $job->qualification }}">
                <span class="form-text text-danger">{{ $errors->first('qualification') }} </span>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="experience">Experience <span class="text-danger">*</span> <small>(In years)</small> </label>
                <input type="number" class="form-control" autocomplete="off" id="experience" placeholder="Enter experience"
                    name="experience" value="{{ old('experience') ?? $job->experience }}" min="1" step="1" max="20">
                <span class="form-text text-danger">{{ $errors->first('experience') }} </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="location">Location <span class="text-danger">*</span></label>
                <input type="input" class="form-control" id="location" autocomplete="off" placeholder="Enter location" name="location"
                    value="{{ old('location') ?? $job->location }}" data-role="tagsinput">
                <span class="form-text text-danger">{{ $errors->first('location') }} </span>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="total_positions">Total Positions <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="total_positions"
                    placeholder="Enter total positions" autocomplete="off" name="total_positions"
                    value="{{ old('total_positions') ?? $job->total_positions }}" min="1" step="1">
                <span class="form-text text-danger">{{ $errors->first('total_positions') }} </span>
            </div>
        </div>
    </div>

	<div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="specialization">Specialization <span class="text-danger">*</span></label>
                <input type="input" class="form-control" autocomplete="off" id="specialization" placeholder="Enter specialization" name="specialization"
                    value="{{ old('specialization') ?? $job->specialization }}">
                <span class="form-text text-danger">{{ $errors->first('specialization') }} </span>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="salary">Salary </label>
                <input type="number" class="form-control" id="salary" min="0" step="1"
                    placeholder="Enter salary" autocomplete="off" name="salary"
                    value="{{ old('salary') ?? $job->salary }}">
                <span class="form-text text-danger">{{ $errors->first('salary') }} </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="image">Job Image <span class="text-danger">*</span> <small>(Max allowed size is 2MB. Allowed types are {{ str_replace("|", ", ", config('settings.image_file_extensions')) }}. Recommended Image dimensions are 250 x 250)</small> </label>
                <input type="file" class="form-control" id="image" name="image" onchange="handleFileChoose(event)">
                <span class="form-text text-danger">{{ $errors->first('image') }} </span>
                @if(isset($job->image))
                    <small class="text-primary imageExists" style="display: block; margin-bottom: 15px;">
                        <a href="{{ $job->image }}" target="_blank">
                            <img src="{{ $job->image }}" target="_blank" class="img-thumbnail" style="height: 200px;" />
                        </a>
                        <span class="btn-sm btn-danger float-right" id="deleteImage"><i class="fa fa-trash"></i></span>
                    </small>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="attachments[]">Job Attachments <span class="text-danger">*</span> <small>(Max allowed size is 5MB. Allowed types are doc, docx, pdf)</small> </label>
                <input type="file" class="form-control" id="attachments[]" name="attachments[]" multiple onchange="handleFileChoose(event)">
                <span class="form-text text-danger">{{ $errors->first('attachments.*') }} </span>
                @if(isset($job->attachment_links) && count($job->attachment_links) > 0)
                    @foreach ($job->attachment_links as $file)
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

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="enable" name="enable" value="1" @if($job->enable == 1 || old('enable')) checked='checked' @endif/>
                    <label class="form-check-label" for="enable"> Enable/Disable Application Form</label>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.card-body -->

@csrf
