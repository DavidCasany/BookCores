<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Llibre;
use App\Models\Autor;
use App\Models\Editorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // <--- IMPRESCINDIBLE per gestionar fitxers públics

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

    // --- GUARDAR NOU LLIBRE ---
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
            'img_hero'    => 'nullable|image|max:4096', // Validació Hero (4MB màx)
            'fitxer_pdf' => 'required|mimes:pdf|max:10000',
        ]);

        // 1. GESTIÓ PORTADA (A public/img)
        $filePortada = $request->file('img_portada');
        $nomPortada = time() . '_' . $filePortada->getClientOriginalName();
        $filePortada->move(public_path('img'), $nomPortada);
        $rutaPortada = 'img/' . $nomPortada;

        // 2. GESTIÓ HERO (A public/img)
        $rutaHero = null;
        if ($request->hasFile('img_hero')) {
            $fileHero = $request->file('img_hero');
            $nomHero = time() . '_hero_' . $fileHero->getClientOriginalName();
            $fileHero->move(public_path('img'), $nomHero);
            $rutaHero = 'img/' . $nomHero;
        }

        // 3. GESTIÓ PDF (Privat a storage/app/pdfs)
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
            'img_portada' => $rutaPortada,
            'img_hero'    => $rutaHero,
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

    // --- ACTUALITZAR LLIBRE ---
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
            // Esborrem la vella
            if ($llibre->img_portada && File::exists(public_path($llibre->img_portada))) {
                File::delete(public_path($llibre->img_portada));
            }
            // Pugem la nova
            $file = $request->file('img_portada');
            $nom = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $nom);
            $dades['img_portada'] = 'img/' . $nom;
        }

        // ACTUALITZAR HERO (NOU)
        if ($request->hasFile('img_hero')) {
            // Esborrem la vella
            if ($llibre->img_hero && File::exists(public_path($llibre->img_hero))) {
                File::delete(public_path($llibre->img_hero));
            }
            // Pugem la nova
            $file = $request->file('img_hero');
            $nom = time() . '_hero_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $nom);
            $dades['img_hero'] = 'img/' . $nom;
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

    // --- ELIMINAR LLIBRE ---
    public function destroy(Llibre $llibre)
    {
        // Esborrar imatges de public/img
        if ($llibre->img_portada && File::exists(public_path($llibre->img_portada))) {
            File::delete(public_path($llibre->img_portada));
        }
        if ($llibre->img_hero && File::exists(public_path($llibre->img_hero))) {
            File::delete(public_path($llibre->img_hero));
        }

        // Esborrar PDF de storage
        if ($llibre->fitxer_pdf && file_exists(storage_path('app/pdfs/' . $llibre->fitxer_pdf))) {
            unlink(storage_path('app/pdfs/' . $llibre->fitxer_pdf));
        }

        $llibre->delete();
        return redirect()->route('admin.llibres.index')->with('success', 'Llibre eliminat.');
    }
}