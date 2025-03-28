
<?php

use App\Http\Controllers\Api\AcceuilimageController;
use App\Http\Controllers\Api\AuController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DirecteurController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EtablissementController;
use App\Http\Controllers\Api\MentionController;
use App\Http\Controllers\Api\NiveauController;
use App\Http\Controllers\Api\AgentscolariteController;
use App\Http\Controllers\Api\AnnonceController;
use App\Http\Controllers\Api\CategoriController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ComController;
use App\Http\Controllers\Api\CourController;
use App\Http\Controllers\Api\EcController;
use App\Http\Controllers\Api\EdtController;
use App\Http\Controllers\Api\EnseignantController;
use App\Http\Controllers\Api\EtudiantController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\GroupedtController;
use App\Http\Controllers\Api\HeureController;
use App\Http\Controllers\Api\JourController;
use App\Http\Controllers\Api\MessagegroupeController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\ParcourController;
use App\Http\Controllers\Api\SalleController;
use App\Http\Controllers\Api\SemestreController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UeController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\CategoriecongeController;
use App\Http\Controllers\Api\CommandeController;
use App\Http\Controllers\Api\CongepermissionController;
use App\Http\Controllers\Api\CursuController;
use App\Http\Controllers\Api\MentionacceuilController;

Route::post('user/register', [AuthController::class, 'register']);
Route::post('user/directeur', [AuthController::class, 'createDirecteur']);
Route::post('user/admin', [AuthController::class, 'createAdmin']);
Route::post('user/login', [AuthController::class, 'login']);
Route::put('user/setup/{id}', [AuthController::class, 'update']);
Route::put('user/setuser/{id}', [AuthController::class, 'setUser']);
Route::delete('user/delete/{id}', [AuthController::class, 'destroy']);
Route::get('user/profilAll', [AuthController::class, 'index']);
Route::get('user/profilOne/{id}', [AuthController::class, 'show']);
Route::post('user/createEtudiant', [AuthController::class, 'store']);
Route::put('user/changemdp/{id}', [AuthController::class, 'changeMdp']);


Route::apiResource('etablissement', EtablissementController::class);


Route::apiResource('cursus', CursuController::class);


Route::apiResource('admin', AdminController::class);
Route::get('/admin/getById/{user_id}', [AdminController::class, 'getByUserId']);
Route::get('/admins/getFirst', [AdminController::class, 'getFirst']);

Route::apiResource('directeur', DirecteurController::class);
Route::get('/directeur/getById/{user_id}', [DirecteurController::class, 'getByUserId']);
Route::get('/directeurs/getFirst', [DirecteurController::class, 'getFirst']);


Route::apiResource('agentscolarite', AgentscolariteController::class);
Route::get('/agentscolarite/getById/{user_id}', [AgentscolariteController::class, 'getByUserId']);


Route::apiResource('enseignant', EnseignantController::class);
Route::get('/enseignant/getById/{user_id}', [EnseignantController::class, 'getByUserId']);
Route::get('/enseignants/{id}', [EnseignantController::class, 'getOneEnseignant']);


Route::apiResource('etudiant', EtudiantController::class);
Route::get('/etudiant/getById/{user_id}', [EtudiantController::class, 'getByUserId']);
Route::get('/etudiant/getByAuId/{au_id}', [EtudiantController::class, 'getByAuId']);
Route::put('/updateByEmailAndPassword', [EtudiantController::class, 'updateByEmailAndPassword']);
Route::get('/getEtudiantByCursusId/{cursu_id}', [EtudiantController::class, 'getEtudiantByCursusId']);


Route::apiResource('au', AuController::class);


Route::apiResource('salle', SalleController::class);


Route::apiResource('edt', EdtController::class);


Route::apiResource('transaction', TransactionController::class);
Route::get('/transactionGetByAU/{au_id}', [TransactionController::class, 'getByIdAU']);


Route::apiResource('commande', CommandeController::class);
Route::get('/commandeGetByEtd/{etudiant_id}', [CommandeController::class, 'getByIdEtudiant']);



Route::apiResource('grpedt', GroupedtController::class);
Route::get('/grpedtGetByAU/{au_id}', [GroupedtController::class, 'getByIdAU']);
Route::get('/grpedtGetByNiveau/{niveau_id}', [GroupedtController::class, 'getByIdNiveau']);



Route::apiResource('niveau', NiveauController::class);
Route::get('/niveau/getById/{au_id}', [NiveauController::class, 'getByAuId']);

Route::apiResource('acceuil', AcceuilimageController::class);


Route::apiResource('mention', MentionController::class);
Route::apiResource('acceuilmention', MentionacceuilController::class);
Route::get('/mention/getById/{niveau_id}', [MentionController::class, 'getByNiveauId']);
Route::get('/mention/getByEnsId/{enseignant_id}/{au_id}', [MentionController::class, 'getByEnseignantId']);
Route::put('/mentions/{id}/clearEnseignant', [MentionController::class, 'clearEnseignantId']);


Route::apiResource('parcours', ParcourController::class);
Route::get('/parcours/getById/{niveau_id}', [ParcourController::class, 'getByNiveauId']);
Route::get('/parcours/getByMentionId/{mention_id}', [ParcourController::class, 'getByMentionId']);
Route::get('/parcours/getByEnsId/{enseignant_id}', [ParcourController::class, 'getByEnseignantId']);
Route::put('/parcours/{id}/clearEnseignant', [ParcourController::class, 'clearEnseignantId']);
Route::get('parcours/enseignant/{enseignant_id}/au/{au_id}', [ParcourController::class, 'getParcoursByIdEnsAndAU']);



Route::apiResource('semestre', SemestreController::class);
Route::get('/semestre/getById/{parcour_id}', [SemestreController::class, 'getByParcoursId']);
Route::get('/semestre/getSemestreByEtudiantId/{etudiantId}', [SemestreController::class, 'getSemestreByEtudiantId']);
Route::get('/semestres/{semestreId}/etudiants', [SemestreController::class, 'showEtudiants']);
Route::post('/semestres/addEtudiant', [SemestreController::class, 'addEtudiant']);
Route::delete('/semestres/{semestreId}/removeEtudiant/{etudiantId}', [SemestreController::class, 'removeEtudiant']);


Route::apiResource('ue', UeController::class);
Route::get('/ue/getById/{semestre_id}', [UeController::class, 'getBySemestreId']);


Route::apiResource('heure', HeureController::class);

Route::apiResource('jours', JourController::class);


Route::apiResource('ec', EcController::class);
Route::get('/ec/getById/{ue_id}', [EcController::class, 'getByUeId']);
Route::get('/ec/getBySemestre/{semestre_id}', [EcController::class, 'getBySemestre']);
Route::get('/ec/getByEnsegnantId/{enseignant_id}', [EcController::class, 'getByEnsegnantId']);
Route::get('/ec/getByEnsegnantIdAndAU/{enseignant_id}/{au_id}', [EcController::class, 'getByEnsegnantIdAndAU']);
Route::put('/ec/{id}/clearEnseignant', [EcController::class, 'clearEnseignantId']);
Route::put('/ec/{ecId}/etudiant/{etudiantId}/note', [EcController::class, 'updateNote']);


Route::apiResource('cours', CourController::class);
Route::get('cours/file/{filename}', [CourController::class, 'downloadCours'])->name('file.download');
Route::get('/cours/getByIdEC/{ec_id}', [CourController::class, 'getByIdEC']);


Route::apiResource('categoris', CategoriController::class);


Route::apiResource('categorieconge', CategoriecongeController::class);


Route::apiResource('note', NoteController::class);


Route::apiResource('annonce', AnnonceController::class);
Route::get('annonce/files/{filename}', [AnnonceController::class, 'downloadFile'])->name('files.download');
Route::get('annonces/categorie/{categori_id}', [AnnonceController::class, 'getAnnonceByIdCategorie']);
Route::get('annonces/user/{user_id}', [AnnonceController::class, 'getAnnonceByIdUser']);
Route::post('/annonces/{annonce}/like', [AnnonceController::class, 'toggleLike']);


Route::apiResource('congepermission', CongepermissionController::class);
Route::get('congepermission/files/{filename}', [CongepermissionController::class, 'downloadFile'])->name('files.download');
Route::get('congepermissions/user/{user_id}', [CongepermissionController::class, 'getAnnonceByIdUser']);



Route::apiResource('coms', ComController::class);
Route::get('coms/annonce/{annonce_id}', [ComController::class, 'getAnnonceByIdAnnonce']);



Route::get('/users', [ChatController::class, 'getUsers']);
Route::get('/messages/{userId1}/{userId2}', [ChatController::class, 'fetchMessages']);
Route::post('/send-message', [ChatController::class, 'sendMessage']);
Route::post('/block-message', [ChatController::class, 'updateUserBlocked']);
Route::get('/messages/{filename}', [ChatController::class, 'downloadFile'])->name('Files.download');
Route::delete('/messages/{id}', [ChatController::class, 'destroyMessage']);
Route::get('last-message/{userId1}/{userId2}', [ChatController::class, 'fetchLastMessage']);
Route::delete('conversations/{selectedUserId}', [ChatController::class, 'destroy']);
Route::delete('conversation/{userId}/{selectedUserId}', [ChatController::class, 'destroyConversation']);



Route::post('/groups', [GroupController::class, 'createGroup']);
Route::post('/groups/{groupId}/members', [GroupController::class, 'addMember']);
Route::post('/groups/{groupId}/messages', [MessagegroupeController::class, 'sendMessage']);
Route::get('/groups/{groupId}/messages', [MessagegroupeController::class, 'getMessages']);
Route::get('/users/{userId}/groups', [GroupController::class, 'getGroupsByUser']);
Route::delete('/groups/{groupId}', [GroupController::class, 'deleteGroup']);
Route::delete('/groups/{groupId}/messages/{messageId}', [MessagegroupeController::class, 'deleteMessage']);
Route::delete('/groups/{groupId}/members/{userId}', [GroupController::class, 'removeMember']);
Route::get('/groups/{id}/users', [GroupController::class, 'getAllUsersByGroupId']);
Route::put('/putgroups/{id}', [GroupController::class, 'putGroupe']);
Route::get('/messageGroup/{filename}', [MessagegroupeController::class, 'downloadFile'])->name('files.download');



Route::middleware('auth:api')->group(function () {
    Route::get('user/profil', [AuthController::class, 'profil']);
    Route::post('user/logout', [AuthController::class, 'logout']);
});
