<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Repositories\CommentsRepository;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\JsonResponse;
use Auth;

class CommentController extends Controller
{
    protected $commentRepository;

    public function __construct(CommentsRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param CommentRequest $request
     * @param int $movieId
     * @return JsonResponse
     */
    public function store(CommentRequest $request, int $movieId): JsonResponse
    {
        if (auth()->check()) {
            $userId = auth()->id();
            $content = $request->input('content');

            $this->commentRepository->createComment($movieId, $userId, $content);

            return response()->json(['message' => 'Comment created successfully'], 201);
        } else {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
    }

    /**
     * @param int $movieId
     * @return JsonResponse
     */
    public function index(int $movieId): JsonResponse
    {
        $comments = $this->commentRepository->getCommentsByMovieId($movieId);

        return response()->json($comments);
    }
}
