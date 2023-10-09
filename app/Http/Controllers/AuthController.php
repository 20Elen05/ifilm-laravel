<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SignUpRequest;
use App\Http\Requests\SigninRequest;
use App\Models\User;
use App\Models\Movie;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Auth;

class AuthController extends Controller{

    use HasApiTokens;

    public function signupUser(SignupRequest $request) {
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

    public function signinUser(SigninRequest $request) {

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
    public function getUsers(Request $request, $userIds){
        $userIdsArray = explode(',', $userIds);
        $users = User::whereIn('id', $userIdsArray)->get();

        return response()->json($users)->setStatusCode(200);
    }

    public function logout(Request $request) {
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete();
            session(['authenticated' => false]);
            return response()->json(['message' => 'Logged out successfully'])->setStatusCode(204);
        } else {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    }

    public function deleteAccount($userId){
        $user = User::find($userId);
        if ($user) {
            $user->delete();
            $user->tokens()->delete();
            return response()->json(['message' => 'User deleted successfully'])->setStatusCode(204);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

//    public function getUserId(Request $request){
//        $userId = $request->session()->get('user_id');
//        dd($userId);
//        return response()->json(['user_id' => $userId]);
//    }

    public function show($userId) {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $likedMovies = $user->likes;
        return response()->json(['user' => $user, 'likedMovies' => $likedMovies]);
    }
}
