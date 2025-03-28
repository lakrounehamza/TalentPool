<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\Api\UserAuthController;
use  App\Http\Controllers\Api\AnnonceController;
use  App\Http\Controllers\Api\CandidatureController;
use  App\Http\Controllers\Api\UserController;
use  App\Http\Controllers\Api\AdminController;
use  App\Http\Controllers\Api\RecruteurController;

// Public routes - no authentication required
Route::post('auth/login',[UserAuthController::class,'login']);
Route::post('auth/register',[UserAuthController::class,'register']);
Route::post('auth/password/forgot',[UserAuthController::class,'forgot']);
Route::post('auth/password/reset',[UserAuthController::class,'reset']);

// Public routes for viewing annonces
Route::get('/annonces/{annonce}',[AnnonceController::class,'show']);
Route::get('/annonces',[AnnonceController::class,'index']);

// Protected routes - authentication required
Route::middleware('jwt.auth')->group(function () {
    // Auth routes
    Route::post('auth/logout',[UserAuthController::class,'logout']);
    Route::post('auth/refresh',[UserAuthController::class,'refresh']);
    
    // Annonce management routes
    Route::post('/annonces',[AnnonceController::class,'store']);
    Route::put('/annonces/{annonce}',[AnnonceController::class,'update']);
    Route::delete('/annonces/{annonce}',[AnnonceController::class,'destroy']);
    
    // Candidature routes
    Route::get('/candidatures',[CandidatureController::class,'index']);
    Route::get('/candidatures/{candidature}',[CandidatureController::class,'show']);
    Route::post('/candidatures',[CandidatureController::class,'store']);
    Route::put('/candidatures/{candidature}',[CandidatureController::class,'update']);
    Route::delete('/candidatures/{candidature}',[CandidatureController::class,'destroy']);
    
    Route::get('/candidatures/candidat/{id}',[CandidatureController::class,'getCandidatureByCandidat']);
    Route::get('/candidatures/{id}/{status}',[CandidatureController::class,'getCandidatureByCandidatAndStatus']);
    Route::get('/candidatures/miennes/status',[CandidatureController::class,'getCandidatureByStatus']);
    Route::get('/notification/candidatures/{id}',[CandidatureController::class,'notificationCandidature']);
    
    // User profile routes
    Route::get('/utilisateurs/profil',[UserController::class,'index']);
    Route::get('/utilisateurs/profil/{user}',[UserController::class,'show']);
    Route::delete('/utilisateurs/{user}',[UserController::class,'destroy']);
    
    // Stats routes
    Route::get('/stats/recruteur',[RecruteurController::class,'recruteur']);
    Route::get('/stats/globales',[AdminController::class,'globales']);
});