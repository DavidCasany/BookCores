<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Llibre;
use App\Models\Autor;
use App\Models\Editorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LlibreController extends Controller
{
    public function index()
    {
        $llibres = Llibre::with(['autor', 'editorial'])
            ->latest('created_at')
            ->paginate(10);
        return view('admin.llibres.index', compact('llibres'));
    }

    public function create(Request $request)
    {
        $autors = Autor::orderBy('nom')->get();
        $editorials = Editorial::orderBy('nom')->get();
        $editorialPreseleccionada = $request->get('editorial_id');

        return view('admin.llibres.create', compact('autors', 'editorials', 'editorialPreseleccionada'));
    }

    // --- GUARDAR (MANERA ANTIGA: NOMÉS NOM FITXER) ---
    public function store(Request $request)
    {
        $request->validate([
            'titol' => 'required|max:255',
            'autor_id' => 'required|exists:autors,id',
            'editorial_id' => 'required|exists:editorials,id',
            'preu' => 'required|numeric|min:0',
            'pagines' => 'required|integer',
            'genere' => 'required|string',
            'descripcio' => 'nullable|string',
            'img_portada' => 'required|image|max:2048',
            'img_hero'    => 'nullable|image|max:4096',
            'fitxer_pdf' => 'required|mimes:pdf|max:10000',
        ]);

        // 1. PORTADA -> Guardem només "nom.jpg"
        $filePortada = $request->file('img_portada');
        $nomPortada = time() . '_' . $filePortada->getClientOriginalName();
        $filePortada->move(public_path('img'), $nomPortada);

        // 2. HERO -> Guardem només "nom.jpg"
        $nomHero = null;
        if ($request->hasFile('img_hero')) {
            $fileHero = $request->file('img_hero');
            $nomHero = time() . '_hero_' . $fileHero->getClientOriginalName();
            $fileHero->move(public_path('img'), $nomHero);
        }

        // 3. PDF (A storage/app/pdfs - Es manté privat)
        $nomPdf = time() . '_' . $request->file('fitxer_pdf')->getClientOriginalName();
        $request->file('fitxer_pdf')->storeAs('pdfs', $nomPdf);

        Llibre::create([
            'titol' => $request->titol,
            'autor_id' => $request->autor_id,
            'editorial_id' => $request->editorial_id,
            'preu' => $request->preu,
            'pagines' => $request->pagines,
            'genere' => $request->genere,
            'descripcio' => $request->descripcio,
            'img_portada' => $nomPortada, // SENSE 'img/'
            'img_hero'    => $nomHero,    // SENSE 'img/'
            'fitxer_pdf' => $nomPdf,
        ]);

        return redirect()->route('admin.llibres.index')->with('success', 'Llibre publicat correctament!');
    }

    public function edit(Llibre $llibre)
    {
        $autors = Autor::orderBy('nom')->get();
        $editorials = Editorial::orderBy('nom')->get();
        return view('admin.llibres.edit', compact('llibre', 'autors', 'editorials'));
    }

    // --- ACTUALITZAR (MANERA ANTIGA) ---
    public function update(Request $request, Llibre $llibre)
    {
        $request->validate([
            'titol' => 'required|max:255',
            'autor_id' => 'required',
            'editorial_id' => 'required',
            'preu' => 'required|numeric',
            'pagines' => 'required|integer',
            'genere' => 'required',
            'descripcio' => 'nullable|string',
            'img_portada' => 'nullable|image|max:2048',
            'img_hero'    => 'nullable|image|max:4096',
            'fitxer_pdf' => 'nullable|mimes:pdf|max:10000',
        ]);

        $dades = $request->except(['img_portada', 'fitxer_pdf', 'img_hero']);

        // ACTUALITZAR PORTADA
        if ($request->hasFile('img_portada')) {
            // Esborrem la vella (Afegim 'img/' manualment per trobar-la)
            if ($llibre->img_portada && File::exists(public_path('img/' . $llibre->img_portada))) {
                File::delete(public_path('img/' . $llibre->img_portada));
            }
            $file = $request->file('img_portada');
            $nom = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $nom);
            $dades['img_portada'] = $nom; // Només el nom
        }

        // ACTUALITZAR HERO
        if ($request->hasFile('img_hero')) {
            if ($llibre->img_hero && File::exists(public_path('img/' . $llibre->img_hero))) {
                File::delete(public_path('img/' . $llibre->img_hero));
            }
            $file = $request->file('img_hero');
            $nom = time() . '_hero_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $nom);
            $dades['img_hero'] = $nom; // Només el nom
        }

        // ACTUALITZAR PDF
        if ($request->hasFile('fitxer_pdf')) {
            if ($llibre->fitxer_pdf && file_exists(storage_path('app/pdfs/' . $llibre->fitxer_pdf))) {
                unlink(storage_path('app/pdfs/' . $llibre->fitxer_pdf));
            }
            $nomPdf = time() . '_' . $request->file('fitxer_pdf')->getClientOriginalName();
            $request->file('fitxer_pdf')->storeAs('pdfs', $nomPdf);
            $dades['fitxer_pdf'] = $nomPdf;
        }

        $llibre->update($dades);

        return redirect()->route('admin.llibres.index')->with('success', 'Llibre actualitzat!');
    }

    // --- ELIMINAR (MANERA ANTIGA) ---
    public function destroy(Llibre $llibre)
    {
        // Afegim 'img/' manualment al path per esborrar
        if ($llibre->img_portada && File::exists(public_path('img/' . $llibre->img_portada))) {
            File::delete(public_path('img/' . $llibre->img_portada));
        }
        if ($llibre->img_hero && File::exists(public_path('img/' . $llibre->img_hero))) {
            File::delete(public_path('img/' . $llibre->img_hero));
        }

        if ($llibre->fitxer_pdf && file_exists(storage_path('app/pdfs/' . $llibre->fitxer_pdf))) {
            unlink(storage_path('app/pdfs/' . $llibre->fitxer_pdf));
        }

        $llibre->delete();
        return redirect()->route('admin.llibres.index')->with('success', 'Llibre eliminat.');
    }
}