<?php declare(strict_types=1);

namespace App\Http\Repositories;

use App\Models\Like;
use App\Http\Contracts\LikesRepositoryInterface;

class LikesRepository implements LikesRepositoryInterface
{
    /**
     * @var Like
     */
    protected Like $like;

    /**
     * @param Like $model
     */
    public function __construct(Like $model)
    {
        $this->model = $model;
    }

    /**
     * @param $likeableType
     * @param $likeableId
     * @param $userId
     * @return mixed
     */
    public function createLike($likeableType, $likeableId, $userId): mixed
    {
        return $this->model::create([
            'user_id' => $userId,
            'likeable_type' => $likeableType,
            'likeable_id' => $likeableId,
        ]);
    }

    /**
     * @param $like
     * @return void
     */
    public function deleteLike($like): void
    {
        $like->delete();
    }
}
