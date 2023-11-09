<?php declare(strict_types=1);

namespace App\Http\Repositories;

use App\Http\Contracts\PaymentsRepositoryInterface;
use App\Models\Payment;
use Stripe\Charge;
use Stripe\Stripe;

class PaymentsRepository implements PaymentsRepositoryInterface
{
    /**
     * @var Payment
     */
    protected Payment $payment;

    /**
     * @param Payment $model
     */
    public function __construct(Payment $model)
    {
        $this->model = $model;
    }

    /**
     * @param $token
     * @param $movieId
     * @param $userId
     * @return bool
     */
    public function processPayment($token, $movieId, $userId): bool
    {
        Stripe::setApiKey('sk_test_51O3HhNLnvvEJ81Oe8CahWIfvZsz5VJEFgzgJXg39V3aow7oC87pFapCW8aL1BXYxnqlRnZQU6yypU5wpJEbHcbnn00tz9vTuWJ');

        try {
            $charge = Charge::create([
                'amount' => 400,
                'currency' => 'usd',
                'source' => $token,
                'description' => 'Movie Payment',
            ]);

            $payment = new Payment([
                'movie_id' => $movieId,
                'user_id' => $userId,
                'charge_id' => $charge->id,
            ]);

            $payment->save();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
