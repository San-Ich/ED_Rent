<?php

use App\Http\Controllers\RentalController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Halaman checkout (form input tanggal)
    Route::get('/checkout/{motor:slug}', [RentalController::class, 'checkout'])->name('checkout');

    // Proses simpan rental
    Route::post('/rental/store', [RentalController::class, 'store'])->name('rental.store');

    // Halaman daftar pesanan saya
    Route::get('/my-orders', function () {
        $rentals = \App\Models\Rental::where('user_id', Auth::id())->latest()->get();
        return view('customer.orders.index', compact('rentals'));
    })->name('customer.orders');
});