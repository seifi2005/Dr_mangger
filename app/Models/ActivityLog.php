<?php

namespace App\Models;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';
    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'entity_name',
        'description',
        'ip_address',
        'user_agent'
    ];

    public function getRecent(int $limit = 10): array
    {
        $sql = "SELECT al.*, u.first_name, u.last_name 
                FROM {$this->table} al
                LEFT JOIN users u ON al.user_id = u.id
                ORDER BY al.created_at DESC
                LIMIT ?";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getAll(int $limit = 50, int $offset = 0, ?string $entityType = null, ?string $action = null): array
    {
        $conditions = [];
        $params = [];
        
        if (!empty($entityType)) {
            $conditions[] = "al.entity_type = ?";
            $params[] = $entityType;
        }
        
        if (!empty($action)) {
            $conditions[] = "al.action = ?";
            $params[] = $action;
        }
        
        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
        
        $sql = "SELECT al.*, u.first_name, u.last_name 
                FROM {$this->table} al
                LEFT JOIN users u ON al.user_id = u.id
                {$whereClause}
                ORDER BY al.created_at DESC
                LIMIT ? OFFSET ?";
        
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function count(?string $entityType = null, ?string $action = null): int
    {
        $conditions = [];
        $params = [];
        
        if (!empty($entityType)) {
            $conditions[] = "entity_type = ?";
            $params[] = $entityType;
        }
        
        if (!empty($action)) {
            $conditions[] = "action = ?";
            $params[] = $action;
        }
        
        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
        
        $sql = "SELECT COUNT(*) as count FROM {$this->table} {$whereClause}";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return (int) $result['count'];
    }

    public function getByEntityType(string $entityType, int $limit = 50): array
    {
        $sql = "SELECT al.*, u.first_name, u.last_name 
                FROM {$this->table} al
                LEFT JOIN users u ON al.user_id = u.id
                WHERE al.entity_type = ?
                ORDER BY al.created_at DESC
                LIMIT ?";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$entityType, $limit]);
        return $stmt->fetchAll();
    }

    public function getByUser(int $userId, int $limit = 50): array
    {
        $sql = "SELECT al.*, u.first_name, u.last_name 
                FROM {$this->table} al
                LEFT JOIN users u ON al.user_id = u.id
                WHERE al.user_id = ?
                ORDER BY al.created_at DESC
                LIMIT ?";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    }

}

