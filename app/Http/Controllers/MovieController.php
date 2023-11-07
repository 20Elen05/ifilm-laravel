<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\Movie;
use App\Models\Category;
use App\Http\Requests\MovieRequest;


class MovieController extends Controller
{
    /**
     * @param MovieRequest $request
     * @return JsonResponse
     *
     */
    public function getMovies(MovieRequest $request): JsonResponse
    {
        $perPage = 20;
        $categoryId = 1;

        $category = Category::find($categoryId);

        $movies = $category->movies()
            ->paginate($perPage);

        $lang = $request->input('lang', 'en');

        $movies->transform(function ($movie) use ($lang) {
            $content = json_decode($movie->content, true);
            $selectedContent = $content[$lang];
            $movie->content = $selectedContent;
            return $movie;
        });

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

        $movie = Movie::with('genres', 'categories', 'payments')->find($id);

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

        $category = Category::find($categoryId);

        $movies = $category->movies()
            ->paginate($perPage);

        $lang = $request->query('lang', 'en');

        $movies->transform(function ($movie) use ($lang) {
            $content = json_decode($movie->content, true);
            $selectedContent = $content[$lang] ?? $content['en'];
            $movie->content = $selectedContent;
            return $movie;
        });

        return response()->json($movies, 200);
    }

    /**
     * @param MovieRequest $request
     * @return JsonResponse
     */
    public function getTopMovies(MovieRequest $request): JsonResponse
    {
        $perPage = 10;
        $categoryId = 2;

        $category = Category::find($categoryId);

        $movies = $category->movies()
            ->paginate($perPage);

        $lang = $request->query('lang', 'en');

        $movies->transform(function ($movie) use ($lang) {
            $content = json_decode($movie->content, true);
            $selectedContent = $content[$lang] ?? $content['en'];
            $movie->content = $selectedContent;
            return $movie;
        });

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

        $category = Category::find($categoryId);

        $movies = $category->movies()
            ->paginate($perPage);

        $lang = $request->query('lang', 'en');

        $movies->transform(function ($movie) use ($lang) {
            $content = json_decode($movie->content, true);
            $selectedContent = $content[$lang] ?? $content['en'];
            $movie->content = $selectedContent;
            return $movie;
        });

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

        $movies = Movie::whereRaw("JSON_UNQUOTE(JSON_EXTRACT(content, '$." . $lang . ".title')) LIKE ? OR JSON_UNQUOTE(JSON_EXTRACT(content, '$." . $lang . ".overview')) LIKE ?", ["%$keyword%", "%$keyword%"])
            ->get();

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

        $movies = Movie::whereIn('movie_id', function ($query) use ($likedMovieIds) {
            $query->select('likeable_id')
                ->from('likes')
                ->where('likeable_type', 'App\Models\Movie')
                ->whereIn('likeable_id', $likedMovieIds);
        })->get();

        $movies->transform(function ($movie) use ($lang) {
            $content = json_decode($movie->content, true);
            $selectedContent = $content[$lang] ?? $content['en'];
            $movie->content = $selectedContent;
            return $movie;
        });

        return response()->json(['movies' => $movies]);
    }

}
