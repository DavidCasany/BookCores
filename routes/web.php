<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LlibreController;
use App\Http\Controllers\CercaController;
use App\Http\Controllers\CistellaController;
use App\Http\Controllers\RessenyaController;
use App\Http\Controllers\PagamentController;
use App\Http\Controllers\Admin\AutorController;
use App\Http\Controllers\Admin\EditorialController; // <--- 1. AFEGEIX AQUESTA IMPORTACIÓ

// Pàgina d'inici
Route::get('/', [LlibreController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutes d'usuaris registrats
Route::middleware('auth')->group(function () {
    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cistella
    Route::get('/cistella', [CistellaController::class, 'index'])->name('cistella.index');
    Route::post('/cistella/afegir/{id}', [CistellaController::class, 'afegir'])->name('cistella.afegir');
    Route::delete('/cistella/eliminar/{id}', [CistellaController::class, 'eliminar'])->name('cistella.eliminar');
    Route::patch('/cistella/actualitzar/{id}', [CistellaController::class, 'actualitzarQuantitat'])->name('cistella.actualitzar');

    // Pagament
    Route::post('/pagament/checkout', [PagamentController::class, 'checkout'])->name('pagament.checkout');
    Route::get('/pagament/exit', [PagamentController::class, 'exit'])->name('pagament.exit');

    // Ressenyes
    Route::post('/ressenyes', [RessenyaController::class, 'store'])->name('ressenyes.store');

    // Biblioteca
    Route::get('/biblioteca', [LlibreController::class, 'biblioteca'])->name('biblioteca');

    // Llegir llibre (PDF)
    Route::get('/llegir/{id}', [LlibreController::class, 'llegir'])->name('llibre.llegir');
});

require __DIR__ . '/auth.php';

// Rutes publiques

// Cerca i Validació de Tags
Route::get('/cerca', [CercaController::class, 'index'])->name('cerca.index');
Route::get('/api/cerca', [CercaController::class, 'buscar'])->name('cerca.api');
Route::get('/api/validar-tag', [CercaController::class, 'validarTag'])->name('cerca.validar');

// Idioma
Route::get('/lang/{idioma}', 'App\Http\Controllers\LocalizationController@index')
    ->where('idioma', 'ca|en|es|ja');

// Detall llibre
Route::get('/llibre/{id}', [LlibreController::class, 'show'])->name('llibres.show');

// Grup de rutes per a l'ADMINISTRADOR
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Rutes per gestionar llibres des de l'editor d'autors
    Route::delete('/autors/llibre/{id}/delete', [AutorController::class, 'destroyLlibre'])->name('autors.llibre.destroy');
    Route::post('/autors/llibre/{id}/anonim', [AutorController::class, 'moureAAnonim'])->name('autors.llibre.anonim');
    Route::post('/autors/llibre/{id}/transferir', [AutorController::class, 'transferirLlibre'])->name('autors.llibre.transferir');

    // NOVA RUTA: Assignar llibre
    Route::post('/autors/{autor}/assignar-llibre', [AutorController::class, 'assignarLlibre'])->name('autors.assignar-llibre');

    // La ruta resource que ja tenies
    Route::resource('autors', AutorController::class);

    // CRUD Complet d'Editorials
    Route::resource('editorials', EditorialController::class);

    //gestió de llibres
    Route::resource('llibres', \App\Http\Controllers\Admin\LlibreController::class);

    // Rutes especials per gestionar llibres dins de l'editorial
    Route::post('/llibres/{llibre}/desvincular', [EditorialController::class, 'desvincularLlibre'])->name('llibres.desvincular');
    Route::post('/editorials/{editorial}/afegir-llibre', [EditorialController::class, 'afegirLlibre'])->name('editorials.afegir-llibre');
});