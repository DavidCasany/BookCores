<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Llibre;
use App\Http\Controllers\LlibreController;  
use App\Http\Controllers\CercaController;

Route::get('/', function () {
    
    // 1. Consulta existent: Els millors valorats (per a la secció de baix)
    $llibresDestacats = Llibre::with('autor')
                        ->orderBy('nota_promig', 'desc')
                        ->take(8)
                        ->get();

    // Ordenem per data de creació descendent i n'agafem, per exemple, 10.
    $llibresRecents = Llibre::orderBy('created_at', 'desc')
                        ->take(10)
                        ->get();
    
    // 3. Passem les DUES variables a la vista
    return view('home', [
        'llibres' => $llibresDestacats,       // variable llibres millor puntuats
        'llibresRecents' => $llibresRecents   // variable llibres més nous per al carrucel
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Rutes públiques (fora del auth middleware si vols que tothom pugui buscar)
Route::get('/cerca', [CercaController::class, 'index'])->name('cerca.index');
Route::get('/api/cerca', [CercaController::class, 'buscar'])->name('cerca.api');

// Canvi idioma
Route::get('/lang/{idioma}', 'App\Http\Controllers\LocalizationController@index')
    ->where('idioma', 'ca|en|es|ja');

require __DIR__.'/auth.php';

// Aquesta ruta és PÚBLICA (fora del middleware 'auth')
Route::get('/llibre/{id}', [LlibreController::class, 'show'])->name('llibres.show');