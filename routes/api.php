<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BienController;
use App\Http\Controllers\Api\ConfigurationController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MembreEquipeController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\TemoignageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Routes publiques
Route::get('/entreprise', [ConfigurationController::class, 'entreprise']);

// Biens (public)
Route::get('/biens', [BienController::class, 'index']);
Route::get('/biens/filters', [BienController::class, 'filters']);
Route::get('/biens/stats', [BienController::class, 'stats']);
Route::get('/biens/{id}', [BienController::class, 'show']);
Route::get('/biens/{bien}/similaires', [BienController::class, 'similaires']);

// Services (public)
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);

// Témoignages (public)
Route::get('/temoignages', [TemoignageController::class, 'index']);

// Équipe (public)
Route::get('/equipe', [MembreEquipeController::class, 'index']);

// Contact (public)
Route::post('/contacts', [ContactController::class, 'store']);

// Authentification
Route::post('/login', [AuthController::class, 'login']);

// Routes protégées (admin)
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/activity', [DashboardController::class, 'recentActivity']);
    Route::get('/dashboard/charts', [DashboardController::class, 'chartsData']);

    // Biens (admin)
    Route::post('/biens', [BienController::class, 'store']);
    Route::put('/biens/{bien}', [BienController::class, 'update']);
    Route::delete('/biens/{bien}', [BienController::class, 'destroy']);
    Route::patch('/biens/{bien}/vedette', [BienController::class, 'toggleVedette']);
    Route::patch('/biens/{bien}/statut', [BienController::class, 'updateStatut']);
    Route::post('/biens/{bien}/images/reorder', [BienController::class, 'reorderImages']);
    Route::delete('/biens/{bien}/images/{imageId}', [BienController::class, 'deleteImage']);

    // Services (admin)
    Route::post('/services', [ServiceController::class, 'store']);
    Route::put('/services/{service}', [ServiceController::class, 'update']);
    Route::delete('/services/{service}', [ServiceController::class, 'destroy']);
    Route::patch('/services/{service}/active', [ServiceController::class, 'toggleActive']);
    Route::post('/services/reorder', [ServiceController::class, 'reorder']);

    // Témoignages (admin)
    Route::get('/temoignages/{temoignage}', [TemoignageController::class, 'show']);
    Route::post('/temoignages', [TemoignageController::class, 'store']);
    Route::put('/temoignages/{temoignage}', [TemoignageController::class, 'update']);
    Route::delete('/temoignages/{temoignage}', [TemoignageController::class, 'destroy']);
    Route::patch('/temoignages/{temoignage}/active', [TemoignageController::class, 'toggleActive']);
    Route::post('/temoignages/reorder', [TemoignageController::class, 'reorder']);

    // Équipe (admin)
    Route::get('/equipe/{membre}', [MembreEquipeController::class, 'show']);
    Route::post('/equipe', [MembreEquipeController::class, 'store']);
    Route::put('/equipe/{membre}', [MembreEquipeController::class, 'update']);
    Route::delete('/equipe/{membre}', [MembreEquipeController::class, 'destroy']);
    Route::patch('/equipe/{membre}/active', [MembreEquipeController::class, 'toggleActive']);
    Route::post('/equipe/reorder', [MembreEquipeController::class, 'reorder']);

    // Contacts (admin)
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::get('/contacts/stats', [ContactController::class, 'stats']);
    Route::get('/contacts/{contact}', [ContactController::class, 'show']);
    Route::put('/contacts/{contact}', [ContactController::class, 'update']);
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy']);
    Route::patch('/contacts/{contact}/read', [ContactController::class, 'markAsRead']);
    Route::patch('/contacts/{contact}/unread', [ContactController::class, 'markAsUnread']);
    Route::post('/contacts/bulk-delete', [ContactController::class, 'bulkDelete']);
    Route::post('/contacts/bulk-read', [ContactController::class, 'bulkMarkAsRead']);

    // Configurations (admin)
    Route::get('/configurations', [ConfigurationController::class, 'index']);
    Route::get('/configurations/{key}', [ConfigurationController::class, 'show']);
    Route::post('/configurations', [ConfigurationController::class, 'store']);
    Route::put('/configurations/{key}', [ConfigurationController::class, 'update']);
    Route::delete('/configurations/{key}', [ConfigurationController::class, 'destroy']);
    Route::post('/configurations/bulk', [ConfigurationController::class, 'bulkUpdate']);
    Route::post('/configurations/clear-cache', [ConfigurationController::class, 'clearCache']);
    
    // Entreprise (admin)
    Route::put('/entreprise', [ConfigurationController::class, 'updateEntreprise']);
});
