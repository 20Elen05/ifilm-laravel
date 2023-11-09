<?php declare(strict_types=1);

namespace App\Http\Contracts;

use App\Models\Comment;

interface CommentsRepositoryInterface
{
    /**
     * @param $movieId
     * @param $userId
     * @param $content
     * @return Comment
     */
    public function createComment($movieId, $userId, $content): Comment;


    /**
     * @param $movieId
     * @return mixed
     */
    public function getCommentsByMovieId($movieId): mixed;

    /**
     * @param $id
     * @return mixed
     */
    public function getCommentById($id): mixed;

}
