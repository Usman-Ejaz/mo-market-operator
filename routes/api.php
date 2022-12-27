<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CareersApiController;
use App\Http\Controllers\Api\ChatbotQueriesController;
use App\Http\Controllers\Api\Client\ClientAttachmentController;
use App\Http\Controllers\Api\Client\ClientRegistrationController;
use App\Http\Controllers\Api\ContactFormQueryController;
use App\Http\Controllers\Api\DocumentsApiController;
use App\Http\Controllers\Api\FaqApiController;
use App\Http\Controllers\Api\MediaLibraryApiController;
use App\Http\Controllers\Api\MenuApiController;
use App\Http\Controllers\Api\NewsletterSubscriptionController;
use App\Http\Controllers\Api\PagesApiController;
use App\Http\Controllers\Api\PublishedPostApiController;
use App\Http\Controllers\Api\RegisterApiController;
use App\Http\Controllers\Api\SitemapApiController;
use App\Http\Controllers\Api\SiteSearchApiController;
use App\Http\Controllers\Api\SliderImageApiController;
use App\Http\Controllers\Api\StaticBlockApiController;
use App\Http\Controllers\Api\TeamsApiController;
use App\Http\Controllers\Api\TrainingsApiController;
use App\Http\Controllers\Api\ChatbotFeedbackApiController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\Api\MODataController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\RSSFeedXMLController;
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


Route::prefix('v1/auth')->group(function () {
    Route::post('register', [ClientRegistrationController::class, 'register'])->name('client.register');
    Route::post('login', [RegisterApiController::class, 'login']);
});

Route::prefix('v1/client-auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('client.auth.login');
    Route::post('logout', [AuthController::class, 'logout'])->name('client.auth.logout');
    Route::post('refresh', [AuthController::class, 'refresh'])->name('client.auth.refresh');
    Route::post('me', [AuthController::class, 'me'])->name('client.auth.me');
});

Route::prefix('v1')->middleware('auth:api-jwt')->name('client.')->group(function () {
    Route::get('reports/info', [ReportController::class, 'info'])->name('reports.info');
    Route::get('reports/billing-and-settlement', [ReportController::class, 'billingAndSettlement'])->name('reports.billing-and-settlement');
    Route::get('reports/billing-and-settlement/info', [ReportController::class, 'billingAndSettlementInfo'])->name('reports.billing-and-settlement-info');
    Route::get('reports/contract-details', [ReportController::class, 'contractDetails'])->name('reports.contract-details');
    Route::get('reports/contract-details/info', [ReportController::class, 'contractDetailsInfo'])->name('reports.contract-details-info');
    Route::get('reports/firm-capacity-certificate', [ReportController::class, 'firmCapacityCertificate'])->name('reports.firm-capacity-certificate');
    Route::get('reports/firm-capacity-certificate/info', [ReportController::class, 'firmCapacityCertificateInfo'])->name('reports.firm-capacity-certificate-info');
    Route::get('reports/metering-data', [ReportController::class, 'meteringData'])->name('reports.metering-data');
    Route::get('reports/metering-data/info', [ReportController::class, 'meteringDataInfo'])->name('reports.metering-data-info');
    Route::get('reports/security-cover', [ReportController::class, 'securityCover'])->name('reports.security-cover');
    Route::get('reports/security-cover/info', [ReportController::class, 'securityCoverInfo'])->name('reports.security-cover-info');
    Route::get('reports/compliance-with-capacity-obligation', [ReportController::class, 'complianceWithCapacityObligation'])->name('reports.compliance-with-capacity-obligation');
    Route::get('reports/compliance-with-capacity-obligation/info', [ReportController::class, 'complianceWithCapacityObligationInfo'])->name('reports.compliance-with-capacity-obligation-info');
    Route::get('reports/{report}', [ReportController::class, 'show'])->name('reports.show');

    //Complaint Module Routes
    Route::get('complaints/departments', [ComplaintController::class, 'getDepartments'])->name('complaints.departments');
    Route::apiResource('complaints', ComplaintController::class)->only(['index', 'store', 'show']);
});
//Route::post('register', [RegisterController::class, 'register']);

Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::post('upload-attachments', [ClientAttachmentController::class, 'store'])->name('client.attachment.store');
    Route::post('remove-attachments', [ClientAttachmentController::class, 'destroy'])->name('client.attachment.delete');

    Route::post('confirm-registration', [ClientRegistrationController::class, 'confirmRegistration'])->name('client.confirm-registation');
    Route::put('update-client', [ClientRegistrationController::class, 'updateClient'])->name('client.update');

    Route::get('download-application', [ClientRegistrationController::class, 'downloadApplication'])->name('client.download-application');
});

Route::prefix("v1")->middleware('verifyApiKey')->group(function () {
    Route::post("submit-query", [ContactFormQueryController::class, "submit"])->name("contact-form-query.submit");
    Route::post("subscribe-to-newsletter", [NewsletterSubscriptionController::class, "subscribe"])->name("newsletters.subscribe");
    Route::get("faqs", [FaqApiController::class, "showFaqs"])->name("faqs.showFaqs");

    Route::get("news-and-blogs", [PublishedPostApiController::class, "getPublishedPosts"])->name("posts.published");
    // Route::get("news-and-blogs/{slug}", [PublishedPostApiController::class, "getSinglePost"])->name("posts.show");

    Route::get('media-libraries', [MediaLibraryApiController::class, 'mediaLibraryList'])->name('media-libraries.list');
    Route::get('media-libraries/{slug}', [MediaLibraryApiController::class, 'mediaFiles'])->name('media-libraries.files');

    Route::get('slider-images', [SliderImageApiController::class, 'getSliderImages'])->name('slider-images.getSliderImages');
    Route::get('menus', [MenuApiController::class, 'getMenus'])->name('menus.getMenus');
    Route::get('static-blocks', [StaticBlockApiController::class, 'show'])->name('static-blocks.show');

    Route::get("announcements", [PublishedPostApiController::class, "getPublishedAnnouncements"])->name("announcements.published");
    // Route::get("announcements/{slug}", [PublishedPostApiController::class, "getAnnouncement"])->name("announcements.show");

    Route::get("jobs", [CareersApiController::class, "getPublishedJobs"])->name("careers.published");
    Route::get("job/{slug}", [CareersApiController::class, "showSingleJob"])->name("careers.show");
    Route::post("submit-job-application", [CareersApiController::class, "submitApplication"])->name("careers.submitApplication");

    Route::get("documents", [DocumentsApiController::class, "getPublishedDocs"])->name("documents.published");
    Route::post("search-document", [DocumentsApiController::class, "search"])->name("documents.search");

    Route::post("search", [SiteSearchApiController::class, "search"])->name("site-search.search");

    Route::get('post-menu', [PublishedPostApiController::class, "postMenus"])->name('posts.menu');
    Route::get('library-menus', [MenuApiController::class, 'libraryMenus'])->name('library.menu');

    Route::get('posts', [PublishedPostApiController::class, "listPosts"])->name('posts.list');
    Route::get('posts/{category}', [PublishedPostApiController::class, "getPostsByCategory"])->name('posts.list');
    Route::get('posts/{category}/{slug}', [PublishedPostApiController::class, "getSinglePost"])->name('posts.show');

    Route::get('library/{category}', [DocumentsApiController::class, 'getDocumentsByCategory'])->name('documents.by-category');

    // Route::get('publications/{category}', [DocumentsApiController::class, 'getDocumentsByCategory'])->name('documents.by-category');
    Route::get('publications/{category}/{slug}', [DocumentsApiController::class, 'getSingleDocument'])->name('documents.show');

    Route::get('pages/{slug}', [PagesApiController::class, "showPage"])->name('pages.showPage');

    Route::get('managers', [TeamsApiController::class, "getManagers"])->name('teams.managers');
    Route::get('team/{manager_id}', [TeamsApiController::class, "getTeam"])->name('teams.team');

    Route::get("trainings", [TrainingsApiController::class, "getTrainings"])->name("trainings.index");
    Route::get("trainings/{slug}", [TrainingsApiController::class, "getTrainingDetails"])->name("trainings.show");

    Route::get('client-registration-form', [ClientRegistrationController::class, 'getRegistrationFormData'])->name('client.registration-form');

    Route::post('save-chat-initiator-details', [ChatbotQueriesController::class, 'storeInitiatorDetails'])->name('chatbot.store-details');
    Route::post('chatbot-query', [ChatbotQueriesController::class, 'askQuestion'])->name('chatbot.ask-query');
    Route::get('close-chat', [ChatbotQueriesController::class, 'sendChatHistoryEmail'])->name('chatbot.send-emails');

    Route::post('feedback-rating', [ChatbotFeedbackApiController::class, 'submitFeedback'])->name('feedback.submit');

    Route::get('sitemap', [SitemapApiController::class, 'index'])->name("sitemap.index");

    Route::get('rss-feed.xml', [RSSFeedXMLController::class, 'generateXML'])->name("rss-feed.generateXML");

    Route::get('mo-data/{mo_datum}/graph', [MODataController::class, 'getGraph']);
    Route::get('mo-data/{mo_datum}/files', [MODataController::class, 'files']);
    Route::resource('mo-data', MODataController::class)->only(['index', 'show']);
});
