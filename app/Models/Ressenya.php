<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ressenya extends Model
{
    protected $table = 'ressenyes';

    protected $fillable = ['text', 'puntuacio', 'user_id', 'llibre_id', 'resposta_a_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function respostes()
    {
        // Una ressenya té moltes "respostes", la columna resposta coincideix amb la teva Id
        return $this->hasMany(Ressenya::class, 'resposta_a_id');
    }
}