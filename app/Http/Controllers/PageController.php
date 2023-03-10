<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Carbon\Carbon;
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

        $cms_page = new Page();
        return view('admin.pages.create', compact('cms_page'));
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
        $data['start_datetime'] = $this->parseDate($request->start_datetime);
        $data['end_datetime'] = $this->parseDate($request->end_datetime);

        if ($request->hasFile('image')) {
            $data['image'] = storeFile(Page::STORAGE_DIRECTORY, $request->file('image'));
        }

        $page = Page::create($data);

        $message = __('messages.record_created', ['module' => 'Page']);

        if ($request->action === "Published") {
            $page->published_at = now();
            $page->save();

            $message = __('messages.record_published', ['module' => 'Page']);
        }

        $request->session()->flash('success', $message);
        return redirect()->route('admin.pages.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $cms_page)
    {
        abort_if(!hasPermission("pages", "view"), 401, __('messages.unauthorized_action'));

        return view('admin.pages.show', compact('cms_page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $cms_page)
    {
        abort_if(!hasPermission("pages", "edit"), 401, __('messages.unauthorized_action'));

        return view('admin.pages.edit', compact('cms_page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $cms_page)
    {
        abort_if(!hasPermission("pages", "edit"), 401, __('messages.unauthorized_action'));

        $data = $this->validateRequest($cms_page);
        $data['start_datetime'] = $this->parseDate($request->start_datetime);
        $data['end_datetime'] = $this->parseDate($request->end_datetime);

        $message = __('messages.record_updated', ['module' => 'Page']);

        if ($request->action === "Unpublished") {
            $data['published_at'] = null;
            $message = __('messages.record_unpublished', ['module' => 'Page']);
        } else if ($request->action === "Published") {
            $data['published_at'] = now();
            $message = __('messages.record_published', ['module' => 'Page']);
        }

        if ($request->get('removeImage') == "1") {
            removeFile(Page::STORAGE_DIRECTORY, $cms_page->image);
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            $data['image'] = storeFile(Page::STORAGE_DIRECTORY, $request->file('image'));
        }

        $cms_page->update($data);

        $request->session()->flash('success', $message);
        return redirect()->route('admin.pages.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $cms_page)
    {
        abort_if(!hasPermission("pages", "delete"), 401, __('messages.unauthorized_action'));

        if ($cms_page->image !== null) {
            removeFile(Page::STORAGE_DIRECTORY, $cms_page->image);
        }

        $cms_page->delete();
        return redirect()->route('admin.pages.index')->with('success', __('messages.record_deleted', ['module' => 'Page']));
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
                ->addColumn('keywords', function ($row) {
                    return truncateWords($row->keywords, 25);
                })
                ->addColumn('status', function ($row) {
                    return $row->isPublished() ? __("Published") : __("Draft");
                })
                ->editColumn('created_at', function ($row) {
                    return [
                        'display' => $row->created_at,
                        'sort' => Carbon::parse(parseDate($row->created_at))->timestamp
                    ];
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if (hasPermission('pages', 'view')) {
                        $link = $row->link . (!$row->isPublished() ? '?unpublished=true' : '');
                        $options .= '<a href="' . $link . '" class="btn btn-primary mr-1" title="Preview" target="_blank">
                            <i class="fas fa-eye"></i>
                        </a>';
                    }
                    if (hasPermission('pages', 'edit')) {
                        $options .= '<a href="' . route('admin.pages.edit', $row->id) . '" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if (hasPermission('pages', 'delete')) {
                        $options .= ' <button type="button" class="btn btn-danger deleteButton" data-action="' . route('admin.pages.destroy', $row->id) . '" title="Delete">
                                <i class="fas fa-trash" data-action="' . route('admin.pages.destroy', $row->id) . '"></i>
                        </button>';
                    }
                    return $options;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    private function validateRequest($page)
    {

        return request()->validate([
            'title' => 'required|min:3|unique:pages,title,' . $page->id,
            'slug' => 'required|unique:pages,slug,' . $page->id,
            'description' => 'required',
            'keywords' => 'nullable',
            'image' => 'sometimes|file|mimes:' . str_replace("|", ",", config('settings.image_file_extensions')) . '|max:' . config('settings.maxImageSize'),
            'start_datetime' => 'nullable',
            'end_datetime' => 'nullable',
            'active' => 'required',
            'created_by' => '',
            'modified_by' => ''
        ], [
            "slug.unique" => __('messages.unique', ['attribute' => 'slug']),
            "image.max" => __('messages.max_file', ['limit' => '2 MB']),
        ]);
    }

    public function deleteImage(Request $request)
    {
        if ($request->ajax()) {
            if (isset($request->page_id)) {

                $page = Page::find($request->page_id);

                if (removeFile(Page::STORAGE_DIRECTORY, $page->image)) {
                    $page->image = null;
                    $page->update();
                    return response()->json(['success' => 'true', 'message' => __('messages.record_deleted', ['module' => 'Image'])], 200);
                }
            }
        }
    }

    private function parseDate($date)
    {
        if ($date) {
            return Carbon::create(str_replace('/', '-', str_replace(' PM', ':00', str_replace(' AM', ':00', $date))));
        }
        return null;
    }
}
