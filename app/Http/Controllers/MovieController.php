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

        return response()->json($movies);
    }

    public function show($id){
        $movie = Movie::with('genres')->find($id);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        return response()->json(['movie' => $movie]);
    }

    public function getNavpanelMovies(Request $request)
    {
        $category = Category::find(1);

        $movies = $category->movies()
            ->paginate(20);

        return response()->json($movies)->setStatusCode(200);
    }

    public function getTopMovies(Request $request)
    {
        $perPage = 10;

        $category = Category::find(2);

        $movies = $category->movies()
            ->paginate($perPage);

        return response()->json($movies)->setStatusCode(200);
    }

    public function getnowPlayingMovies(Request $request)
    {
        $category = Category::find(3);

        $movies = $category->movies()
            ->paginate(10);

        return response()->json($movies)->setStatusCode(200);
    }

    public function search(Request $request)
    {
        $keyword = $request->query('keyword');

        $movies = Movie::where('title', 'LIKE', '%' . $keyword . '%')
            ->orWhere('overview', 'LIKE', '%' . $keyword . '%')
            ->get();

        return response()->json($movies)->setStatusCode(200);
    }

    public function checkLikeStatus($id) {
        $user = auth()->user();
        $movie = Movie::findOrFail($id);

        $isLiked = $movie->likes()->where('user_id', $user->id)->exists();

        return response()->json(['isLiked' => $isLiked])->setStatusCode(200);
    }

//    public function getLikedMovies(Request $request) {
//        $likedMovieIds = $request->input('likedMovieIds');
//
//        $movies = Movie::whereIn('id', $likedMovieIds)->get();
//
//        return response()->json(['movies' => $movies]);
//    }

    public function getLikedMovies(Request $request) {
        $likedMovieIds = $request->input('likedMovieIds');

        $movies = Movie::whereIn('movie_id', function($query) use ($likedMovieIds) {
            $query->select('likeable_id')
                ->from('likes')
                ->where('likeable_type', 'App\Models\Movie')
                ->whereIn('likeable_id', $likedMovieIds);
        })->get();

        return response()->json(['movies' => $movies])->setStatusCode(200);
    }
}
