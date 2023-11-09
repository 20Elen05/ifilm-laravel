<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SignUpRequest;
use App\Http\Requests\SigninRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Auth;

class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param SignUpRequest $request
     * @return JsonResponse
     */
    public function signupUser(SignupRequest $request): JsonResponse
    {
        $userData = $request->only(['firstName', 'surname', 'username', 'email', 'password']);
        $user = $this->userRepository->create($userData);

        return response()->json([
            'message' => 'User registered successfully',
            'status' => true,
            'token' => $user->createToken('API TOKEN')->plainTextToken,
        ])->setStatusCode(200);
    }

    /**
     * @param SigninRequest $request
     * @return JsonResponse
     */
    public function signinUser(SigninRequest $request): JsonResponse
    {
        $username = $request->input('username');
        $password = $request->input('password');
        $user = $this->userRepository->findByUsername($username);

        if ($user && Hash::check($password, $user->password)) {
            $userId = $user->id;
            $token = $user->createToken('authToken');
            session(['authenticated' => true]);

            return response()->json([
                'message' => 'Success',
                'user_id' => $userId,
                'token' => $token->plainTextToken,
            ])->setStatusCode(200);
        } else {
            return response()->json(['message' => 'error']);
        }
    }

    /**
     * @param $userIds
     * @return JsonResponse
     */
    public function getUsers(string $userIds): JsonResponse
    {
        $userIdsArray = explode(',', $userIds);
        $users = $this->userRepository->getUsersByIds($userIdsArray);

        return response()->json($users)->setStatusCode(200);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $user = Auth::user();
        if ($user) {
            $this->userRepository->logoutUserTokens($user);
            session(['authenticated' => false]);
            return response()->json(['message' => 'Logged out successfully'])->setStatusCode(204);
        } else {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    }

    /**
     * @param $userId
     * @return JsonResponse
     */
    public function deleteAccount(int $userId): JsonResponse
    {
        $user = $this->userRepository->findUserById($userId);
        if ($user) {
            $this->userRepository->deleteUser($userId);
            return response()->json(['message' => 'User deleted successfully'])->setStatusCode(204);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    /**
     * @param $userId
     * @return JsonResponse
     */
    public function show(int $userId): JsonResponse
    {
        $user = $this->userRepository->findUserById($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $likedMovies = $user->likes;
        return response()->json(['user' => $user, 'likedMovies' => $likedMovies]);
    }
}
