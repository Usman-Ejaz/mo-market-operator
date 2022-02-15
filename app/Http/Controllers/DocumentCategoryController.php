<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DocumentCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->role->hasPermission("document-categories", "list")) {
            return abort(403);
        }

        return view("admin.document-categories.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->role->hasPermission("document-categories", "create")) {
            return abort(403);
        }
        $documentCategory = new DocumentCategory();
        return view("admin.document-categories.create", compact("documentCategory"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::user()->role->hasPermission('document-categories', 'create')) {
            return abort(403);
        }
        $category = new DocumentCategory();
        $category = DocumentCategory::create( $this->validateRequest($category) );

        $request->session()->flash('success', 'Category Added Successfully!');
        return redirect()->route('admin.document-categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DocumentCategory  $documentCategory
     * @return \Illuminate\Http\Response
     */
    public function show(DocumentCategory $documentCategory)
    {
        if (!Auth::user()->role->hasPermission('document-categories', 'view')) {
            return abort(403);
        }

        return view('admin.document-categories.show', compact('documentCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocumentCategory  $documentCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(DocumentCategory $documentCategory)
    {
        if (!Auth::user()->role->hasPermission('document-categories', 'edit')) {
            return abort(403);
        }
        return view('admin.document-categories.edit', compact('documentCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DocumentCategory  $documentCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DocumentCategory $documentCategory)
    {
        if (!Auth::user()->role->hasPermission('document-categories', 'edit')) {
            return abort(403);
        }

        $data = $this->validateRequest($documentCategory);
        $documentCategory->update($data);

        $request->session()->flash('success', 'Category Updated Successfully!');
        return redirect()->route('admin.document-categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DocumentCategory  $documentCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocumentCategory $documentCategory)
    {
        if (!Auth::user()->role->hasPermission('document-categories', 'delete')) {
            return abort(403);
        }

        $documentCategory->delete();

        return redirect()->route('admin.document-categories.index')->with('success', 'Category Deleted Successfully!');
    }

    public function list(Request $request)
    {
        if (!Auth::user()->role->hasPermission('document-categories', 'list')) {
            return abort(403);
        }

        if ($request->ajax()) {
            $data = DocumentCategory::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return ($row->name) ? ( (strlen($row->name) > 50) ? substr($row->name, 0, 50).'...' : $row->name ) : '';
                })               
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                   $options = '';
                    if (Auth::user()->role->hasPermission('document-categories', 'edit')) {
                        $options .= '<a href="' . route('admin.document-categories.edit', $row->id) . '" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if (Auth::user()->role->hasPermission('document-categories', 'delete')) {
                        $options .= ' <form action="'. route('admin.document-categories.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
                            '.csrf_field().'
                            '.method_field("DELETE").'
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm(\'Are You Sure Want to delete this record?\')" title="Delete">
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

    private function validateRequest($category) {        
        return request()->validate([
            'name' => 'required|min:3|unique:document_categories,name'
        ], [
            'name.unique' => 'Document categories name should be unique.'
        ]);
    }
}
