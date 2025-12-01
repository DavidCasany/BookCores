<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Llibre extends Model
{
    use HasFactory;

    protected $fillable = [
        'titol', 'descripcio', 'genere', 'preu', 'nota_promig',
        'img_portada', 'fitxer_pdf', 'autor_id', 'editorial_id',
    ];

    // Aquesta és la funció clau que connecta amb l'altre model
    public function autor()
    {
        return $this->belongsTo(Autor::class);
    }
    
    // També necessitarem l'editorial aviat
    public function editorial()
    {
        return $this->belongsTo(Editorial::class);
    }
}