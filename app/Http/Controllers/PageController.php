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
        abort_if(!hasPermission("pages", "list"), 401, __('messages.unauthorized_action'));

        return view('admin.pages.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!hasPermission("pages", "create"), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission("pages", "create"), 401, __('messages.unauthorized_action'));

        $page = new Page();
        $data = $this->validateRequest($page);
        $data['start_datetime'] = $request->start_date ?? null;
        $data['end_datetime'] = $request->end_date ?? null;
        $page = Page::create($data);

        $this->storeImage($page);

        if ($request->action === "Published") {
            $page->published_at = now();
            $page->save();
        }

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
        abort_if(!hasPermission("pages", "view"), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission("pages", "edit"), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission("pages", "edit"), 401, __('messages.unauthorized_action'));

        $previousImage = $page->image;
        $data = $this->validateRequest($page);
        $data['start_datetime'] = $request->start_date ?? null;
        $data['end_datetime'] = $request->end_date ?? null;
        $page->update($data);

        if ($request->action === "Unpublished") {
            $page->published_at = null;
            $page->save();
        } else if ($request->action === "Published") {
            $page->published_at = now();
            $page->save();
        }

        $this->storeImage($page, $previousImage);

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
        abort_if(!hasPermission("pages", "delete"), 401, __('messages.unauthorized_action'));

        if ($page->image !== null) {
            $file_path = public_path(config('filepaths.pageImagePath.public_path')) . basename($page->image);
            unlink($file_path);
        }

        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page Deleted Successfully!');
    }

    public function list(Request $request)
    {
        abort_if(!hasPermission("pages", "list"), 401, __('messages.unauthorized_action'));

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
                    if( hasPermission('pages', 'edit') ) {
                        $options .= '<a href="' . route('admin.pages.edit', $row->id) . '" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if( hasPermission('pages', 'delete') ) {
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

        return request()->validate([
            'title' => 'required|min:3',
            'slug' => 'required|unique:pages,slug,'.$page->id,
            'description' => 'required',
            'keywords' => 'nullable',
            'image' => 'sometimes|file|image|max:2000',
            'start_datetime' => 'nullable',
            'end_datetime' => 'nullable',
            'active' => 'required',
            'created_by' => '',
            'modified_by' => ''
        ], [
            "slug.unique" => __('messages.unique', ['attribute' => 'Slug']),
            "image.max" => __('messages.max_file', ['limit' => '2 MB']),
        ]);
    }

    private function storeImage($page, $previousImage = null){

        if (request()->has('image')) {

            if ($previousImage !== null) {
                $file_path = public_path(config('filepaths.pageImagePath.public_path')) . basename($previousImage);
                unlink($file_path);
            }

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
                $image_path = config('filepaths.pageImagePath.public_path').basename($page->image);

                if( unlink($image_path) ){
                    $page->image = null;
                    $page->update();

                    return response()->json(['success' => 'true', 'message' => 'Image Deleted Successfully'], 200);
                }
            }
        }
    }
}
