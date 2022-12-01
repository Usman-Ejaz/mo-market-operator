<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="name" class="form-lable">File Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                        required>
                    <span class="form-text text-danger">{{ $errors->first('name') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">

                    <label for="link" class="form-label">Paste File Link</label>
                    <input class="form-control" type="text" id="link" name="link">
                    <span class="form-text text-danger">{{ $errors->first('link') }} </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="file" class="form-label">Upload File</label>
                    <input class="form-control" type="file" id="file" name="file" accept=".xls,.xlsx">
                    <span class="form-text text-danger">{{ $errors->first('file') }} </span>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add File</button>
    </div>
</div>
@csrf
<script type="text/javascript">
    //document.addEventListener("DOMContentLoaded", function(event) {
    const fileInput = document.querySelector('#file');
    const linkInput = document.querySelector('#link');

    fileInput.addEventListener('change', function(e) {
        disableInput(linkInput);
    });

    linkInput.addEventListener('keyup', function(e) {
        if (linkInput.value !== '') {
            disableInput(fileInput);
            return;
        }
        enableInput(fileInput);
    });

    function disableInput(inputElement) {
        inputElement.setAttribute('disabled', '');
    }

    function enableInput(inputElement) {
        inputElement.removeAttribute('disabled');
    }
    //});
</script>
