<?php

namespace App\Models;

class Specialty extends Model
{
    protected $table = 'medical_specialties';
    protected $fillable = [
        'name_fa',
        'name_en',
        'description',
        'status'
    ];

    public function getDoctorsCount(int $specialtyId): int
    {
        $sql = "SELECT COUNT(*) as count FROM doctors WHERE specialty_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$specialtyId]);
        $result = $stmt->fetch();
        return (int) $result['count'];
    }

    public function getWithDoctorsCount(int $id): ?array
    {
        $sql = "SELECT s.*, COUNT(d.id) as doctors_count 
                FROM {$this->table} s 
                LEFT JOIN doctors d ON s.id = d.specialty_id 
                WHERE s.id = ?
                GROUP BY s.id";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}

