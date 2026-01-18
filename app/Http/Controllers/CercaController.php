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

    // ✅ Comprova si el tag existeix a la BD
    public function validarTag(Request $request)
    {
        $tag = $request->input('tag');

        if (empty($tag)) return response()->json(['valid' => false]);

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
        $sort = $request->input('sort', 'relevance'); // 📥 Recuperem el paràmetre de sort
        $tags = $request->input('tags', []);

        if (empty($query) && empty($tags)) {
            return response()->json([]);
        }

        // --- AUTORS I EDITORIALS (Sense sort avançat, només per nota) ---
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

        // --- CERCA DE LLIBRES (PER TAG O PER TÍTOL) ---
        // 1. Preparem la consulta base
        $books = Llibre::with(['autor', 'editorial'])
                       ->withAvg('ressenyes', 'puntuacio');

        // 2. Apliquem els filtres (Tag o Títol)
        if ($type === 'tag') {
            if (!empty($tags)) {
                // Lògica OR per múltiples tags
                $books->where(function($globalQuery) use ($tags) {
                    foreach ($tags as $tag) {
                        $globalQuery->orWhere(function($q) use ($tag) {
                            $q->where('genere', $tag)
                              ->orWhereHas('autor', fn($a) => $a->where('nom', $tag))
                              ->orWhereHas('editorial', fn($e) => $e->where('nom', $tag));
                        });
                    }
                });
            } elseif ($query) {
                // Suggeriments mentre escriu
                $books->where(function($q) use ($query) {
                    $q->where('genere', 'LIKE', "{$query}%")
                      ->orWhereHas('autor', fn($q) => $q->where('nom', 'LIKE', "{$query}%"))
                      ->orWhereHas('editorial', fn($q) => $q->where('nom', 'LIKE', "{$query}%"));
                });
            }
        } else {
            // Cerca normal per títol (Comença per...)
            $books->where('titol', 'LIKE', "{$query}%");
        }

        // 3. 🚀 APLIQUEM L'ORDENACIÓ (SORTING)
        switch ($sort) {
            case 'preu_asc':
                $books->orderBy('preu', 'asc');
                break;
            case 'preu_desc':
                $books->orderBy('preu', 'desc');
                break;
            case 'newest':
                $books->latest(); // Ordena per created_at desc
                break;
            case 'relevance':
            default:
                // Per defecte: Més ben valorats
                $books->orderByDesc('ressenyes_avg_puntuacio');
                break;
        }

        // 4. Retornem resultats limitats
        return response()->json($books->limit(20)->get());
    }
}