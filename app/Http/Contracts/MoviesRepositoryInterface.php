<?php declare(strict_types=1);

namespace App\Http\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Movie;

interface MoviesRepositoryInterface
{
    /**
     * @param Category $category
     * @param $lang
     * @param $perPage
     * @return mixed
     */
    public function getMoviesInCategory(Category $category, string $lang = 'en', int $perPage = 20): mixed;

    /**
     * @param $movies
     * @param $lang
     * @return mixed
     */
    public function transformMoviesContent($movies, $lang): mixed;

    /**
     * @param $id
     * @return Movie | null
     */
    public function findMovieById($id):  Movie | null;

    /**
     * @param $keyword
     * @param string $lang
     * @return Collection
     */
    public function searchMoviesByKeyword($keyword, string $lang = 'en'): Collection;

    /**
     * @param $movieId
     * @param $userId
     * @return mixed
     */
    public function checkLikeStatus($movieId, $userId): mixed;

    /**
     * @param $movieData
     * @param $movieDataRu
     * @param $categoryId
     * @return Movie
     */
    public function createNewMovie($movieData, $movieDataRu, $categoryId): Movie;
}
