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
        $cistella = Compra::where('user_id', Auth::id())
                          ->where('estat', 'en_proces')
                          ->with('llibres')
                          ->latest()
                          ->first();

        if (!$cistella || $cistella->llibres->isEmpty()) {
            return redirect()->route('cistella.index');
        }

        
        Stripe::setApiKey(env('STRIPE_SECRET'));

        
        $lineItems = [];
        foreach ($cistella->llibres as $llibre) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $llibre->titol,
                    ],
                    'unit_amount' => $llibre->pivot->preu_unitari * 100,
                ],
                'quantity' => $llibre->pivot->quantitat,
            ];
        }

        
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('pagament.exit'), 
            'cancel_url' => route('cistella.index'), 
        ]);

        return redirect($session->url);
    }

    public function exit()
    {

        $cistella = Compra::where('user_id', Auth::id())
                          ->where('estat', 'en_proces')
                          ->latest()
                          ->first();

        if ($cistella) {
            $cistella->estat = 'pagat'; 
            $cistella->save();
        }

        return view('pagament.exit');
    }
}