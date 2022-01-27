<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CkeditorImageUploader;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;

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

Route::get('/admin/dashboard', function () {
    return view('admin/dashboard/index');
})->middleware(['auth'])->name('admin.dashboard');

// Routes for User Module
Route::get('admin/users/list', [UserController::class, 'list'])->name('admin.users.list')->middleware(['auth']);
Route::post('admin/users/deleteImage', [UserController::class, 'deleteImage'])->name('admin.users.deleteImage')->middleware(['auth']);
Route::resource('/admin/users', UserController::class, [
    'as' => 'admin'
])->middleware(['auth']);

// Routes for News Module
Route::get('admin/news/list', [NewsController::class, 'list'])->name('admin.news.list')->middleware(['auth']);
Route::post('admin/news/deleteImage', [NewsController::class, 'deleteImage'])->name('admin.news.deleteImage')->middleware(['auth']);
Route::resource('/admin/news', NewsController::class, [
    'as' => 'admin'
])->middleware(['auth']);

// Routes for Job Module
Route::get('admin/jobs/list', [JobController::class, 'list'])->name('admin.jobs.list')->middleware(['auth']);
Route::post('admin/jobs/deleteImage', [JobController::class, 'deleteImage'])->name('admin.jobs.deleteImage')->middleware(['auth']);
Route::resource('/admin/jobs', JobController::class, [
    'as' => 'admin'
])->middleware(['auth']);

// Routes for Application Module
Route::get('admin/jobs/{job}/applications', [JobController::class, 'getJobApplications'])->name('admin.job.applications')->middleware(['auth']);
Route::get('admin/jobs/{job}/applications/list', [JobController::class, 'getApplicationsList'])->name('admin.job.applications.list')->middleware(['auth']);
Route::get('admin/jobs/{job}/applications/export', [JobController::class, 'exportApplicationsList'])->name('admin.job.applications.list.export')->middleware(['auth']);
Route::get('admin/applications/{application}', [ApplicationController::class, 'show'])->name('admin.job.application.detail')->middleware(['auth']);
Route::delete('admin/applications/{application}', [ApplicationController::class, 'destroy'])->name('admin.job.application.destroy')->middleware(['auth']);

// Routes for FAQ Module
Route::get('admin/faqs/list', [FaqController::class, 'list'])->name('admin.faqs.list')->middleware(['auth']);
Route::resource('/admin/faqs', FaqController::class, [
    'as' => 'admin'
])->middleware(['auth']);

// Routes for Role Module
Route::get('admin/roles/list', [RoleController::class, 'list'])->name('admin.roles.list')->middleware(['auth']);
Route::resource('/admin/roles', RoleController::class, [
    'as' => 'admin'
])->middleware(['auth']);

// Routes for Permission Module
Route::get('admin/permissions', [PermissionController::class, 'index'])->name('admin.permissions.index')->middleware(['auth']);
Route::post('admin/permissions/getpermissions', [PermissionController::class, 'getPermissions'])->name('admin.permissions.getpermissions')->middleware(['auth']);
Route::post('admin/permissions/store', [PermissionController::class, 'store'])->name('admin.permissions.store')->middleware(['auth']);

// Routes for Menu Module
Route::get('admin/menus/{menu}/submenus', [MenuController::class, 'submenus'])->name('admin.menus.submenus')->middleware(['auth']);
Route::patch('admin/menus/{menu}/submenusupdate', [MenuController::class, 'submenusupdate'])->name('admin.menus.submenusupdate')->middleware(['auth']);
Route::get('admin/menus/list', [MenuController::class, 'list'])->name('admin.menus.list')->middleware(['auth']);
Route::resource('/admin/menus', MenuController::class, [
    'as' => 'admin'
])->middleware(['auth']);


// Routes for Document Module
Route::get('admin/documents/list', [DocumentController::class, 'list'])->name('admin.documents.list')->middleware(['auth']);
Route::post('admin/documents/deleteFile', [DocumentController::class, 'deleteFile'])->name('admin.documents.deleteFile')->middleware(['auth']);
Route::resource('/admin/documents', DocumentController::class, [
    'as' => 'admin'
])->middleware(['auth']);

// Routes for Page Module
Route::get('admin/pages/list', [PageController::class, 'list'])->name('admin.pages.list')->middleware(['auth']);
Route::post('admin/pages/deleteImage', [PageController::class, 'deleteImage'])->name('admin.pages.deleteImage')->middleware(['auth']);
Route::resource('/admin/pages', PageController::class, [
    'as' => 'admin'
])->middleware(['auth']);

// Route for uploading images for ckeditor
Route::post('admin/ckeditor/upload', [CkeditorImageUploader::class, 'upload'])->name('admin.ckeditor.upload')->middleware(['auth']);

require __DIR__.'/auth.php';
