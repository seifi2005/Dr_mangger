<?php

namespace App\Models;

use Database;

abstract class Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    public function all(int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?"
        );
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $fields = [];
        $placeholders = [];
        
        foreach ($this->fillable as $field) {
            if (isset($data[$field])) {
                $fields[] = $field;
                $placeholders[] = ':' . $field;
            }
        }
        
        if (empty($fields)) {
            throw new \Exception("No fillable fields provided");
        }
        
        $fieldsStr = implode(', ', $fields);
        $placeholdersStr = implode(', ', $placeholders);
        
        $sql = "INSERT INTO {$this->table} ({$fieldsStr}) VALUES ({$placeholdersStr})";
        $stmt = $this->connection->prepare($sql);
        
        foreach ($fields as $field) {
            $stmt->bindValue(':' . $field, $data[$field] ?? null);
        }
        
        $stmt->execute();
        return (int) $this->connection->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $set = [];
        foreach ($this->fillable as $field) {
            if (isset($data[$field])) {
                $set[] = "{$field} = :{$field}";
            }
        }
        
        if (empty($set)) {
            return false;
        }
        
        $setClause = implode(', ', $set);
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id);
        
        foreach ($this->fillable as $field) {
            if (isset($data[$field])) {
                $stmt->bindValue(':' . $field, $data[$field]);
            }
        }
        
        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->connection->prepare(
            "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?"
        );
        return $stmt->execute([$id]);
    }

    public function where(string $field, string $operator, $value): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$field} {$operator} ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$value]);
        return $stmt->fetchAll();
    }

    public function count(): int
    {
        $stmt = $this->connection->query("SELECT COUNT(*) as count FROM {$this->table}");
        $result = $stmt->fetch();
        return (int) $result['count'];
    }

    public function getConnection(): \PDO
    {
        return $this->connection;
    }
}

