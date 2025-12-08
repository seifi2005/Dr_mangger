<?php

namespace App\Models;

class MedicalCenter extends Model
{
    protected $table = 'medical_centers';
    protected $fillable = [
        'name',
        'type',
        'license_number',
        'manager_name',
        'phone',
        'address',
        'status'
    ];

    public function search(string $term, int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE name LIKE ? 
                   OR license_number LIKE ? 
                   OR manager_name LIKE ?
                ORDER BY created_at DESC
                LIMIT ? OFFSET ?";
        
        $searchTerm = "%{$term}%";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $limit, $offset]);
        return $stmt->fetchAll();
    }

    public function searchCount(string $term): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE name LIKE ? 
                   OR license_number LIKE ? 
                   OR manager_name LIKE ?";
        
        $searchTerm = "%{$term}%";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        $result = $stmt->fetch();
        return (int) $result['count'];
    }
}

