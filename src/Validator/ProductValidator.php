<?php

declare(strict_types=1);

namespace App\Validator;

use App\Core\Exception\ValidationException;

class ProductValidator
{
    public function validate(array $data): void
    {
        $errors = [];

        if (!isset($data['name']) || empty($data['name'])) {
            $errors['name'] = 'Product name is required';
        }

        if (!isset($data['price']) || !is_numeric($data['price']) || $data['price'] < 0) {
            $errors['price'] = 'Product price must be a positive number';
        }

        if (!isset($data['sku']) || empty($data['sku'])) {
            $errors['sku'] = 'Product SKU is required';
        }

        if (!isset($data['category_id']) || !is_numeric($data['category_id']) || $data['category_id'] <= 0) {
            $errors['category_id'] = 'Valid category_id is required';
        }

        if (!isset($data['stock_quantity']) || !is_numeric($data['stock_quantity']) || $data['stock_quantity'] < 0) {
            $errors['stock_quantity'] = 'Stock quantity must be a non-negative number';
        }

        if (!isset($data['status']) || !in_array($data['status'], ['active', 'inactive'])) {
            $errors['status'] = 'Status must be either active or inactive';
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
    }
}
