<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\FaqCategory;
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
        if( !hasPermission('faqs', 'list') ){
            return abort(403);
        }

        return view('admin.faqs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( !hasPermission('faqs', 'create') ){
            return abort(403);
        }

        $faq = new Faq();
        $categories = FaqCategory::all();
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
        if( !hasPermission('faqs', 'create') ){
            return abort(403);
        }

        $faq = new Faq();
        $faq = Faq::create($this->validateRequest($faq) );
        
        $request->session()->flash('success', "Faq {$request->action} Successfully!");
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
        if( !hasPermission('faqs', 'view') ){
            return abort(403);
        }

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
        if( !hasPermission('faqs', 'edit') ){
            return abort(403);
        }

        $categories = FaqCategory::all();

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
        if( !hasPermission('faqs', 'edit') ){
            return abort(403);
        }

        $faq->update($this->validateRequest($faq));

        $request->session()->flash('success', "Faq {$request->action} Successfully!");
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
        if( !hasPermission('faqs', 'delete') ){
            return abort(403);
        }

        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ Deleted Successfully!');
    }

    /**
     * Get all faqs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Data to render in list/index view
     */
    public function list(Request $request)
    {
        if( !hasPermission('faqs', 'list') ){
            return abort(403);
        }

        if ($request->ajax()) {
            $data = Faq::query();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('question', function ($row) {
                    return truncateWords($row->question, 80);
                })
                ->addColumn('category', function ($row) {
                    return truncateWords($row->category->name, 30);
                })
                ->orderColumn('created_at', 'created_at $1')
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if( hasPermission('faqs', 'edit') ) {
                        $options .= '<a href="' . route('admin.faqs.edit', $row->id) . '" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if( hasPermission('faqs', 'delete') ) {
                        $options .= ' <form action="'. route('admin.faqs.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
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

    private function validateRequest($faq){
        
        return tap( request()->validate([
                'question' => 'required|min:5',
                'category_id' => 'required',
                'answer' => 'required',
                'active' => 'nullable',
                'created_by' => '',
                'modified_by' => ''
            ]), function(){
            }
        );
    }
}

