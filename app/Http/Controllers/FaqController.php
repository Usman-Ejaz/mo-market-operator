<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\FaqCategory;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!hasPermission("faqs", "list"), 401, __('messages.unauthorized_action'));

        return view('admin.faqs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!hasPermission("faqs", "create"), 401, __('messages.unauthorized_action'));

        $faq = new Faq();
        $categories = FaqCategory::latest()->get();
        return view('admin.faqs.create', compact('faq', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(!hasPermission("faqs", "create"), 401, __('messages.unauthorized_action'));

        $faq = new Faq();
        $data = $this->validateRequest($faq);

        $message = __('messages.record_created', ['module' => 'FAQ']);

        if ($request->action === "Published") {
            $data['published_at'] = now();

            $message = __('messages.record_published', ['module' => 'FAQ']);
        }

        $faq = Faq::create($data);

        $request->session()->flash('success', $message);
        return redirect()->route('admin.faqs.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function show(Faq $faq)
    {
        abort_if(!hasPermission("faqs", "view"), 401, __('messages.unauthorized_action'));

        return view('admin.faqs.show', compact('faq'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function edit(Faq $faq)
    {
        abort_if(!hasPermission("faqs", "edit"), 401, __('messages.unauthorized_action'));

        $categories = FaqCategory::latest()->get();

        return view('admin.faqs.edit', compact('faq', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Faq $faq)
    {
        abort_if(!hasPermission("faqs", "edit"), 401, __('messages.unauthorized_action'));

        $data = $this->validateRequest($faq);

        $message = __('messages.record_updated', ['module' => 'FAQ']);

        if ($request->action === "Published") {
            $data['published_at'] = now();

            $message = __('messages.record_published', ['module' => 'FAQ']);
        } else if ($request->action === "Unpublished") {
            $data['published_at'] = null;

            $message = __('messages.record_unpublished', ['module' => 'FAQ']);
        }

        $faq->update($data);

        $request->session()->flash('success', $message);
        return redirect()->route('admin.faqs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function destroy(Faq $faq)
    {
        abort_if(!hasPermission("faqs", "delete"), 401, __('messages.unauthorized_action'));

        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', __('messages.record_deleted', ['module' => 'FAQ']));
    }

    /**
     * Get all faqs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Data to render in list/index view
     */
    public function list(Request $request)
    {
        abort_if(!hasPermission("faqs", "list"), 401, __('messages.unauthorized_action'));

        if ($request->ajax()) {
            $data = Faq::with('category')->latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('question', function ($row) {
                    return truncateWords($row->question, 50);
                })
                ->addColumn('category', function ($row) {
                    return truncateWords($row->category->name, 30);
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
                    if( hasPermission('faqs', 'edit') ) {
                        $options .= '<a href="' . route('admin.faqs.edit', $row->id) . '" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if( hasPermission('faqs', 'delete') ) {
                        $options .= ' <button type="button" class="btn btn-danger deleteButton" data-action="'. route('admin.faqs.destroy', $row->id ) .'" title="Delete">
                                <i class="fas fa-trash" data-action="'. route('admin.faqs.destroy', $row->id ) .'"></i>
                        </button>';
                    }
                    return $options;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    private function validateRequest($faq){
        return request()->validate([
            'question' => 'required|min:5',
            'category_id' => 'required',
            'answer' => 'required',
            'active' => 'nullable',
            'created_by' => '',
            'modified_by' => ''
        ],[], ['category_id' => 'category']);
    }
}

