<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('families/{family}', [FamilyController::class, 'show'])->name('families.show');
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('subcategories/{subcategory}', [SubcategoryController::class, 'show'])->name('subcategories.show');
Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

// Ruta de búsqueda
Route::get('search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');

// Rutas del carrito
Route::get('cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::get('cart/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');

// Ruta de prueba para la familia con productos
Route::get('/test-family', function () {
    $family = \App\Models\Family::find(18);
    return redirect()->route('families.show', $family);
});

// Rutas para el proceso de checkout y envío (requieren autenticación)
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('shipping', [ShippingController::class, 'index'])->name('shipping.index');
    Route::get('checkout/payment', [\App\Http\Controllers\CheckoutController::class, 'showPaymentOptions'])->name('checkout.payment');
    Route::post('checkout/process-payment', [\App\Http\Controllers\CheckoutController::class, 'processPayment'])->name('checkout.process-payment');
});

// Las reseñas se manejan dentro del componente Livewire ProductReviews

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Ruta para seguimiento de pedidos
    Route::get('/my-orders', function () {
        return view('orders.index');
    })->name('orders.index');
    
    Route::get('/my-orders/{order_id}', function ($order_id) {
        return view('orders.show', ['order_id' => $order_id]);
    })->name('orders.show');
});
