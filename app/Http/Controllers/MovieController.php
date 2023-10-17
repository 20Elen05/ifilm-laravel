<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function getMovies(Request $request){
        $perPage = 20;
        $categoryId = 1;

        $category = Category::find($categoryId);

        $movies = $category->movies()
            ->paginate($perPage);

        $lang = $request->query('lang', 'en');

        $movies->transform(function ($movie) use ($lang) {
            $content = json_decode($movie->content, true);
            $selectedContent = $content[$lang];
            $movie->content = $selectedContent;
            return $movie;
        });

        return response()->json($movies);
    }


    public function show($id, Request $request){
        $lang = $request->query('lang', 'en');

        $movie = Movie::with('genres')->find($id);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        $content = json_decode($movie->content, true);

        $selectedContent = $content[$lang] ?? $content['en'];

        $movie->content = $selectedContent;

        return response()->json(['movie' => $movie]);
    }


    public function getNavpanelMovies(Request $request)
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

        return response()->json($movies)->setStatusCode(200);
    }


    public function getTopMovies(Request $request)
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

        return response()->json($movies)->setStatusCode(200);
    }


    public function getNowPlayingMovies(Request $request)
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

        return response()->json($movies)->setStatusCode(200);
    }


    public function search(Request $request)
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

        return response()->json($movies)->setStatusCode(200);
    }

    public function checkLikeStatus($id) {
        $user = auth()->user();
        $movie = Movie::findOrFail($id);

        $isLiked = $movie->likes()->where('user_id', $user->id)->exists();

        return response()->json(['isLiked' => $isLiked])->setStatusCode(200);
    }

    public function getLikedMovies(Request $request) {
        $likedMovieIds = $request->input('likedMovieIds');
        $lang = $request->query('lang', 'en');

        $movies = Movie::whereIn('movie_id', function($query) use ($likedMovieIds) {
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

        return response()->json(['movies' => $movies])->setStatusCode(200);
    }

}
