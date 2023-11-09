<?php declare(strict_types=1);

namespace App\Http\Contracts;

use App\Models\Category;

interface CategoriesRepositoryInterface
{
    /**
     * @param int $categoryId
     * @return mixed
     */
    public function findCategoryById(int $categoryId): mixed;
}
