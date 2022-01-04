<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

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
        News::create($this->validateRequest());

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
        $test = $news->update($this->validateRequest($news));

        return redirect()->route('admin.news.show', $news->id);
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

    private function validateRequest($news){
        return request()->validate([
            'title' => 'required|min:3',
            'slug' => 'required|unique:news,slug,'.$news->id,
            'description' => 'required|min:10',
            'keywords' => '',
            'image' => '',
            //'start_datetime' => 'date_format:d-m-Y H:i:s',
            //'end_datetime' => 'date_format:d-m-Y H:i:s',
            'start_datetime' => '',
            'end_datetime' => '',
            'newscategory_id' => '',
            'active' => '',
        ]);
    }
}
