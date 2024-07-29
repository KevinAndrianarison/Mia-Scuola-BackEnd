<?php

use App\Http\Controllers\Api\AuController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EtablissementController;
use App\Http\Controllers\Api\NiveauController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::apiResource('etablissement', EtablissementController::class);

Route::apiResource('au', AuController::class);

Route::apiResource('niveau', NiveauController::class);


Route::middleware('auth:api')->group(function () {
    Route::get('profil', [AuthController::class, 'profil']);
    Route::post('logout', [AuthController::class, 'logout']);
});
