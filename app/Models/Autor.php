<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    use HasFactory;

    // IMPORTANT: Definim la taula explícitament per evitar errors de pluralització
    protected $table = 'autors';

    protected $fillable = ['nom', 'biografia', 'user_id'];

    // Relació: Un autor té molts llibres
    public function llibres()
    {
        // 1r paràmetre: Model fill (Llibre)
        // 2n paràmetre: Clau forana a la taula 'llibres' (autor_id)
        // 3r paràmetre: Clau primària a la taula 'autors' (id)
        return $this->hasMany(Llibre::class, 'autor_id', 'id');
    }
}