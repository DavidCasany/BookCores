<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Llibre;
use App\Models\Compra;
use Illuminate\Support\Facades\Auth;

class CistellaController extends Controller
{
    public function afegir($llibre_id)
    {
        $user = Auth::user();

        // 1. Busquem o creem una cistella oberta (estat 'oberta' o similar)
        // Per simplificar, busquem l'última compra o en creem una de nova
        $cistella = Compra::firstOrCreate(
            ['user_id' => $user->id, 'total' => 0], // Condició de cerca (pots millorar-la amb un camp 'status')
            ['created_at' => now(), 'updated_at' => now()]
        );

        // 2. Afegim el llibre a la taula pivot
        $llibre = Llibre::findOrFail($llibre_id);
        
        // El mètode 'llibres()' ha d'estar definit al model Compra
        // attach accepta l'ID i un array amb els camps extra de la taula pivot
        $cistella->llibres()->attach($llibre_id, [
            'quantitat' => 1,
            'preu_unitari' => $llibre->preu
        ]);

        // 3. Actualitzem el total de la compra (opcional, es pot calcular dinàmicament)
        $cistella->total += $llibre->preu;
        $cistella->save();

        return redirect()->back()->with('success', 'Llibre afegit a la cistella correctament!');
    }
}