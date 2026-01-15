<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Llibre;
use App\Models\Autor;      // <--- IMPORTANT: Assegura't que tens això
use App\Models\Editorial;  // <--- I això

class CercaController extends Controller
{
    public function index()
    {
        return view('cerca.index');
    }

    public function buscar(Request $request)
    {
        $query = $request->input('q');
        $type = $request->input('type');
        $tags = $request->input('tags', []);

        // --- CERCA D'AUTORS ---
        if ($type === 'autor') {
            $autors = Autor::with(['llibres' => function($q) {
                    // Dins de l'autor, ordenem els llibres per nota
                    $q->orderBy('nota_promig', 'desc');
                }])
                // Busquem per nom (LIKE %...%)
                ->where('nom', 'LIKE', "%{$query}%")
                ->get();
            
            return response()->json($autors);
        }

        // --- CERCA D'EDITORIALS ---
        if ($type === 'editorial') {
            $editorials = Editorial::with(['llibres' => function($q) {
                    $q->orderBy('nota_promig', 'desc');
                }])
                ->where('nom', 'LIKE', "%{$query}%")
                ->get();
            
            return response()->json($editorials);
        }

        // --- CERCA PER TAGS (GÈNERE) ---
        if ($type === 'tag') {
            $books = Llibre::with(['autor', 'editorial']);

            if (!empty($tags)) {
                $books->whereIn('genere', $tags);
            } elseif ($query) {
                // Busquem llibres que tinguin aquest gènere mentre escrivim
                $books->where('genere', 'LIKE', "%{$query}%");
            }

            return response()->json($books->orderBy('nota_promig', 'desc')->get());
        }

        // --- CERCA DE LLIBRES (Per defecte) ---
        // Si no és cap dels anteriors, assumim que és "llibre"
        $books = Llibre::with(['autor', 'editorial'])
            ->where('titol', 'LIKE', "%{$query}%")
            ->orderByRaw("CASE WHEN titol = ? THEN 1 ELSE 2 END", [$query]) // Prioritzem coincidència exacta
            ->orderBy('nota_promig', 'desc')
            ->limit(20)
            ->get();
        
        return response()->json($books);
    }
}
