<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Llibre; 

Route::get('/', function () {
    
    $llibresDestacats = Llibre::with('autor')
                        ->orderBy('nota_promig', 'desc')
                        ->take(8)
                        ->get();
    
    return view('home', ['llibres' => $llibresDestacats]);
})->name('home');

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