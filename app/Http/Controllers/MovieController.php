<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\Movie;
use App\Http\Requests\MovieRequest;
use App\Http\Contracts\MoviesRepositoryInterface;
use App\Http\Contracts\CategoriesRepositoryInterface;

class MovieController extends Controller
{
    protected $categoryRepository;
    protected $movieRepository;

    public function __construct(MoviesRepositoryInterface $moviesRepository, CategoriesRepositoryInterface $categoriesRepository)
    {
        $this->movieRepository = $moviesRepository;
        $this->categoryRepository = $categoriesRepository;
    }

    /**
     * @param MovieRequest $request
     * @return JsonResponse
     *
     */
    public function getMovies(MovieRequest $request): JsonResponse
    {
        $perPage = 20;
        $categoryId = 1;

        $category = $this->categoryRepository->findCategoryById($categoryId);

        $movies = $this->movieRepository->getMoviesInCategory($category, $request->input('lang', 'en'), $perPage);

        return response()->json($movies);
    }

    /**
     * @param $id
     * @param MovieRequest $request
     * @return JsonResponse
     */
    public function show($id, MovieRequest $request): JsonResponse
    {
        $lang = $request->input('lang', 'en');

        $movie = $this->movieRepository->findMovieById($id);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        $content = json_decode($movie->content, true);

        $selectedContent = $content[$lang] ?? $content['en'];

        $movie->content = $selectedContent;

        return response()->json(['movie' => $movie]);
    }

    /**
     * @param MovieRequest $request
     * @return JsonResponse
     */
    public function getNavpanelMovies(MovieRequest $request): JsonResponse
    {
        $perPage = 20;
        $categoryId = 1;

        $category = $this->categoryRepository->findCategoryById($categoryId);

        $movies = $this->movieRepository->getMoviesInCategory($category, $request->input('lang', 'en'), $perPage);

        return response()->json($movies);
    }

    /**
     * @param MovieRequest $request
     * @return JsonResponse
     */
    public function getTopMovies(MovieRequest $request): JsonResponse
    {
        $perPage = 10;
        $categoryId = 2;

        $category = $this->categoryRepository->findCategoryById($categoryId);

        $movies = $this->movieRepository->getMoviesInCategory($category, $request->input('lang', 'en'), $perPage);

        return response()->json($movies);
    }

    /**
     * @param MovieRequest $request
     * @return JsonResponse
     */
    public function getNowPlayingMovies(MovieRequest $request): JsonResponse
    {
        $perPage = 10;
        $categoryId = 3;

        $category = $this->categoryRepository->findCategoryById($categoryId);

        $movies = $this->movieRepository->getMoviesInCategory($category, $request->input('lang', 'en'), $perPage);

        return response()->json($movies);
    }

    /**
     * @param MovieRequest $request
     * @return JsonResponse
     */
    public function search(MovieRequest $request): JsonResponse
    {
        $keyword = $request->query('keyword');
        $lang = $request->query('lang', 'en');

        $movies = $this->movieRepository->searchMoviesByKeyword($keyword, $lang);

        foreach ($movies as $movie) {
            $content = json_decode($movie->content, true);
            $selectedContent = $content[$lang] ?? $content['en'];
            $movie->content = $selectedContent;
        }

        return response()->json($movies);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function checkLikeStatus($id): JsonResponse
    {
        $user = auth()->user();
        $movie = Movie::findOrFail($id);

        $isLiked = $movie->likes()->where('user_id', $user->id)->exists();

        return response()->json(['isLiked' => $isLiked]);
    }

    /**
     * @param MovieRequest $request
     * @return JsonResponse
     */
    public function getLikedMovies(MovieRequest $request): JsonResponse
    {
        $likedMovieIds = $request->input('likedMovieIds');
        $lang = $request->query('lang', 'en');

        $movies = $this->movieRepository->getLikedMovies($likedMovieIds, $lang);

        $movies->transform(function ($movie) use ($lang) {
            $content = json_decode($movie->content, true);
            $selectedContent = $content[$lang] ?? $content['en'];
            $movie->content = $selectedContent;
            return $movie;
        });

        return response()->json(['movies' => $movies]);
    }

}
