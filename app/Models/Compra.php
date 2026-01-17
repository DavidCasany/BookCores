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
    protected $fillable = ['total', 'user_id'];

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
}