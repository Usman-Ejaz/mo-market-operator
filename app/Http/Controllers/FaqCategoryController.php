<?php

namespace App\Http\Controllers;

use App\Models\FaqCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class FaqCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!hasPermission("faq_categories", "list"), 401, __('messages.unauthorized_action'));

        return view("admin.faq-categories.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!hasPermission("faq_categories", "create"), 401, __('messages.unauthorized_action'));

        $faqCategory = new FaqCategory();
        return view("admin.faq-categories.create", compact("faqCategory"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(!hasPermission("faq_categories", "create"), 401, __('messages.unauthorized_action'));

        $category = new FaqCategory();
        $category = FaqCategory::create($this->validateRequest($category));

        $request->session()->flash('success', __('messages.record_created', ['module' => 'Category']));
        return redirect()->route('admin.faq-categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FaqCategory  $faqCategory
     * @return \Illuminate\Http\Response
     */
    public function show(FaqCategory $faqCategory)
    {
        abort_if(!hasPermission("faq_categories", "view"), 401, __('messages.unauthorized_action'));

        return view('admin.faq-categories.show', compact('faqCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FaqCategory  $faqCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(FaqCategory $faqCategory)
    {
        abort_if(!hasPermission("faq_categories", "edit"), 401, __('messages.unauthorized_action'));

        return view('admin.faq-categories.edit', compact('faqCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FaqCategory  $faqCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FaqCategory $faqCategory)
    {
        abort_if(!hasPermission("faq_categories", "edit"), 401, __('messages.unauthorized_action'));

        $data = $this->validateRequest($faqCategory);
        $faqCategory->update($data);

        $request->session()->flash('success', __('messages.record_updated', ['module' => 'Category']));
        return redirect()->route('admin.faq-categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FaqCategory  $faqCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(FaqCategory $faqCategory)
    {
        abort_if(!hasPermission("faq_categories", "delete"), 401, __('messages.unauthorized_action'));

        $faqCategory->delete();
        return redirect()->route('admin.faq-categories.index')->with('success', __('messages.record_deleted', ['module' => 'Category']));
    }

    public function list(Request $request)
    {
        abort_if(!hasPermission("faq_categories", "list"), 401, __('messages.unauthorized_action'));

        if ($request->ajax()) {
            $data = FaqCategory::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return truncateWords($row->name, 50);
                })
                ->editColumn('created_at', function ($row) {
                    return [
                        'display' => $row->created_at,
                        'sort' => Carbon::parse(parseDate($row->created_at))->timestamp
                    ];
                })
                ->addColumn('action', function ($row) {
                   $options = '';
                    if (hasPermission('faq_categories', 'edit')) {
                        $options .= '<a href="' . route('admin.faq-categories.edit', $row->id) . '" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if (hasPermission('faq_categories', 'delete')) {
                        $options .= ' <button type="button" class="btn btn-danger deleteButton" data-action="'. route('admin.faq-categories.destroy', $row->id ) .'" title="Delete">
                                <i class="fas fa-trash" data-action="'. route('admin.faq-categories.destroy', $row->id ) .'"></i>
                        </button>';
                    }
                    return $options;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    private function validateRequest($category) {
        return request()->validate([
            'name' => 'required|min:3|unique:faq_categories,name,'.$category->id
        ], [
            'name.unique' => __('messages.unique', ['attribute' => 'Category'])
        ]);
    }
}
