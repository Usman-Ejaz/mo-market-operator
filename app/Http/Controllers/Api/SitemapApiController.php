<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use Illuminate\Http\Request;

class SitemapApiController extends BaseApiController
{
    
    public function index()
    {
        try {
            $pages = Page::published()->oldest()->get();

            if ($pages->count() > 0) {
                return $this->sendResponse($pages, 'Sitemap retrieved successfully.');
            } else {
                return $this->sendResponse([], "Could not find pages.");
            }
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }
}
