<div class="card-body">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label>Start DateTime:</label>
            <div class="input-group date" id="starttime" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" name="start_datetime" data-target="#starttime"  value="{{ old('start_datetime') ?? $news->start_datetime }}" placeholder="d/m/Y H:i:s">
                <div class="input-group-append" data-target="#starttime" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
                <small class="form-text text-danger">{{ $errors->first('start_datetime') }} </small>
            </div>
        </div>
      </div>
    </div>

    <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="endtime">End DateTime</label>
        <div class="input-group date" id="endtime" data-target-input="nearest">
              <input type="text" class="form-control datetimepicker-input" name="end_datetime" data-target="#endtime"  value="{{ old('end_datetime') ?? $news->end_datetime }}" placeholder="d/m/Y H:i:s">
              <div class="input-group-append" data-target="#endtime" data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
              </div>
              <small class="form-text text-danger">{{ $errors->first('end_datetime') }} </small>
          </div>
      </div>
    </div>
  </div>

  <div class="row">

    @if( $news->created_at )
    <div class="col-md-12">
      <label for="endtime">Created At:</label>
      <span>{{$news->created_at}}</span>
    </div>
    @endif

    @if( $news->modified_at )
    <div class="col-md-12">
      <label for="endtime">Modified At:</label>
      <span>{{$news->modified_at ?? 'Not yet'}}</span>
    </div>
    @endif

    @if( $news->active )
    <div class="col-md-12">
      <label for="endtime">Status:</label>
      <span>{{ ($news->active == 'Active') ? 'Published' : '' }}</span>
    </div>
    @endif

  </div>

</div>
