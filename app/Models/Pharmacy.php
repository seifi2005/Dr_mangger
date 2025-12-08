<?php

namespace App\Models;

class Pharmacy extends Model
{
    protected $table = 'pharmacies';
    protected $fillable = [
        'name',
        'license_number',
        'owner_name',
        'owner_national_code',
        'phone',
        'mobile',
        'address',
        'province',
        'city',
        'district',
        'latitude',
        'longitude',
        'status'
    ];

    public function search(string $term, int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE name LIKE ? 
                   OR license_number LIKE ? 
                   OR owner_name LIKE ?
                   OR address LIKE ?
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
                WHERE name LIKE ? 
                   OR license_number LIKE ? 
                   OR owner_name LIKE ?
                   OR address LIKE ?";
        
        $searchTerm = "%{$term}%";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        $result = $stmt->fetch();
        return (int) $result['count'];
    }

    public function getByProvinceAndCity(?string $province = null, ?string $city = null, ?string $district = null): array
    {
        $conditions = [];
        $params = [];

        if ($province) {
            $conditions[] = "province = ?";
            $params[] = $province;
        }

        if ($city) {
            $conditions[] = "city = ?";
            $params[] = $city;
        }

        if ($district) {
            $conditions[] = "district = ?";
            $params[] = $district;
        }

        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
        
        $sql = "SELECT * FROM {$this->table} {$whereClause} ORDER BY created_at DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getDistinctProvinces(): array
    {
        $sql = "SELECT DISTINCT province FROM {$this->table} WHERE province IS NOT NULL AND province != '' ORDER BY province ASC";
        $stmt = $this->connection->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function getDistinctCities(?string $province = null): array
    {
        $sql = "SELECT DISTINCT city FROM {$this->table} WHERE city IS NOT NULL AND city != ''";
        $params = [];
        
        if ($province) {
            $sql .= " AND province = ?";
            $params[] = $province;
        }
        
        $sql .= " ORDER BY city ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function getDistinctDistricts(?string $province = null, ?string $city = null): array
    {
        $sql = "SELECT DISTINCT district FROM {$this->table} WHERE district IS NOT NULL AND district != ''";
        $params = [];
        
        if ($province) {
            $sql .= " AND province = ?";
            $params[] = $province;
        }
        
        if ($city) {
            $sql .= " AND city = ?";
            $params[] = $city;
        }
        
        $sql .= " ORDER BY district ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}

