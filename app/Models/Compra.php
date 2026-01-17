<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    // 1. Definim la taula i la clau primària (important per evitar errors de noms)
    protected $table = 'compres';
    protected $primaryKey = 'id_compra';

    // 2. Camps que es poden omplir massivament (necessari per al firstOrCreate)
    protected $fillable = ['total', 'user_id', 'estat'];

    // 3. RELACIÓ CLAU: Una compra té molts llibres
    public function llibres()
    {
        // Relació Many-to-Many
        // Paràmetres: (Model, Taula Pivot, Clau Local, Clau Forana)
        return $this->belongsToMany(Llibre::class, 'compra_llibre', 'compra_id', 'llibre_id')
                    ->withPivot('quantitat', 'preu_unitari') // Camps extra de la taula pivot
                    ->withTimestamps();
    }

    // Opcional: Relació amb l'usuari (per si vols saber de qui és la compra)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // MOSTRAR LA BIBLIOTECA (Llibres comprats)
    public function biblioteca()
    {
        // 1. Busquem totes les compres PAGADES de l'usuari
        $compres = Compra::where('user_id', \Illuminate\Support\Facades\Auth::id())
                         ->where('estat', 'pagat')
                         ->with('llibres.autor') // Carreguem llibres i autors
                         ->get();

        // 2. Extraiem els llibres i eliminem duplicats (per si n'ha comprat un dos cops)
        // Utilitzem flatMap per ajuntar totes les col·leccions de llibres en una de sola
        $llibres = $compres->flatMap(function ($compra) {
            return $compra->llibres;
        })->unique('id_llibre');

        return view('biblioteca.index', compact('llibres'));
    }
}