<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/services', [ServiceController::class, 'index']);
Route::post('/services', [ServiceController::class, 'store'])->middleware(['auth:sanctum', 'admin']);
Route::get('/services/{service}', [ServiceController::class, 'show']);
