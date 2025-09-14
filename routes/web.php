<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\TransactionController;

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

Route::get('/', [ProductController::class, 'index'])->name('home');

// ✅ Produk (CRUD untuk seller)
Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class)->except(['show']);
    // show detail produk (untuk buyer)
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
});

// ✅ Keranjang (Cart)
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'store'])->name('cart.store');
});

// ✅ Item dalam Keranjang (CartItems)
Route::middleware(['auth'])->group(function () {
    Route::delete('/cart-items/{cartItem}', [CartItemController::class, 'destroy'])->name('cart-items.destroy');
    Route::patch('/cart-items/{cartItem}/decrease', [CartItemController::class, 'decrease'])->name('cart-items.decrease');
});

// ✅ Transaksi (Checkout & Histori)
Route::middleware(['auth'])->group(function () {
    Route::post('/checkout', [TransactionController::class, 'checkout'])->name('checkout.store');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'seller'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
