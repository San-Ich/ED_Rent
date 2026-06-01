<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\RentalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/rentals', function (Request $request) {
        return $request->user()->rentals()->with('motor')->get();
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/midtrans-webhook', [RentalController::class, 'midtransWebhook']);
Route::post('/login', [AuthController::class, 'login']);
