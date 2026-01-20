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
    // 1. LLISTA DE LLIBRES (ADMIN)
    public function index()
    {
        // Carreguem amb relacions per evitar 1000 consultes a la BD
        $llibres = Llibre::with(['autor', 'editorial'])
            ->latest() // Els més nous primer
            ->paginate(10);

        return view('admin.llibres.index', compact('llibres'));
    }

    // 2. FORMULARI DE CREAR
    public function create(Request $request)
    {
        $autors = Autor::orderBy('nom')->get();
        $editorials = Editorial::orderBy('nom')->get();
        
        // Si venim des de la pàgina d'una editorial, ja la deixem seleccionada
        $editorialPreseleccionada = $request->get('editorial_id');

        return view('admin.llibres.create', compact('autors', 'editorials', 'editorialPreseleccionada'));
    }

    // 3. GUARDAR A LA BASE DE DADES
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
            // Validació de fitxers
            'img_portada' => 'required|image|max:2048', // Màx 2MB
            'fitxer_pdf' => 'required|mimes:pdf|max:10000', // Màx 10MB
        ]);

        // Pugem la IMATGE a la carpeta pública
        $rutaImatge = $request->file('img_portada')->store('portades', 'public');

        // Pugem el PDF a la carpeta PRIVADA (storage/app/pdfs)
        // Així ningú el pot descarregar sense passar pel teu controlador "llegir"
        $nomPdf = time() . '_' . $request->file('fitxer_pdf')->getClientOriginalName();
        $request->file('fitxer_pdf')->storeAs('pdfs', $nomPdf); // Guarda a storage/app/pdfs

        Llibre::create([
            'titol' => $request->titol,
            'autor_id' => $request->autor_id,
            'editorial_id' => $request->editorial_id,
            'preu' => $request->preu,
            'pagines' => $request->pagines,
            'genere' => $request->genere,
            'descripcio' => $request->descripcio,
            'img_portada' => $rutaImatge, // Guardem la ruta "portades/foto.jpg"
            'fitxer_pdf' => $nomPdf,       // Guardem només el nom "123_llibre.pdf"
        ]);

        return redirect()->route('admin.llibres.index')->with('success', 'Llibre publicat correctament!');
    }

    // 4. FORMULARI D'EDITAR
    public function edit(Llibre $llibre)
    {
        $autors = Autor::orderBy('nom')->get();
        $editorials = Editorial::orderBy('nom')->get();

        return view('admin.llibres.edit', compact('llibre', 'autors', 'editorials'));
    }

    // 5. ACTUALITZAR
    public function update(Request $request, Llibre $llibre)
    {
        $request->validate([
            'titol' => 'required|max:255',
            'autor_id' => 'required',
            'editorial_id' => 'required',
            'preu' => 'required|numeric',
            'pagines' => 'required|integer',
            'genere' => 'required',
            'img_portada' => 'nullable|image|max:2048', // Opcional en update
            'fitxer_pdf' => 'nullable|mimes:pdf|max:10000', // Opcional en update
        ]);

        $dades = $request->except(['img_portada', 'fitxer_pdf']);

        // Si pugem nova IMATGE
        if ($request->hasFile('img_portada')) {
            // Esborrem la vella
            if ($llibre->img_portada) {
                Storage::disk('public')->delete($llibre->img_portada);
            }
            $dades['img_portada'] = $request->file('img_portada')->store('portades', 'public');
        }

        // Si pugem nou PDF
        if ($request->hasFile('fitxer_pdf')) {
            // Esborrem el vell
            if ($llibre->fitxer_pdf) {
                // Nota: Com que els PDFs estan a 'local' (storage/app/pdfs), usem el disk per defecte o path manual
                if (file_exists(storage_path('app/pdfs/' . $llibre->fitxer_pdf))) {
                    unlink(storage_path('app/pdfs/' . $llibre->fitxer_pdf));
                }
            }
            $nomPdf = time() . '_' . $request->file('fitxer_pdf')->getClientOriginalName();
            $request->file('fitxer_pdf')->storeAs('pdfs', $nomPdf);
            $dades['fitxer_pdf'] = $nomPdf;
        }

        $llibre->update($dades);

        return redirect()->route('admin.llibres.index')->with('success', 'Llibre actualitzat!');
    }

    // 6. ELIMINAR
    public function destroy(Llibre $llibre)
    {
        // 1. Esborrem fitxers físics per no deixar brossa al servidor
        if ($llibre->img_portada) {
            Storage::disk('public')->delete($llibre->img_portada);
        }
        if ($llibre->fitxer_pdf && file_exists(storage_path('app/pdfs/' . $llibre->fitxer_pdf))) {
            unlink(storage_path('app/pdfs/' . $llibre->fitxer_pdf));
        }

        // 2. Esborrem de la BD
        $llibre->delete();

        return redirect()->route('admin.llibres.index')->with('success', 'Llibre eliminat.');
    }
}