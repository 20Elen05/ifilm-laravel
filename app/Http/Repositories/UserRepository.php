<?php declare(strict_types=1);

namespace App\Http\Repositories;

use App\Models\User;
use App\Http\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var User
     */
    protected User $user;

    /**
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }


    /**'
     * @param array $userData
     * @return User
     */
    public function create(array $userData): User
    {
        return $this->model::create([
            'first_name' => $userData['firstName'],
            'surname' => $userData['surname'],
            'username' => $userData['username'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
        ]);
    }

    /**
     * @param string $username
     * @return User|null
     */
    public function findByUsername(string $username): ?User
    {
        return $this->model::where('username', $username)->first();
    }

    /**
     * @param array $userIds
     * @return Collection
     */
    public function getUsersByIds(array $userIds): Collection
    {
        return $this->model::whereIn('id', $userIds)->get();
    }

    /**
     * @param User $user
     * @return void
     */
    public function logoutUserTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * @param int $userId
     * @return User|null
     */
    public function findUserById(int $userId): ?User
    {
        return $this->model::find($userId);
    }

    /**
     * @param int $userId
     * @return void
     */
    public function deleteUser(int $userId): void
    {
        $user = $this->model::find($userId);
        if ($user) {
            $user->delete();
        }
    }
}
