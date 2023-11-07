<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SignUpRequest;
use App\Http\Requests\SigninRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Http\JsonResponse;
use Auth;

class AuthController extends Controller
{

    use HasApiTokens;

    /**
     * @param SignUpRequest $request
     * @return JsonResponse
     */
    public function signupUser(SignupRequest $request): JsonResponse
    {
        $user = User::create([
            'first_name' => $request->input('firstName'),
            'surname' => $request->input('surname'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
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

        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            $userId = Auth::id();
            $user = Auth::user();
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
    public function getUsers($userIds): JsonResponse
    {
        $userIdsArray = explode(',', $userIds);
        $users = User::whereIn('id', $userIdsArray)->get();

        return response()->json($users)->setStatusCode(200);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete();
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
        $user = User::find($userId);
        if ($user) {
            $user->delete();
            $user->tokens()->delete();
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
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $likedMovies = $user->likes;
        return response()->json(['user' => $user, 'likedMovies' => $likedMovies]);
    }
}
