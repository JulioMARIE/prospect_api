<?php

use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PaysController;
use App\Http\Controllers\SocieteController;
use App\Http\Controllers\SuiviController;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommercialController;
use App\Http\Controllers\QuotaController;
use App\Http\Controllers\ProspectionController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\ResponsableController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UtilisateurController;

// Route de connexion (login)
Route::post('/commercialLogin', [AuthController::class, 'commercialLogin']);
Route::post('/responsableLogin', [AuthController::class, 'responsableLogin']);

// Route pour le mot de passe oublié (sans authentification)
Route::post('resetPassword', [PasswordResetController::class, 'resetPassword']);

// Routes nécessitant une authentification
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/logout', [AuthController::class, 'logout']);
    
    Route::post('/changePassword/{utilisateur}', [AuthController::class, 'changePassword']);
    // // Route d'authentification (pour vérifier le token)
    // Route::get('/user', [AuthController::class, 'user']);

    Route::apiResource('/responsables', ResponsableController::class);
    // Route::apiResource('/commercials', CommercialController::class);
    Route::apiResource('societes', SocieteController::class);
    Route::apiResource('pays', PaysController::class);
    Route::get('/communesPays/{pays}', [PaysController::class, 'communesPays']);

    // Routes pour le Responsable
    Route::prefix('responsable')->group(function () {
        // Route::apiResource('utilisateurs', UtilisateurController::class)->except('update');
        // Route::post('commercials/{u}', [CommercialController::class, "update"]);
        Route::post('addPermission/{u}', [UtilisateurController::class, "addPermission"]);
        Route::apiResource('commercials', CommercialController::class);
        Route::apiResource('permissions', PermissionController::class);
        Route::apiResource('quotas', QuotaController::class)->except('store');
        Route::post('addquota', [QuotaController::class, "store"]);
        Route::apiResource('prospections', ProspectionController::class);
        Route::apiResource('suivis', SuiviController::class);
        Route::apiResource('utilisateurs', UtilisateurController::class);
        // Route::get('rapports', [RapportController::class, 'index']);
    });

    // Routes pour le Commercial
    Route::prefix('commercial')->group(function () {
        Route::apiResource('prospections', ProspectionController::class)->except('index');
        Route::get('myprospect/{commercial}', [CommercialController::class, 'myprospect']);
        Route::get('mesquotas/{commercial}', [CommercialController::class, 'mesquotas']);
        Route::get('statutQuotaActuel/{commercial}', [CommercialController::class, 'statutQuotaActuel']);
        Route::post('prospections/{prospection}/suivi', [ProspectionController::class, 'ajouterSuivi']);
    });
});


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
