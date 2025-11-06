<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return redirect()->route('products.index');
})->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::middleware(['auth', 'verified'])->group(function () {
    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])
        ->name('cart.store')
        ->middleware('throttle:20,1'); // 20 requisições por minuto
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])
        ->name('cart.update')
        ->middleware('throttle:20,1');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])
        ->name('cart.destroy')
        ->middleware('throttle:20,1');

    // Order routes
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/orders', [OrderController::class, 'store'])
        ->name('orders.store')
        ->middleware('throttle:5,1'); // 5 pedidos por minuto (ação crítica)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

require __DIR__.'/settings.php';
