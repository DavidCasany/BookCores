<?php

namespace App\Http\Controllers;

use App\Models\Llibre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LlibreController extends Controller
{
    public function index()
    {
        // Agafem les dades igual que abans
        $llibresRecents = Llibre::latest()->take(3)->get();
        $llibres = Llibre::with('autor')->get();

        // COMPROVACIÓ: Si l'usuari està loguejat (Auth::check()), mostrem la nova vista 'home-auth'.
        // Si no, mostrem la vista antiga, ara reanomenada 'home-guest'.
        if (Auth::check()) {
            return view('home-auth', compact('llibres', 'llibresRecents'));
        } else {
            return view('home-guest', compact('llibres', 'llibresRecents'));
        }
    }

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