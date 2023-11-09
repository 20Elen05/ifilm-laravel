<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Repositories\RatingsRepository;
use App\Models\Rating;
use App\Models\Movie;
use App\Http\Requests\RatingRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Contracts\RatingsRepositoryInterface;
use App\Http\Contracts\MoviesRepositoryInterface;


class RatingController extends Controller
{
    protected $moviesRepository;
    protected $ratingsRepository;

    public function __construct(RatingsRepositoryInterface $ratingsRepository, MoviesRepositoryInterface $moviesRepository)
    {
        $this->ratingsRepository = $ratingsRepository;
        $this->moviesRepository = $moviesRepository;
    }

    /**
     * @param RatingRequest $request
     * @return JsonResponse
     */
    public function rateMovie(RatingRequest $request): JsonResponse
    {
        $user = auth()->user();
        $ratingData = $request->validated();

        $existingRating = $this->ratingsRepository->findRating($user->id, $ratingData['movie_id']);

        if (!$existingRating) {
            $this->ratingsRepository->createRating([
                'user_id' => $user->id,
                'movie_id' => $ratingData['movie_id'],
                'rating' => $ratingData['rating'],
            ]);
            $movie = $this->moviesRepository->findMovieById($ratingData['movie_id']);
            $movie->vote_count += 1;
            $movie->vote_average = (($movie->vote_average * ($movie->vote_count - 1)) + $ratingData['rating']) / $movie->vote_count;
            $movie->save();
            return response()->json(['message' => 'rated successfully'], 201);
        } else {
            return response()->json(['message' => 'already rated'], 409);
        }
    }
}
