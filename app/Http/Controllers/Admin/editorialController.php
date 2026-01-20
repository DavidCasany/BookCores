<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Editorial;
use App\Models\Llibre;
use Illuminate\Http\Request;

class EditorialController extends Controller
{

public function index(Request $request)
{
    
    $query = Editorial::withCount('llibres');

    
    if ($request->has('search') && $request->search != '') {
        $cerca = $request->search;
        $query->where(function($q) use ($cerca) {
            $q->where('nom', 'like', "%{$cerca}%")
              ->orWhere('descripcio', 'like', "%{$cerca}%");
        });
    }

    
    $editorials = $query->paginate(10)->withQueryString();

    return view('admin.editorials.index', compact('editorials'));
}

    
    public function create()
    {
        return view('admin.editorials.create');
    }

    
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

  
    public function edit(Editorial $editorial)
    {
        $editorial->load('llibres');
        $altresEditorials = Editorial::where('id', '!=', $editorial->id)->get();
        $llibresExterns = Llibre::where('editorial_id', '!=', $editorial->id)->get();

        return view('admin.editorials.edit', compact('editorial', 'altresEditorials', 'llibresExterns'));
    }

   
    public function update(Request $request, Editorial $editorial)
    {
        $request->validate(['nom' => 'required', 'descripcio' => 'nullable']);
        $editorial->update($request->all());
        return back()->with('success', 'Dades actualitzades.');
    }

    public function destroy(Request $request, Editorial $editorial)
    {
        if ($editorial->llibres()->count() > 0) {
            
            $request->validate([
                'accio_llibres' => 'required|in:esborrar,autopublicar'
            ]);

            if ($request->accio_llibres === 'esborrar') {
                
                $editorial->llibres()->delete();
                
            } elseif ($request->accio_llibres === 'autopublicar') {
                
                $autopublicat = Editorial::where('nom', 'Autopublicat')->first();
                
                if ($autopublicat) {
                    $editorial->llibres()->update(['editorial_id' => $autopublicat->id]);
                } else {
                    
                    $editorial->llibres()->update(['editorial_id' => 1]);
                }
            }
        }

        $editorial->delete();
        return redirect()->route('admin.editorials.index')->with('success', 'Editorial eliminada.');
    }

   

    public function desvincularLlibre(Request $request, Llibre $llibre)
    {
        $request->validate([
            'accio' => 'required|in:esborrar,transferir,autopublicar',
            'target_editorial_id' => 'required_if:accio,transferir|exists:editorials,id'
        ]);

        
        if ($request->accio === 'esborrar') {
            $llibre->delete();
            return back()->with('success', 'Llibre eliminat definitivament.');
        }

        
        if ($request->accio === 'autopublicar') {
            $autopublicat = Editorial::where('nom', 'Autopublicat')->first();
            $targetId = $autopublicat ? $autopublicat->id : 1;
            
            $llibre->update(['editorial_id' => $targetId]);
            return back()->with('success', 'Llibre mogut a Autopublicats.');
        }

        
        if ($request->accio === 'transferir') {
            $llibre->update(['editorial_id' => $request->target_editorial_id]);
            return back()->with('success', 'Llibre transferit d\'editorial.');
        }
    }

   
    public function afegirLlibre(Request $request, Editorial $editorial)
    {
        $request->validate([
            'origen' => 'required|in:nou,existent',
            'llibre_id' => 'required_if:origen,existent|exists:llibres,id_llibre'
        ]);

        if ($request->origen === 'nou') {
            
            return redirect()->route('admin.llibres.create', ['editorial_id' => $editorial->id]);
        }

        if ($request->origen === 'existent') {
            $llibre = Llibre::find($request->llibre_id);
            $llibre->update(['editorial_id' => $editorial->id]);
            return back()->with('success', 'Llibre afegit al catàleg.');
        }
    }
}