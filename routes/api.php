<?php
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', 'AuthController@logout');
    Route::delete('/users/{userId}', 'AuthController@deleteAccount');
    Route::get('/users/{userIds}', 'AuthController@getUsers');
    Route::post('/rate-movie', 'App\Http\Controllers\RatingController@rateMovie');
});

Route::namespace('App\Http\Controllers')->group(function () {
    Route::post('/signUp', 'AuthController@signupUser')->name('signupUser');
    Route::post('/signIn', 'AuthController@signinUser')->name('signinUser');
    Route::get('/user-id', 'AuthController@getUserId');
    Route::get('/user/{userId}', 'AuthController@show');
});

Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/movies', 'MovieController@getMovies');
    Route::get('/movie/{id}', 'MovieController@show');
    Route::get('/navpanelMovies', 'MovieController@getNavpanelMovies');
    Route::get('/nowPlayingMovies', 'MovieController@getnowPlayingMovies');
    Route::get('/topMovies', 'MovieController@getTopMovies');
    Route::get('/search', 'MovieController@search');
    Route::get('movies/liked', 'MovieController@getLikedMovies');
    Route::middleware('auth:sanctum')->get('/movies/{id}/check-like-status', 'MovieController@checkLikeStatus');

});

Route::namespace('App\Http\Controllers')->group(function () {
    Route::middleware('auth:sanctum')->post('/movies/{id}/like', 'LikeController@toggleLikeMovie');
    Route::middleware('auth:sanctum')->post('/comments/{id}/like', 'LikeController@toggleLikeCom');
});

Route::namespace('App\Http\Controllers')->group(function(){
    Route::get('/movie/{movie}/comments', 'CommentController@index');
    Route::middleware('auth:sanctum')->post('/movie/{movie}/comments', 'CommentController@store');
});



