<?php

use App\Http\Controllers\Auth\ApiAuthenticationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileReviewController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [ApiAuthenticationController::class, 'store'])->name('api.login');

Route::middleware('auth:api')->group(function () {
    Route::post('refresh', [ApiAuthenticationController::class, 'refresh'])
        ->name('api.refresh');
    Route::post('logout', [ApiAuthenticationController::class, 'destroy'])
        ->name('api.logout');
    Route::get('genres', [GenreController::class, 'index'])->name('genres.index');
    Route::get('reviews', [ProfileReviewController::class, 'index'])->name('reviews.index');
    Route::apiResource('movies', MovieController::class);
    Route::apiResource('movies.reviews', ReviewController::class)
        ->shallow();
    Route::apiResource('follows', FollowController::class)
        ->only('index', 'store', 'destroy');
    Route::apiResource('favorites', FavoriteController::class)
        ->only('index', 'store', 'destroy');
});
