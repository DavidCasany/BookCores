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

    // Validar Tag
    public function validarTag(Request $request)
    {
        $tag = $request->input('tag');
        if (empty($tag)) return response()->json(['valid' => false]);

        
        $keys = $this->getTranslationKeys($tag);

        
        $existeix = Llibre::whereIn('genere', $keys)->exists()
                 || Autor::where('nom', 'LIKE', "%{$tag}%")->exists() 
                 || Editorial::where('nom', 'LIKE', "%{$tag}%")->exists(); 

        return response()->json(['valid' => $existeix]);
    }

    public function buscar(Request $request)
    {
        $queryText = $request->input('q'); 
        $type = $request->input('type');
        $sort = $request->input('sort', 'relevance');
        $tags = $request->input('tags', []);

        if (empty($queryText) && empty($tags)) {
            return response()->json([]);
        }

        // Obtenir claus de traducció
        $translatedKeys = $this->getTranslationKeys($queryText);
        
        if (!in_array($queryText, $translatedKeys)) {
            $translatedKeys[] = $queryText;
        }

        // Cerca d'autors / editorials / llibres / tags
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

        $books = Llibre::with(['autor', 'editorial'])
                       ->withAvg('ressenyes', 'puntuacio');

        if ($type === 'tag') {
            if (!empty($tags)) {
                $books->where(function($globalQuery) use ($tags) {
                    foreach ($tags as $tag) {
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

                $books->where(function($q) use ($translatedKeys, $queryText) {

                    foreach ($translatedKeys as $key) {
                        $q->orWhere('genere', 'LIKE', "%{$key}%");
                    }

                    $q->orWhereHas('autor', fn($a) => $a->where('nom', 'LIKE', "%{$queryText}%"))
                      ->orWhereHas('editorial', fn($e) => $e->where('nom', 'LIKE', "%{$queryText}%"));
                });
            }
        } else {

            $books->where(function($q) use ($translatedKeys) {
                foreach ($translatedKeys as $key) {
                    $q->orWhere('titol', 'LIKE', "%{$key}%");
                }
            });
        }

        // Ordenació
        switch ($sort) {
            case 'preu_asc': $books->orderBy('preu', 'asc'); break;
            case 'preu_desc': $books->orderBy('preu', 'desc'); break;
            case 'newest': $books->latest(); break;
            case 'relevance': 
            default: $books->orderByDesc('ressenyes_avg_puntuacio'); break;
        }

        return response()->json($books->limit(20)->get());
    }

    //Funció de traducció per buscar a la base de dades
    private function getTranslationKeys($term)
    {
        if (empty($term)) return [];

        $foundKeys = [];
        $locales = ['ca', 'es', 'en', 'ja']; 

        foreach ($locales as $locale) {
            $path = lang_path("$locale.json");
            
            if (File::exists($path)) {
                $translations = json_decode(File::get($path), true);
                
                if ($translations) {
                    foreach ($translations as $key => $value) {
                       
                        if (stripos($value, $term) !== false) {
                            $foundKeys[] = $key; 
                        }
                    }
                }
            }
        }

        return array_unique($foundKeys);
    }
}