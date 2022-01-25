{{ env('settings.datetime_format') }}
<div class="card-body">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
            <label>Start DateTime:</label>
            <div class="input-group">
                <input type="text" autocomplete="off" class="form-control" id="start_datetime" name="start_datetime" value="{{ old('start_datetime') ?? $news->start_datetime }}" placeholder="{{  config('settings.datetime_format') }}">
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
              <input type="text" autocomplete="off" class="form-control" id="end_datetime" name="end_datetime" value="{{ old('end_datetime') ?? $news->end_datetime }}" placeholder="{{  config('settings.datetime_format') }}">
              <div class="input-group-append">
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

    @if( isset($news->active) && \Route::current()->getName() == 'admin.news.edit' )
    <div class="col-md-12">
      <label for="endtime">Status:</label>
      <span>{{ ($news->active == 'Active') ? 'Published' : 'Draft' }}</span>
    </div>
    @endif

  </div>

</div>
