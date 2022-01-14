<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Exception\CardException;

class StripeController extends Controller
{
    //
    public function stripe()
    {
        $months = collect(range(1, 12))->map(function ($item) {
            return sprintf('%02d', $item);
        });

        $years = collect(range(0, 20))->map(function ($item) {
           return (string) date('Y') + $item;
        });

        return view('payment', compact('years', 'months'));
    }

    public function stripePost(Request $request)
    {
        $request->validate([
            'stripeToken' => 'required'
        ]);

        try {
            Charge::create([
                'amount' => 1000,
                'currency' => 'myr',
                'source' => $request->stripeToken,
                'description' => 'This payment is tested on purpose'
            ]);
        } catch (CardException | \Exception $e) {
            return redirect()->route('stripe.form')->withErrors(['msg' => $e->getMessage()]);
        }


        return redirect()->route('stripe.form')->with('success', 'Payment successful!');
    }
}
