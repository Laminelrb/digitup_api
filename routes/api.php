<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PropertyController;

Route::prefix('v1')->group(function () {

    // Routes publiques 
    // Liste et détails des propriétés

    Route::get('/properties', [PropertyController::class, 'index']);
    Route::get('/properties/{id}', [PropertyController::class, 'show']);


    // Connexion
    Route::post('/login', [AuthController::class, 'login']);




    // Routes protégées par Sanctum (token)

    Route::middleware('auth:sanctum')->group(function () {

        // CRUD propriétés
        Route::post('/properties', [PropertyController::class, 'store']); //Liste propriétés
        Route::put('/properties/{id}', [PropertyController::class, 'update']); //modifier propriété
        Route::delete('/properties/{id}', [PropertyController::class, 'destroy']); //suprimer propriété (soft delete)

        // Gestion des propriétés supprimées
        Route::get('/properties/trashed/list', [PropertyController::class, 'trashed']); // Liste propriétés suprimées (soft delete)
        Route::post('/properties/{id}/restore', [PropertyController::class, 'restore']); // Restaurer
        Route::delete('/properties/{id}/force', [PropertyController::class, 'forceDestroy']); // Supprimer définitivement

        // CRUD utilisateurs / agents (admin uniquement via Policy)
        Route::post('/users', [UserController::class, 'store']);       // Créer un agent
        Route::put('/users/{id}', [UserController::class, 'update']); // Modifier un agent
        Route::delete('/users/{id}', [UserController::class, 'destroy']); // Supprimer un agent
        Route::get('/users', [UserController::class, 'index']);       // Liste des agents
        Route::get('/users/{id}', [UserController::class, 'show']);   // Détail d’un agent

        // Déconnexion
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
