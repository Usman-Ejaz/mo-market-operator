<?php

namespace App\Http\Controllers;

use App\Models\MediaLibrary;
use App\Models\MediaLibraryFile;
use Faker\Provider\Medical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use OpenApi\Attributes\MediaType;

class MediaLibraryController extends Controller
{

    private $disk = null;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->disk = Storage::disk('app');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(! hasPermission('media_library', 'list'), __('auth.error_code'), __('messages.unauthorized_action'));
        
        return view('admin.media-library.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(! hasPermission('media_library', 'create'), __('auth.error_code'), __('messages.unauthorized_action'));

        $mediaLibrary = new MediaLibrary;
        return view('admin.media-library.create', compact('mediaLibrary'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(! hasPermission('media_library', 'create'), __('auth.error_code'), __('messages.unauthorized_action'));
        
        $mediaLibrary = new MediaLibrary;
        $data = $this->validateRequest($mediaLibrary);
        $data['directory'] = strtolower(Str::slug($data['name'], '_'));
        $this->makeDirectory($data);
        
        MediaLibrary::create($data);

        $request->session()->flash('success', 'Media Library Added successfully!');
        return redirect()->route('admin.media-library.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MediaLibrary  $mediaLibrary
     * @return \Illuminate\Http\Response
     */
    public function show(MediaLibrary $mediaLibrary)
    {
        abort_if(! hasPermission('media_library', 'view'), __('auth.error_code'), __('messages.unauthorized_action'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MediaLibrary  $mediaLibrary
     * @return \Illuminate\Http\Response
     */
    public function edit(MediaLibrary $mediaLibrary)
    {
        abort_if(! hasPermission('media_library', 'edit'), __('auth.error_code'), __('messages.unauthorized_action'));

        return view('admin.media-library.edit', compact('mediaLibrary'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MediaLibrary  $mediaLibrary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MediaLibrary $mediaLibrary)
    {
        abort_if(! hasPermission('media_library', 'edit'), __('auth.error_code'), __('messages.unauthorized_action'));

        $data = $this->validateRequest($mediaLibrary);
        $data['directory'] = strtolower(Str::slug($data['name'], '_'));

        $this->makeDirectory($data, $mediaLibrary->directory);

        $mediaLibrary->update($data);

        $request->session()->flash('success', 'Media Library Updated successfully!');
        return redirect()->route('admin.media-library.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MediaLibrary  $mediaLibrary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, MediaLibrary $mediaLibrary)
    {
        abort_if(! hasPermission('media_library', 'delete'), __('auth.error_code'), __('messages.unauthorized_action'));

        $dir = MediaLibrary::MEDIA_STORAGE . $mediaLibrary->directory;
        
        if ($this->disk->exists($dir)) {
            $this->disk->deleteDirectory($dir);
        }
        
        $mediaLibrary->delete();
        $request->session()->flash('success', 'Media Library deleted successfully!');
        return redirect()->route('admin.media-library.index');
    }
    
    /**
     * list
     *
     * @param  Request $request
     * @return void
     */
    public function list(Request $request)
    {
        abort_if(! hasPermission('media_library', 'list'), __('auth.error_code'), __('messages.unauthorized_action'));

        if ($request->ajax()) {
            $data = MediaLibrary::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return ( isset($row->name)) ? $row->name : '';
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if (hasPermission('media_library', 'edit')) {
                        $options .= '<a href="'. route('admin.media-library.edit',$row->id) .'" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }

                    if (hasPermission('media_library', 'delete')) {
                        $options .= ' <form action="'. route('admin.media-library.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
                            '.csrf_field().'
                            '.method_field("DELETE").'
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm(\'Are You Sure Want to delete this record?\')" title="Delete">
                                    <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                    }

                    if (hasPermission('media_library', 'manage_files')) {
                        $options .= '<a href="'. route('admin.media-library.files',$row->id) .'" class="btn btn-primary ml-1" title="Manage Files">
                            <i class="fas fa-file-image"></i>
                        </a>';
                    }

                    return $options;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function mediaFilesList(Request $request, MediaLibrary $mediaLibrary)
    {
        if ($request->ajax()) {
            $files = $mediaLibrary->files();
            return response(['data' => $files, 'status' => 'success'], 200);
        }
    }
    
    /**
     * mediaFiles
     *
     * @param  mixed $mediaLibrary
     * @return void
     */
    public function mediaFiles(MediaLibrary $mediaLibrary)
    {
        abort_if(! hasPermission('media_library', 'manage_files'), __('auth.error_code'), __('messages.unauthorized_action'));
        
        return view('admin.media-library.files', ['files' => $mediaLibrary->files(), 'mediaLibrary' => $mediaLibrary]);
    }

    public function uploadFile(Request $request, MediaLibrary $mediaLibrary)
    {
        if ($request->hasFile('filepond')) {
            $mediaLibraryFile = new MediaLibraryFile;
            $filename = storeFile(MediaLibrary::MEDIA_STORAGE . $mediaLibrary->directory . '/', $request->file('filepond'), null);
            $mediaLibraryFile->file = $filename;
            $mediaLibraryFile->media_library_id = $mediaLibrary->id;
            $mediaLibraryFile->save();

        } else {
            // $data = $request->get('imageString');
            // list($type, $data) = explode(';', $data);
            // list(, $data)      = explode(',', $data);
            // $data = base64_decode($data);
            // list(, $extension) = explode('/', $type);
            // $filename = md5(time() . time()) . '.' .$extension;
            // Storage::disk('app')->put(MediaLibrary::MEDIA_STORAGE . $mediaLibrary->directory . '/' . $filename, $data);
        }
        return response(serveFile(MediaLibrary::MEDIA_STORAGE . $mediaLibrary->directory . '/', $filename), 200);
    }

    private function validateRequest($mediaLibrary)
    {
        return request()->validate([
            'name' => 'required|unique:media_libraries,name,'.$mediaLibrary->id
        ]);
    }

    private function makeDirectory($data, $oldDir = null)
    {
        $dir = MediaLibrary::MEDIA_STORAGE . $data['directory'];                

        if ($oldDir !== null && $oldDir !== $data['directory']) {
            $this->disk->move(MediaLibrary::MEDIA_STORAGE . $oldDir, $dir);
            return;
        }

        if (!$this->disk->exists($dir)) 
        {
            $this->disk->makeDirectory($dir);
        }
    }

    public function updateFile(Request $request) 
    {

        $mediaFile = MediaLibraryFile::where('id', $request->get('id'))->with('mediaLibrary')->first();

        if (!$mediaFile) {
            // Show some error here
            return;
        }

        $filename = basename($mediaFile->file);

        if ($request->has('dataURL')) {
            removeFile(MediaLibrary::MEDIA_STORAGE . $mediaFile->mediaLibrary->directory . '/', $mediaFile->file);

            $data = $request->get('dataURL');
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);
            list(, $extension) = explode('/', $type);
            $filename = md5(time()) . md5(time()) . '.' . $extension;
            Storage::disk('app')->put(MediaLibrary::MEDIA_STORAGE . $mediaFile->mediaLibrary->directory . '/' . $filename, $data);

        }
        $mediaFile->update([
            'file' => $filename,
            'featured' => $request->get('featured') ? 1 : 0
        ]);

        return response(['message' => 'Image updated successfully', 'status' => 'success'], 200);
    }
}
