<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Autor;
use Illuminate\Http\Request;

class AutorController extends Controller
{

    public function index(Request $request)
    {
        $query = Autor::query();


        if ($request->has('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

  
        $autors = $query->with('llibres')
            ->withCount('llibres')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.autors.index', compact('autors'));
    }


    public function create()
    {
        return view('admin.autors.create');
    }


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


    public function edit(Autor $autor)
    {
        $autor->load('llibres');

        $altresAutors = Autor::where('id', '!=', $autor->id)->orderBy('nom')->get();

        
        $llibresExterns = \App\Models\Llibre::where('autor_id', '!=', $autor->id)
            ->orderBy('titol')
            ->get();
        return view('admin.autors.edit', compact('autor', 'altresAutors', 'llibresExterns'));
    }


    public function assignarLlibre(Request $request, Autor $autor)
    {
        if ($request->origen === 'nou') {

            return redirect()->route('admin.llibres.create', ['autor_id' => $autor->id]);
        }

       
        $request->validate([
            'llibre_id' => 'required|exists:llibres,id_llibre', 
        ]);

        $llibre = \App\Models\Llibre::findOrFail($request->llibre_id);
        $llibre->update(['autor_id' => $autor->id]);

        return back()->with('success', 'Llibre assignat correctament a l\'autor.');
    }


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

    public function destroy(Request $request, Autor $autor)
    {
    
        $accio = $request->input('accio_llibres');

        if ($accio === 'anonim') {

            $anonim = Autor::firstOrCreate(
                ['nom' => 'Anònim'],
                ['biografia' => 'Autor genèric del sistema per a llibres orfes.']
            );

          
            $autor->llibres()->update(['autor_id' => $anonim->id]);

            $missatge = "Autor eliminat. Els seus llibres s'han mogut a 'Anònim'.";
        } else {
            
            $missatge = "Autor i tots els seus llibres eliminats definitivament.";
        }

        $autor->delete();

        return redirect()->route('admin.autors.index')
            ->with('success', $missatge);
    }

    

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
