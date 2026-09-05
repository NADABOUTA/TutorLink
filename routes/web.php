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

    // CRUD Offres & Acceptation
    Route::get('/offres', [\App\Http\Controllers\OffreController::class, 'index'])->name('offres.index');
    Route::post('/demandes/{demande}/offres', [\App\Http\Controllers\OffreController::class, 'store'])->name('demandes.offres.store');
    Route::patch('/offres/{offre}/accepter', [\App\Http\Controllers\OffreController::class, 'accepter'])->name('offres.accepter');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Modération Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/moderation', [\App\Http\Controllers\Admin\ModerationController::class, 'index'])->name('moderation.index');
        Route::patch('/moderation/{demande}/approuver', [\App\Http\Controllers\Admin\ModerationController::class, 'approuver'])->name('moderation.approuver');
        Route::patch('/moderation/{demande}/refuser', [\App\Http\Controllers\Admin\ModerationController::class, 'refuser'])->name('moderation.refuser');
    });
});

require __DIR__.'/auth.php';
