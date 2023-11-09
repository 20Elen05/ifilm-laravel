<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Repositories\CommentsRepository;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use App\Http\Contracts\LikesRepositoryInterface;
use App\Http\Contracts\MoviesRepositoryInterface;
use App\Http\Contracts\CommentsRepositoryInterface;
use Auth;

class LikeController extends Controller
{
    protected $likeRepository;
    protected $movieRepository;
    protected $commentRepository;

    public function __construct(LikesRepositoryInterface $likeRepository, MoviesRepositoryInterface $moviesRepository, CommentsRepositoryInterface $commentRepository)
    {
        $this->likeRepository = $likeRepository;
        $this->movieRepository = $moviesRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function toggleLikeMovie($id): JsonResponse
    {
        $user = auth()->user();
        $movie = $this->movieRepository->findMovieById($id);

        $existingLike = $movie->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            $this->likeRepository->deleteLike($existingLike);
            return response()->json(['message' => 'Movie is unliked']);
        } else {
            $this->likeRepository->createLike('App\Models\Movie', $movie->movie_id, $user->id);
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
        $comment = $this->commentRepository->getCommentById($id);

        $existingLike = $comment->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            $this->likeRepository->deleteLike($existingLike);
            $comment->decrement('likes_count');
            return response()->json(['message' => 'Com is unliked']);
        } else {
            $this->likeRepository->createLike('App\Models\Comment', $comment->id, $user->id);
            $comment->increment('likes_count');

            return response()->json(['message' => 'Comment liked successfully'])->setStatusCode(201);
        }
    }
}
