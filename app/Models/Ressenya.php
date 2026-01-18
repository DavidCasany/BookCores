<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ressenya extends Model
{
    protected $table = 'ressenyes';

    // 1. Afegim el nou nom a la llista de camps permesos
    protected $fillable = ['text', 'puntuacio', 'user_id', 'llibre_id', 'resposta_a_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 2. Actualitzem la relació de respostes
    public function respostes()
    {
        // Una ressenya té moltes "respostes" on la columna 'resposta_a_id' coincideix amb el meu ID
        return $this->hasMany(Ressenya::class, 'resposta_a_id');
    }
}