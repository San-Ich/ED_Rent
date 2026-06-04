<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', [LandingPageController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // AREA PROFIL CUSTOMER (Sudah disatukan dan tidak tabrakan lagi)
    Route::get('/customer-profile', [ProfileController::class, 'index'])->name('customer.profile');
    Route::put('/customer-profile/update', [ProfileController::class, 'update'])->name('customer.profile.update');

    // RUTE CATALOG & CHECKOUT
    // Route::get('/checkout/{motor:slug}', [RentalController::class, 'checkout'])->name('checkout');
    // Route::post('/rental/store', [RentalController::class, 'store'])->name('rental.store');
    Route::post('/booking/store', [RentalController::class, 'store'])->name('booking.store');
    Route::get('/catalog', [MotorController::class, 'index'])->name('catalog');
    Route::get('catalog/{id}', [MotorController::class, 'show'])->name('catalog.show');

    // RUTE RENTAL & TRANSAKSI CUSTOMER
    Route::get('/my-orders', [RentalController::class, 'index'])->name('customer.orders');
    Route::get('/rental/{id}/download-struk', [RentalController::class, 'downloadStruk'])->name('customer.rental.download-struk');
    Route::post('/rental/{id}/Konfirmasi-Motor', [RentalController::class, 'KonfirmasiMotor'])->name('customer.rental.kembalikan');
    Route::get('/rental/{id}/Pembayaran-Denda', [RentalController::class, 'PembayaranDenda'])->name('customer.rental.denda');
    Route::get('/orders/{id}/payment', [RentalController::class, 'paymentPage'])->name('customer.orders.payment');

    // CUSTOMER FEATURE GATE
    Route::middleware(['can:access-customer-features'])->group(function () {
        Route::get('/orders', [RentalController::class, 'index'])->name('customer.orders.index');
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

require __DIR__.'/auth.php';
