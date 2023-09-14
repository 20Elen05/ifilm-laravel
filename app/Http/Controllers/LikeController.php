<?php

namespace App\Http\Controllers;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Movie;
use Auth;
use Illuminate\Http\Request;

class LikeController extends Controller {

    public function toggleLikeMovie(Request $request, $id) {
        $user = auth()->user();

        $movie = Movie::findOrFail($id);

        $existingLike = $movie->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            $existingLike->delete();
            return response()->json(['message' => 'Movie is unliked']);
        } else {
            $like = new Like();
            $like->user_id = $user->id;
            $like->likeable_type = Movie::class;
            $like->likeable_id = $movie->id;
            $like->save();

            return response()->json(['message' => 'Movie liked successfully']);
        }
    }

    public function toggleLikeCom(Request $request, $id){
        $user = auth()->user();
        $comment = Comment::findOrFail($id);


        $existingLike = $comment->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            $existingLike->delete();
            $comment->decrement('likes_count');

            return response()->json(['message' => 'Com is unliked']);
        } else {
            $like = new Like();
            $like->user_id = $user->id;
            $like->likeable_type = Comment::class;
            $like->likeable_id = $comment->id;
            $like->save();

            $comment->increment('likes_count');

            return response()->json(['message' => 'Comment liked successfully']);
        }
    }
}
