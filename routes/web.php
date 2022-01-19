<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\JobController;
use App\Models\NewsCategory;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;

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
    //App\Models\News::factory()->count(5)->create();
    //NewsCategory::factory()->create();
});


Route::get('/admin/dashboard', function () {
    return view('admin/dashboard/index');
})->middleware(['auth'])->name('admin.dashboard');


// Route::prefix('admin')->name('admin.')->group(function () {
//     Route::resource('news', NewsController::class)->middleware(['auth']);
// });

Route::get('admin/news/list', [NewsController::class, 'list'])->name('admin.news.list')->middleware(['auth']);
Route::post('admin/news/deleteImage', [NewsController::class, 'deleteImage'])->name('admin.news.deleteImage')->middleware(['auth']);

Route::resource('/admin/news', NewsController::class, [
    'as' => 'admin'
])->middleware(['auth']);

// Routes for Job Module
Route::get('admin/jobs/list', [JobController::class, 'list'])->name('admin.jobs.list')->middleware(['auth']);
Route::post('admin/job/deleteImage', [NewsController::class, 'deleteImage'])->name('admin.job.deleteImage')->middleware(['auth']);
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

//Route::resource('customers', 'CustomersController')->middleware(['auth'])->name('index', 'customers');

require __DIR__.'/auth.php';
