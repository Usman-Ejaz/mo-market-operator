<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RSSFeedXMLController extends Controller
{
    
    /**
     * 
     * @OA\Tag(
     *     name="RSS Feed",
     *     description="API Endpoints of RSS Feed"
     * )
     * 
     * @OA\Get(
     *      path="/rss-feed.xml",
     *      operationId="generateXML",
     *      tags={"RSS Feed"},
     *      summary="Get list of RSS Feed XMl",
     *      description="Returns RSS Feed XMl",
     *      security={{"BearerAppKey": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Success"          
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Could not found",
     *      ),
     *  )
     */
    public function generateXML()
    {        
        $pages = $this->getRecords('Page');
        $posts = $this->getRecords('Post');
        $medias = $this->getRecords('MediaLibrary');

        $todaysPublishedRecords = collect([]);

        $todaysPublishedRecords = $todaysPublishedRecords->merge($pages)->merge($posts)->merge($medias);

        return response()->view('admin.rss-feed.index', compact('todaysPublishedRecords'))->header('Content-Type', 'application/xml');
    }
    
    /**
     * getRecords
     *
     * @param  mixed $model
     * @return mixed
     */
    private function getRecords($model)
    {
        $model = 'App\\Models\\' . $model;

        $records = $model::todaysPublishedRecords()->get();

        return $records;
    }
}
