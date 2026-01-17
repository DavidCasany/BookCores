<?php

namespace App\Http\Controllers;

use App\Models\Llibre;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LlibreController extends Controller
{
    // Pàgina d'inici
    public function index()
    {
        // CARREGUEM 'ressenyes' PER PODER CALCULAR LA NOTA AL HOME
        $llibresRecents = Llibre::with('ressenyes')->latest()->take(3)->get();
        $llibres = Llibre::with(['autor', 'ressenyes'])->get();

        if (Auth::check()) {
            return view('home-auth', compact('llibres', 'llibresRecents'));
        } else {
            return view('home-guest', compact('llibres', 'llibresRecents'));
        }
    }

    // Detall del llibre
    public function show($id)
    {
        $llibre = Llibre::with(['autor', 'editorial', 'ressenyes.user'])->findOrFail($id);
        return view('llibres.show', compact('llibre'));
    }

    // Biblioteca
    public function biblioteca()
    {
        $compres = Compra::where('user_id', Auth::id())
                         ->where('estat', 'pagat')
                         ->with('llibres.autor')
                         ->get();

        $llibres = $compres->flatMap(function ($compra) {
            return $compra->llibres;
        })->unique('id_llibre');

        return view('biblioteca.index', compact('llibres'));
    }
}