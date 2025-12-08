<?php

namespace App\Models;

class Doctor extends Model
{
    protected $table = 'doctors';
    protected $fillable = [
        'first_name',
        'last_name',
        'national_code',
        'id_number',
        'birth_date',
        'gender',
        'father_name',
        'is_deceased',
        'medical_license',
        'specialty_id',
        'employment_status',
        'medical_org_membership',
        'mobile',
        'clinic_phone',
        'home_phone',
        'email',
        'from_qom',
        'clinic_postal_address',
        'work_address',
        'home_postal_address',
        'clinic_latitude',
        'clinic_longitude',
        'clinic_name',
        'description',
        'registration_date',
        'file_number',
        'image',
        'status'
    ];

    public function getWithSpecialty(int $id): ?array
    {
        $sql = "SELECT d.*, ms.name_fa as specialty_name 
                FROM {$this->table} d 
                LEFT JOIN medical_specialties ms ON d.specialty_id = ms.id 
                WHERE d.id = ?";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function getAllWithSpecialty(int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT d.*, ms.name_fa as specialty_name 
                FROM {$this->table} d 
                LEFT JOIN medical_specialties ms ON d.specialty_id = ms.id 
                ORDER BY d.created_at DESC
                LIMIT ? OFFSET ?";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function search(string $term, int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT d.*, ms.name_fa as specialty_name 
                FROM {$this->table} d 
                LEFT JOIN medical_specialties ms ON d.specialty_id = ms.id 
                WHERE d.first_name LIKE ? 
                   OR d.last_name LIKE ?
                   OR d.national_code LIKE ?
                   OR d.medical_license LIKE ?
                ORDER BY d.created_at DESC
                LIMIT ? OFFSET ?";
        
        $searchTerm = "%{$term}%";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $limit, $offset]);
        return $stmt->fetchAll();
    }

    public function searchCount(string $term): int
    {
        $sql = "SELECT COUNT(*) as count
                FROM {$this->table} d 
                WHERE d.first_name LIKE ? 
                   OR d.last_name LIKE ?
                   OR d.national_code LIKE ?
                   OR d.medical_license LIKE ?";
        
        $searchTerm = "%{$term}%";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        $result = $stmt->fetch();
        return (int) $result['count'];
    }

    public function getByClinicName(string $clinicName, int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT d.*, ms.name_fa as specialty_name 
                FROM {$this->table} d 
                LEFT JOIN medical_specialties ms ON d.specialty_id = ms.id 
                WHERE d.clinic_name = ?
                ORDER BY d.first_name ASC, d.last_name ASC
                LIMIT ? OFFSET ?";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$clinicName, $limit, $offset]);
        return $stmt->fetchAll();
    }

    public function countByClinicName(string $clinicName): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE clinic_name = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$clinicName]);
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

    public function filter(array $filters = [], int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT d.*, ms.name_fa as specialty_name 
                FROM {$this->table} d 
                LEFT JOIN medical_specialties ms ON d.specialty_id = ms.id 
                WHERE 1=1";
        $params = [];

        if (!empty($filters['first_name'])) {
            $sql .= " AND d.first_name LIKE ?";
            $params[] = "%{$filters['first_name']}%";
        }

        if (!empty($filters['last_name'])) {
            $sql .= " AND d.last_name LIKE ?";
            $params[] = "%{$filters['last_name']}%";
        }

        if (!empty($filters['national_code'])) {
            $sql .= " AND d.national_code LIKE ?";
            $params[] = "%{$filters['national_code']}%";
        }

        if (!empty($filters['id_number'])) {
            $sql .= " AND d.id_number LIKE ?";
            $params[] = "%{$filters['id_number']}%";
        }

        if (!empty($filters['medical_license'])) {
            $sql .= " AND d.medical_license LIKE ?";
            $params[] = "%{$filters['medical_license']}%";
        }

        if (!empty($filters['mobile'])) {
            $sql .= " AND d.mobile LIKE ?";
            $params[] = "%{$filters['mobile']}%";
        }

        if (isset($filters['from_qom']) && $filters['from_qom'] !== '') {
            $sql .= " AND d.from_qom = ?";
            $params[] = $filters['from_qom'] == '1' ? 1 : 0;
        }

        if (!empty($filters['file_number'])) {
            $sql .= " AND d.file_number LIKE ?";
            $params[] = "%{$filters['file_number']}%";
        }

        if (isset($filters['is_deceased']) && $filters['is_deceased'] !== '') {
            $sql .= " AND d.is_deceased = ?";
            $params[] = $filters['is_deceased'] == '1' ? 1 : 0;
        }

        if (!empty($filters['specialty_id'])) {
            $sql .= " AND d.specialty_id = ?";
            $params[] = $filters['specialty_id'];
        }

        if (!empty($filters['clinic_name'])) {
            $sql .= " AND d.clinic_name LIKE ?";
            $params[] = "%{$filters['clinic_name']}%";
        }

        if (!empty($filters['status'])) {
            $sql .= " AND d.status = ?";
            $params[] = $filters['status'];
        }

        $sql .= " ORDER BY d.clinic_name ASC, d.first_name ASC, d.last_name ASC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function filterCount(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) as count 
                FROM {$this->table} d 
                LEFT JOIN medical_specialties ms ON d.specialty_id = ms.id 
                WHERE 1=1";
        $params = [];

        if (!empty($filters['first_name'])) {
            $sql .= " AND d.first_name LIKE ?";
            $params[] = "%{$filters['first_name']}%";
        }

        if (!empty($filters['last_name'])) {
            $sql .= " AND d.last_name LIKE ?";
            $params[] = "%{$filters['last_name']}%";
        }

        if (!empty($filters['national_code'])) {
            $sql .= " AND d.national_code LIKE ?";
            $params[] = "%{$filters['national_code']}%";
        }

        if (!empty($filters['id_number'])) {
            $sql .= " AND d.id_number LIKE ?";
            $params[] = "%{$filters['id_number']}%";
        }

        if (!empty($filters['medical_license'])) {
            $sql .= " AND d.medical_license LIKE ?";
            $params[] = "%{$filters['medical_license']}%";
        }

        if (!empty($filters['mobile'])) {
            $sql .= " AND d.mobile LIKE ?";
            $params[] = "%{$filters['mobile']}%";
        }

        if (isset($filters['from_qom']) && $filters['from_qom'] !== '') {
            $sql .= " AND d.from_qom = ?";
            $params[] = $filters['from_qom'] == '1' ? 1 : 0;
        }

        if (!empty($filters['file_number'])) {
            $sql .= " AND d.file_number LIKE ?";
            $params[] = "%{$filters['file_number']}%";
        }

        if (isset($filters['is_deceased']) && $filters['is_deceased'] !== '') {
            $sql .= " AND d.is_deceased = ?";
            $params[] = $filters['is_deceased'] == '1' ? 1 : 0;
        }

        if (!empty($filters['specialty_id'])) {
            $sql .= " AND d.specialty_id = ?";
            $params[] = $filters['specialty_id'];
        }

        if (!empty($filters['clinic_name'])) {
            $sql .= " AND d.clinic_name LIKE ?";
            $params[] = "%{$filters['clinic_name']}%";
        }

        if (!empty($filters['status'])) {
            $sql .= " AND d.status = ?";
            $params[] = $filters['status'];
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return (int) $result['count'];
    }

    public function getGroupedByClinic(array $filters = []): array
    {
        $doctors = $this->filter($filters, 10000, 0); // Get all filtered doctors
        
        $grouped = [];
        foreach ($doctors as $doctor) {
            $clinicName = $doctor['clinic_name'] ?? 'بدون مرکز درمانی';
            if (!isset($grouped[$clinicName])) {
                $grouped[$clinicName] = [];
            }
            $grouped[$clinicName][] = $doctor;
        }
        
        ksort($grouped); // Sort by clinic name
        return $grouped;
    }

    public function getDistinctClinicNames(): array
    {
        $sql = "SELECT DISTINCT clinic_name FROM {$this->table} WHERE clinic_name IS NOT NULL AND clinic_name != '' ORDER BY clinic_name ASC";
        $stmt = $this->connection->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}

