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
        $query = Autor::query();

        // Filtre de cerca
        if ($request->has('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        // Carreguem 'llibres' per mostrar els títols a la taula
        // i 'withCount' per saber el total de forma eficient
        $autors = $query->with('llibres')
            ->withCount('llibres')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.autors.index', compact('autors'));
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
        $request->validate([
            'nom' => 'required|string|max:255|unique:autors,nom',
            'biografia' => 'nullable|string',
        ], [
            'nom.required' => 'El nom de l\'autor és obligatori.',
            'nom.unique' => 'Aquest autor ja existeix a la base de dades.',
        ]);

        Autor::create([
            'nom' => $request->nom,
            'biografia' => $request->biografia,
        ]);

        return redirect()->route('admin.autors.index')
            ->with('success', 'Autor creat correctament!');
    }

    /**
     * Mostrar el formulari d'edició i els llibres de l'autor.
     */
    public function edit(Autor $autor)
    {
        $autor->load('llibres');

        // Altres autors (per transferir)
        $altresAutors = Autor::where('id', '!=', $autor->id)->orderBy('nom')->get();

        // Llibres que NO són d'aquest autor (per assignar-los-hi)
        $llibresExterns = \App\Models\Llibre::where('autor_id', '!=', $autor->id)
            ->orderBy('titol')
            ->get();

        return view('admin.autors.edit', compact('autor', 'altresAutors', 'llibresExterns'));
    }

    // ... (resta de mètodes destroy, update...)

    // --- NOU MÈTODE PER ASSIGNAR ---
    public function assignarLlibre(Request $request, Autor $autor)
    {
        if ($request->origen === 'nou') {
            // Si vol crear un llibre nou, el redirigim al formulari de crear llibre
            // passant-li l'ID de l'autor per pre-seleccionar-lo (ho farem al Bloc 4)
            return redirect()->route('admin.llibres.create', ['autor_id' => $autor->id]);
        }

        // Si vol assignar un existent
        $request->validate([
            'llibre_id' => 'required|exists:llibres,id_llibre', // o 'id' segons la teva taula
        ]);

        $llibre = \App\Models\Llibre::findOrFail($request->llibre_id);
        $llibre->update(['autor_id' => $autor->id]);

        return back()->with('success', 'Llibre assignat correctament a l\'autor.');
    }

    /**
     * Actualitzar les dades de l'autor.
     */
    public function update(Request $request, Autor $autor)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:autors,nom,' . $autor->id,
            'biografia' => 'nullable|string',
        ]);

        $autor->update([
            'nom' => $request->nom,
            'biografia' => $request->biografia,
        ]);

        return redirect()->route('admin.autors.index')
            ->with('success', 'Dades de l\'autor actualitzades.');
    }

    /**
     * Eliminar un autor (AMB LÒGICA AVANÇADA).
     * Ara rep el Request per saber què fer amb els llibres.
     */
    public function destroy(Request $request, Autor $autor)
    {
        // Recuperem l'opció triada al desplegable de la vista
        $accio = $request->input('accio_llibres');

        if ($accio === 'anonim') {
            // OPCIÓ A: Salvar els llibres movent-los a "Anònim"
            $anonim = Autor::firstOrCreate(
                ['nom' => 'Anònim'],
                ['biografia' => 'Autor genèric del sistema per a llibres orfes.']
            );

            // Actualitzem massivament els llibres d'aquest autor
            $autor->llibres()->update(['autor_id' => $anonim->id]);

            $missatge = "Autor eliminat. Els seus llibres s'han mogut a 'Anònim'.";
        } else {
            // OPCIÓ B: Esborrar-ho tot (per defecte)
            // La base de dades (cascadeOnDelete) s'encarrega d'esborrar els llibres
            $missatge = "Autor i tots els seus llibres eliminats definitivament.";
        }

        // Finalment eliminem l'autor
        $autor->delete();

        return redirect()->route('admin.autors.index')
            ->with('success', $missatge);
    }

    // --- GESTIÓ DE LLIBRES DES DE L'EDITOR D'AUTOR ---

    public function destroyLlibre($id_llibre)
    {
        $llibre = \App\Models\Llibre::findOrFail($id_llibre);
        $llibre->delete();

        return back()->with('success', 'Llibre eliminat definitivament.');
    }

    public function moureAAnonim($id_llibre)
    {
        $llibre = \App\Models\Llibre::findOrFail($id_llibre);
        $anonim = Autor::firstOrCreate(['nom' => 'Anònim']);

        $llibre->update(['autor_id' => $anonim->id]);

        return back()->with('success', 'Llibre mogut a "Anònim".');
    }

    public function transferirLlibre(Request $request, $id_llibre)
    {
        $request->validate([
            'nou_autor_id' => 'required|exists:autors,id',
        ]);

        $llibre = \App\Models\Llibre::findOrFail($id_llibre);
        $llibre->update(['autor_id' => $request->nou_autor_id]);

        return back()->with('success', 'Llibre transferit correctament.');
    }
}
