<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ressenya extends Model
{
    // AVIS IMPORTANT: Diem a Laravel que la taula és en català
    protected $table = 'ressenyes';

    protected $fillable = ['text', 'puntuacio', 'user_id', 'llibre_id'];

    // Relació inversa: Una ressenya pertany a un Usuari
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
