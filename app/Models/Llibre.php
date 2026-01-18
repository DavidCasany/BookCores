<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Llibre extends Model
{
    use HasFactory;

    protected $table = 'llibres';
    protected $primaryKey = 'id_llibre';

    protected $fillable = [
        'titol', 'descripcio', 'genere', 'preu', 'nota_promig',
        'img_portada', 'img_hero', 'fitxer_pdf', 'autor_id', 'editorial_id',
    ];

    public function autor()
    {
        return $this->belongsTo(Autor::class, 'autor_id');
    }
    
    public function editorial()
    {
        return $this->belongsTo(Editorial::class, 'editorial_id');
    }

    public function ressenyes()
    {
        return $this->hasMany(Ressenya::class, 'llibre_id', 'id_llibre');
    }

    
    public function compres()
    {
        // Relació inversa de Many-to-Many
        return $this->belongsToMany(Compra::class, 'compra_llibre', 'llibre_id', 'compra_id')
                    ->withPivot('quantitat', 'preu_unitari')
                    ->withTimestamps();
    }
}