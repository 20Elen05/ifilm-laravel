<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Movie;

class RatingController extends Controller {
    public function rateMovie(Request $request)
    {
        $user = auth()->user();

        $ratingData = $request->all();

        $existingRating = Rating::where('user_id', $user->id)
            ->where('movie_id', $ratingData['movie_id'])
            ->first();

        if (!$existingRating) {
            $rating = new Rating([
                'user_id' => $user->id,
                'movie_id' => $ratingData['movie_id'],
                'rating' => $ratingData['rating'],
            ]);
            $rating->save();

            $movie = Movie::find($ratingData['movie_id']);
            $movie->vote_count += 1;
            $movie->vote_average = (($movie->vote_average * ($movie->vote_count - 1)) + $ratingData['rating']) / $movie->vote_count;
            $movie->save();

            return response()->json(['message' => 'rated successfully'], 201);
        }else{
            return response()->json(['message' => 'already rated'], 409);
        }
    }
}
