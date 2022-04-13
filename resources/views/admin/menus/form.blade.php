<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="input" class="form-control" id="name" placeholder="Enter name" name="name" value="{{ old('name') ?? $menu->name }}">
                <span class="form-text text-danger">{{ $errors->first('name') }} </span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Theme <span class="text-danger">*</span></label>
                <select class="custom-select" name="theme" id="theme">
                    <option value="">Please select a theme</option>
                    @foreach(config('settings.themes') as $themeName => $themeDisplayName)
                        <option value="{{$themeName}}" {{ ($menu->theme === $themeName) ? 'selected' : '' }}>{{ $themeDisplayName }}</option>
                    @endforeach
                </select>
                <span class="form-text text-danger">{{ $errors->first('theme') }} </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Status <span class="text-danger">*</span></label>
                <select class="custom-select" name="active" id="active">
                    <option value="">Please select status</option>
                    @foreach($menu->activeOptions() as $StatusId => $statusName)
                        <option value="{{$StatusId}}" {{ ($menu->active === $statusName) ? 'selected' : '' }}>{{$statusName}}</option>
                    @endforeach
                </select>
                <span class="form-text text-danger">{{ $errors->first('status') }} </span>
            </div>
        </div>
        @if (Route::is('admin.menus.create'))
            <div class="col-md-6">
                <div class="form-group">
                    <label>Identifier <span class="text-danger">*</span></label>
                    <input type="input" class="form-control" id="identifier" placeholder="Enter identifier" name="identifier" value="{{ old('identifier') ?? $menu->identifier }}">
                    <span class="form-text text-danger">{{ $errors->first('identifier') }} </span>
                </div>
            </div>
        @endif

        @if (Route::is('admin.menus.edit'))        
            <input type="hidden" class="form-control" id="identifier" placeholder="Enter identifier" name="identifier" value="{{ old('identifier') ?? $menu->identifier }}">
        @endif
        
    </div>

</div>
<!-- /.card-body -->

@csrf
