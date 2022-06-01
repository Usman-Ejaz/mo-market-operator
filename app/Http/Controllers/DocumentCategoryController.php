<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use Carbon\Carbon;
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
        abort_if(!hasPermission("document_categories", "list"), 401, __('messages.unauthorized_action'));

        return view("admin.document-categories.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!hasPermission("document_categories", "create"), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission("document_categories", "create"), 401, __('messages.unauthorized_action'));

        $category = new DocumentCategory();
        
        $data = $this->validateRequest($category);
        $data['slug'] = str_slug($data['name']);

        $category = DocumentCategory::create($data);

        $request->session()->flash('success', __('messages.record_created', ['module' => 'Category']));
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
        abort_if(!hasPermission("document_categories", "view"), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission("document_categories", "edit"), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission("document_categories", "edit"), 401, __('messages.unauthorized_action'));

        $data = $this->validateRequest($documentCategory);
        $data['slug'] = str_slug($data['name']);
        $documentCategory->update($data);

        $request->session()->flash('success', __('messages.record_updated', ['module' => 'Category']));
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
        abort_if(!hasPermission("document_categories", "delete"), 401, __('messages.unauthorized_action'));

        $documentCategory->delete();
        return redirect()->route('admin.document-categories.index')->with('success', __('messages.record_deleted', ['module' => 'Category']));
    }

    public function list(Request $request)
    {
        abort_if(!hasPermission("document_categories", "list"), 401, __('messages.unauthorized_action'));

        if ($request->ajax()) {
            $data = DocumentCategory::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return ($row->name) ? ( (strlen($row->name) > 50) ? substr($row->name, 0, 50).'...' : $row->name ) : '';
                })
                ->editColumn('created_at', function ($row) {
                    return [
                        'display' => $row->created_at,
                        'sort' => Carbon::parse(parseDate($row->created_at))->timestamp
                    ];
                })
                ->addColumn('action', function ($row) {
                   $options = '';
                    if (hasPermission('document_categories', 'edit')) {
                        $options .= '<a href="' . route('admin.document-categories.edit', $row->id) . '" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if (hasPermission('document_categories', 'delete')) {
                        $options .= ' <form action="'. route('admin.document-categories.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
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

    private function validateRequest($category) {
        return request()->validate([
            'name' => 'required|min:3|unique:document_categories,name,'.$category->id
        ], [
            'name.unique' => __('messages.unique', ['attribute' => 'Category'])
        ]);
    }
}
