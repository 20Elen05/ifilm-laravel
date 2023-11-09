<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Contracts\PaymentsRepositoryInterface;
use App\Http\Requests\PaymentRequest;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    protected $paymentsRepository;

    public function __construct(PaymentsRepositoryInterface $paymentsRepository)
    {
        $this->paymentsRepository = $paymentsRepository;
    }


    /**
     * @param PaymentRequest $request
     * @return JsonResponse
     */
    public function store(PaymentRequest $request): JsonResponse
    {
        $token = $request->input('token');
        $movieId = $request->input('movie_id');
        $userId = $request->input('user_id');

        $paymentProcessed = $this->paymentsRepository->processPayment($token, $movieId, $userId);

        if ($paymentProcessed) {
            return response()->json(['message' => 'Payment successful'], 200);
        } else {
            return response()->json(['message' => 'Payment failed'], 400);
        }
    }
}
