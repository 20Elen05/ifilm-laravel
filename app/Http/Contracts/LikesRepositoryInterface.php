<?php declare(strict_types=1);

namespace App\Http\Contracts;

interface LikesRepositoryInterface
{
    /**
     * @param $likeableType
     * @param $likeableId
     * @param $userId
     * @return mixed
     */
    public function createLike($likeableType, $likeableId, $userId): mixed;

    /**
     * @param $like
     * @return void
     */
    public function deleteLike($like): void;

}
