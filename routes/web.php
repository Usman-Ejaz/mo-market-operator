<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CkeditorImageUploader;
use App\Http\Controllers\DocumentCategoryController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Http\Request;

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
    return view('welcome');
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

    // Routes for News Module
    Route::get('news/list', [NewsController::class, 'list'])->name('news.list');
    Route::post('news/deleteImage', [NewsController::class, 'deleteImage'])->name('news.deleteImage');
    Route::resource('news', NewsController::class);

    // Routes for Job Module
    Route::get('jobs/list', [JobController::class, 'list'])->name('jobs.list');
    Route::post('jobs/deleteImage', [JobController::class, 'deleteImage'])->name('jobs.deleteImage');
    Route::resource('jobs', JobController::class);

    // Routes for Application Module
    Route::get('jobs/{job}/applications', [JobController::class, 'getJobApplications'])->name('job.applications');
    Route::get('jobs/{job}/applications/list', [JobController::class, 'getApplicationsList'])->name('job.applications.list');    
    Route::get('applications/{application}', [ApplicationController::class, 'show'])->name('job.application.detail');
    Route::delete('applications/{application}', [ApplicationController::class, 'destroy'])->name('job.application.destroy');

    // Routes for FAQ Module
    Route::get('faqs/list', [FaqController::class, 'list'])->name('faqs.list');
    Route::resource('faqs', FaqController::class);

    // Routes for Role Module
    Route::get('roles/list', [RoleController::class, 'list'])->name('roles.list');
    Route::resource('roles', RoleController::class);

    // Routes for Permission Module
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::post('permissions/getpermissions', [PermissionController::class, 'getPermissions'])->name('permissions.getpermissions');
    Route::post('permissions/store', [PermissionController::class, 'store'])->name('permissions.store');

    // Routes for Menu Module
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
    Route::get('pages/list', [PageController::class, 'list'])->name('pages.list');
    Route::post('pages/deleteImage', [PageController::class, 'deleteImage'])->name('pages.deleteImage');
    Route::resource('pages', PageController::class);

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
    Route::get('subscribers/list', [SubscriberController::class, 'list'])->name('subscribers.list');
    Route::post('subscribers/toggle-subscription/{subscriber}', [SubscriberController::class, 'toggleSubscription'])->name('subscribers.toggleSubscription');
    Route::resource("subscribers", SubscriberController::class);
});
Route::middleware(['auth'])->prefix("admin")->name("admin.")->group(function () {
    Route::get('jobs/{job}/applications/export', [JobController::class, 'exportApplicationsList'])->name('job.applications.list.export');
});

Route::get("create-password/{user}", [NewPasswordController::class, "createPassword"])->name("create-password")->middleware(["guest"]);

require __DIR__.'/auth.php';
