<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class PublishedNewsApiController extends BaseApiController
{
    public function getPublishedNews() {
        try {
            $posts = News::published()->latest()->get();

            if ($posts->count() > 0) {
                return $this->sendResponse($posts, "Success");
            } else {
                return $this->sendResponse([], "Could not find news.");
            }
        } catch (\Exception $e) {
            return $this->sendError("Something went wrong.", ["errors" => $e->getMessage()], 402);
        }
    }

    public function getSingleNews ($slug) {
        try {
            $post = News::published()->where("slug", "=", $slug)->first();

            if ($post) {
                return $this->sendResponse($post, "Success");
            } else {
                return $this->sendResponse([], "Could not found news");
            }
        } catch (\Exception $e) {
            return $this->sendError("Something went wrong.", ["errors" => $e->getMessage()], 402);
        }
    }
}
