<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Llibre;
use App\Models\Compra;
use Illuminate\Support\Facades\Auth;

class CistellaController extends Controller
{
    // MOSTRAR LA CISTELLA
    public function index()
    {
        // 🟢 CANVI: Filtrem per estat 'en_proces'
        $cistella = Compra::where('user_id', Auth::id())
                          ->where('estat', 'en_proces') 
                          ->with('llibres')
                          ->latest()
                          ->first();

        return view('cistella.index', compact('cistella'));
    }

    // AFEGIR LLIBRE
    public function afegir($llibre_id)
    {
        $user = Auth::user();
        $llibre = Llibre::findOrFail($llibre_id);

        // 1. Busquem l'última cistella OBERTA de l'usuari
        // 🟢 CANVI: Filtrem per estat 'en_proces'
        $cistella = Compra::where('user_id', $user->id)
                          ->where('estat', 'en_proces')
                          ->latest()
                          ->first();

        // Si no en té cap, en creem una de nova
        if (!$cistella) {
            $cistella = Compra::create([
                'user_id' => $user->id,
                'total' => 0,
                'estat' => 'en_proces' // Forcem l'estat per seguretat
            ]);
        }

        // 2. Comprovem si el llibre ja és a la cistella
        $llibreALaCistella = $cistella->llibres()->where('compra_llibre.llibre_id', $llibre_id)->first();

        if ($llibreALaCistella) {
            // Si ja hi és, incrementem la quantitat
            $cistella->llibres()->updateExistingPivot($llibre_id, [
                'quantitat' => $llibreALaCistella->pivot->quantitat + 1
            ]);
        } else {
            // Si no hi és, l'afegim
            $cistella->llibres()->attach($llibre_id, [
                'quantitat' => 1,
                'preu_unitari' => $llibre->preu
            ]);
        }

        $this->recalcularTotal($cistella);

        return redirect()->back()->with('success', 'Llibre afegit correctament!');
    }

    // ELIMINAR LLIBRE
    public function eliminar($llibre_id)
    {
        // 🟢 CANVI: Filtrem per estat 'en_proces'
        $cistella = Compra::where('user_id', Auth::id())
                          ->where('estat', 'en_proces')
                          ->latest()
                          ->first();
        
        if ($cistella) {
            $cistella->llibres()->detach($llibre_id);
            $this->recalcularTotal($cistella);
        }

        return redirect()->route('cistella.index');
    }

    // ACTUALITZAR QUANTITAT
    public function actualitzarQuantitat(Request $request, $llibre_id)
    {
        // 🟢 CANVI: Filtrem per estat 'en_proces'
        $cistella = Compra::where('user_id', Auth::id())
                          ->where('estat', 'en_proces')
                          ->latest()
                          ->first();
        
        if (!$cistella) return redirect()->back();

        $quantitat = $request->input('quantitat');

        if ($quantitat <= 0) {
            return $this->eliminar($llibre_id);
        }

        $cistella->llibres()->updateExistingPivot($llibre_id, [
            'quantitat' => $quantitat
        ]);

        $this->recalcularTotal($cistella);

        return redirect()->route('cistella.index');
    }

    // Recalcular el total
    private function recalcularTotal($cistella)
    {
        $cistella->load('llibres');
        $nouTotal = 0;
        foreach ($cistella->llibres as $item) {
            $nouTotal += $item->pivot->quantitat * $item->pivot->preu_unitari;
        }
        $cistella->total = $nouTotal;
        $cistella->save();
    }
}