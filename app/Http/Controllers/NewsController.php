<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( !Auth::user()->role->hasPermission('news', 'list') ){
            return abort(403);
        }

        return view('admin.news.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( !Auth::user()->role->hasPermission('news', 'create') ){
            return abort(403);
        }

        $news = new News();
        return view('admin.news.create', compact('news'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( !Auth::user()->role->hasPermission('news', 'create') ){
            return abort(403);
        }
        $news = new News();
        $news = News::create( $this->validateRequest($news) );

        $this->storeImage($news);

        if ($request->action === "Published") {
            $news->published_at = now();
            $news->save();
        }

        $request->session()->flash('success', "News {$request->action} Successfully!");
        return redirect()->route('admin.news.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        if( !Auth::user()->role->hasPermission('news', 'view') ){
            return abort(403);
        }

        return view('admin.news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news)
    {
        if( !Auth::user()->role->hasPermission('news', 'edit') ){
            return abort(403);
        }

        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, News $news)
    {
        if( !Auth::user()->role->hasPermission('news', 'edit') ){
            return abort(403);
        }
        $previousImage = $news->image;
        $news->update($this->validateRequest($news));
        
        $this->storeImage($news, $previousImage);

        if ($request->action === "Unpublished") {
            $news->published_at = null;
            $news->save();
        } else if ($request->action === "Published") {
            $news->published_at = now();
            $news->save();
        }

        $request->session()->flash('success', "News {$request->action} Successfully!");
        return redirect()->route('admin.news.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        if( !Auth::user()->role->hasPermission('news', 'delete') ){
            return abort(403);
        }

        if ($news->image !== null) {
            $file_path = public_path(config('filepaths.newsImagePath.public_path')) . basename($news->image);
            unlink($file_path);
        }

        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'News Deleted Successfully!');
    }

    public function list(Request $request)
    {
        if( !Auth::user()->role->hasPermission('news', 'list') ){
            return abort(403);
        }

        if ($request->ajax()) {
            $data = News::latest()->get();

            return Datatables::of($data)
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
                ->addColumn('news_category', function ($row) {
                    return ($row->news_category) ? $row->news_category : '';
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if( Auth::user()->role->hasPermission('news', 'edit') ) {
                        $options .= '<a href="' . route('admin.news.edit', $row->id) . '" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if( Auth::user()->role->hasPermission('news', 'delete') ) {
                        $options .= ' <form action="'. route('admin.news.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
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

    private function validateRequest($news){

        return tap( request()->validate([
            'title' => 'required|min:3',
            'slug' => 'required|unique:news,slug,'.$news->id,
            'description' => 'required',
            'keywords' => 'nullable',
            'image' => 'nullable',
            'start_datetime' => 'nullable',
            'end_datetime' => 'nullable',
            'news_category' => 'required|integer',
            'active' => 'required',
            'created_by' => '',
            'modified_by' => ''
        ], [
            'slug.unique' => __('messages.unique', ['attribute' => 'Slug'])
        ]), function(){
            if( request()->hasFile('image') ){
                request()->validate([
                    'image' => 'file|image|max:2000'
                ]);
            }
        });
    }

    private function storeImage($news, $previousImage = null){

        if (request()->has('image')) {

            if ($previousImage !== null) {
                $file_path = public_path(config('filepaths.newsImagePath.public_path')) . basename($previousImage);
                unlink($file_path);
            }
            
            $uploadFile = request()->file('image');
            $file_name = $uploadFile->hashName();
            $uploadFile->storeAs(config('filepaths.newsImagePath.internal_path'), $file_name);

            $news->update(['image' => $file_name ]);
        }
    }

    public function deleteImage(Request $request){
        if ($request->ajax()) {
            if( isset($request->news_id) ){

                $news = News::find($request->news_id);
                $image_path = public_path(config('filepaths.newsImagePath.public_path')).basename($news->image);

                if( unlink($image_path) ){
                    $news->image = null;
                    $news->update();

                    return response()->json(['success' => 'true', 'message' => 'Image Deleted Successfully'], 200);
                }
            }

        }

    }

}
