<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalController;
use App\Models\Rental;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', [LandingPageController::class, 'index'])->name('home');

Route::get('/catalog', function () {
    return view('catalog');
})->name('catalog');

Route::get('/booking', function () {
    return view('booking');
})->name('booking');

Route::middleware(['auth'])->group(function () {
    
    Route::get('/checkout/{motor:slug}', [RentalController::class, 'checkout'])->name('checkout');
    
    Route::post('/rental/store', [RentalController::class, 'store'])->name('rental.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/my-orders', function () {
        $rentals = Rental::where('user_id', Auth::id())->latest()->get();
        return view('customer.orders.index', compact('rentals'));
    })->name('customer.orders');
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