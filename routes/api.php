
<?php

use App\Http\Controllers\Api\AuController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DirecteurController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EtablissementController;
use App\Http\Controllers\Api\MentionController;
use App\Http\Controllers\Api\NiveauController;
use App\Http\Controllers\Api\AgentscolariteController;
use App\Http\Controllers\Api\EnseignantController;

Route::post('user/register', [AuthController::class, 'register']);
Route::post('user/login', [AuthController::class, 'login']);
Route::put('user/setup/{id}', [AuthController::class, 'update']);
Route::delete('user/delete/{id}', [AuthController::class, 'destroy']);
Route::get('user/profilAll', [AuthController::class, 'index']);
Route::get('user/profilOne/{id}', [AuthController::class, 'show']);


Route::apiResource('etablissement', EtablissementController::class);
Route::get('etablissement/image/{filename}', [EtablissementController::class, 'downloadImage'])->name('file.download');


Route::apiResource('directeur', DirecteurController::class);


Route::apiResource('agentscolarite', AgentscolariteController::class);


Route::apiResource('enseignant', EnseignantController::class);


Route::apiResource('au', AuController::class);


Route::apiResource('niveau', NiveauController::class);
Route::get('/niveau/getById/{au_id}', [NiveauController::class, 'getByAuId']);


Route::apiResource('mention', MentionController::class);
Route::get('/mention/getById/{niveau_id}', [MentionController::class, 'getByNiveauId']);



Route::middleware('auth:api')->group(function () {
    Route::get('user/profil', [AuthController::class, 'profil']);
    Route::post('user/logout', [AuthController::class, 'logout']);
});
