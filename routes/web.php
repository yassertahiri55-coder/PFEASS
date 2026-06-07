


<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DossierController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RendezVousController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/expert/dashboard', function () {
        return view('expert_dashboard');
    });
    Route::post('/rendezvous', [RendezVousController::class, 'store'])->name('rendezvous.store');
    Route::post('/admin/sinistres/{id}/statut', [AdminController::class, 'updateSinistreStatus'])->name('admin.sinistre.updateStatus');
    Route::post('/admin/notifications/send', [AdminController::class, 'sendNotification'])->name('admin.notifications.send');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::post('/admin/users/{id}/update', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::post('/admin/users/{id}/delete', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::post('/admin/users/{id}/approve', [AdminController::class, 'approveUser'])->name('admin.users.approve');
    Route::post('/admin/users/{id}/reject', [AdminController::class, 'rejectUser'])->name('admin.users.reject');
    // Routes pour valider/refuser dossier (AJAX compatible)
    Route::post('dossiers/{id}/valider', [DossierController::class, 'valider']);
    Route::post('dossiers/{id}/refuser', [DossierController::class, 'refuser']);
});
