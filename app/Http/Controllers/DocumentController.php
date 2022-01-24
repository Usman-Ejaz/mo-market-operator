<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.documents.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $document = new Document();
        return view('admin.documents.create', compact('document'));
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
        $document = new Document();
        $document = $this->validateRequest($document);
        $extension = request()->file('file')->getClientOriginalExtension();
        if($request->get('convert') == null || ( $extension != 'doc' && $extension != 'docx' && $extension != 'ppt' && $extension != 'pptx' && $extension != 'txt' && $extension !='odt')) {
            $document = Document::create($document);
            $this->storeFile($document);
            $request->session()->flash('success', 'Document was successfully added!');
        } else {
            $document = Document::create($document);
            $uploadFile = request()->file('file');
            $file_name = $uploadFile->hashName();
            $uploadFile->storeAs(config('filepaths.documentsFilePath.public_path'), $file_name);
            $storagePath = public_path( config('filepaths.documentsFilePath.internal_path') );
            $storageFile = $storagePath.$file_name;
            
            exec('/usr/lib/libreoffice/program/soffice.bin --headless --convert-to pdf:writer_pdf_Export -env:UserInstallation=file:///tmp/LibreOffice_Conversion_${USER} --outdir '.$storagePath.' '.$storageFile);
            $convertedFileName = pathinfo($file_name, PATHINFO_FILENAME);
            if (file_exists($storagePath. $convertedFileName . '.pdf')) {
                $document->update([
                    'file' => $convertedFileName.'.pdf',
                ]);
                unlink($storagePath.$file_name);
                $request->session()->flash('success', 'Document was successfully added!');
            } else {
                $request->session()->flash('error', 'Something went wrong during conversion!');
            }
        }
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
        return view('admin.documents.edit', compact('document'));
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
        $data = $this->validateRequest($document);
        $extension = 'notSupported';
        if (request()->hasFile('file')) {
            $file_path = config('filepaths.documentsFilePath.internal_path').$document->file; 
            unlink($file_path);
            $extension = request()->file('file')->getClientOriginalExtension();
        }
        if($request->get('convert') == null || ($extension != 'doc' && $extension != 'docx' && $extension != 'ppt' && $extension != 'pptx' && $extension != 'txt' && $extension != 'odt') ) {
            $document->update($data);
            $this->storeFile($document);
            $request->session()->flash('success', 'Document was successful updated!');
        } else {
            $document->update($data);
            $uploadFile = request()->file('file');
            $file_name = $uploadFile->hashName();
            $uploadFile->storeAs(config('filepaths.documentsFilePath.public_path'), $file_name);
            $storagePath = public_path( config('filepaths.documentsFilePath.internal_path') );
            $storageFile = $storagePath.$file_name;
            
            exec('/usr/lib/libreoffice/program/soffice.bin --headless --convert-to pdf:writer_pdf_Export -env:UserInstallation=file:///tmp/LibreOffice_Conversion_${USER} --outdir '.$storagePath.' '.$storageFile);
            $convertedFileName = pathinfo($file_name, PATHINFO_FILENAME);
            if (file_exists($storagePath. $convertedFileName . '.pdf')) {
                $document->update([
                    'file' => $convertedFileName.'.pdf',
                ]);
                unlink($storagePath.$file_name);
                $request->session()->flash('success', 'Document was successful updated!');
            } else {
                $request->session()->flash('error', 'Something went wrong during conversion!');
            }
        }
        return redirect()->route('admin.documents.edit', $document->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        $file_path = config('filepaths.documentsFilePath.internal_path').$document->file;
        $document->delete();
        unlink($file_path);

        return redirect()->route('admin.documents.index')->with('success', 'Document was successful deleted!');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Document::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    return ($row->title) ? ( (strlen($row->title) > 50) ? substr($row->title,0,50).'...' : $row->title ) : '';
                })               
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? Carbon::parse($row->created_at)->format('d/m/Y H:i:s') : '';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="'. route('admin.documents.edit',$row->id) .'" class="btn btn-primary" title="edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <form action="'. route('admin.documents.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
                            '.csrf_field().'
                            '.method_field("DELETE").'
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm(\'Are You Sure Want to delete this record?\')" title="delete">
                                    <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    private function validateRequest($document){

        return tap( request()->validate([
            'title' => 'required|min:3',
            'keywords' => 'nullable',
            'file' => 'nullable',
            'created_by' => '',
            'modified_by' => ''
        ]), function(){
            if( request()->hasFile('file') ){
                request()->validate([
                    'file' => 'file|file'
                ]);
            }
        });
    }

    private function storeFile($document){

        if (request()->has('file')) {
            $uploadFile = request()->file('file');
            $file_name = $uploadFile->hashName();
            $uploadFile->storeAs(config('filepaths.documentsFilePath.public_path'), $file_name);

            $document->update([
                'file' => $file_name,
            ]);
        }
    }

    public function deleteFile(Request $request){
        if ($request->ajax()) {
            if( isset($request->document_id) ){

                $document = Document::find($request->document_id);
                $file_path = config('filepaths.documentsFilePath.internal_path').$document->file;

                if( unlink($file_path) ){
                    $document->file = null;
                    $document->update();

                    return response()->json(['success' => 'true', 'message' => 'File deleted successfully'], 200);
                }
            }

        }

    }

}

