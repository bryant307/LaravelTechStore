<?php

use App\Http\Controllers\Admin\CoverController;
use App\Http\Controllers\Admin\OptionController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FamilyController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubcategoryController;

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');

Route::get('/options', [OptionController::class, 'index'] )->name('options.index');

Route::resource('families', FamilyController::class);

Route::resource('categories', CategoryController::class);

Route::resource('subcategories', SubcategoryController::class);

Route::resource('products', ProductController::class);

Route::resource('covers', CoverController::class);

Route::post('covers/update-order', [CoverController::class, 'updateOrder'])->name('covers.update-order');

// Routes for order management
Route::resource('orders', OrderController::class);