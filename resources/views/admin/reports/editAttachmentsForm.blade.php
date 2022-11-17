<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                        required>
                    <span class="form-text text-danger">{{ $errors->first('name') }}</span>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="file" class="form-label">Upload</label>
                    <input class="form-control" type="file" id="file" name="file" accept=".xls,.xlsx">
                    <span class="form-text text-danger">{{ $errors->first('file') }} </span>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add Attachment</button>
    </div>
</div>
<script type="text/javascript"></script>
