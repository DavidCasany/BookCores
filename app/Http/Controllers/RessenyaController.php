<?php

namespace App\Http\Controllers;

use App\Models\Ressenya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RessenyaController extends Controller
{
    public function store(Request $request)
    {
        // Validem les dades
        $request->validate([
            'text' => 'required|string|max:1000',
            'llibre_id' => 'required|exists:llibres,id_llibre',
            'puntuacio' => 'nullable|integer|min:1|max:5',
            'resposta_a_id' => 'nullable|exists:ressenyes,id', 
        ]);

        // Guardem a la BD
        Ressenya::create([
            'user_id' => Auth::id(),
            'llibre_id' => $request->llibre_id,
            'text' => $request->text,
            'puntuacio' => $request->puntuacio,
            'resposta_a_id' => $request->resposta_a_id,
        ]);

        return back()->with('success', 'Comentari publicat!');
    }
}