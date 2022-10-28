<div class="row">
    <div class="col-md-12">
        @if (isset($data->files) && $data->files->count() > 0)
            @foreach ($data->files as $file)
                <form method="POST"
                    action="{{ route('admin.mo-data.remove-file', ['mo_datum' => $data->id, 'file' => $file->id]) }}">
                    <div>
                        <small class="text-primary imageExists">
                            <a href="{{ serveFile('mo-data/', $file->file_path) }}" target="_blank">
                                {{ $file->name }}
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
