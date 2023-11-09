<?php declare(strict_types=1);

namespace App\Http\Repositories;

use App\Models\Rating;
use App\Http\Contracts\RatingsRepositoryInterface;

class RatingsRepository implements RatingsRepositoryInterface
{
    /**
     * @var Rating
     */
    protected Rating $rating;

    /**
     * @param Rating $model
     */
    public function __construct(Rating $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createRating(array $data): mixed
    {
        return $this->model::create([
            'user_id' => $data['user_id'],
            'movie_id' => $data['movie_id'],
            'rating' => $data['rating'],
        ]);
    }

    /**
     * @param $userId
     * @param $movieId
     * @return mixed
     */
    public function findRating($userId, $movieId): mixed
    {
        return Rating::where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->first();
    }
}
