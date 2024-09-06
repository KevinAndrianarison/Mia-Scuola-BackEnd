
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
use App\Http\Controllers\Api\EtudiantController;
use App\Http\Controllers\Api\ParcourController;
use App\Http\Controllers\Api\SemestreController;
use Illuminate\Support\Facades\Mail;

Route::post('user/register', [AuthController::class, 'register']);
Route::post('user/login', [AuthController::class, 'login']);
Route::put('user/setup/{id}', [AuthController::class, 'update']);
Route::delete('user/delete/{id}', [AuthController::class, 'destroy']);
Route::get('user/profilAll', [AuthController::class, 'index']);
Route::get('user/profilOne/{id}', [AuthController::class, 'show']);
Route::post('user/createEtudiant', [AuthController::class, 'store']);



Route::apiResource('etablissement', EtablissementController::class);
Route::get('etablissement/image/{filename}', [EtablissementController::class, 'downloadImage'])->name('file.download');


Route::apiResource('directeur', DirecteurController::class);


Route::apiResource('agentscolarite', AgentscolariteController::class);


Route::apiResource('enseignant', EnseignantController::class);


Route::apiResource('etudiant', EtudiantController::class);



Route::apiResource('au', AuController::class);


Route::apiResource('niveau', NiveauController::class);
Route::get('/niveau/getById/{au_id}', [NiveauController::class, 'getByAuId']);


Route::apiResource('mention', MentionController::class);
Route::get('/mention/getById/{niveau_id}', [MentionController::class, 'getByNiveauId']);
Route::get('/mention/getByEnsId/{enseignant_id}', [MentionController::class, 'getByEnseignantId']);



Route::apiResource('parcours', ParcourController::class);
Route::get('/parcours/getById/{niveau_id}', [ParcourController::class, 'getByNiveauId']);
Route::get('/parcours/getByEnsId/{enseignant_id}', [ParcourController::class, 'getByEnseignantId']);


Route::apiResource('semestre', SemestreController::class);
Route::get('/semestre/getById/{parcour_id}', [SemestreController::class, 'getByParcoursId']);
Route::get('/semestres/{semestreId}/etudiants', [SemestreController::class, 'showEtudiants']);
Route::post('/semestres/addEtudiant', [SemestreController::class, 'addEtudiant']);
Route::delete('/semestres/{semestreId}/removeEtudiant/{etudiantId}', [SemestreController::class, 'removeEtudiant']);


Route::middleware('auth:api')->group(function () {
    Route::get('user/profil', [AuthController::class, 'profil']);
    Route::post('user/logout', [AuthController::class, 'logout']);
});


