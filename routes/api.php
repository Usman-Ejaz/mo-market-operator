<?php

use App\Http\Controllers\Api\CareersApiController;
use App\Http\Controllers\Api\ContactFormQueryController;
use App\Http\Controllers\Api\DocumentsApiController;
use App\Http\Controllers\Api\FaqApiController;
use App\Http\Controllers\Api\NewsletterSubscriptionController;
use App\Http\Controllers\Api\PublishedNewsApiController;
use App\Http\Controllers\Api\RegisterApiController;
use App\Http\Controllers\Api\SitemapApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterApiController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    
});

Route::prefix("v1")->middleware('verifyApiKey')->group(function () {
    Route::post("submit-query", [ContactFormQueryController::class, "store"])->name("contact-form-query.store");
    Route::post("subscribe-to-newsletter", [NewsletterSubscriptionController::class, "subscribe"])->name("newsletters.subscribe");
    Route::get("faqs", [FaqApiController::class, "show"])->name("faqs.show");

    Route::get("get-news", [PublishedNewsApiController::class, "getPublishedNews"])->name("news.published");
    Route::get("show-news/{slug}", [PublishedNewsApiController::class, "getSingleNews"])->name("news.show");

    Route::get("get-jobs", [CareersApiController::class, "getPublishedJobs"])->name("careers.published");
    Route::get("show-job/{slug}", [CareersApiController::class, "showSingleJob"])->name("careers.show");
    Route::post("submit-job-application", [CareersApiController::class, "submitApplication"])->name("careers.submitApplication");

    Route::get("get-documents", [DocumentsApiController::class, "getPublishedDocs"])->name("documents.published");
    Route::post("search-document", [DocumentsApiController::class, "search"])->name("documents.search");

    Route::get('sitemap', [SitemapApiController::class, 'index'])->name("sitemap.index");
});