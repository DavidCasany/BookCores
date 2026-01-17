<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LlibreController;
use App\Http\Controllers\CercaController;
use App\Http\Controllers\CistellaController;
use App\Http\Controllers\RessenyaController; // Assegura't que això està importat

// Pàgina d'inici
Route::get('/', [LlibreController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- RUTES PER A USUARIS REGISTRATS (TOT JUNT AQUÍ) ---
Route::middleware('auth')->group(function () {
    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cistella
    Route::post('/cistella/afegir/{id}', [CistellaController::class, 'afegir'])->name('cistella.afegir');
    Route::get('/cistella', [CistellaController::class, 'index'])->name('cistella.index');
    Route::delete('/cistella/eliminar/{id}', [CistellaController::class, 'eliminar'])->name('cistella.eliminar');
    
    // Ressenyes (Aquesta és la ruta que necessites)
    Route::post('/ressenyes', [RessenyaController::class, 'store'])->name('ressenyes.store');
});

// Rutes públiques
Route::get('/cerca', [CercaController::class, 'index'])->name('cerca.index');
Route::get('/api/cerca', [CercaController::class, 'buscar'])->name('cerca.api');

// Idioma
Route::get('/lang/{idioma}', 'App\Http\Controllers\LocalizationController@index')
    ->where('idioma', 'ca|en|es|ja');

require __DIR__.'/auth.php';

// Detall llibre
Route::get('/llibre/{id}', [LlibreController::class, 'show'])->name('llibres.show');