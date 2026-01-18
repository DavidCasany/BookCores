<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editorial extends Model
{
    use HasFactory;

    
    protected $table = 'editorials';

    protected $fillable = ['nom', 'descripcio'];

    // Una editorial té molts llibres (clau forana i clau primria)
    public function llibres()
    {
        return $this->hasMany(Llibre::class, 'editorial_id', 'id');
    }
}