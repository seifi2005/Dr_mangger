<?php

namespace App\Models;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'first_name',
        'last_name',
        'national_code',
        'mobile',
        'email',
        'password',
        'role',
        'address',
        'image',
        'status'
    ];

    public function search(string $term, int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE first_name LIKE ? 
                   OR last_name LIKE ? 
                   OR national_code LIKE ?
                   OR mobile LIKE ?
                ORDER BY created_at DESC
                LIMIT ? OFFSET ?";
        
        $searchTerm = "%{$term}%";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $limit, $offset]);
        return $stmt->fetchAll();
    }

    public function searchCount(string $term): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE first_name LIKE ? 
                   OR last_name LIKE ? 
                   OR national_code LIKE ?
                   OR mobile LIKE ?";
        
        $searchTerm = "%{$term}%";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        $result = $stmt->fetch();
        return (int) $result['count'];
    }

    public function findByNationalCode(string $nationalCode, ?int $excludeId = null): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE national_code = ?";
        $params = [$nationalCode];
        
        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() ?: null;
    }

    public function findByMobile(string $mobile, ?int $excludeId = null): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE mobile = ?";
        $params = [$mobile];
        
        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() ?: null;
    }
}

