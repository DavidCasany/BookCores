<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Llibre;
use App\Models\Compra;
use Illuminate\Support\Facades\Auth;

class CistellaController extends Controller
{
    // Mostrar cistella
    public function index()
    {
        $cistella = Compra::where('user_id', Auth::id())
                          ->where('estat', 'en_proces') 
                          ->with('llibres')
                          ->latest()
                          ->first();

        return view('cistella.index', compact('cistella'));
    }

    public function afegir($llibre_id)
    {
        $user = Auth::user();
        $llibre = Llibre::findOrFail($llibre_id);
        $cistella = Compra::where('user_id', $user->id)
                          ->where('estat', 'en_proces')
                          ->latest()
                          ->first();

  
        if (!$cistella) {
            $cistella = Compra::create([
                'user_id' => $user->id,
                'total' => 0,
                'estat' => 'en_proces' // Forcem l'estat per seguretat
            ]);
        }
        $llibreALaCistella = $cistella->llibres()->where('compra_llibre.llibre_id', $llibre_id)->first();

        if ($llibreALaCistella) {
            $cistella->llibres()->updateExistingPivot($llibre_id, [
                'quantitat' => $llibreALaCistella->pivot->quantitat + 1
            ]);
        } else {
            $cistella->llibres()->attach($llibre_id, [
                'quantitat' => 1,
                'preu_unitari' => $llibre->preu
            ]);
        }

        $this->recalcularTotal($cistella);
        return redirect()->back()->with('success', 'Llibre afegit correctament!');
    }


    public function eliminar($llibre_id)
    {
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


    public function actualitzarQuantitat(Request $request, $llibre_id)
    {
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