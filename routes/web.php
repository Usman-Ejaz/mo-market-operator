<?php

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




//Route::resource('customers', 'CustomersController')->middleware(['auth'])->name('index', 'customers');

require __DIR__.'/auth.php';
