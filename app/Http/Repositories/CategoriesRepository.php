<?php declare(strict_types=1);

namespace App\Http\Repositories;

use App\Models\Category;
use App\Http\Contracts\CategoriesRepositoryInterface;

class CategoriesRepository implements CategoriesRepositoryInterface
{
    /**
     * @var Category
     */
    protected Category $category;

    /**
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        $this->model = $model;
    }


    /**
     * @param $categoryId
     * @return mixed
     */
    public function findCategoryById($categoryId):mixed
    {
        return $this->model::find($categoryId);
    }
}
