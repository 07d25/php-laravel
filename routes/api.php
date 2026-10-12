<?php

use App\Http\Controllers\GenreController;
use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthController::class)->name('health');

// - GET /api/v1/genres
// - POST /api/v1/genres
// - GET /api/v1/genres/{genre}
// - PUT/PATCH /api/v1/genres/{genre}
// - DELETE /api/v1/genres/{genre}
Route::apiResource('genres', GenreController::class);
