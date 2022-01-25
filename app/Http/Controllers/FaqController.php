<?php

namespace App\Http\Controllers;

use App\Models\Faq;
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
        if( !Auth::user()->role->hasPermission('faq', 'list') ){
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
        if( !Auth::user()->role->hasPermission('faq', 'create') ){
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
        if( !Auth::user()->role->hasPermission('faq', 'create') ){
            return abort(403);
        }

        $faq = new Faq();
        $faq = Faq::create( $this->validateRequest($faq) );

        if ($faq->exists) {
            $request->session()->flash('success', 'Faq was successfully added!');
            return redirect()->route('admin.faqs.index');
        }

        $request->session()->flash('error', 'Faq was not added, please try again.');
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
        if( !Auth::user()->role->hasPermission('faq', 'view') ){
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
        if( !Auth::user()->role->hasPermission('faq', 'edit') ){
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
        if( !Auth::user()->role->hasPermission('faq', 'edit') ){
            return abort(403);
        }

        if ( $faq->update($this->validateRequest($faq)) ) {
            $request->session()->flash('success', 'Faq was successfully updated!');
            return redirect()->route('admin.faqs.edit', $faq->id);
        }

        $request->session()->flash('error', 'Faq was not updated, please try again');
        return redirect()->route('admin.faqs.edit', $faq->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function destroy(Faq $faq)
    {
        if( !Auth::user()->role->hasPermission('faq', 'delete') ){
            return abort(403);
        }

        if( $faq->delete() ) {
            return redirect()->route('admin.faqs.index')->with('success', 'FAQ was successfully deleted!');
        }

        return redirect()->route('admin.faqs.index')->with('error', 'FAQ was not deleted!');
    }

    /**
     * Get all faqs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Data to render in list/index view
     */
    public function list(Request $request)
    {
        if( !Auth::user()->role->hasPermission('faq', 'list') ){
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
                    return ($row->created_at) ? Carbon::parse($row->created_at)->format('d/m/Y H:i:s') : '';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="'. route('admin.faqs.edit',$row->id) .'" class="btn btn-primary" title="edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <form action="'. route('admin.faqs.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
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

