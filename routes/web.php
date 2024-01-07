<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/products', [\App\Http\Controllers\ProductsController::class, 'index'])->name('products.index');
Route::get('/products/json', [\App\Http\Controllers\ProductsController::class, 'indexJson'])->name('products.indexJson');
Route::post('/products/convert', [\App\Http\Controllers\ProductsController::class, 'processAndConvert'])->name('products.convert');
