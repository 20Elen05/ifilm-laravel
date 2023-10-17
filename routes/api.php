<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('/signUp', 'signupUser')->name('signupUser');
    Route::post('/signIn', 'signinUser')->name('signinUser');
    Route::get('/user/{userId}', 'show');

    Route::middleware(['auth:sanctum'])->group(function (){
        Route::get('/user', function (Request $request) {return $request->user();});
        Route::post('/logout', 'logout');
        Route::delete('/users/{userId}', 'deleteAccount');
        Route::get('/users/{userIds}', 'getUsers');
    });
});

Route::controller(MovieController::class)->group(function () {
    Route::get('/movies', 'getMovies');
    Route::get('/movie/{id}', 'show');
    Route::get('/navpanelMovies', 'getNavpanelMovies');
    Route::get('/nowPlayingMovies', 'getnowPlayingMovies');
    Route::get('/topMovies', 'getTopMovies');
    Route::get('/search', 'search');
    Route::get('movies/liked', 'getLikedMovies');
    Route::middleware('auth:sanctum')->get('/movies/{id}/check-like-status', 'checkLikeStatus');
});

Route::controller(LikeController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::post('/movies/{id}/like', 'toggleLikeMovie');
    Route::post('/comments/{id}/like', 'toggleLikeCom');
});

Route::controller(CommentController::class)->group(function () {
    Route::get('/movie/{movie}/comments', 'index');
    Route::middleware('auth:sanctum')->post('/movie/{movie}/comments', 'store');
});

Route::middleware('auth:sanctum')->post('/rate-movie', 'App\Http\Controllers\RatingController@rateMovie');
