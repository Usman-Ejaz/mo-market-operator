<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables;


class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.news.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = NewsCategory::all();
        $news = new News();
        return view('admin.news.create', compact('categories', 'news'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $news = new News();
        News::create($this->validateRequest($news));

        $this->storeImage($news);

        $request->session()->flash('alert-success', 'News was successful added!');
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
        $categories = NewsCategory::all();
        
        return view('admin.news.edit', compact('categories', 'news'));
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
        $news->update($this->validateRequest($news));

        $this->storeImage($news);

        $request->session()->flash('alert-success', 'News was successful updated!');
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
        $news->delete();

        return redirect()->route('admin.news.index');
    }

    public function getNews(Request $request)
    {
        if ($request->ajax()) {
            $data = News::with('newscategory')->latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    return ($row->title) ? ( (strlen($row->title) > 50) ? substr($row->title,0,50).'...' : $row->title ) : '';
                })
                ->addColumn('slug', function ($row) {
                    return ($row->slug) ? ( (strlen($row->slug) > 50) ? substr($row->slug,0,50).'...' : $row->slug ) : '';
                })
                ->addColumn('newscategory', function ($row) {
                    return ($row->newsCategory) ? $row->newsCategory->name : '';
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? Carbon::parse($row->created_at)->format('d/m/Y H:i:s') : '';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="'. route('admin.news.edit',$row->id) .'" class="btn btn-primary">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <form action="'. route('admin.news.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
                            '.csrf_field().'
                            '.method_field("DELETE").'
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm(\'Are You Sure Want to delete this record?\')">
                                    <i class="fas fa-trash"></i>
                            </button>
                        </form>';
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
            'keywords' => '',
            'image' => 'nullable',
            'start_datetime' => 'nullable|date_format:d/m/Y H:i:s',
            'end_datetime' => 'nullable|date_format:d/m/Y H:i:s',
            'newscategory_id' => '',
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

        if(request()->has('image')){
            $news->update([
                'image' => request()->image->store('uploads', 'public')
            ]);
        }
    }

}
