<?php

declare(strict_types=1);

namespace App\Core\Http;

use App\Core\Config\ApiConfig;

class RequestParameters
{
    private array $parameters;

    public function __construct(array $queryParams)
    {
        $this->parameters = $queryParams;
    }

    public function getPage(): int
    {
        return max(1, (int)($this->parameters['page'] ?? ApiConfig::DEFAULT_PAGE));
    }

    public function getPerPage(): int
    {
        $perPage = (int)($this->parameters['per_page'] ?? ApiConfig::DEFAULT_PER_PAGE);
        return min(max(1, $perPage), ApiConfig::MAX_PER_PAGE);
    }

    public function getOffset(): int
    {
        return ($this->getPage() - 1) * $this->getPerPage();
    }

    public function getSortField(): string
    {
        return $this->parameters['sort'] ?? ApiConfig::DEFAULT_SORT_FIELD;
    }

    public function getSortOrder(): string
    {
        $order = strtolower($this->parameters['order'] ?? ApiConfig::DEFAULT_SORT_ORDER);
        return in_array($order, ApiConfig::ALLOWED_SORT_ORDERS) ? $order : ApiConfig::DEFAULT_SORT_ORDER;
    }

    public function getFilters(): array
    {
        $filters = [];

        if (isset($this->parameters['price_from'])) {
            $filters['price_from'] = (float)$this->parameters['price_from'];
        }
        if (isset($this->parameters['price_to'])) {
            $filters['price_to'] = (float)$this->parameters['price_to'];
        }

        if (isset($this->parameters['stock_quantity_from'])) {
            $filters['stock_quantity_from'] = (int)$this->parameters['stock_quantity_from'];
        }
        if (isset($this->parameters['stock_quantity_to'])) {
            $filters['stock_quantity_to'] = (int)$this->parameters['stock_quantity_to'];
        }

        if (isset($this->parameters['category_id'])) {
            $filters['category_id'] = (int)$this->parameters['category_id'];
        }

        if (isset($this->parameters['status'])) {
            $filters['status'] = $this->parameters['status'];
        }

        return $filters;
    }
}
