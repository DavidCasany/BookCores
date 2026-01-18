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
        $llibresRecents = Llibre::with('ressenyes')->latest()->take(3)->get();
        $llibres = Llibre::with(['autor', 'ressenyes'])->get();

        if (Auth::check()) {
            return view('home-auth', compact('llibres', 'llibresRecents'));
        } else {
            return view('home-guest', compact('llibres', 'llibresRecents'));
        }
    }

    // Fitxa del llibre
    public function show($id)
    {
        $llibre = Llibre::with(['autor', 'editorial', 'ressenyes.user'])->findOrFail($id);
        return view('llibres.show', compact('llibre'));
    }

    /**
     * 📚 LA MEVA BIBLIOTECA (OPTIMITZADA)
     */
    public function biblioteca(Request $request)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        // 1. Consulta optimitzada: Llibres comprats i pagats
        $query = Llibre::whereHas('compres', function($q) use ($user) {
            $q->where('user_id', $user->id)
              ->where('estat', 'pagat'); 
        })->with('autor'); 

        // 2. Filtre de Cerca
        if ($request->has('q') && !empty($request->q)) {
            $query->where('titol', 'LIKE', '%' . $request->q . '%');
        }

        // 3. Ordenació (més recents primer)
        $query->latest('id_llibre'); 

        // 4. Paginació
        $llibres = $query->paginate(12)->withQueryString();

        return view('biblioteca.index', compact('llibres'));
    }

    /**
     * 📖 LLEGIR LLIBRE (SEGURETAT)
     */
    public function llegir($id)
    {
        $llibre = Llibre::findOrFail($id);
        $user = Auth::user();

        if (!$user) abort(403);

        // 1. Seguretat: Té el llibre comprat?
        $teElLlibre = Compra::where('user_id', $user->id)
            ->where('estat', 'pagat')
            ->whereHas('llibres', function($query) use ($id) {
                $query->where('llibres.id_llibre', $id);
            })->exists();

        if (!$teElLlibre) {
            abort(403, "Accés denegat. Has de comprar aquest llibre per llegir-lo.");
        }

        // 2. Servir PDF (Assegura't que la ruta és correcta)
        $path = storage_path('app/pdfs/' . $llibre->fitxer_pdf);

        if (!file_exists($path)) {
            abort(404, "El fitxer PDF no s'ha trobat al servidor.");
        }

        return response()->file($path);
    }
}