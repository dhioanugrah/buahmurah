<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ManajemenController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'manajemen' ? redirect('/manajemen') : redirect('/kasir');
    }

    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    // Kasir routes
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.dashboard');
    Route::post('/kasir/sale', [KasirController::class, 'storeSale'])->name('kasir.sale.store');
    Route::post('/kasir/expense', [KasirController::class, 'storeExpense'])->name('kasir.expense.store');

    // Manajemen routes
    Route::get('/manajemen', [ManajemenController::class, 'index'])->name('manajemen.dashboard');
    Route::post('/manajemen/cashflow', [ManajemenController::class, 'storeCashflow'])->name('manajemen.cashflow.store');
    Route::post('/manajemen/kulakan', [ManajemenController::class, 'storeKulakan'])->name('manajemen.kulakan.store');
    Route::post('/manajemen/salary', [ManajemenController::class, 'storeSalary'])->name('manajemen.salary.store');
    Route::post('/manajemen/opname', [ManajemenController::class, 'storeStockOpname'])->name('manajemen.opname.store');
    Route::post('/manajemen/fruit', [ManajemenController::class, 'storeFruit'])->name('manajemen.fruit.store');
    Route::put('/manajemen/fruit/{fruit}', [ManajemenController::class, 'updateFruit'])->name('manajemen.fruit.update');
});
