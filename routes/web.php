<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LlibreController;
use App\Http\Controllers\CercaController;
use App\Http\Controllers\CistellaController; // <--- AFEGIT IMPORTACIÓ

// Pàgina d'inici
Route::get('/', [LlibreController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// RUTES PER A USUARIS REGISTRATS
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 🟢 NOVA RUTA (Aquesta és la que faltava i feia petar la pàgina)
    Route::post('/cistella/afegir/{id}', [CistellaController::class, 'afegir'])->name('cistella.afegir');
});

// Rutes públiques de cerca
Route::get('/cerca', [CercaController::class, 'index'])->name('cerca.index');
Route::get('/api/cerca', [CercaController::class, 'buscar'])->name('cerca.api');

// Canvi idioma
Route::get('/lang/{idioma}', 'App\Http\Controllers\LocalizationController@index')
    ->where('idioma', 'ca|en|es|ja');

require DIR.'/auth.php';

// Detall del llibre (Pública)
Route::get('/llibre/{id}', [LlibreController::class, 'show'])->name('llibres.show');