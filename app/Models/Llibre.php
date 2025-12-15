<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Llibre extends Model
{
    use HasFactory;

    // Assegura't que tens això posat, que abans t'ha donat guerra!
    protected $primaryKey = 'id_llibre';

    protected $fillable = [
        'titol', 'descripcio', 'genere', 'preu', 'nota_promig',
        'img_portada', 'fitxer_pdf', 'autor_id', 'editorial_id',
    ];

    public function autor()
    {
        return $this->belongsTo(Autor::class);
    }
    
    public function editorial()
    {
        return $this->belongsTo(Editorial::class);
    }

    // CORRECCIÓ AQUÍ:
    public function ressenyas()
    {
        // 1r paràmetre: El Model fill
        // 2n paràmetre: La clau forana a la taula 'ressenyes' (és 'llibre_id', no 'llibre_id_llibre')
        // 3r paràmetre: La clau primària a la taula 'llibres' (és 'id_llibre')
        return $this->hasMany(Ressenya::class, 'llibre_id', 'id_llibre');
    }
}