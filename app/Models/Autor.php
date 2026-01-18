<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    use HasFactory;
    protected $table = 'autors';
    protected $fillable = ['nom', 'biografia', 'user_id'];

    // Relació:Un autor té molts llibres (clau forana i clau primaria)
    public function llibres()
    {
        return $this->hasMany(Llibre::class, 'autor_id', 'id');
    }
}