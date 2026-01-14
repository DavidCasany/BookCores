<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Llibre;
use App\Models\Autor;
use App\Models\Editorial;

class CercaController extends Controller
{
    // Mostra la pàgina de cerca (buid)
    public function index()
    {
        return view('cerca.index');
    }

    // Retorna els resultats en JSON (AJAX)
    public function buscar(Request $request)
    {
        $query = $request->input('q');
        $type = $request->input('type');
        $tags = $request->input('tags', []); // Array de tags

        // 1. CERCA PER LLIBRE (Títol)
        if ($type === 'llibre') {
            $books = Llibre::with(['autor', 'editorial'])
                ->where('titol', 'LIKE', "%{$query}%")
                // Truc SQL: Ordena primer si el títol és EXACTE, després per Nota
                ->orderByRaw("CASE WHEN titol = ? THEN 1 ELSE 2 END", [$query])
                ->orderBy('nota_promig', 'desc')
                ->limit(20)
                ->get();
            
            return response()->json($books);
        }

        // 2. CERCA PER AUTOR
        if ($type === 'autor') {
            $autors = Autor::with(['llibres' => function($q) {
                    $q->orderBy('nota_promig', 'desc'); // Els llibres de l'autor, els millors primer
                }])
                ->where('nom', 'LIKE', "%{$query}%")
                ->get();
            
            return response()->json($autors);
        }

        // 3. CERCA PER EDITORIAL
        if ($type === 'editorial') {
            $editorials = Editorial::with(['llibres' => function($q) {
                    $q->orderBy('nota_promig', 'desc');
                }])
                ->where('nom', 'LIKE', "%{$query}%")
                ->get();
            
            return response()->json($editorials);
        }

        // 4. CERCA PER TAGS (GÈNERE)
        if ($type === 'tag') {
            // Nota: Com que la teva DB actual només té un camp 'genere' (string),
            // buscarem llibres que coincideixin amb algun dels tags seleccionats.
            // Si tinguessis una taula 'tags' many-to-many, es faria diferent.
            
            $books = Llibre::with(['autor', 'editorial']);

            if (!empty($tags)) {
                $books->whereIn('genere', $tags);
            } elseif ($query) {
                // Si encara no ha afegit el tag al botó, busquem suggeriments
                 $books->where('genere', 'LIKE', "%{$query}%");
            }

            $results = $books->orderBy('nota_promig', 'desc')->get();
            return response()->json($results);
        }

        return response()->json([]);
    }
}
