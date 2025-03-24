<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\Api\UserAuthController;
use  App\Http\Controllers\Api\AnnonceController;

// Route::post('login',[UserAuthController::class,'login']);
// Route::post('register',[UserAuthController::class,'register']);
// Route::middleware('jwt.auth')->group(function () {
//     Route::post('/logout', [UserAuthController::class, 'logout']);
// });
Route::apiResource('/annonces',AnnonceController::class);