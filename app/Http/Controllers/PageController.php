<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if( !Auth::user()->role->hasPermission('pages', 'list') ){
            return abort(403);
        }

        return view('admin.pages.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( !Auth::user()->role->hasPermission('pages', 'create') ){
            return abort(403);
        }

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
        if( !Auth::user()->role->hasPermission('pages', 'create') ){
            return abort(403);
        }

        $page = new Page();
        $page = Page::create( $this->validateRequest($page) );

        $this->storeImage($page);

        $request->session()->flash('success', "Page {$request->action} Successfully!");
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
        if( !Auth::user()->role->hasPermission('pages', 'view') ){
            return abort(403);
        }

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
        if( !Auth::user()->role->hasPermission('pages', 'edit') ){
            return abort(403);
        }

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
        if( !Auth::user()->role->hasPermission('pages', 'edit') ){
            return abort(403);
        }
        if (request()->has('image')) {
            $file_path = config('filepaths.pageImagePath.public_path').$page->image; 
            unlink($file_path);
        }
        $page->update($this->validateRequest($page));

        $this->storeImage($page);

        $request->session()->flash('success', "Page {$request->action} Successfully!");
        return redirect()->route('admin.pages.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        if( !Auth::user()->role->hasPermission('pages', 'delete') ){
            return abort(403);
        }
        $file_path = config('filepaths.pageImagePath.public_path').$page->image;
        unlink($file_path);

        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page Deleted Successfully!');
    }

    public function list(Request $request)
    {
        if( !Auth::user()->role->hasPermission('pages', 'list') ){
            return abort(403);
        }
        if ($request->ajax()) {
            $data = Page::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    return truncateWords($row->title, 35);
                })
                ->addColumn('slug', function ($row) {
                    return truncateWords($row->slug, 35);
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if( Auth::user()->role->hasPermission('pages', 'edit') ) {
                        $options .= '<a href="' . route('admin.pages.edit', $row->id) . '" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if( Auth::user()->role->hasPermission('pages', 'delete') ) {
                        $options .= ' <form action="'. route('admin.pages.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
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

    private function validateRequest($page){

        return tap( request()->validate([
            'title' => 'required|min:3',
            'slug' => 'required',
            'description' => 'required|min:10',
            'keywords' => 'nullable',
            'image' => 'nullable',
            'start_datetime' => 'nullable|date_format:'.config('settings.datetime_format'),
            'end_datetime' => 'nullable|date_format:'.config('settings.datetime_format'),
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

                    return response()->json(['success' => 'true', 'message' => 'Image Deleted Successfully'], 200);
                }
            }

        }

    }
}
