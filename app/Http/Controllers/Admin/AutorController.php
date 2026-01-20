<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Autor;
use Illuminate\Http\Request;

class AutorController extends Controller
{
    /**
     * Llistar autors (amb cerca inclosa)
     */
    public function index(Request $request)
    {
        // Iniciem la consulta
        $query = Autor::query();

        // Si hi ha alguna cosa al buscador, filtrem pel nom
        if ($request->has('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        // Recuperem els autors amb la llista de llibres carregada (per mostrar-los o comptar-los)
        // I paginem els resultats (10 per pàgina)
        $autors = $query->with('llibres')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.autors.index', compact('autors'));
    }

    /**
     * Eliminar un autor
     */
    public function destroy(Autor $autor)
    {
        // La base de dades ja s'encarrega d'esborrar els llibres (Cascade)
        // així que només hem d'esborrar l'autor.
        $autor->delete();

        return redirect()->route('admin.autors.index')
            ->with('success', 'Autor i els seus llibres eliminats correctament.');
    }
    
    /**
     * Mostrar el formulari per crear un nou autor.
     */
    public function create()
    {
        return view('admin.autors.create');
    }

    /**
     * Guardar el nou autor a la base de dades.
     */
    public function store(Request $request)
    {
        // 1. Validem les dades
        $request->validate([
            'nom' => 'required|string|max:255|unique:autors,nom', // Nom obligatori i únic
            // Si tens camp descripció a la BD, descomenta la línia de sota:
            // 'biografia' => 'nullable|string', 
        ], [
            'nom.required' => 'El nom de l\'autor és obligatori.',
            'nom.unique' => 'Aquest autor ja existeix a la base de dades.',
        ]);

        // 2. Creem l'autor
        Autor::create([
            'nom' => $request->nom,
            // 'biografia' => $request->biografia, // Si en tens
        ]);

        // 3. Redirigim amb missatge d'èxit
        return redirect()->route('admin.autors.index')
            ->with('success', 'Autor creat correctament!');
    }
}