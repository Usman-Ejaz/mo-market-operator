<?php

namespace App\Http\Controllers;

use App\Models\MediaLibrary;
use App\Models\MediaLibraryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;

class MediaFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MediaLibrary $mediaLibrary)
    {
        abort_if(! hasPermission('media_library', 'manage_files'), __('auth.error_code'), __('messages.unauthorized_action'));
        
        return view('admin.media-library.files', ['mediaLibrary' => $mediaLibrary]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, MediaLibrary $mediaLibrary)
    {
        abort_if(! hasPermission('media_library', 'manage_files'), __('auth.error_code'), __('messages.unauthorized_action'));

        $directoryPrefix = MediaLibrary::MEDIA_STORAGE . $mediaLibrary->directory . '/';

        if ($request->hasFile('filepond')) {
            $mediaLibraryFile = new MediaLibraryFile;
            $filename = storeFile($directoryPrefix, $request->file('filepond'), null);
            $mediaLibraryFile->file = $filename;
            $mediaLibraryFile->media_library_id = $mediaLibrary->id;
            $mediaLibraryFile->save();

            return response(serveFile($directoryPrefix, $filename), 200);
        }

        return response('Something went wrong.', 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        abort_if(! hasPermission('media_library', 'manage_files'), __('auth.error_code'), __('messages.unauthorized_action'));

        $mediaFile = MediaLibraryFile::where('id', $request->get('id'))->with('mediaLibrary')->first();

        if (!$mediaFile) {
            // Show some error here
            return;
        }

        $filename = basename($mediaFile->file);
        
        if ($request->has('dataURL')) {

            $directoryPrefix = MediaLibrary::MEDIA_STORAGE . $mediaFile->mediaLibrary->directory . '/';

            removeFile($directoryPrefix, $filename);

            $data = $request->get('dataURL');
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);
            list(, $extension) = explode('/', $type);
            $filename = md5(time()) . md5(time()) . '.' . $extension;
            Storage::disk('app')->put($directoryPrefix . $filename, $data);

            if ($request->has('imageWidth') && $request->has('imageHeight')) {
                $path = config('settings.storage_disk_base_path') . $directoryPrefix . $filename;
                $width = $request->get('imageWidth');
                $height = $request->get('imageHeight');
                Image::make($path)->resize($width, $height)->save($path);
            }
        }

        if ($request->get('featured') == "true") {
            MediaLibraryFile::where('media_library_id', $mediaFile->media_library_id)
                ->where('featured', 1)
                ->where('id', '!=', $mediaFile->id)
                ->update(['featured' => 0]);
        }

        $mediaFile->update([
            'file' => $filename,
            'featured' => $request->get('featured') == "true" ? 1 : 0
        ]);

        return response(['message' => __('messages.record_updated', ['module' => 'Media file']), 'status' => 'success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        abort_if(! hasPermission('media_library', 'manage_files'), __('auth.error_code'), __('messages.unauthorized_action'));

        if ($request->ajax())
        {
            $media = MediaLibraryFile::where('id', $request->get('id'))->with('mediaLibrary')->first();
            if ($media) {
                $directoryPrefix = MediaLibrary::MEDIA_STORAGE . $media->mediaLibrary->directory . '/';
                removeFile($directoryPrefix, $media->file);

                $media->delete();
                return response(['message' => __('messages.record_deleted', ['module' => 'Media file']), 'status' => 'success'], 200);
            }

            return response(['message' => 'Media file does not exist.', 'status' => 'error'], 400);
        }
    }

    public function list(Request $request, MediaLibrary $mediaLibrary)
    {
        abort_if(! hasPermission('media_library', 'manage_files'), __('auth.error_code'), __('messages.unauthorized_action'));
        
        if ($request->ajax()) {
            $files = $mediaLibrary->files();
            return response(['data' => $files, 'status' => 'success'], 200);
        }
    }
}
