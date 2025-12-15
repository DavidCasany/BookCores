<?php

namespace App\Http\Controllers;

use App\Models\Llibre;
use Illuminate\Http\Request;

class LlibreController extends Controller
{
    public function show($id)
    {
        // 1. Busquem el llibre per ID
        // 2. Amb 'with' carreguem JA l'autor, editorial i ressenyes (per eficiència)
        // 3. findOrFail: Si no existeix l'ID, dona error 404 automàticament
        $llibre = Llibre::with(['autor', 'editorial', 'ressenyas.user'])->findOrFail($id);

        // Retornem la vista amb les dades del llibre
        return view('llibres.show', compact('llibre'));
    }
}