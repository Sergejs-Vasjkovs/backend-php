<?php

declare(strict_types=1);

namespace App\Model;

use App\Core\Database\Database;
use PDO;

abstract class AbstractModel
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';

    protected array $sortableFields = ['id'];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    abstract public function getTable(): string;

    protected function buildWhereClause(array $filters): array
    {
        $where = [];
        $params = [];

        foreach ($filters as $field => $value) {
            switch ($field) {
                case 'price_from':
                    $where[] = 'p.price >= :price_from';
                    $params['price_from'] = $value;
                    break;
                case 'price_to':
                    $where[] = 'p.price <= :price_to';
                    $params['price_to'] = $value;
                    break;
                case 'stock_quantity_from':
                    $where[] = 'p.stock_quantity >= :stock_quantity_from';
                    $params['stock_quantity_from'] = $value;
                    break;
                case 'stock_quantity_to':
                    $where[] = 'p.stock_quantity <= :stock_quantity_to';
                    $params['stock_quantity_to'] = $value;
                    break;
                case 'category_id':
                    $where[] = 'p.category_id = :category_id';
                    $params['category_id'] = $value;
                    break;
                case 'status':
                    $where[] = 'p.status = :status';
                    $params['status'] = $value;
                    break;
            }
        }

        return [
            'where' => $where ? ' WHERE ' . implode(' AND ', $where) : '',
            'params' => $params
        ];
    }

    protected function validateSortField(string $field): string
    {
        return in_array($field, $this->sortableFields) ? $field : 'id';
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->getTable()} WHERE {$this->primaryKey} = :id"
        );
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findAll(array $conditions = []): array
    {
        $sql = "SELECT * FROM {$this->getTable()}";
        $params = [];

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $whereClauses = [];
            foreach ($conditions as $key => $value) {
                $whereClauses[] = "$key = :$key";
                $params[$key] = $value;
            }
            $sql .= implode(' AND ', $whereClauses);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $fields = array_keys($data);
        $values = array_map(fn($field) => ":$field", $fields);

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->getTable(),
            implode(', ', $fields),
            implode(', ', $values)
        );

        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $fields = array_map(
            fn($field) => "$field = :$field",
            array_keys($data)
        );

        $sql = sprintf(
            "UPDATE %s SET %s WHERE %s = :id",
            $this->getTable(),
            implode(', ', $fields),
            $this->primaryKey
        );

        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->getTable()} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }

    public function findAllWithPagination(array $filters = [], string $sortField = 'id', string $sortOrder = 'desc', int $limit = 10, int $offset = 0): array
    {
        $whereData = $this->buildWhereClause($filters);

        $sortField = $this->validateSortField($sortField);
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';

        $sql = sprintf(
            "SELECT * FROM %s%s ORDER BY %s %s LIMIT :limit OFFSET :offset",
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

    public function count(array $filters = []): int
    {
        $whereData = $this->buildWhereClause($filters);

        $sql = sprintf(
            "SELECT COUNT(*) FROM %s%s",
            $this->getTable(),
            $whereData['where']
        );

        $stmt = $this->db->prepare($sql);
        $stmt->execute($whereData['params']);

        return (int)$stmt->fetchColumn();
    }
}
