<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editorial extends Model
{
    use HasFactory;

    // Definim la taula per evitar problemes de pluralització
    protected $table = 'editorials';

    protected $fillable = ['nom', 'descripcio'];

    // Relació: Una editorial té molts llibres
    public function llibres()
    {
        return $this->hasMany(Llibre::class, 'editorial_id', 'id');
    }
}