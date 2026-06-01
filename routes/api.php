
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});

use App\Http\Controllers\RoleController;
use App\Http\Controllers\SinistreController;
use App\Http\Controllers\DossierController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\RendezVousController;

use App\Http\Controllers\Api\Auth\RegisterApiController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Api\Auth\LoginApiController;

Route::apiResource('roles', RoleController::class);
Route::middleware('auth:sanctum')->group(function () {
	Route::apiResource('sinistres', SinistreController::class);
	Route::post('sinistres/{id}/envoyer-documents', [SinistreController::class, 'envoyerDocumentsAExpert']);
	Route::apiResource('dossiers', DossierController::class);
	// Les routes valider/refuser sont déplacées dans web.php
	Route::apiResource('notifications', NotificationController::class);
	Route::apiResource('commentaires', CommentaireController::class);
	Route::apiResource('rendezvous', RendezVousController::class);
	Route::get('documents/{id}/download', [DocumentController::class, 'download']);
	Route::apiResource('documents', DocumentController::class);
});

Route::post('register', [RegisterApiController::class, 'register']);
Route::post('login', [LoginApiController::class, 'login']);
