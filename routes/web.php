<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ChatBotKnowledgeBaseController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CkeditorImageUploader;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContactPageQueryController;
use App\Http\Controllers\DocumentCategoryController;
use App\Http\Controllers\FaqCategoryController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MediaFileController;
use App\Http\Controllers\MediaLibraryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchStatisticController;
use App\Http\Controllers\StaticBlockController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});

Route::middleware(['auth', 'preventBrowserHistory'])->prefix("admin")->name("admin.")->group(function () {
    
    Route::get('dashboard', function () {
        return view('admin.dashboard.index');
    })->name('dashboard');
    
    // Routes for User Module
    Route::get('users/list', [UserController::class, 'list'])->name('users.list');
    Route::post('users/deleteImage', [UserController::class, 'deleteImage'])->name('users.deleteImage');
    Route::resource('users', UserController::class);
    
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('update-profile/{user}', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('deleteImage', [ProfileController::class, 'deleteImage'])->name('profile.deleteImage');
    
    // Routes for Posts Module
    Route::get('posts/list', [PostController::class, 'list'])->name('posts.list');
    Route::post('posts/deleteImage', [PostController::class, 'deleteImage'])->name('posts.deleteImage');
    Route::resource('posts', PostController::class);
    
    // Routes for Job Module
    Route::get('jobs/list', [JobController::class, 'list'])->name('jobs.list');
    Route::post('jobs/deleteImage', [JobController::class, 'deleteImage'])->name('jobs.deleteImage');
    Route::get('jobs/{job}/applications/export', [JobController::class, 'exportApplicationsList'])->name('job.applications.list.export')->withoutMiddleware(['preventBrowserHistory']);
    Route::resource('jobs', JobController::class);
    
    // Routes for Application Module
    Route::get('jobs/{job}/applications', [JobController::class, 'getJobApplications'])->name('job.applications');
    Route::get('jobs/{job}/applications/list', [JobController::class, 'getApplicationsList'])->name('job.applications.list');    
    Route::get('applications/{application}', [ApplicationController::class, 'show'])->name('job.application.detail');
    Route::delete('applications/{application}', [ApplicationController::class, 'destroy'])->name('job.application.destroy');
    
    // Routes for FAQ Module
    Route::get('faqs/list', [FaqController::class, 'list'])->name('faqs.list');
    Route::resource('faqs', FaqController::class);

    Route::get('faq-categories/list', [FaqCategoryController::class, 'list'])->name('faq-categories.list');
    Route::resource('faq-categories', FaqCategoryController::class);
    
    // Routes for Role Module
    Route::get('roles/list', [RoleController::class, 'list'])->name('roles.list');
    Route::resource('roles', RoleController::class);
    
    // Routes for Permission Module
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::post('permissions/getpermissions', [PermissionController::class, 'getPermissions'])->name('permissions.getpermissions');
    Route::post('permissions/store', [PermissionController::class, 'store'])->name('permissions.store');
    
    // Routes for Menu Module
    Route::post('menus/search', [MenuController::class, 'search'])->name('menus.search');
    Route::get('menus/{menu}/submenus', [MenuController::class, 'submenus'])->name('menus.submenus');
    Route::patch('menus/{menu}/submenusupdate', [MenuController::class, 'submenusupdate'])->name('menus.submenusupdate');
    Route::get('menus/list', [MenuController::class, 'list'])->name('menus.list');
    Route::resource('menus', MenuController::class);
    
    // Routes for Document Module
    Route::get('document-categories/list', [DocumentCategoryController::class, 'list'])->name('document-categories.list');    
    Route::resource('document-categories', DocumentCategoryController::class);
    
    // Routes for Document Module
    Route::get('documents/list', [DocumentController::class, 'list'])->name('documents.list');
    Route::post('documents/deleteFile', [DocumentController::class, 'deleteFile'])->name('documents.deleteFile');
    Route::resource('documents', DocumentController::class);
    
    // Routes for Page Module
    Route::get('cms-pages/list', [PageController::class, 'list'])->name('pages.list');
    Route::post('cms-pages/deleteImage', [PageController::class, 'deleteImage'])->name('pages.deleteImage');
    Route::resource('cms-pages', PageController::class, ['names' => 'pages']);
    
    // Route for uploading images for ckeditor
    Route::post('ckeditor/upload', [CkeditorImageUploader::class, 'upload'])->name('ckeditor.upload');
    
    // Route for settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::patch('settings/update', [SettingsController::class, 'update'])->name('settings.update');
    
    // Routes for Newsletter Module
    Route::get('newsletters/list', [NewsletterController::class, 'list'])->name('newsletters.list');
    Route::post('newsletters/sendNewsLetter/{newsletter}', [NewsletterController::class, 'sendNewsLetter'])->name('newsletters.sendNewsLetter');
    Route::resource('newsletters', NewsletterController::class);
    
    // Routes for Subscribers
    Route::post('subscribers/bulk-action', [SubscriberController::class, 'bulkToggle'])->name('subscribers.bulkToggle');
    Route::get('subscribers/list', [SubscriberController::class, 'list'])->name('subscribers.list');
    Route::post('subscribers/toggle-subscription/{subscriber}', [SubscriberController::class, 'toggleSubscription'])->name('subscribers.toggleSubscription');
    Route::resource("subscribers", SubscriberController::class);
    
    // Routes for Document Module
    Route::get('contact-page-queries/list', [ContactPageQueryController::class, 'list'])->name('contact-page-queries.list');
    Route::resource('contact-page-queries', ContactPageQueryController::class);
    
    // Routes for Search Statistics
    Route::get('search-statistics/list', [SearchStatisticController::class, 'list'])->name('search-statistics.list');
    Route::get('search-statistics/export-keywords', [SearchStatisticController::class, 'exportkeywords'])->name('search-statistics.export-list')->withoutMiddleware(['preventBrowserHistory']);
    Route::resource('search-statistics', SearchStatisticController::class);

    Route::get('knowledge-base/list', [ChatBotKnowledgeBaseController::class, 'list'])->name('knowledge-base.list');
    Route::resource('knowledge-base', ChatBotKnowledgeBaseController::class);

    Route::get('clients/list', [ClientController::class, 'list'])->name('clients.list');
    Route::resource('clients', ClientController::class);

    Route::get('static-block/list', [StaticBlockController::class, 'list'])->name('static-block.list');
    Route::resource('static-block', StaticBlockController::class);

    Route::get('media-library/list', [MediaLibraryController::class, 'list'])->name('media-library.list');
    Route::resource('media-library', MediaLibraryController::class);
    
    Route::post('media-library/{mediaLibrary}/upload', [MediaFileController::class, 'store'])->name('media-library.files.upload');
    Route::get('media-library/{mediaLibrary}/manage-files/list', [MediaFileController::class, 'list'])->name('media-library.files.list');
    Route::get('media-library/{mediaLibrary}/manage-files', [MediaFileController::class, 'index'])->name('media-library.files');
    Route::post('manage-files/remove', [MediaFileController::class, 'destroy'])->name('media-library.files.remove');
    Route::post('media-library/updateFile', [MediaFileController::class, 'update'])->name('media-library.updateFile');
    
    
    Route::get("update-password", [ProfileController::class, "updatePasswordView"])->name("update-password");
    Route::post("update-password", [ProfileController::class, "updatePassword"])->name("password-update");
});

Route::get('pages/{slug}', function ($slug) {
    return '<html><body><h1>'. $slug . '</h1></body></html>';
})->name('pages.show');

require __DIR__.'/auth.php';
