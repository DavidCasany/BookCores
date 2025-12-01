<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\App;
// use Illuminate\Support\Facades\Session; // Opcional si fas servir session() helper
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Idioma
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. NOU: Comprovar si la petició vol canviar l'idioma (ex: ?lang=en)
        // 'lang' és el 'name' del teu select al formulari HTML.
        if ($request->has('lang')) {
            // Guardem l'idioma triat a la sessió amb la clau 'idioma'
            session()->put('idioma', $request->get('lang'));
        }

        // 2. CODI QUE JA TENIES (Aplicar l'idioma)
        // Si hi ha un idioma guardat a la sessió, l'apliquem a l'aplicació
        if (session()->has('idioma')) {
            App::setLocale(session()->get('idioma'));
        }

        return $next($request);
    }
}