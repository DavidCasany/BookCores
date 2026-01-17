<?php

namespace App\Http\Controllers;

use App\Models\Llibre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LlibreController extends Controller
{
    public function index()
    {
        $llibresRecents = Llibre::latest()->take(3)->get();
        $llibres = Llibre::with('autor')->get();

        if (Auth::check()) {
            return view('home-auth', compact('llibres', 'llibresRecents'));
        } else {
            return view('home-guest', compact('llibres', 'llibresRecents'));
        }
    }

    public function show($id)
    {
        // 🟢 CORREGIT: 'ressenyes.user' (amb E), no 'ressenyas.user'
        $llibre = Llibre::with(['autor', 'editorial', 'ressenyes.user'])->findOrFail($id);

        return view('llibres.show', compact('llibre'));
    }
}