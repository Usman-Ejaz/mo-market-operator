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
                value="{{ $report->name }}">
            <span class="form-text text-danger">{{ $errors->first('name') }} </span>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="publish_date">Publish Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="name" placeholder="Enter the publish date"
                name="publish_date" value="{{ $report->publish_date->format('Y-m-d') }}">
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
                            <option value="janaury" {{ $attribute->pivot->value == 'janaury' ? 'selected' : '' }}>Janaury
                            </option>
                            <option value="february" {{ $attribute->pivot->value == 'february' ? 'selected' : '' }}>February
                            </option>
                            <option value="march" {{ $attribute->pivot->value == 'march' ? 'selected' : '' }}>March</option>
                            <option value="april" {{ $attribute->pivot->value == 'april' ? 'selected' : '' }}>April</option>
                            <option value="may" {{ $attribute->pivot->value == 'may' ? 'selected' : '' }}>May</option>
                            <option value="june" {{ $attribute->pivot->value == 'june' ? 'selected' : '' }}>June</option>
                            <option value="july" {{ $attribute->pivot->value == 'july' ? 'selected' : '' }}>July</option>
                            <option value="august" {{ $attribute->pivot->value == 'august' ? 'selected' : '' }}>August</option>
                            <option value="september" {{ $attribute->pivot->value == 'september' ? 'selected' : '' }}>September
                            </option>
                            <option value="october" {{ $attribute->pivot->value == 'october' ? 'selected' : '' }}>October
                            </option>
                            <option value="november" {{ $attribute->pivot->value == 'november' ? 'selected' : '' }}>November
                            </option>
                            <option value="december" {{ $attribute->pivot->value == 'december' ? 'selected' : '' }}>December
                            </option>
                        </select>
                        <span class="form-text text-danger">{{ $errors->first("report_attributes[$attribute->id]") }} </span>
                    </div>
                </div>
            @break

            @case('year')
                <div class="col-md-6 attribute">
                    <div class="form-group">
                        <label for="publish_date">{{ $attribute->name }} <span class="text-danger">*</span></label>
                        <select class="form-control" name="report_attributes[{{ $attribute->id }}]" required>
                            <option value="2020" {{ $attribute->pivot->value == 2020 ? 'selected' : '' }}>2020</option>
                            <option value="2021" {{ $attribute->pivot->value == 2021 ? 'selected' : '' }}>2021</option>
                            <option value="2022" {{ $attribute->pivot->value == 2022 ? 'selected' : '' }}>2022</option>
                            <option value="2023" {{ $attribute->pivot->value == 2023 ? 'selected' : '' }}>2023</option>
                            <option value="2024" {{ $attribute->pivot->value == 2024 ? 'selected' : '' }}>2024</option>
                            <option value="2025" {{ $attribute->pivot->value == 2025 ? 'selected' : '' }}>2025</option>
                            <option value="2026" {{ $attribute->pivot->value == 2026 ? 'selected' : '' }}>2026</option>
                            <option value="2027" {{ $attribute->pivot->value == 2027 ? 'selected' : '' }}>2027</option>
                            <option value="2028" {{ $attribute->pivot->value == 2028 ? 'selected' : '' }}>2028</option>
                            <option value="2029" {{ $attribute->pivot->value == 2029 ? 'selected' : '' }}>2029</option>
                            <option value="2030" {{ $attribute->pivot->value == 2030 ? 'selected' : '' }}>2030</option>
                        </select>
                        <span class="form-text text-danger">{{ $errors->first("report_attributes[$attribute->id]") }} </span>
                    </div>
                </div>
            @break

            @case('date')
                <div class="col-md-6 attribute">
                    <div class="form-group">
                        <label for="publish_date">{{ $attribute->name }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id=""
                            name="report_attributes[{{ $attribute->id }}]" value="{{ $attribute->pivot->value }}">
                        <span class="form-text text-danger">{{ $errors->first('report_attributes') }} </span>
                    </div>
                </div>
            @break

            @default
                <div class="col-md-6 attribute">
                    <div class="form-group">
                        <label for="publish_date">{{ $attribute->name }} <span class="text-danger">*</span></label>
                        <input type="input" class="form-control" id=""
                            name="report_attributes[{{ $attribute->id }}]" value="{{ $attribute->pivot->value }}">
                        <span class="form-text text-danger">{{ $errors->first("report_attributes[$attribute->id]") }} </span>
                    </div>
                </div>
        @endswitch
    @endforeach



    {{-- <div class="col-md-12">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <span class="form-text text-danger">{{ $error }} </span>
            @endforeach

        @endif
    </div> --}}



</div>

<button type="submit" class="btn btn-success btn-block">Update</button>

</div>

@csrf

@push('optional-scripts')
    <script type="text/javascript"></script>
@endpush
