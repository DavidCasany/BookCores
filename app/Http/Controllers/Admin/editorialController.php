<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Editorial;
use App\Models\Llibre;
use Illuminate\Http\Request;

class EditorialController extends Controller
{
    // 1. LLISTAR
// Assegura't de tenir això a dalt de tot de l'arxiu:
// use Illuminate\Http\Request;
// use App\Models\Editorial;

public function index(Request $request)
{
    // Comencem la consulta comptant els llibres (com ja feies)
    $query = Editorial::withCount('llibres');

    // Si hi ha una cerca, filtrem per nom o descripció
    if ($request->has('search') && $request->search != '') {
        $cerca = $request->search;
        $query->where(function($q) use ($cerca) {
            $q->where('nom', 'like', "%{$cerca}%")
              ->orWhere('descripcio', 'like', "%{$cerca}%");
        });
    }

    // Paginem els resultats i mantenim el text de la cerca als enllaços de pàgina
    $editorials = $query->paginate(10)->withQueryString();

    return view('admin.editorials.index', compact('editorials'));
}

    // 2. CREAR
    public function create()
    {
        return view('admin.editorials.create');
    }

    // 3. GUARDAR
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'descripcio' => 'nullable|string',
        ]);

        $editorial = Editorial::create($request->all());

        return redirect()->route('admin.editorials.edit', $editorial)
            ->with('success', 'Editorial creada correctament.');
    }

    // 4. EDITAR (La vista complexa)
    public function edit(Editorial $editorial)
    {
        $editorial->load('llibres');
        
        // Carreguem altres editorials per poder transferir (totes menys l'actual)
        $altresEditorials = Editorial::where('id', '!=', $editorial->id)->get();
        
        // Carreguem llibres que NO són d'aquesta editorial per poder-los "robar"
        // (Opcional: Si tens milers de llibres, això s'hauria de fer amb AJAX)
        $llibresExterns = Llibre::where('editorial_id', '!=', $editorial->id)->get();

        return view('admin.editorials.edit', compact('editorial', 'altresEditorials', 'llibresExterns'));
    }

    // 5. ACTUALITZAR
    public function update(Request $request, Editorial $editorial)
    {
        $request->validate(['nom' => 'required', 'descripcio' => 'nullable']);
        $editorial->update($request->all());
        return back()->with('success', 'Dades actualitzades.');
    }

    // 6. ELIMINAR EDITORIAL (Amb lògica de seguretat)
    public function destroy(Request $request, Editorial $editorial)
    {
        // Si l'editorial té llibres, hem de decidir què fer
        if ($editorial->llibres()->count() > 0) {
            
            $request->validate([
                'accio_llibres' => 'required|in:esborrar,autopublicar'
            ]);

            if ($request->accio_llibres === 'esborrar') {
                // Opció A: Esborrar-ho tot
                $editorial->llibres()->delete();
                
            } elseif ($request->accio_llibres === 'autopublicar') {
                // Opció B: Moure a Autopublicat (ID 1 o per nom)
                // Busquem per nom per si de cas l'ID canvia en el futur
                $autopublicat = Editorial::where('nom', 'Autopublicat')->first();
                
                if ($autopublicat) {
                    $editorial->llibres()->update(['editorial_id' => $autopublicat->id]);
                } else {
                    // Fallback de seguretat: si no troba 'Autopublicat', intenta l'ID 1
                    $editorial->llibres()->update(['editorial_id' => 1]);
                }
            }
        }

        $editorial->delete();
        return redirect()->route('admin.editorials.index')->with('success', 'Editorial eliminada.');
    }

    // --- FUNCIONS ESPECIALS PER GESTIONAR LLIBRES ---

    // A. Treure un llibre d'aquesta editorial
    public function desvincularLlibre(Request $request, Llibre $llibre)
    {
        $request->validate([
            'accio' => 'required|in:esborrar,transferir,autopublicar',
            'target_editorial_id' => 'required_if:accio,transferir|exists:editorials,id'
        ]);

        // CAS 1: Eliminar el llibre per sempre
        if ($request->accio === 'esborrar') {
            $llibre->delete();
            return back()->with('success', 'Llibre eliminat definitivament.');
        }

        // CAS 2: Moure a Autopublicat
        if ($request->accio === 'autopublicar') {
            $autopublicat = Editorial::where('nom', 'Autopublicat')->first();
            $targetId = $autopublicat ? $autopublicat->id : 1;
            
            $llibre->update(['editorial_id' => $targetId]);
            return back()->with('success', 'Llibre mogut a Autopublicats.');
        }

        // CAS 3: Transferir a una altra editorial concreta
        if ($request->accio === 'transferir') {
            $llibre->update(['editorial_id' => $request->target_editorial_id]);
            return back()->with('success', 'Llibre transferit d\'editorial.');
        }
    }

    // B. Afegir un llibre a aquesta editorial
    public function afegirLlibre(Request $request, Editorial $editorial)
    {
        $request->validate([
            'origen' => 'required|in:nou,existent',
            'llibre_id' => 'required_if:origen,existent|exists:llibres,id_llibre'
        ]);

        if ($request->origen === 'nou') {
            // Redirigim a crear llibre (pre-seleccionant aquesta editorial)
            // Nota: Quan tinguis la ruta de crear llibres, descomenta això:
            return redirect()->route('admin.llibres.create', ['editorial_id' => $editorial->id]);
        }

        if ($request->origen === 'existent') {
            $llibre = Llibre::find($request->llibre_id);
            $llibre->update(['editorial_id' => $editorial->id]);
            return back()->with('success', 'Llibre afegit al catàleg.');
        }
    }
}