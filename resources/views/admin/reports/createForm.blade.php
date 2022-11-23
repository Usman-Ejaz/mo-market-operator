<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="category_id">Category <span class="text-danger">*</span></label>
            <select class="form-control" name="category_id" id="category_select" required>
                @if (old('category_id'))
                    <option value="">Choose a category</option>
                @else
                    <option value="" selected>Choose a category</option>
                @endif
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="sub_category_id">Sub Category <span class="text-danger">*</span></label>
            <select class="form-control" name="sub_category_id" id="sub_category_select" required>
                <option value="" selected>Choose a sub category</option>
            </select>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="name">Title <span class="text-danger">*</span></label>
            <input type="input" class="form-control" id="name" placeholder="Enter report title" name="name"
                value="{{ old('name') }}">
            <span class="form-text text-danger">{{ $errors->first('name') }} </span>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="publish_date">Publish Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="name" placeholder="Enter the publish date"
                name="publish_date" value="{{ old('publish_date') }}">
            <span class="form-text text-danger">{{ $errors->first('publish_date') }} </span>
        </div>
    </div>

    <div class="col-md-12 attributes-header" style="display:none">
        <h1 class="text-center">Report Attributes</h1>
    </div>

    <div class="col-md-12">
        <h1 class="text-center">Attachments</h1>
        <span class="form-text text-danger text-center">{{ $errors->first('attachment_files.*') }}</span>
    </div>

    <div class="col-md-12 fileInputAddButtonContainer">
        <button type="button" class="btn btn-primary my-1 mx-auto fileInputAddButton"> + Add new attachment </span>
    </div>

    <div class="col-md-12">
        <button type="submit" class="btn btn-success btn-block">Add</button>
    </div>

    @if ($errors->has('report_attributes.*'))
        <div class="col-md-12">
            <span class="text-center text-danger">Some of the report attribute fields are invalid.</span>
        </div>
    @endif

    {{-- @foreach ($errors->get('report_attributes') as $key => $message)
        <div class="col-md-12">
            <span class="text-danger"> {{ $key . ': ' . $message[0] }} </span>
        </div>
    @endforeach --}}
</div>

@push('optional-scripts')
    <script type="text/javascript">
        const baseURL = "{{ config('app.url') }}";
        const categorySelect = document.querySelector('#category_select');
        const subCategorySelect = document.querySelector('#sub_category_select');
        const attributesHeader = document.querySelector('.attributes-header');
        const fileInputHTML = `
        <div class="col-md-6 fileNameInput">
            <div class="form-group">
                <label for="attachment_files">Name</label>
                <input type="text" class="form-control" name="attachment_files[][name]" required>
            </div>
        </div>
        <div class="col-md-6 fileInput">
            <div class="form-group">
                <label for="attachment_files">Upload</label>
                <input type="file" class="form-control" name="attachment_files[][file]" required>
                <button type="button" class="btn-sm btn-danger mt-1 fileInputDeleteButton"><i class="fa fa-trash"></i></buttton>
            </div>
        </div>`;
        const fileInputAddButtonContainer = document.querySelector(".fileInputAddButtonContainer");
        const fileInputAddButton = document.querySelector(".fileInputAddButton");

        const domParser = new DOMParser();

        window.addEventListener("load", function() {
            categorySelect.dispatchEvent(new Event('change'));
        }, false);

        categorySelect.addEventListener('change', async function(e) {
            const categoryID = e.target.value;
            console.log(categoryID);
            resetSelect(subCategorySelect, "Choose a sub category");
            resetAttributesContainer();

            if (categoryID != "") {
                const subCategories = await fetch(`${baseURL}/reports/${categoryID}/sub-categories`)
                    .then(res => res.json());
                subCategories.forEach((subCat) => {
                    subCategorySelect.appendChild(createOption(subCat.name, subCat.id));
                });

                return;
            }
            resetSelect(subCategorySelect, "Choose a sub category");
            return;
        });

        subCategorySelect.addEventListener('change', async function(e) {
            const subCategoryID = e.target.value;
            resetAttributesContainer();
            if (subCategoryID != "") {
                await renderAttributes(subCategoryID);
                return;
            }
            return;
        });

        fileInputAddButton.addEventListener('click', async function(e) {
            addAttachmentFields();
        });



        async function renderAttributes(subCategoryID) {
            resetAttributesContainer();
            const attributes = await fetch(`${baseURL}/reports/${subCategoryID}/attributes`)
                .then(res => res.json());
            attributes.forEach(att => {
                attributesHeader.after(createAttributeNode(att));
            });
            attributesHeader.style.display = "block";
        }

        function createAttributeNode(att) {
            let attributeHTMlString = getInputBasedOnType(att);

            return domParser.parseFromString(attributeHTMlString, "text/html").body.firstChild;
        }

        function getInputBasedOnType(att) {
            let attributeHTMlString = "";

            function capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            };
            switch (att.type.name) {
                case "month":
                    const selectOptionsForMonths = att.type.allowed_values.map(value =>
                            `<option value="${value}">${capitalizeFirstLetter(value)}</option>`)
                        .join("");
                    attributeHTMlString = `
                        <div class="col-md-6 attribute">
                            <div class="form-group">
                                <label for="publish_date">${att.name} <span class="text-danger">*</span></label>
                                <select class="form-control" name="report_attributes[${att.id}]" required>
                                    <option value="" selected></option>
                                    ${selectOptionsForMonths}
                                </select>
                            </div>
                        </div>`;
                    break;
                case "year":
                    const selectOptionsForYears = att.type.allowed_values.map(value =>
                            `<option value="${value}">${value}</option>`)
                        .join("");
                    attributeHTMlString = `
                        <div class="col-md-6 attribute">
                            <div class="form-group">
                                <label for="publish_date">${att.name} <span class="text-danger">*</span></label>
                                <select class="form-control" name="report_attributes[${att.id}]" required>
                                    <option value="" selected></option>
                                    ${selectOptionsForYears}
                                </select>
                            </div>
                        </div>`;
                    break;
                case "date":
                    attributeHTMlString = `
                            <div class="col-md-6 attribute">
                                <div class="form-group">
                                    <label for="publish_date">${att.name} <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="" name="report_attributes[${att.id}]" required>
                                </div>
                            </div>`;
                    break;

                default:
                    attributeHTMlString = `
                            <div class="col-md-6 attribute">
                                <div class="form-group">
                                    <label for="publish_date">${att.name} <span class="text-danger">*</span></label>
                                    <input type="input" class="form-control" id="" name="report_attributes[${att.id}]"  required>
                                </div>
                            </div>`;
            }

            return attributeHTMlString;
        }

        function resetAttributesContainer() {
            document.querySelectorAll(".attribute").forEach((e) => e.remove());
            attributesHeader.style.display = "none";
        }

        function createOption(text, value) {
            const optionNode = document.createElement("option");
            optionNode.setAttribute("value", value);
            optionNode.textContent = text;
            return optionNode;
        }

        function resetSelect(selectNode, defaultText) {
            selectNode.innerHTML = "";
            selectNode.appendChild(createOption(defaultText, ""));
        }

        function addAttachmentFields() {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = fileInputHTML;
            fileInputAddButtonContainer.before(tempDiv.children[0], tempDiv.children[1]);
            const allFileInputs = document.querySelectorAll('.fileInput');
            const allFileNameInputs = document.querySelectorAll('.fileNameInput');
            const currentFileInput = allFileInputs[allFileInputs.length - 1];
            const currentFileNameInput = allFileNameInputs[allFileNameInputs.length - 1];
            const fileInputDeleteButton = currentFileInput.querySelector('.fileInputDeleteButton');

            fileInputDeleteButton.addEventListener('click', async function(e) {
                currentFileInput.remove();
                currentFileNameInput.remove();
            });
        }
    </script>
@endpush
