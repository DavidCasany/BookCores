<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LlibreController;
use App\Http\Controllers\CercaController;
use App\Http\Controllers\CistellaController;
use App\Http\Controllers\RessenyaController;
use App\Http\Controllers\PagamentController; // <--- AFEGIT (Necessari per pagar)

// Pàgina d'inici
Route::get('/', [LlibreController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- RUTES PER A USUARIS REGISTRATS ---
Route::middleware('auth')->group(function () {
    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 🛒 CISTELLA (Aquí és on faltaven coses)
    Route::get('/cistella', [CistellaController::class, 'index'])->name('cistella.index');
    Route::post('/cistella/afegir/{id}', [CistellaController::class, 'afegir'])->name('cistella.afegir');
    Route::delete('/cistella/eliminar/{id}', [CistellaController::class, 'eliminar'])->name('cistella.eliminar');
    Route::patch('/cistella/actualitzar/{id}', [CistellaController::class, 'actualitzarQuantitat'])->name('cistella.actualitzar'); // <--- FALTAVA AQUESTA

    // 💳 PAGAMENT (Preparem el terreny per Stripe)
    Route::post('/pagament/checkout', [PagamentController::class, 'checkout'])->name('pagament.checkout');
    Route::get('/pagament/exit', [PagamentController::class, 'exit'])->name('pagament.exit');
    
    // Ressenyes
    Route::post('/ressenyes', [RessenyaController::class, 'store'])->name('ressenyes.store');
});

// Rutes públiques de cerca
Route::get('/cerca', [CercaController::class, 'index'])->name('cerca.index');
Route::get('/api/cerca', [CercaController::class, 'buscar'])->name('cerca.api');

// Idioma
Route::get('/lang/{idioma}', 'App\Http\Controllers\LocalizationController@index')
    ->where('idioma', 'ca|en|es|ja');

require __DIR__.'/auth.php';

// Detall llibre (al final per evitar conflictes)
Route::get('/llibre/{id}', [LlibreController::class, 'show'])->name('llibres.show');

Route::get('/biblioteca', [App\Http\Controllers\LlibreController::class, 'biblioteca'])->name('biblioteca');

// Llegir llibre (PDF)
Route::get('/llegir/{id}', [App\Http\Controllers\LlibreController::class, 'llegir'])->name('llibre.llegir');