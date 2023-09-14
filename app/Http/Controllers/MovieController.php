<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function getMovies(Request $request)
    {
        $perPage = 20;
        $categoryId = 1;

        $movies = Movie::where('category_id', $categoryId)
            ->paginate($perPage);

        return response()->json($movies);
    }

    public function show($id)
    {
        $movie = Movie::with('genres')->find($id);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        return response()->json(['movie' => $movie]);
    }

    public function getNavpanelMovies(Request $request)
    {

        $categoryId = 1;
        $movies = Movie::where('category_id', $categoryId)
            ->paginate(20);

        return response()->json($movies);
    }

    public function getTopMovies(Request $request)
    {
        $perPage = 10;
        $categoryId = 2;

        $movies = Movie::where('category_id', $categoryId)
            ->paginate($perPage);

        return response()->json($movies);
    }

    public function getnowPlayingMovies(Request $request)
    {
        $categoryId = 3;
        $movies = Movie::where('category_id', $categoryId)
            ->paginate(10);

        return response()->json($movies);
    }

    public function search(Request $request)
    {
        $keyword = $request->query('keyword');

        $movies = Movie::where('title', 'LIKE', '%' . $keyword . '%')
            ->orWhere('overview', 'LIKE', '%' . $keyword . '%')
            ->get();

        return response()->json($movies);
    }

    public function checkLikeStatus($id) {
        $user = auth()->user();
        $movie = Movie::findOrFail($id);

        $isLiked = $movie->likes()->where('user_id', $user->id)->exists();

        return response()->json(['isLiked' => $isLiked]);
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

        $movies = Movie::whereIn('id', function($query) use ($likedMovieIds) {
            $query->select('likeable_id')
                ->from('likes')
                ->where('likeable_type', 'App\Models\Movie')
                ->whereIn('likeable_id', $likedMovieIds);
        })->get();

        return response()->json(['movies' => $movies]);
    }



}
