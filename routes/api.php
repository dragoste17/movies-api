<?php

use App\Http\Controllers\Client\ClientMovieController;
use App\Http\Controllers\Client\FavoriteController;
use App\Http\Controllers\MovieController;
use App\Http\Middleware\VerifyApiKey;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware([VerifyApiKey::class])->prefix('internal/movies')->group(function () {
    Route::get('/', [MovieController::class, 'index']);
    Route::get('/{movie}', [MovieController::class, 'show']);
});

// Route::middleware('auth:api')->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorite/{movie}', [FavoriteController::class, 'store']);
    Route::get('/movies', [ClientMovieController::class, 'index']);
    Route::get('/movie/(movieId)', [ClientMovieController::class, 'show']);
// });
