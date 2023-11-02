<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use App\Models\Payment;

class paymentController extends Controller
{
    public function store(Request $request)
    {
        Stripe::setApiKey('sk_test_51O3HhNLnvvEJ81Oe8CahWIfvZsz5VJEFgzgJXg39V3aow7oC87pFapCW8aL1BXYxnqlRnZQU6yypU5wpJEbHcbnn00tz9vTuWJ');

        try {
            $charge = Charge::create([
                'amount' => 400,
                'currency' => 'usd',
                'source' => $request->input('token'),
                'description' => 'Movie Payment',
            ]);

            $payment = new Payment();
            $payment->movie_id = $request->input('movie_id');
            $payment->user_id = $request->input('user_id');
            $payment->charge_id = $charge->id;

            $payment->save();

            return response()->json(['message' => 'Payment successful'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Payment failed'], 400);
        }
    }

}
