<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!hasPermission("posts", "list"), 401, __('messages.unauthorized_action'));

        return view('admin.posts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!hasPermission("posts", "create"), 401, __('messages.unauthorized_action'));

        $post = new Post();
        return view('admin.posts.create', compact('post'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(!hasPermission("posts", "create"), 401, __('messages.unauthorized_action'));

        $post = new Post;
        $data = $this->validateRequest($post);
        $data['start_datetime'] = $this->parseDate($request->start_datetime);
        $data['end_datetime'] = $this->parseDate($request->end_datetime);
        $post = Post::create($data);

        $this->storeImage($post);

        $message = __('messages.record_created', ['module' => 'Post']);

        if ($request->action === "Published") {
            $post->published_at = now();
            $post->save();

            $message = __('messages.record_published', ['module' => 'Post']);
        }

        $request->session()->flash('success', $message);
        return redirect()->route('admin.posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        abort_if(!hasPermission("posts", "view"), 401, __('messages.unauthorized_action'));

        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        abort_if(!hasPermission("posts", "edit"), 401, __('messages.unauthorized_action'));

        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        abort_if(!hasPermission("posts", "edit"), 401, __('messages.unauthorized_action'));
        
        $data = $this->validateRequest($post);
        $data['start_datetime'] = $this->parseDate($request->start_datetime);
        $data['end_datetime'] = $this->parseDate($request->end_datetime);
        
        if ($request->has('image')) {
            $data['image'] = storeFile(Post::STORAGE_DIRECTORY, $request->file('image'), $post->image);
        }
        
        $message = __('messages.record_updated', ['module' => 'Post']);
        
        if ($request->action === "Unpublished") {
            $data['published_at'] = null;            
            $message = __('messages.record_unpublished', ['module' => 'Post']);
        } else if ($request->action === "Published") {
            $data['published_at'] = now();            
            $message = __('messages.record_published', ['module' => 'Post']);
        }
        
        $post->update($data);
        
        $request->session()->flash('success', $message);
        return redirect()->route('admin.posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        abort_if(!hasPermission("posts", "delete"), 401, __('messages.unauthorized_action'));

        $post->image !== null && removeFile(Post::STORAGE_DIRECTORY, $post->image);

        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', __('messages.record_deleted', ['module' => 'Post']));
    }

    public function list(Request $request)
    {
        abort_if(!hasPermission("posts", "list"), 401, __('messages.unauthorized_action'));

        if ($request->ajax()) {
            $data = Post::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    return truncateWords($row->title, 27);
                })
                ->addColumn('slug', function ($row) {
                    return truncateWords($row->slug, 27);
                })
                ->addColumn('keywords', function ($row) {
                    return truncateWords($row->keywords, 27);
                })
                ->addColumn('status', function ($row) {
                    return $row->isPublished() ? 'Published' : 'Draft';
                })
                ->addColumn('post_category', function ($row) {
                    return ($row->post_category) ? $row->post_category : '';
                })
                ->editColumn('created_at', function ($row) {
                    return [
                        'display' => $row->created_at,
                        'sort' => Carbon::parse(parseDate($row->created_at))->timestamp
                    ];
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if (hasPermission('posts', 'view')) {
                        $link = $row->link . (! $row->isPublished() ? '?unpublished=true' : '');
                        $options .= '<a href="' . $link . '" class="btn btn-primary mr-1" title="Preview" target="_blank">
                            <i class="fas fa-eye"></i>
                        </a>';
                    }
                    if( hasPermission('posts', 'edit') ) {
                        $options .= '<a href="' . route('admin.posts.edit', $row->id) . '" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if( hasPermission('posts', 'delete') ) {
                        $options .= ' <form action="'. route('admin.posts.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
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

    private function validateRequest($post){

        return request()->validate([
            'title' => 'required|min:3|unique:posts,title,'.$post->id,
            'slug' => 'required|unique:posts,slug,'.$post->id,
            'description' => 'required',
            'keywords' => 'nullable',
            'image' => 'sometimes|file|mimes:'. str_replace("|", ",", config('settings.image_file_extensions')) .'|max:' . config('settings.maxImageSize'),
            'start_datetime' => 'nullable',
            'end_datetime' => 'nullable',
            'post_category' => 'required|integer',
            'active' => 'required',
            'created_by' => '',
            'modified_by' => ''
        ], [
            'slug.unique' => __('messages.unique', ['attribute' => 'Slug']),
            'image.max' => __('messages.max_file', ['limit' => '2 MB']),
        ]);
    }

    private function storeImage ($post, $oldFile = null) {
        if (request()->hasFile('image')) {
            $post->update(['image' => storeFile(Post::STORAGE_DIRECTORY, request()->file('image'), $oldFile)]);
        }
    }

    public function deleteImage(Request $request) {
        if ($request->ajax()) {
            if (isset($request->post_id)) {
                $post = Post::find($request->post_id);
                if (removeFile(Post::STORAGE_DIRECTORY, $post->image)) {
                    $post->image = null;
                    $post->update();
                    return response()->json(['success' => 'true', 'message' => __('messages.image_deleted')], 200);
                }
            }
        }
    }

    private function parseDate($date) {
        if ($date) {
            return Carbon::create(str_replace('/', '-', str_replace(' PM', ':00', str_replace(' AM', ':00', $date))));
        }
        return null;
    }
}
