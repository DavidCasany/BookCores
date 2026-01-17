<?php

namespace App\Http\Controllers;

use App\Models\Llibre;
use App\Models\Compra; // <--- IMPORTANT: Necessari per a la biblioteca
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LlibreController extends Controller
{
    // Pàgina d'inici
    public function index()
    {
        $llibresRecents = Llibre::latest()->take(3)->get();
        $llibres = Llibre::with('autor')->get();

        if (Auth::check()) {
            return view('home-auth', compact('llibres', 'llibresRecents'));
        } else {
            return view('home-guest', compact('llibres', 'llibresRecents'));
        }
    }

    // Detall del llibre
    public function show($id)
    {
        // Recorda: 'ressenyes.user' (amb E) perquè ho vam arreglar abans
        $llibre = Llibre::with(['autor', 'editorial', 'ressenyes.user'])->findOrFail($id);

        return view('llibres.show', compact('llibre'));
    }

    // 📚 NOVA FUNCIÓ: BIBLIOTECA
    public function biblioteca()
    {
        // 1. Busquem totes les compres PAGADES de l'usuari actual
        $compres = Compra::where('user_id', Auth::id())
                         ->where('estat', 'pagat')
                         ->with('llibres.autor') // Carreguem llibres i autors optimitzats
                         ->get();

        // 2. Extraiem els llibres de dins les compres i eliminem duplicats
        $llibres = $compres->flatMap(function ($compra) {
            return $compra->llibres;
        })->unique('id_llibre');

        // 3. Retornem la vista
        return view('biblioteca.index', compact('llibres'));
    }
}