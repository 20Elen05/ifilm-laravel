<?php declare(strict_types=1);

namespace App\Http\Contracts;

interface PaymentsRepositoryInterface
{
    /**
     * @param $token
     * @param $movieId
     * @param $userId
     * @return bool
     */
    public function processPayment($token, $movieId, $userId): bool;

}
