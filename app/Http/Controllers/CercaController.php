<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Llibre;
use App\Models\Autor;
use App\Models\Editorial;

class CercaController extends Controller
{
    public function index()
    {
        return view('cerca.index');
    }

    // ✅ NOVA FUNCIÓ: Comprova si el tag existeix a la BD
    public function validarTag(Request $request)
    {
        $tag = $request->input('tag');

        if (empty($tag)) return response()->json(['valid' => false]);

        // Comprovem si existeix exactament com a Gènere, Autor o Editorial
        $existeixGenere = Llibre::where('genere', $tag)->exists();
        $existeixAutor = Autor::where('nom', $tag)->exists();
        $existeixEditorial = Editorial::where('nom', $tag)->exists();

        return response()->json([
            'valid' => $existeixGenere || $existeixAutor || $existeixEditorial
        ]);
    }

    public function buscar(Request $request)
    {
        $query = $request->input('q');
        $type = $request->input('type');
        $tags = $request->input('tags', []);

        if (empty($query) && empty($tags)) {
            return response()->json([]);
        }

        // ... (Codi Autor i Editorial es queden igual) ...
        if ($type === 'autor') {
            $autors = Autor::with(['llibres' => function($q) {
                $q->withAvg('ressenyes', 'puntuacio')->orderByDesc('ressenyes_avg_puntuacio');
            }])->where('nom', 'LIKE', "%{$query}%")->get();
            return response()->json($autors);
        }

        if ($type === 'editorial') {
            $editorials = Editorial::with(['llibres' => function($q) {
                $q->withAvg('ressenyes', 'puntuacio')->orderByDesc('ressenyes_avg_puntuacio');
            }])->where('nom', 'LIKE', "%{$query}%")->get();
            return response()->json($editorials);
        }

        // --- 🏷️ CERCA PER TAGS (MODIFICAT) ---
        if ($type === 'tag') {
            $books = Llibre::with(['autor', 'editorial'])
                           ->withAvg('ressenyes', 'puntuacio');

            // 1. Si hi ha tags afegits, filtrem per ells (Lògica AND: ha de complir tots els tags)
            if (!empty($tags)) {
                foreach ($tags as $tag) {
                    $books->where(function($q) use ($tag) {
                        $q->where('genere', $tag)                            // És aquest gènere?
                          ->orWhereHas('autor', fn($q) => $q->where('nom', $tag))       // O l'autor es diu així?
                          ->orWhereHas('editorial', fn($q) => $q->where('nom', $tag));  // O l'editorial es diu així?
                    });
                }
            } 
            
            // 2. Si l'usuari està escrivint al input i encara no ha donat a Enter (suggeriments)
            // En aquest cas, busquem llibres que tinguin alguna relació semblant al que escriu
            elseif ($query) {
                $books->where(function($q) use ($query) {
                    $q->where('genere', 'LIKE', "{$query}%")
                      ->orWhereHas('autor', fn($q) => $q->where('nom', 'LIKE', "{$query}%"))
                      ->orWhereHas('editorial', fn($q) => $q->where('nom', 'LIKE', "{$query}%"));
                });
            }

            return response()->json($books->orderByDesc('ressenyes_avg_puntuacio')->get());
        }

        // --- CERCA DE LLIBRES PER TÍTOL (Per defecte) ---
        $books = Llibre::with(['autor', 'editorial'])
            ->withAvg('ressenyes', 'puntuacio')
            ->where('titol', 'LIKE', "{$query}%")
            ->orderByDesc('ressenyes_avg_puntuacio')
            ->limit(20)
            ->get();
        
        return response()->json($books);
    }
}