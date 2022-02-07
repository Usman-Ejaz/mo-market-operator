<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Sitemap as SitemapResource;
use App\Models\Page;
use Illuminate\Http\Request;

class SitemapApiController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::all();

        return $this->sendResponse(SitemapResource::collection($pages), 'Sitemap retrieved successfully.');
    }
}
