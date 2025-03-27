<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\Api\UserAuthController;
use  App\Http\Controllers\Api\AnnonceController;
use  App\Http\Controllers\Api\CandidatureController;
use  App\Http\Controllers\Api\UserController;
use  App\Http\Controllers\Api\AdminController;
use  App\Http\Controllers\Api\RecruteurController;

// Route::post('login',[UserAuthController::class,'login']);
// Route::post('register',[UserAuthController::class,'register']);
// Route::middleware('jwt.auth')->group(function () {
//     Route::post('/logout', [UserAuthController::class, 'logout']);
// });
Route::post('auth/login',[UserAuthController::class,'login']);
Route::post('auth/register',[UserAuthController::class,'register']);
Route::post('auth/logout',[UserAuthController::class,'logout']);
Route::post('auth/refresh',[UserAuthController::class,'refresh']);
Route::post('auth/password/forgot',[UserAuthController::class,'forgot']);
Route::post('auth/password/reset',[UserAuthController::class,'reset']);

Route::get('/annonces/{annonce}',[AnnonceController::class,'show']);
Route::get('/annonces',[AnnonceController::class,'index']);
Route::post('/annonces',[AnnonceController::class,'store']);
Route::put('/annonces/{annonce}',[AnnonceController::class,'update']);
Route::delete('/annonces/{annonce}',[AnnonceController::class,'destroy']);

Route::get('/candidatures',[CandidatureController::class,'index']);
Route::get('/candidatures/{candidature}',[CandidatureController::class,'show']);
Route::post('/candidatures',[CandidatureController::class,'store']);
Route::put('/candidatures/{candidature}',[CandidatureController::class,'update']);
Route::delete('/candidatures/{candidature}',[CandidatureController::class,'destroy']);

Route::get('/candidatures/candidat/{id}',[CandidatureController::class,'getCandidatureByCandidat']);
Route::get('/candidatures/{id}/{status}',[CandidatureController::class,'getCandidatureByCandidatAndStatus']);
Route::get('/candidatures/miennes/status',[CandidatureController::class,'getCandidatureByCandidat']);
Route::get('/notification/candidatures/{id}',[CandidatureController::class,'getCandidatureByStatus']);

Route::get('/utilisateurs/profil',[UserController::class,'index']);
Route::get('/utilisateurs/profil/{user}',[UserController::class,'show']);
Route::delete('/utilisateurs/{user}',[UserController::class,'destroy']);
Route::get('/stats/recruteur',[RecruteurController::class,'recruteur']);
Route::get('/stats/globales',[AdminController::class,'globales']);