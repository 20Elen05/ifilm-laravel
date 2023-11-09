<?php declare(strict_types=1);

namespace App\Http\Repositories;

use App\Models\CategoryMovie;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Category;
use App\Http\Contracts\MoviesRepositoryInterface;
use App\Models\MovieGenre;
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
     * @return Movie | null
     */
    public function findMovieById($id): Movie | null
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
        return $this->model::whereIn('movie_id', function ($query) use ($likedMovieIds) {
            $query->select('likeable_id')
                ->from('likes')
                ->where('likeable_type', 'App\Models\Movie')
                ->whereIn('likeable_id', $likedMovieIds);
        })->get();
    }


    /**
     * @param $movieData
     * @param $movieDataRu
     * @param $categoryId
     * @return Movie
     */
    public function createNewMovie($movieData, $movieDataRu, $categoryId): Movie
    {
        $apiKey = "a348e7136197bd5186dd097b93931f79";

        $movieId = $movieData['id'];

        $existingMovie = $this->model::where('movie_id', $movieId)->first();
        if ($existingMovie) {
            return $existingMovie;
        }

        $movie = new Movie();
        $movie->movie_id = $movieId;
        $movie->original_language = $movieData['original_language'];
        $movie->original_title = $movieData['original_title'];
        $movie->popularity = $movieData['popularity'];
        $movie->vote_average = $movieData['vote_average'];
        $movie->vote_count = $movieData['vote_count'];
        $movie->release_date = $movieData['release_date'];
        $movie->content = json_encode([
            'en' => [
                'title' => $movieData['title'],
                'poster_path' => $movieData['poster_path'],
                'overview' => $movieData['overview'],
            ],
            'ru' => [
                'title' => $movieDataRu['title'],
                'poster_path' => $movieDataRu['poster_path'],
                'overview' => $movieDataRu['overview'],
            ]
        ]);

        $movieResponse = Http::get("https://api.themoviedb.org/3/movie/{$movieData['id']}?api_key=$apiKey&language=en");
        if ($movieResponse->successful()) {
            $film = $movieResponse->json();
            $movie->runtime = $film['runtime'];
            $countries = $film['production_countries'];
            $movie->production_countries = implode(',', array_column($countries, 'name'));
            $movie->budget = $film['budget'];
        }

        $genreIds = $movieData['genre_ids'];
        $existingGenres = Genre::whereIn('id', $genreIds)->get()->pluck('id');
        foreach ($existingGenres as $genreId) {
            MovieGenre::create([
                'movie_id' => $movie->movie_id,
                'genre_id' => $genreId
            ]);
        }

        CategoryMovie::create([
            'movie_id' => $movie->movie_id,
            'category_id' => $categoryId
        ]);

        $movie->save();

        return $movie;
    }
}

