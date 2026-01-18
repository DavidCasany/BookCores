<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Llibre;
use App\Models\Autor;
use App\Models\Editorial;
use Illuminate\Support\Facades\File;

class CercaController extends Controller
{
    public function index()
    {
        return view('cerca.index');
    }

    // ✅ Validar Tag: Mira si existeix a la BD o si és una traducció d'un tag existent
    public function validarTag(Request $request)
    {
        $tag = $request->input('tag');
        if (empty($tag)) return response()->json(['valid' => false]);

        // 1. Busquem les claus originals (per si ha escrit el tag en japonès o anglès)
        $keys = $this->getTranslationKeys($tag);

        // 2. Comprovem si alguna d'aquestes claus existeix com a gènere, autor o editorial
        $existeix = Llibre::whereIn('genere', $keys)->exists()
                 || Autor::where('nom', 'LIKE', "%{$tag}%")->exists() // Autors no es tradueixen
                 || Editorial::where('nom', 'LIKE', "%{$tag}%")->exists(); // Editorials no es tradueixen

        return response()->json(['valid' => $existeix]);
    }

    public function buscar(Request $request)
    {
        $queryText = $request->input('q'); // El text que escriu l'usuari
        $type = $request->input('type');
        $sort = $request->input('sort', 'relevance');
        $tags = $request->input('tags', []);

        if (empty($queryText) && empty($tags)) {
            return response()->json([]);
        }

        // --- 1. OBTENIR CLAUS DE TRADUCCIÓ ---
        // Si l'usuari busca "Mystery", volem trobar "Misteri" per buscar-lo a la BD.
        $translatedKeys = $this->getTranslationKeys($queryText);
        
        // Afegim també el text original per si busca directament en català o és un nom propi
        if (!in_array($queryText, $translatedKeys)) {
            $translatedKeys[] = $queryText;
        }

        // --- 2. CERCA D'AUTORS I EDITORIALS (NO ES TRADUEIXEN) ---
        if ($type === 'autor') {
            $autors = Autor::with(['llibres' => function($q) {
                $q->withAvg('ressenyes', 'puntuacio')->orderByDesc('ressenyes_avg_puntuacio');
            }])->where('nom', 'LIKE', "%{$queryText}%")->get();
            return response()->json($autors);
        }

        if ($type === 'editorial') {
            $editorials = Editorial::with(['llibres' => function($q) {
                $q->withAvg('ressenyes', 'puntuacio')->orderByDesc('ressenyes_avg_puntuacio');
            }])->where('nom', 'LIKE', "%{$queryText}%")->get();
            return response()->json($editorials);
        }

        // --- 3. CERCA DE LLIBRES (MULTI-IDIOMA) ---
        $books = Llibre::with(['autor', 'editorial'])
                       ->withAvg('ressenyes', 'puntuacio');

        if ($type === 'tag') {
            // Cerca per TAGS (Gèneres)
            if (!empty($tags)) {
                $books->where(function($globalQuery) use ($tags) {
                    foreach ($tags as $tag) {
                        // Traduïm també els tags que venen de la URL
                        $tagKeys = $this->getTranslationKeys($tag);
                        $tagKeys[] = $tag;

                        $globalQuery->orWhere(function($q) use ($tagKeys, $tag) {
                            $q->whereIn('genere', $tagKeys)
                              ->orWhereHas('autor', fn($a) => $a->where('nom', $tag))
                              ->orWhereHas('editorial', fn($e) => $e->where('nom', $tag));
                        });
                    }
                });
            } elseif ($queryText) {
                // Suggeriments mentre escriu (Tags)
                $books->where(function($q) use ($translatedKeys, $queryText) {
                    // Busquem per qualsevol de les traduccions possibles del gènere
                    foreach ($translatedKeys as $key) {
                        $q->orWhere('genere', 'LIKE', "%{$key}%");
                    }
                    // Autors i Editorials pel nom original
                    $q->orWhereHas('autor', fn($a) => $a->where('nom', 'LIKE', "%{$queryText}%"))
                      ->orWhereHas('editorial', fn($e) => $e->where('nom', 'LIKE', "%{$queryText}%"));
                });
            }
        } else {
            // Cerca per TÍTOL (Llibre)
            // Busquem si el títol a la BD coincideix amb alguna de les traduccions del text cercat
            $books->where(function($q) use ($translatedKeys) {
                foreach ($translatedKeys as $key) {
                    $q->orWhere('titol', 'LIKE', "%{$key}%");
                }
            });
        }

        // --- 4. ORDENACIÓ ---
        switch ($sort) {
            case 'preu_asc': $books->orderBy('preu', 'asc'); break;
            case 'preu_desc': $books->orderBy('preu', 'desc'); break;
            case 'newest': $books->latest(); break;
            case 'relevance': 
            default: $books->orderByDesc('ressenyes_avg_puntuacio'); break;
        }

        return response()->json($books->limit(20)->get());
    }

    /**
     * 🧠 FUNCIÓ MÀGICA: CERCA INVERSA DE TRADUCCIONS
     * Busca el text input a tots els fitxers JSON (ca, es, en, ja)
     * i retorna les claus (keys) originals que es guarden a la BD.
     */
    private function getTranslationKeys($term)
    {
        if (empty($term)) return [];

        $foundKeys = [];
        $locales = ['ca', 'es', 'en', 'ja']; // Els teus 4 idiomes

        foreach ($locales as $locale) {
            $path = lang_path("$locale.json");
            
            if (File::exists($path)) {
                $translations = json_decode(File::get($path), true);
                
                if ($translations) {
                    foreach ($translations as $key => $value) {
                        // Si el valor traduït conté el terme de cerca (ignorant majúscules)
                        if (stripos($value, $term) !== false) {
                            $foundKeys[] = $key; // Guardem la CLAU (el que hi ha a la BD)
                        }
                    }
                }
            }
        }

        return array_unique($foundKeys);
    }
}