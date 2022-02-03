<?php

namespace App\Http\Controllers;

use App\Models\Faq;
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
        if( !Auth::user()->role->hasPermission('faqs', 'list') ){
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
        if( !Auth::user()->role->hasPermission('faqs', 'create') ){
            return abort(403);
        }

        $faq = new Faq();
        return view('admin.faqs.create', compact('faq'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( !Auth::user()->role->hasPermission('faqs', 'create') ){
            return abort(403);
        }

        $faq = new Faq();
        $faq = Faq::create( $this->validateRequest($faq) );
        
        $request->session()->flash('success', 'Faq Added Successfully!');
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
        if( !Auth::user()->role->hasPermission('faqs', 'view') ){
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
        if( !Auth::user()->role->hasPermission('faqs', 'edit') ){
            return abort(403);
        }

        return view('admin.faqs.edit', compact('faq'));
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
        if( !Auth::user()->role->hasPermission('faqs', 'edit') ){
            return abort(403);
        }

        $faq->update($this->validateRequest($faq));

        $request->session()->flash('success', 'Faq Updated Successfully!');
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
        if( !Auth::user()->role->hasPermission('faqs', 'delete') ){
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
        if( !Auth::user()->role->hasPermission('faqs', 'list') ){
            return abort(403);
        }

        if ($request->ajax()) {
            $data = Faq::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('question', function ($row) {
                    return ($row->question) ? ( (strlen($row->question) > 100) ? substr($row->question,0,100).'...' : $row->question ) : '';
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if( Auth::user()->role->hasPermission('faqs', 'edit') ) {
                        $options .= '<a href="' . route('admin.faqs.edit', $row->id) . '" class="btn btn-primary" title="edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if( Auth::user()->role->hasPermission('faqs', 'delete') ) {
                        $options .= ' <form action="'. route('admin.faqs.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
                            '.csrf_field().'
                            '.method_field("DELETE").'
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm(\'Are You Sure Want to delete this record?\')" title="delete">
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
                'answer' => 'required',
                'active' => 'nullable',
                'created_by' => '',
                'modified_by' => ''
            ]), function(){              
            }
        );
    }
}

