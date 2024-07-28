<?php
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EtablissementController;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::apiResource('etablissement', EtablissementController::class);


Route::middleware('auth:api')->group(function () {
    Route::get('profil', [AuthController::class, 'profil']);
    Route::post('logout', [AuthController::class, 'logout']);
});
