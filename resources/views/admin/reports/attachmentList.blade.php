<div class="row">
    <div class="col-md-12">
        @if (isset($report->attachments) && $report->attachments->count() > 0)
            @foreach ($report->attachments as $attachment)
                <form method="POST"
                    action="{{ route('admin.reports.remove-attachment', ['report' => $report->id, 'attachment' => $attachment->id]) }}">
                    <div>
                        <small class="text-primary imageExists">
                            <a href="{{ $attachment->file_path }}" target="_blank">
                                {{ $attachment->name }}
                            </a>
                            <button type="submit" class="btn-sm btn-danger float-right"><i class="fa fa-trash"></i></span>
                                @csrf
                                @method('DELETE')
                        </small>
                    </div>
                </form>
                <br />
            @endforeach
        @endif
    </div>
</div>
