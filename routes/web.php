<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalController;
use App\Models\Rental;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', [LandingPageController::class, 'index'])->name('home');

Route::get('/catalog', [MotorController::class, 'index'])->name('catalog');
Route::get('catalog/{id}', [MotorController::class, 'show'])->name('catalog.show');

Route::post('/midtrans/webhook', [RentalController::class, 'midtransWebhook']);

Route::middleware(['auth'])->group(function () {

    Route::get('/checkout/{motor:slug}', [RentalController::class, 'checkout'])->name('checkout');

    Route::post('/rental/store', [RentalController::class, 'store'])->name('rental.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/my-orders', [RentalController::class, 'index'])->name('customer.orders');
    Route::post('/booking/store', [RentalController::class, 'store'])->name('booking.store');


    Route::get('/rental/{id}/download-struk', [RentalController::class, 'downloadStruk'])->name('customer.rental.download-struk');
    Route::post('/rental/{id}/Konfirmasi-Motor', [RentalController::class, 'KonfirmasiMotor'])->name('customer.rental.kembalikan');
    Route::get('/rental/{id}/Pembayaran-Denda', [RentalController::class, 'PembayaranDenda'])->name('customer.rental.denda');

    Route::get('/orders/{id}/payment', [RentalController::class, 'paymentPage'])->name('customer.orders.payment');
    Route::middleware(['can:access-customer-features'])->group(function () {
        Route::get('/orders', [RentalController::class, 'index'])->name('customer.orders');
        Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');
    });

    Route::get('/protected-media/{path}', function ($path) {
        $user = Auth::user();

        if (!Auth::check()) {
            abort(403);
        }
        if ($user->role !== 'admin' && !str_contains($path, $user->id)) {
            abort(403);
        }
        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return response()->file(Storage::disk('local')->path($path));
    })->where('path', '.*')->middleware('auth')->name('media.show');
});
