<?php

use App\Http\Controllers\PaysController;
use App\Http\Controllers\SocieteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommercialController;
use App\Http\Controllers\QuotaController;
use App\Http\Controllers\ProspectionController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\ResponsableController;
use App\Http\Controllers\PermissionController;

// Route de connexion (login)
Route::post('/commercialLogin', [AuthController::class, 'commercialLogin']);
Route::post('/responsableLogin', [AuthController::class, 'responsableLogin']);

// Route pour le mot de passe oublié (sans authentification)
Route::post('/forgotPassword', [AuthController::class, 'forgotPassword']);

// Routes nécessitant une authentification
Route::middleware('auth:sanctum')->group(function () {

    // // Route d'authentification (pour vérifier le token)
    // Route::get('/user', [AuthController::class, 'user']);

    Route::apiResource('/responsables', ResponsableController::class);
    Route::apiResource('societes', SocieteController::class);
    Route::apiResource('pays', PaysController::class);
    Route::get('/communesPays/{pays}', [PaysController::class, 'communesPays']);

    // Routes pour le Responsable
    Route::prefix('responsable')->group(function () {
        Route::apiResource('commercials', CommercialController::class);
        Route::apiResource('quotas', QuotaController::class);
        // Route::get('rapports', [RapportController::class, 'index']);
    });

    // Routes pour le Commercial
    Route::prefix('commercial')->group(function () {
        Route::apiResource('prospections', ProspectionController::class);
        Route::get('mes-prospections', [ProspectionController::class, 'mesProspections']);
        Route::post('prospections/{prospection}/suivi', [ProspectionController::class, 'ajouterSuivi']);
    });
});


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
