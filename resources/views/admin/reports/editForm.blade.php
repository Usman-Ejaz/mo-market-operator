<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="category_id">Category <span class="text-danger">*</span></label>
            <select class="form-control" name="category_id" id="category_select" readonly>
                <option selected> {{ $report->subCategory->category->name }} </option>
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="sub_category_id">Sub Category <span class="text-danger">*</span></label>
            <select class="form-control" name="sub_category_id" id="sub_category_select" readonly>
                <option selected>{{ $report->subCategory->name }}</option>
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="name">Title <span class="text-danger">*</span></label>
            <input type="input" class="form-control" id="name" placeholder="Enter report title" name="name"
                value="{{ $report->name }}" required>
            <span class="form-text text-danger">{{ $errors->first('name') }} </span>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="publish_date">Publish Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="name" placeholder="Enter the publish date"
                name="publish_date" value="{{ $report->publish_date }}" required>
            <span class="form-text text-danger">{{ $errors->first('publish_date') }} </span>
        </div>
    </div>

    <div class="col-md-12 attributes-header">
        <h1 class="text-center">Report Attributes</h1>
    </div>

    @foreach ($report->filledAttributes as $attribute)
        @switch($attribute->type->name)
            @case('month')
                <div class="col-md-6 attribute">
                    <div class="form-group">
                        <label for="publish_date">{{ $attribute->name }}<span class="text-danger">*</span></label>
                        <select class="form-control" name="report_attributes[{{ $attribute->id }}]" required>
                            @foreach ($attribute->type->allowed_values as $value)
                                <option value="{{ $value }}" {{ $attribute->pivot->value == $value ? 'selected' : '' }}>
                                    {{ ucfirst($value) }}</option>
                            @endforeach

                        </select>
                        <span class="form-text text-danger">{{ $errors->first("report_attributes.$attribute->id") }}</span>
                    </div>
                </div>
            @break

            @case('year')
                <div class="col-md-6 attribute">
                    <div class="form-group">
                        <label for="publish_date">{{ $attribute->name }} <span class="text-danger">*</span></label>
                        <select class="form-control" name="report_attributes[{{ $attribute->id }}]" required>
                            @foreach ($attribute->type->allowed_values as $value)
                                <option value="{{ $value }}"
                                    {{ $attribute->pivot->value == $value ? 'selected' : '' }}>
                                    {{ ucfirst($value) }}</option>
                            @endforeach
                        </select>
                        <span class="form-text text-danger">{{ $errors->first("report_attributes.$attribute->id") }} </span>
                    </div>
                </div>
            @break

            @case('date')
                <div class="col-md-6 attribute">
                    <div class="form-group">
                        <label for="publish_date">{{ $attribute->name }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id=""
                            name="report_attributes[{{ $attribute->id }}]" value="{{ $attribute->pivot->value }}" required>
                        <span class="form-text text-danger">{{ $errors->first("report_attributes.$attribute->id") }} </span>
                    </div>
                </div>
            @break

            @case('number')
                <div class="col-md-6 attribute">
                    <div class="form-group">
                        <label for="publish_date">{{ $attribute->name }} <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id=""
                            name="report_attributes[{{ $attribute->id }}]" value="{{ $attribute->pivot->value }}" required>
                        <span class="form-text text-danger">{{ $errors->first("report_attributes.$attribute->id") }} </span>
                    </div>
                </div>
            @break

            @case('file')
                <div class="col-md-12 attribute">
                    <div class="form-group">
                        <label for="publish_date">{{ $attribute->name }} <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="" name="file_attachments[{{ $attribute->id }}]"
                            accept=".xls,.xlsx,.pdf,.doc,.docx">
                        <small class="fileExists">
                            <p>
                                Open Attachment Of -
                                <a href="{{ $attribute->pivot->value }}"
                                    target="_blank">{{ collect(explode('/', $attribute->pivot->value))->last() }}</a>
                            </p>
                        </small>
                        <span class="form-text text-danger">{{ $errors->first("file_attachments.$attribute->id") }} </span>
                    </div>
                </div>
            @break

            @default
                <div class="col-md-6 attribute">
                    <div class="form-group">
                        <label for="publish_date">{{ $attribute->name }} <span class="text-danger">*</span></label>
                        <input type="input" class="form-control" id=""
                            name="report_attributes[{{ $attribute->id }}]" value="{{ $attribute->pivot->value }}" required>
                        <span class="form-text text-danger">{{ $errors->first("report_attributes.$attribute->id") }} </span>
                    </div>
                </div>
        @endswitch
    @endforeach
    <div class="col-md-12">
        <button type="submit" class="btn btn-success btn-block">Update</button>
    </div>

    @if ($errors->has('report_attributes.*'))
        <div class="col-md-12">
            <span class="text-center text-danger">Some of the report attribute fields are invalid.</span>
        </div>
    @endif
</div>
