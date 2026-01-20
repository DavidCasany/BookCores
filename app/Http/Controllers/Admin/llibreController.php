<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Llibre;
use App\Models\Autor;
use App\Models\Editorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LlibreController extends Controller
{
    // 1. LLISTA DE LLIBRES
    public function index()
    {
        $llibres = Llibre::with(['autor', 'editorial'])
            ->latest('created_at')
            ->paginate(10);

        return view('admin.llibres.index', compact('llibres'));
    }

    // 2. FORMULARI DE CREAR
    public function create(Request $request)
    {
        $autors = Autor::orderBy('nom')->get();
        $editorials = Editorial::orderBy('nom')->get();
        
        // Si venim des de la fitxa d'una editorial, la pre-seleccionem
        $editorialPreseleccionada = $request->get('editorial_id');

        return view('admin.llibres.create', compact('autors', 'editorials', 'editorialPreseleccionada'));
    }

    // 3. GUARDAR NOU LLIBRE
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
            'img_portada' => 'required|image|max:2048', // Obligatori al crear
            'fitxer_pdf' => 'required|mimes:pdf|max:10000', // Obligatori al crear
        ]);

        // Pugem fitxers
        $rutaImatge = $request->file('img_portada')->store('portades', 'public');
        
        $nomPdf = time() . '_' . $request->file('fitxer_pdf')->getClientOriginalName();
        $request->file('fitxer_pdf')->storeAs('pdfs', $nomPdf); // Es guarda a storage/app/pdfs

        Llibre::create([
            'titol' => $request->titol,
            'autor_id' => $request->autor_id,
            'editorial_id' => $request->editorial_id,
            'preu' => $request->preu,
            'pagines' => $request->pagines,
            'genere' => $request->genere,
            'descripcio' => $request->descripcio,
            'img_portada' => $rutaImatge,
            'fitxer_pdf' => $nomPdf,
        ]);

        return redirect()->route('admin.llibres.index')->with('success', 'Llibre publicat correctament!');
    }

    // 4. FORMULARI D'EDITAR (RETOCAR TOT)
    public function edit(Llibre $llibre)
    {
        $autors = Autor::orderBy('nom')->get();
        $editorials = Editorial::orderBy('nom')->get();

        return view('admin.llibres.edit', compact('llibre', 'autors', 'editorials'));
    }

    // 5. ACTUALITZAR LLIBRE
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
            'img_portada' => 'nullable|image|max:2048', // Opcional al editar
            'fitxer_pdf' => 'nullable|mimes:pdf|max:10000', // Opcional al editar
        ]);

        $dades = $request->except(['img_portada', 'fitxer_pdf']);

        // GESTIÓ D'IMATGE NOVA
        if ($request->hasFile('img_portada')) {
            // Esborrem la vella si existeix
            if ($llibre->img_portada && Storage::disk('public')->exists($llibre->img_portada)) {
                Storage::disk('public')->delete($llibre->img_portada);
            }
            $dades['img_portada'] = $request->file('img_portada')->store('portades', 'public');
        }

        // GESTIÓ DE PDF NOU
        if ($request->hasFile('fitxer_pdf')) {
            // Esborrem el vell (a la carpeta local)
            if ($llibre->fitxer_pdf && file_exists(storage_path('app/pdfs/' . $llibre->fitxer_pdf))) {
                unlink(storage_path('app/pdfs/' . $llibre->fitxer_pdf));
            }
            
            $nomPdf = time() . '_' . $request->file('fitxer_pdf')->getClientOriginalName();
            $request->file('fitxer_pdf')->storeAs('pdfs', $nomPdf);
            $dades['fitxer_pdf'] = $nomPdf;
        }

        $llibre->update($dades);

        return redirect()->route('admin.llibres.index')->with('success', 'Llibre actualitzat correctament.');
    }

    // 6. ELIMINAR LLIBRE
    public function destroy(Llibre $llibre)
    {
        // Neteja de fitxers
        if ($llibre->img_portada) {
            Storage::disk('public')->delete($llibre->img_portada);
        }
        if ($llibre->fitxer_pdf && file_exists(storage_path('app/pdfs/' . $llibre->fitxer_pdf))) {
            unlink(storage_path('app/pdfs/' . $llibre->fitxer_pdf));
        }

        $llibre->delete();

        return redirect()->route('admin.llibres.index')->with('success', 'Llibre eliminat.');
    }
}