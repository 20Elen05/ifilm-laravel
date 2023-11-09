<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Repositories\UserRepository;
use App\Http\Contracts\UserRepositoryInterface;
use App\Http\Repositories\MoviesRepository;
use App\Http\Contracts\MoviesRepositoryInterface;
use App\Http\Repositories\CategoriesRepository;
use App\Http\Contracts\CategoriesRepositoryInterface;
use App\Http\Repositories\CommentsRepository;
use App\Http\Contracts\CommentsRepositoryInterface;
use App\Http\Repositories\RatingsRepository;
use App\Http\Contracts\RatingsRepositoryInterface;
use App\Http\Repositories\LikesRepository;
use App\Http\Contracts\LikesRepositoryInterface;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(MoviesRepositoryInterface::class, MoviesRepository::class);
        $this->app->bind(CategoriesRepositoryInterface::class, CategoriesRepository::class);
        $this->app->bind(CommentsRepositoryInterface::class, CommentsRepository::class);
        $this->app->bind(RatingsRepositoryInterface::class, RatingsRepository::class);
        $this->app->bind(LikesRepositoryInterface::class, LikesRepository::class);

    }
}
