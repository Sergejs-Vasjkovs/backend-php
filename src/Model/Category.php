<?php

declare(strict_types=1);

namespace App\Model;

use App\Core\Cache\Redis;

class Category extends AbstractModel
{
    private const CACHE_KEY = 'categories:all';

    protected array $sortableFields = [
        'id',
        'name'
    ];

    public function getTable(): string
    {
        return 'categories';
    }

    public function findAllWithProductCount(): array
    {
        // get from cache
        $redis = Redis::getInstance();
        $cached = $redis ? $redis->get(self::CACHE_KEY) : null;

        if ($cached !== null) {
            return json_decode($cached, true);
        }

        // if cache not found - get from db
        $sql = "SELECT c.*, COUNT(p.id) as product_count 
                FROM categories c
                LEFT JOIN products p ON c.id = p.category_id
                GROUP BY c.id
                ORDER BY c.id ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $categories = $stmt->fetchAll();

        // save to cache
        if ($redis) {
            $redis->setex(
                self::CACHE_KEY,
                Redis::getTtl(),
                json_encode($categories)
            );
        }

        return $categories;
    }

    public function clearCache(): void
    {
        $redis = Redis::getInstance();
        if ($redis) {
            $redis->del(self::CACHE_KEY);
        }
    }
}
