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
use Image;

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
        $data['slug'] = $data['directory'];
        $this->makeDirectory($data);
        
        MediaLibrary::create($data);

        $request->session()->flash('success', __('messages.record_created', ['module' => 'Media Library']));
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
        $data['slug'] = $data['directory'];
        $this->makeDirectory($data, $mediaLibrary->directory);

        $mediaLibrary->update($data);

        $request->session()->flash('success', __('messages.record_updated', ['module' => 'Media Library']));
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
        $request->session()->flash('success', __('messages.record_deleted', ['module' => 'Media Library']));
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
                ->addColumn('description', function ($row) {
                    return ( isset($row->description)) ? $row->description : '';
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

    private function validateRequest($mediaLibrary)
    {
        return request()->validate([
            'name' => 'required|unique:media_libraries,name,'.$mediaLibrary->id,
            'description' => 'nullable|string'
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
}
