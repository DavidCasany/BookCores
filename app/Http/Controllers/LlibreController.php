<?php

namespace App\Http\Controllers;

use App\Models\Llibre;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LlibreController extends Controller
{
    // Pàgina d'inici
    public function index()
    {
        // 1. Slider: 3 llibres més recents (per al Hero)
        $llibresRecents = Llibre::with('ressenyes')->latest()->take(3)->get();
        
        // 2. Millor Valorats (Per a la secció superior)
        $millorValorats = Llibre::with(['autor', 'ressenyes'])
            ->withAvg('ressenyes', 'puntuacio')
            ->orderByDesc('ressenyes_avg_puntuacio')
            ->take(10)
            ->get();

        // 3. Resta de llibres agrupats per gènere
        $llibresPerGenere = Llibre::with(['autor', 'ressenyes'])
                                  ->get()
                                  ->groupBy('genere');

        if (Auth::check()) {
            return view('home-auth', compact('llibresPerGenere', 'llibresRecents', 'millorValorats'));
        } else {
            return view('home-guest', compact('llibresPerGenere', 'llibresRecents', 'millorValorats'));
        }
    }

    public function show($id)
    {
        $llibre = Llibre::with(['autor', 'editorial', 'ressenyes.user'])->findOrFail($id);
        return view('llibres.show', compact('llibre'));
    }

    // BIBLIOTECA (Es manté igual, agrupada per gènere)
    public function biblioteca(Request $request)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $query = Llibre::whereHas('compres', function($q) use ($user) {
            $q->where('user_id', $user->id)
              ->where('estat', 'pagat'); 
        })->with('autor'); 

        if ($request->has('q') && !empty($request->q)) {
            $query->where('titol', 'LIKE', '%' . $request->q . '%');
        }

        $totsElsLlibres = $query->latest('id_llibre')->get();
        $llibresPerGenere = $totsElsLlibres->groupBy('genere');

        return view('biblioteca.index', compact('llibresPerGenere'));
    }

    public function llegir($id)
    {
        $llibre = Llibre::findOrFail($id);
        $user = Auth::user();

        if (!$user) abort(403);

        $teElLlibre = Compra::where('user_id', $user->id)
            ->where('estat', 'pagat')
            ->whereHas('llibres', function($query) use ($id) {
                $query->where('llibres.id_llibre', $id);
            })->exists();

        if (!$teElLlibre) {
            abort(403, "Accés denegat.");
        }

        $path = storage_path('app/pdfs/' . $llibre->fitxer_pdf);

        if (!file_exists($path)) {
            abort(404, "El fitxer PDF no s'ha trobat al servidor.");
        }

        return response()->file($path);
    }
}