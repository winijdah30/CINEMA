<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AnimeController;
use Illuminate\Support\Facades\Route;


Route::resource('movies', MovieController::class);
Route::get('/', [MovieController::class, 'index'])->name('home');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Tarifs
Route::get('/tarif', function () {
    return view('tarif');  // ou autre action
})->name('tarif');

//Client 
Route::controller(CartController::class)->group(function () {
    Route::get('/cart', [CartController::class, 'movies_client'])->name('clients.cart');
    Route::delete('cart/{movie}', 'supprimer_panier')->name('carts.destroy');
    Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');
    Route::get('/clients', [CartController::class, 'stats'])->name('clients.stats');
})->middleware(['can:order']);

//  Réservation 
Route::middleware(['auth'])->group(function () {
    // Panier
    //Route::resource('clients', ClientController::class)->only(['index', 'destroy']);
    
    // Commandes
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

//animes 
Route::resource('animes', AnimeController::class);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
