<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\AbstractController;
use App\Model\Product;
use App\Validator\ProductValidator;
use App\Core\Exception\ValidationException;
use App\Core\Http\RequestParameters;
use Laminas\Diactoros\Response\JsonResponse;

class ProductController extends AbstractController
{
    private Product $product;
    private ProductValidator $validator;
    private RequestParameters $requestParameters;

    public function __construct()
    {
        parent::__construct();
        $this->product = new Product();
        $this->validator = new ProductValidator();
        $this->requestParameters = new RequestParameters($this->request->getQueryParams());
    }

    public function index(): JsonResponse
    {
        $page = $this->requestParameters->getPage();
        $perPage = $this->requestParameters->getPerPage();
        $sortField = $this->requestParameters->getSortField();
        $sortOrder = $this->requestParameters->getSortOrder();
        $filters = $this->requestParameters->getFilters();

        $products = $this->product->findAllWithPaginationAndCategories(
            $filters,
            $sortField,
            $sortOrder,
            $perPage,
            $this->requestParameters->getOffset()
        );

        $total = $this->product->countWithFilters($filters);

        $meta = [
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => ceil($total / $perPage)
        ];

        return $this->success([
            'items' => $products,
            'meta' => $meta
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->product->findWithCategory($id);

        if (!$product) {
            return $this->error('Product not found', 404);
        }

        return $this->success($product);
    }

    public function create(): JsonResponse
    {
        $data = $this->request->getBody();

        try {
            $this->validator->validate($data);
        } catch (ValidationException $e) {
            return $this->error('Validation failed', 422, $e->getErrors());
        }

        $id = $this->product->create($data);
        $product = $this->product->findWithCategory($id);

        return $this->success($product, 'Product created successfully');
    }

    public function update(int $id): JsonResponse
    {
        if (!$this->product->find($id)) {
            return $this->error('Product not found', 404);
        }

        $data = $this->request->getBody();

        try {
            $this->validator->validate($data);
        } catch (ValidationException $e) {
            return $this->error('Validation failed', 422, $e->getErrors());
        }

        $this->product->update($id, $data);
        $product = $this->product->findWithCategory($id);

        return $this->success($product, 'Product updated successfully');
    }

    public function delete(int $id): JsonResponse
    {
        if (!$this->product->find($id)) {
            return $this->error('Product not found', 404);
        }

        $this->product->delete($id);
        return $this->success([], 'Product with id: ' . $id . ' deleted successfully');
    }
}
