<?php

use App\Http\Controllers\CategoryController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/{parentId}/child', [CategoryController::class, 'getChild']);
Route::post('/subcategories', [CategoryController::class, 'storeSubcategory'])->name('subcategories.store');
// Route::get('/subcategories/{parentId}', [CategoryController::class, 'getSubcategories'])->name('categories.getSubcategories');
