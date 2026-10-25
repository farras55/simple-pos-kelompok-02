<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Route untuk Guest (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

// Route yang bisa diakses SEMUA user yang sudah login (Admin, Manager, & Kasir)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Kasir POS
    Route::get('/pos', [TransactionController::class, 'create'])->name('pos.create');
    Route::post('/pos', [TransactionController::class, 'store'])->name('transactions.store');

    // Logout
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

// Route khusus ADMIN dan MANAGER
Route::middleware(['auth', 'role:admin,manager'])->group(function () {
    // Riwayat transaksi
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
});

// Route yang HANYA bisa diakses oleh ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Kategori
    Route::resource('categories', CategoryController::class)->except('show');

    // Produk
    Route::resource('products', ProductController::class)->except(['show', 'destroy']);
});