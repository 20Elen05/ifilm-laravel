<?php declare(strict_types=1);

namespace App\Http\Contracts;
use Illuminate\Database\Eloquent\Collection;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * @param array $userData
     * @return User
     */
    public function create(array $userData): User;

    /**
     * @param string $username
     * @return User|null
     */
    public function findByUsername(string $username): ?User;

    /**
     * @param array $userIds
     * @return Collection
     */
    public function getUsersByIds(array $userIds): Collection;

    /**
     * @param User $user
     * @return void
     */
    public function logoutUserTokens(User $user): void;

    /**
     * @param int $userId
     * @return User|null
     */
    public function findUserById(int $userId): ?User;

    /**
     * @param int $userId
     * @return void
     */
    public function deleteUser(int $userId): void;

}
