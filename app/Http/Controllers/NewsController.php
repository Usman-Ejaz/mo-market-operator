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

        $request->session()->flash('success', 'News was successfully added!');
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

        $news->update($this->validateRequest($news));

        $this->storeImage($news);

        $request->session()->flash('success', 'News was successfully updated!');
        return redirect()->route('admin.news.edit', $news->id);
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

        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'News was successfully deleted!');
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
                    return ($row->title) ? ( (strlen($row->title) > 50) ? substr($row->title,0,50).'...' : $row->title ) : '';
                })
                ->addColumn('slug', function ($row) {
                    return ($row->slug) ? ( (strlen($row->slug) > 50) ? substr($row->slug,0,50).'...' : $row->slug ) : '';
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
                        $options .= '<a href="' . route('admin.news.edit', $row->id) . '" class="btn btn-primary" title="edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if( Auth::user()->role->hasPermission('news', 'delete') ) {
                        $options .= ' <form action="'. route('admin.news.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
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

    private function validateRequest($news){

        return tap( request()->validate([
            'title' => 'required|min:3',
            'slug' => 'required|unique:news,slug,'.$news->id,
            'description' => 'required|min:10',
            'keywords' => 'nullable',
            'image' => 'nullable',
            'start_datetime' => 'nullable|date_format:'.config('settings.datetime_format'),
            'end_datetime' => 'nullable|date_format:'.config('settings.datetime_format'),
            'news_category' => 'required|integer',
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

    private function storeImage($news){

        if (request()->has('image')) {
            $uploadFile = request()->file('image');
            $file_name = $uploadFile->hashName();
            $uploadFile->storeAs(config('filepaths.newsImagePath.internal_path'), $file_name);

            $news->update([
                'image' => $file_name,
            ]);
        }
    }

    public function deleteImage(Request $request){
        if ($request->ajax()) {
            if( isset($request->news_id) ){

                $news = News::find($request->news_id);
                $image_path = config('filepaths.newsImagePath.public_path').$news->image;

                if( unlink($image_path) ){
                    $news->image = null;
                    $news->update();

                    return response()->json(['success' => 'true', 'message' => 'image deleted successfully'], 200);
                }
            }

        }

    }

}
