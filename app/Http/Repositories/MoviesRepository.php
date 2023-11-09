<?php declare(strict_types=1);

namespace App\Http\Repositories;

use App\Models\Movie;
use App\Models\Category;
use App\Http\Contracts\MoviesRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class MoviesRepository implements MoviesRepositoryInterface
{
    /**
     * @var Movie
     */
    protected Movie $movie;

    /**
     * @param Movie $model
     */
    public function __construct(Movie $model)
    {
        $this->model = $model;
    }

    /**
     * @param Category $category
     * @param $lang
     * @param $perPage
     * @return LengthAwarePaginator
     */
    public function getMoviesInCategory(Category $category, $lang = 'en', $perPage = 20): LengthAwarePaginator
    {
        $movies = $category->movies()
            ->paginate($perPage);

        return $this->transformMoviesContent($movies, $lang);
    }

    /**
     * @param $movies
     * @param $lang
     * @return mixed
     */
    public function transformMoviesContent($movies, $lang): mixed
    {
        $movies->transform(function ($movie) use ($lang) {
            $content = json_decode($movie->content, true);
            $selectedContent = $content[$lang];
            $movie->content = $selectedContent;
            return $movie;
        });

        return $movies;
    }

    /**
     * @param $id
     * @return Mixed
     */
    public function findMovieById($id): Mixed
    {
        return $this->model::with('genres', 'categories', 'payments')->find($id);
    }

    /**
     * @param $keyword
     * @param $lang
     * @return Collection
     */
    public function searchMoviesByKeyword($keyword, $lang = 'en'): Collection
    {
        return $this->model::whereRaw("JSON_UNQUOTE(JSON_EXTRACT(content, '$." . $lang . ".title')) LIKE ? OR JSON_UNQUOTE(JSON_EXTRACT(content, '$." . $lang . ".overview')) LIKE ?", ["%$keyword%", "%$keyword%"])
            ->get();
    }

    /**
     * @param $movieId
     * @param $userId
     * @return mixed
     */
    public function checkLikeStatus($movieId, $userId): mixed
    {
        $movie = $this->model::findOrFail($movieId);

        return $movie->likes()->where('user_id', $userId)->exists();
    }

    /**
     * @param $likedMovieIds
     * @param string $lang
     * @return Collection
     */
    public function getLikedMovies($likedMovieIds, string $lang = 'en'): Collection
    {
        return Movie::whereIn('movie_id', function ($query) use ($likedMovieIds) {
            $query->select('likeable_id')
                ->from('likes')
                ->where('likeable_type', 'App\Models\Movie')
                ->whereIn('likeable_id', $likedMovieIds);
        })->get();
    }
}

