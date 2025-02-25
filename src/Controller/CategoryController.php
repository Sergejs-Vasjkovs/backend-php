<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Category;
use Laminas\Diactoros\Response\JsonResponse;

class CategoryController extends AbstractController
{
    private Category $category;

    public function __construct()
    {
        parent::__construct();
        $this->category = new Category();
    }

    public function index(): JsonResponse
    {
        $categories = $this->category->findAllWithProductCount();
        return $this->success($categories);
    }

    public function show(int $id): JsonResponse
    {
        $category = $this->category->find($id);

        if (!$category) {
            return $this->error('Category not found', 404);
        }

        return $this->success($category);
    }

    public function refreshCache(): JsonResponse
    {
        $this->category->clearCache();
        return $this->success([], 'Categories cache cleared');
    }
}
