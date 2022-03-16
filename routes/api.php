<?php

use App\Http\Controllers\Api\CareersApiController;
use App\Http\Controllers\Api\Client\ClientAttachmentController;
use App\Http\Controllers\Api\Client\ClientRegistrationController;
use App\Http\Controllers\Api\ContactFormQueryController;
use App\Http\Controllers\Api\DocumentsApiController;
use App\Http\Controllers\Api\FaqApiController;
use App\Http\Controllers\Api\NewsletterSubscriptionController;
use App\Http\Controllers\Api\PublishedPostApiController;
use App\Http\Controllers\Api\RegisterApiController;
use App\Http\Controllers\Api\SitemapApiController;
use App\Http\Controllers\Api\SiteSearchApiController;
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


Route::prefix('v1/auth')->group(function() {
    Route::post('register', [ClientRegistrationController::class, 'register'])->name('client.register');
    Route::post('login', [RegisterApiController::class, 'login']);
});
//Route::post('register', [RegisterController::class, 'register']);

Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::post('upload-attachments', [ClientAttachmentController::class, 'store'])->name('client.attachment.store');
    Route::post('remove-attachments', [ClientAttachmentController::class, 'destroy'])->name('client.attachment.delete');
});

Route::prefix("v1")->middleware('verifyApiKey')->group(function () {
    Route::post("submit-query", [ContactFormQueryController::class, "store"])->name("contact-form-query.store");
    Route::post("subscribe-to-newsletter", [NewsletterSubscriptionController::class, "subscribe"])->name("newsletters.subscribe");
    Route::get("faqs", [FaqApiController::class, "show"])->name("faqs.show");

    Route::get("get-posts", [PublishedPostApiController::class, "getPublishedPosts"])->name("posts.published");
    Route::get("show-post/{slug}", [PublishedPostApiController::class, "getSinglePost"])->name("posts.show");

    Route::get("get-jobs", [CareersApiController::class, "getPublishedJobs"])->name("careers.published");
    Route::get("show-job/{slug}", [CareersApiController::class, "showSingleJob"])->name("careers.show");
    Route::post("submit-job-application", [CareersApiController::class, "submitApplication"])->name("careers.submitApplication");

    Route::get("get-documents", [DocumentsApiController::class, "getPublishedDocs"])->name("documents.published");
    Route::post("search-document", [DocumentsApiController::class, "search"])->name("documents.search");

    Route::post("search", [SiteSearchApiController::class, "search"])->name("site-search.search");

    Route::get('sitemap', [SitemapApiController::class, 'index'])->name("sitemap.index");
});