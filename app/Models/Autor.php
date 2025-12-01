<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'biografia', 'user_id'];

    public function llibres()
    {
        return $this->hasMany(Llibre::class);
    }
}