<?php

use App\Http\Controllers\DemandeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // CRUD Demandes
    Route::resource('demandes', DemandeController::class);
    Route::patch('/demandes/{demande}/terminer', [DemandeController::class, 'terminer'])->name('demandes.terminer');
    Route::post('/demandes/ai-suggest', [\App\Http\Controllers\AiSuggestionController::class, 'suggest'])->name('demandes.ai-suggest');

    // CRUD Offres & Acceptation
    Route::get('/offres', [\App\Http\Controllers\OffreController::class, 'index'])->name('offres.index');
    Route::post('/demandes/{demande}/offres', [\App\Http\Controllers\OffreController::class, 'store'])->name('demandes.offres.store');
    Route::patch('/offres/{offre}/accepter', [\App\Http\Controllers\OffreController::class, 'accepter'])->name('offres.accepter');

    // Avis & Évaluations (Phase 8)
    Route::post('/demandes/{demande}/avis', [\App\Http\Controllers\AvisController::class, 'store'])->name('avis.store');

    // Commentaires & Échanges étudiants / tuteurs
    Route::post('/demandes/{demande}/commentaires', [\App\Http\Controllers\CommentaireController::class, 'store'])->name('demandes.commentaires.store');

    // Notifications in-app (Cloche & Historique)
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Espace Admin (Phase 9)
    Route::middleware('can:admin')->prefix('admin')->name('admin.')->group(function () {
        // Modération
        Route::get('/moderation', [\App\Http\Controllers\Admin\ModerationController::class, 'index'])->name('moderation.index');
        Route::patch('/moderation/{demande}/approuver', [\App\Http\Controllers\Admin\ModerationController::class, 'approuver'])->name('moderation.approuver');
        Route::patch('/moderation/{demande}/refuser', [\App\Http\Controllers\Admin\ModerationController::class, 'refuser'])->name('moderation.refuser');

        // Gestion Utilisateurs
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/toggle', [\App\Http\Controllers\Admin\UserController::class, 'toggle'])->name('users.toggle');
    });
});

require __DIR__.'/auth.php';
