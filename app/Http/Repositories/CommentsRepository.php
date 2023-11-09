<?php declare(strict_types=1);

namespace App\Http\Repositories;

use App\Models\Comment;
use App\Http\Contracts\CommentsRepositoryInterface;

class CommentsRepository implements CommentsRepositoryInterface
{
    /**
     * @var Comment
     */
    protected Comment $comment;

    /**
     * @param Comment $model
     */
    public function __construct(Comment $model)
    {
        $this->model = $model;
    }

    /**
     * @param $movieId
     * @param $userId
     * @param $content
     * @return Comment
     */
    public function createComment($movieId, $userId, $content): Comment
    {
        return $this->model::create([
            'movie_id' => $movieId,
            'user_id' => $userId,
            'content' => $content,
        ]);
    }

    /**
     * @param $movieId
     * @return Mixed
     */
    public function getCommentsByMovieId($movieId): mixed
    {
        return $this->model::with('user', 'likes')->where('movie_id', $movieId)->get();
    }

    /**
     * @param $movieId
     * @return mixed
     */
    public function getCommentById($id): mixed
    {
        return $this->model::with('user', 'likes')->find($id);
    }

}
