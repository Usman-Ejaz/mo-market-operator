{{ env('settings.datetime_format') }}
<div class="card-body">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
            <label>Start DateTime:</label>
            <div class="input-group">
                <input type="text" autocomplete="off" class="form-control" id="start_datetime" name="start_datetime" value="{{ old('start_datetime') ?? $job->start_datetime }}" placeholder="{{  config('settings.datetime_format') }}">
                <div class="input-group-append">
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
          <div class="input-group">
              <input type="text" autocomplete="off" class="form-control" id="end_datetime" name="end_datetime" value="{{ old('end_datetime') ?? $job->end_datetime }}" placeholder="{{  config('settings.datetime_format') }}">
              <div class="input-group-append">
                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
              </div>
              <small class="form-text text-danger">{{ $errors->first('end_datetime') }} </small>
          </div>
      </div>
    </div>
  </div>

  <div class="row">

    @if( $job->created_at )
    <div class="col-md-12">
      <label for="endtime">Created At:</label>
      <span>{{$job->created_at}}</span>
    </div>
    @endif

    @if( $job->modified_at )
    <div class="col-md-12">
      <label for="endtime">Modified At:</label>
      <span>{{$job->modified_at ?? 'Not yet'}}</span>
    </div>
    @endif

    @if( isset($job->active) && \Route::current()->getName() == 'admin.jobs.edit' )
    <div class="col-md-12">
      <label for="endtime">Status:</label>
      <span>{{ ($job->active == 'Active') ? 'Published' : 'Draft' }}</span>
    </div>
    @endif

  </div>

</div>
