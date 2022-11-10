<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="title">Title <span class="text-danger">*</span></label>
            <input type="input" class="form-control" id="title" autocomplete="off" placeholder="Enter Post Title"
                name="title" value="{{ old('title') ?? $data->title }}" disabled>
            <span class="form-text text-danger">{{ $errors->first('title') }} </span>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="description">Description <span class="text-danger">*</span></label>
            <textarea class="form-control ckeditor" id="description" placeholder="Enter Post Description" name="description"
                rows="400" cols="50">
        {{ old('description') ?? $data->description }}
                @if ($data->description == '')
<h2>Overview</h2>
                    <p> Write a breif overview </p>

                    <h2>Data Source</h2>
                    <p>Write some details about the data source</p>
                    
                    <%files_slot%/>
                    <p>Copy and paste the above line anywhere to change where files are displayed</p>

                    <h2>Disclaimer</h2>
                    <p>Write a brief disclaimer</p>
@endif
            </textarea>
            <span class="form-text text-danger">{{ $errors->first('description') }} </span>
        </div>
    </div>

    @if ($data->extra_attributes_count > 0)
        <div class="col-md-12">
            <div class="form-group">
                <div class="row">
                    @foreach ($data->extraAttributes as $attribute)
                        <div class="col-md-6">
                            <label for="extra_attribute-{{ $attribute->id }}">{{ $attribute->title }} </label>
                            <input class="form-control" type="text" name="extra_attributes[{{ $attribute->id }}]"
                                id="extra_attribute_{{ $attribute->id }}" value={{ $attribute->value }}>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    <button type="submit" class="btn btn-primary">Update</button>

</div>

<!-- /.card-body -->

@csrf
