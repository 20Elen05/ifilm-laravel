<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\JsonResponse;
use Auth;

class CommentController extends Controller
{
    /**
     * @param CommentRequest $request
     * @param $movieId
     * @return JsonResponse
     */
    public function store(CommentRequest $request, int $movieId): JsonResponse
    {
        if (auth()->check()) {
            $userId = auth()->id();

            $comment = new Comment();
            $comment->movie_id = $movieId;
            $comment->user_id = $userId;
            $comment->content = $request->input('content');
            $comment->save();

            return response()->json(['message' => 'Comment created successfully'], 201);
        } else {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
    }

    /**
     * @param $movieId
     * @return JsonResponse
     */
    public function index(int $movieId): JsonResponse
    {
        $comments = Comment::with('user', 'likes')->where('movie_id', $movieId)->get();

        return response()->json($comments);
    }
}
