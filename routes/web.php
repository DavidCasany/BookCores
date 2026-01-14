<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Llibre;
use App\Http\Controllers\LlibreController;

// --- CANVI AQUI ---
// Substituïm tota la funció anònima per la crida al controlador
Route::get('/', [LlibreController::class, 'index'])->name('home');
// ------------------

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Canvi idioma
Route::get('/lang/{idioma}', 'App\Http\Controllers\LocalizationController@index')
    ->where('idioma', 'ca|en|es|ja');

require __DIR__.'/auth.php';

// Aquesta ruta és PÚBLICA
Route::get('/llibre/{id}', [LlibreController::class, 'show'])->name('llibres.show');