<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="subject">Subject <span class="text-danger">*</span></label>
                <input type="input" class="form-control" id="subject" placeholder="Enter Subject" name="subject"
                    value="{{ old('subject') ?? $newsletter->subject }}" autocomplete="off">
                <span class="form-text text-danger">{{ $errors->first('subject') }} </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="description">Description <span class="text-danger">*</span></label>
                <textarea class="form-control ckeditor" id="description" placeholder="Enter Newsletter Description"
                    name="description" rows="400"
                    cols="50">{{ old('description') ?? $newsletter->description }}</textarea>
                <span class="form-text text-danger">{{ $errors->first('description') }} </span>
            </div>
        </div>
    </div>
</div>
<!-- /.card-body -->

@csrf
