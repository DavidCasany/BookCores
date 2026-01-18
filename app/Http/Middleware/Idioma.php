<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\App;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Idioma
{

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('lang')) {
    
            session()->put('idioma', $request->get('lang'));
        }

  
        if (session()->has('idioma')) {
            App::setLocale(session()->get('idioma'));
        }

        return $next($request);
    }
}