<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compra;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PagamentController extends Controller
{
    public function checkout()
    {
        // 1. Busquem la cistella OBERTA de l'usuari
        $cistella = Compra::where('user_id', Auth::id())
                          ->where('estat', 'en_proces')
                          ->with('llibres')
                          ->latest()
                          ->first();

        if (!$cistella || $cistella->llibres->isEmpty()) {
            return redirect()->route('cistella.index');
        }

        // 2. Configurem Stripe amb la clau secreta del .env
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // 3. Creem la llista de productes per a Stripe
        $lineItems = [];
        foreach ($cistella->llibres as $llibre) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $llibre->titol,
                    ],
                    'unit_amount' => $llibre->pivot->preu_unitari * 100, // Stripe vol cèntims!
                ],
                'quantity' => $llibre->pivot->quantitat,
            ];
        }

        // 4. Iniciem la sessió de pagament a Stripe
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('pagament.exit'), // On torna si paga bé
            'cancel_url' => route('cistella.index'), // On torna si cancel·la
        ]);

        // 5. Redirigim l'usuari cap a Stripe
        return redirect($session->url);
    }

    public function exit()
    {
        // L'usuari torna de Stripe -> Marquem com a PAGAT
        $cistella = Compra::where('user_id', Auth::id())
                          ->where('estat', 'en_proces')
                          ->latest()
                          ->first();

        if ($cistella) {
            $cistella->estat = 'pagat'; // Canviem l'estat!
            $cistella->save();
        }

        return view('pagament.exit');
    }
}