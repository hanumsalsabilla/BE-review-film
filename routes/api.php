<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MoviesController;
use App\Http\Controllers\API\CastsController;
use App\Http\Controllers\API\GenresController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\CastMovieController;

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
Route::prefix('v1')->group(function () {
    Route::apiResource('movie', MoviesController::class);
    Route::apiResource('genre', GenresController::class);
    Route::apiResource('cast', CastsController::class);
    Route::apiResource('cast-movie', CastMovieController::class);
    Route::get('movies/{movieId}/reviews', [ReviewController::class, 'index']);


    Route::middleware(['auth:api'])->group(function () {
        Route::get('me', [AuthController::class, 'getUser']);
        Route::post('update-user', [AuthController::class, 'updateUser'])->middleware('isVerificationAccount');
    });
    
    Route::prefix('auth')->group(function(){
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login'])->middleware('api','cors');
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
        Route::post('generate-otp-code', [AuthController::class, 'generateOtpCode']);
        Route::post('verifikasi-akun', [AuthController::class, 'verifikasi']);
    });
    
    Route::middleware(['auth:api', 'isAdmin'])->group(function () {
        Route::apiResource('role', RoleController::class);
    });

    Route::middleware(['auth:api', 'isVerificationAccount'])->group(function () {
        Route::post('profile', [ProfileController::class, 'store']);
        Route::get('get-profile', [ProfileController::class, 'index']);
        Route::post('review', [ReviewController::class, 'store']);
    });
});
