


<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/debug-db', function () {
    return config('database.connections.mysql');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin_dashboard');
    });
    Route::get('/expert/dashboard', function () {
        return view('expert_dashboard');
    });
    // Ajout des routes valider/refuser dossier pour session
    Route::post('api/dossiers/{id}/valider', [App\Http\Controllers\DossierController::class, 'valider']);
    Route::post('api/dossiers/{id}/refuser', [App\Http\Controllers\DossierController::class, 'refuser']);
});

