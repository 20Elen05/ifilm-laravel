<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Movie;
use App\Models\Comment;
use App\Models\Like;
use Auth;

class CommentController extends Controller{
    public function store(Request $request, $movieId)
    {
        if (auth()->check()) {
            $userId = auth()->id();
            $request->validate([
                'content' => 'required|string',
            ]);

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

    public function index($movieId) {
        $comments = Comment::with('user', 'likes')->where('movie_id', $movieId)->get();

        return response()->json($comments);
    }

}
