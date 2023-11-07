<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Comment;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Auth;

class LikeController extends Controller
{

    /**
     * @param $id
     * @return JsonResponse
     */
    public function toggleLikeMovie($id): JsonResponse
    {
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
            $like->likeable_id = $movie->movie_id;
            $like->save();

            return response()->json(['message' => 'Movie liked successfully']);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function toggleLikeCom($id): JsonResponse
    {
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

            return response()->json(['message' => 'Comment liked successfully'])->setStatusCode(201);
        }
    }
}
