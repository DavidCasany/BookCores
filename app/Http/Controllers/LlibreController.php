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
    public function llegir($id)
    {
        $llibre = Llibre::findOrFail($id);

        // 1. Seguretat: Comprovem si l'usuari ha comprat el llibre
        // Busquem si existeix una compra d'aquest usuari, pagada, que contingui aquest llibre
        $teElLlibre = Compra::where('user_id', Auth::id())
            ->where('estat', 'pagat')
            ->whereHas('llibres', function($query) use ($id) {
                $query->where('llibres.id_llibre', $id);
            })->exists();

        if (!$teElLlibre) {
            abort(403, "No tens permís per llegir aquest llibre. Compra'l primer!");
        }

        // 2. Comprovem si el fitxer existeix al disc
        $path = storage_path('app/pdfs/' . $llibre->fitxer_pdf);

        if (!file_exists($path)) {
            abort(404, "El fitxer PDF no s'ha trobat al servidor.");
        }

        // 3. Servim el fitxer perquè el navegador l'obri (inline)
        return response()->file($path);
    }
}