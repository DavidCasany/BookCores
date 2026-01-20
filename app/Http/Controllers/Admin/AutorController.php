<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Autor;
use Illuminate\Http\Request;

class AutorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // De moment, retornem una vista simple que crearem al Pas 4
        // Més endavant aquí passarem la llista d'autors: $autors = Autor::all();
        return view('admin.autors.index');
    }

    // ... la resta de mètodes (create, store...) els farem després
}