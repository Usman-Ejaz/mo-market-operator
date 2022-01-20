<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.pages.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = new Page();
        return view('admin.pages.create', compact('page'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $page = new Page();
        $page = Page::create( $this->validateRequest($page) );

        $this->storeImage($page);

        $request->session()->flash('success', 'Page was successful added!');
        return redirect()->route('admin.pages.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $page->update($this->validateRequest($page));

        $this->storeImage($page);

        $request->session()->flash('success', 'Page was successful updated!');
        return redirect()->route('admin.pages.edit', $page->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page was successful deleted!');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Page::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    return ($row->title) ? ( (strlen($row->title) > 35) ? substr($row->title,0,35).'...' : $row->title ) : '';
                })
                ->addColumn('slug', function ($row) {
                    return ($row->slug) ? ( (strlen($row->slug) > 35) ? substr($row->slug,0,35).'...' : $row->slug ) : '';
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? Carbon::parse($row->created_at)->format('d/m/Y H:i:s') : '';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="'. route('admin.pages.edit',$row->id) .'" class="btn btn-primary" title="edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <form action="'. route('admin.pages.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
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

    private function validateRequest($page){

        return tap( request()->validate([
            'title' => 'required|min:3',
            'slug' => 'required',
            'description' => 'required|min:10',
            'keywords' => 'nullable',
            'image' => 'nullable',
            'start_datetime' => 'nullable|date_format:d/m/Y H:i:s',
            'end_datetime' => 'nullable|date_format:d/m/Y H:i:s',
            'active' => 'required',
            'created_by' => '',
            'modified_by' => ''
        ]), function(){
            if( request()->hasFile('image') ){
                request()->validate([
                    'image' => 'file|image|max:2000'
                ]);
            }
        });
    }

    private function storeImage($page){

        if (request()->has('image')) {
            $uploadFile = request()->file('image');
            $file_name = $uploadFile->hashName();
            $uploadFile->storeAs(config('filepaths.pageImagePath.internal_path'), $file_name);

            $page->update([
                'image' => $file_name,
            ]);
        }
    }

    public function deleteImage(Request $request){
        if ($request->ajax()) {
            if( isset($request->page_id) ){

                $page = Page::find($request->page_id);
                $image_path = config('filepaths.pageImagePath.public_path').$page->image;

                if( unlink($image_path) ){
                    $page->image = null;
                    $page->update();

                    return response()->json(['success' => 'true', 'message' => 'image deleted successfully'], 200);
                }
            }

        }

    }
}
