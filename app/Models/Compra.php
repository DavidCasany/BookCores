<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    
    protected $table = 'compres';
    protected $primaryKey = 'id_compra';

   
    protected $fillable = ['total', 'user_id', 'estat'];

  
    public function llibres()
    {
        // Many-to-Many (Model, Taula Pivot, Clau Local, Clau Forana)
        return $this->belongsToMany(Llibre::class, 'compra_llibre', 'compra_id', 'llibre_id')
                    ->withPivot('quantitat', 'preu_unitari') 
                    ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}