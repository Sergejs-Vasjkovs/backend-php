<?php

declare(strict_types=1);

namespace App\Core\Config;

class ApiConfig
{
    // Pagination
    public const DEFAULT_PAGE = 1;
    public const DEFAULT_PER_PAGE = 10;
    public const MAX_PER_PAGE = 100;

    // Sorting
    public const DEFAULT_SORT_FIELD = 'id';
    public const DEFAULT_SORT_ORDER = 'asc';
    public const ALLOWED_SORT_ORDERS = ['asc', 'desc'];

    // Statuses
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const ALLOWED_STATUSES = [self::STATUS_ACTIVE, self::STATUS_INACTIVE];
}
