<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
class DocumentController extends Controller
{
    private $allowedFileExtensions = array('doc','docx', 'txt', 'ppt', 'pptx', 'odt');

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!hasPermission("documents", "list"), 401, __('messages.unauthorized_action'));

        return view('admin.documents.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!hasPermission("documents", "create"), 401, __('messages.unauthorized_action'));

        $document = new Document();
        $categories = DocumentCategory::all();
        return view('admin.documents.create', compact('document', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     * And check for converison doc,docx,ppt,pptx,txt into pdf
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(!hasPermission("documents", "create"), 401, __('messages.unauthorized_action'));
        
        $document = new Document();
        $data = $this->validateRequest($document);

        $data['slug'] = str_slug($data['title']);
        
        $convertFiles = $request->convert !== null && $request->convert == '1';

        $filenames = "";

        if (count($data['file']) > 0) {

            foreach ($data['file'] as $file) {
                $filename = storeFile(Document::STORAGE_DIRECTORY, $file);

                if ($convertFiles) {
                    $extension = $file->getClientOriginalExtension();
                    if (in_array($extension, $this->allowedFileExtensions)) {
                        $filename = $this->convertFile($filename);
                    }
                }
                $filenames .= $filename . ",";
            }
        }
        
        $data['file'] = trim($filenames, ",");

        if ($request->hasFile('image')) {
            $data['image'] = storeFile(Document::STORAGE_DIRECTORY, $request->file('image'));
        }

        $message = __('messages.record_created', ['module' => 'Document']);

        if ($request->action === "Published") {
            $data['published_at'] = now();

            $message = __('messages.record_published', ['module' => 'Document']);
        }
        
        $document = Document::create($data);

        $request->session()->flash('success', $message);
        return redirect()->route('admin.documents.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        abort_if(!hasPermission("documents", "view"), 401, __('messages.unauthorized_action'));

        return view('admin.documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        abort_if(!hasPermission("documents", "edit"), 401, __('messages.unauthorized_action'));

        $categories = DocumentCategory::all();
        
        return view('admin.documents.edit', compact('document', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        abort_if(!hasPermission("documents", "edit"), 401, __('messages.unauthorized_action'));

        $data = $this->validateRequest($document);
        
        $data['slug'] = str_slug($data['title']);

        $data['file'] = $this->handleFileUpload($document, $request);

        if ($request->has('image')) {
            $data['image'] = storeFile(Document::STORAGE_DIRECTORY, $request->file('image'), $document->image);
        }

        $message = __('messages.record_updated', ['module' => 'Document']);
        
        if ($request->action === "Published") {
            $data['published_at'] = now();

            $message = __('messages.record_published', ['module' => 'Document']);
        } else if ($request->action === "Unpublished") {
            $data['published_at'] = null;

            $message = __('messages.record_unpublished', ['module' => 'Document']);
        }
        
        $document->update($data);

        $request->session()->flash('success', $message);
        return redirect()->route('admin.documents.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        abort_if(!hasPermission("documents", "delete"), 401, __('messages.unauthorized_action'));
        
        if ($document->file !== null) {
            foreach ($document->file as $file) {
                removeFile(Document::STORAGE_DIRECTORY, $file);
            }
        }

        $document->removeImage();
        $document->delete();
        
        return redirect()->route('admin.documents.index')->with('success', __('messages.record_deleted', ['module' => 'Document']));
    }

    public function list(Request $request)
    {
        abort_if(!hasPermission("documents", "list"), 401, __('messages.unauthorized_action'));

        if ($request->ajax()) {
            $data = Document::with('category')->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    return truncateWords($row->title, 35);
                })
                ->addColumn('keywords', function ($row) {
                    return truncateWords($row->keywords, 20);
                })
                ->addColumn('category', function ($row) {
                    return truncateWords($row->category->name, 15);
                })
                ->addColumn('status', function ($row) {
                    return $row->isPublished() ? 'Published' : 'Draft';
                })
                ->editColumn('created_at', function ($row) {
                    return [
                        'display' => $row->created_at,
                        'sort' => Carbon::parse(parseDate($row->created_at))->timestamp
                    ];
                })
                ->addColumn('action', function ($row) {
                   $options = '';
                    if( hasPermission('documents', 'edit') ) {
                        $options .= '<a href="' . route('admin.documents.edit', $row->id) . '" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if( hasPermission('documents', 'delete') ) {
                        $options .= ' <form action="'. route('admin.documents.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
                            '.csrf_field().'
                            '.method_field("DELETE").'
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm(\''. __('messages.record_delete') .'\')" title="Delete">
                                    <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                    }
                    return $options;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    private function validateRequest($document) {
        
        $rule = [
            'title' => 'required|min:3|unique:documents,title,'.$document->id,
            'keywords' => 'nullable',
            'category_id' => 'required',
            'file.*' => 'required|max: ' . config('settings.maxDocumentSize'),
            'image' => 'required|file|max:' . config('settings.maxImageSize'),
            'created_by' => '',
            'modified_by' => ''
        ];

        $request = request();

        if (! $request->hasFile('file')) {
            unset($rule['file.*']);
        }

        if (! $request->has('image')) {
            unset($rule['image']);
        }

        return request()->validate($rule, [
            'file.*.max' => __('messages.max_file', ['limit' => '5 MB']),
            'image.max' => __('messages.max_file', ['limit' => '2 MB']),
        ]);
    }

    private function convertFile($filename)
    {
        $storagePath = config('settings.storage_disk_base_path') . Document::STORAGE_DIRECTORY;

        $storageFile = $storagePath . $filename;
        
        exec('/usr/lib/libreoffice/program/soffice.bin --headless --convert-to pdf:writer_pdf_Export -env:UserInstallation=file:///tmp/LibreOffice_Conversion_${USER} --outdir '.$storagePath.' '.$storageFile);
        
        $convertedFileName = pathinfo($storageFile, PATHINFO_FILENAME);

        if (file_exists($storagePath . $convertedFileName . '.pdf')) {
            unlink($storageFile);
        }
        return $convertedFileName . ".pdf";
    }

    public function deleteFile(Request $request){
        if ($request->ajax()) {
            if( isset($request->document_id) ){

                $document = Document::find($request->document_id);
                if (removeFile(Document::STORAGE_DIRECTORY, $document->file)) {
                    $document->file = null;
                    $document->update();

                    return response()->json(['success' => 'true', 'message' => __('messages.record_deleted', ['module' => 'File'])], 200);
                }
            }
        }
    }

    private function handleFileUpload($document, $request)
    {
        $convertFiles = $request->convert !== null && $request->convert == '1';
        $filenames = implode(",", $document->file);        

        if ($request->hasFile('file')) 
        {
            $uploadedFiles = $request->file('file');

            if (count($uploadedFiles) > 0) {
                $tempnames = "";
                foreach ($uploadedFiles as $file) {
                    $filename = storeFile(Document::STORAGE_DIRECTORY, $file);
                    $tempnames .= $filename . ",";
                }

                $tempnames = trim($tempnames, ",");
                $filenames = trim($tempnames . ',' . $filenames, ",");
            }
        }

        if ($request->get('removeFile') !== null)
        {
            $removedFiles = explode(",", $request->get('removeFile'));
            foreach ($removedFiles as $file) {
                removeFile(Document::STORAGE_DIRECTORY, $file);
                $filenames = str_replace($file, "", $filenames);
                $filenames = str_replace(",,", ",", $filenames);
                $filenames = trim($filenames, ",");
            }
        }

        if ($convertFiles) {
            $filenames = explode(",", $filenames);
            $tempnames = "";
            foreach ($filenames as $filename) {
                $extension = explode(".", basename($filename))[1];
                if (in_array($extension, $this->allowedFileExtensions)) {
                    $filename = $this->convertFile($filename);
                    $tempnames .= $filename . ",";
                }
            }

            $filenames = $tempnames;
        }

        return $filenames;
    }
}

