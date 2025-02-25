<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\AbstractModel;

class Product extends AbstractModel
{
    protected array $sortableFields = [
        'id',
        'name',
        'price',
        'stock_quantity'
    ];

    public function getTable(): string
    {
        return 'products';
    }

    public function findWithCategory(int $id): ?array
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->getTable()} p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findAllWithCategories(array $conditions = []): array
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->getTable()} p
                LEFT JOIN categories c ON p.category_id = c.id";
        $params = [];

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $whereClauses = [];
            foreach ($conditions as $key => $value) {
                $whereClauses[] = "p.$key = :$key";
                $params[$key] = $value;
            }
            $sql .= implode(' AND ', $whereClauses);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function findAllWithPaginationAndCategories(
        array $filters = [],
        string $sortField = 'id',
        string $sortOrder = 'desc',
        int $limit = 10,
        int $offset = 0
    ): array {
        $whereData = $this->buildWhereClause($filters);

        $sortField = $this->validateSortField($sortField);
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';

        $sql = sprintf(
            "SELECT p.*, c.name as category_name 
             FROM %s p
             LEFT JOIN categories c ON p.category_id = c.id
             %s 
             ORDER BY p.%s %s 
             LIMIT :limit OFFSET :offset",
            $this->getTable(),
            $whereData['where'],
            $sortField,
            $sortOrder
        );

        $params = array_merge($whereData['params'], [
            'limit' => $limit,
            'offset' => $offset
        ]);

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function countWithFilters(array $filters = []): int
    {
        $whereData = $this->buildWhereClause($filters);

        $sql = sprintf(
            "SELECT COUNT(*) FROM %s p %s",
            $this->getTable(),
            $whereData['where']
        );

        $stmt = $this->db->prepare($sql);
        $stmt->execute($whereData['params']);

        return (int)$stmt->fetchColumn();
    }
}
