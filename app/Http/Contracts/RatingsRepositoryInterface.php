<?php declare(strict_types=1);

namespace App\Http\Contracts;

use App\Models\Rating;

interface RatingsRepositoryInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function createRating(array $data): mixed;

}
