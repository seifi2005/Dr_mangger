<?php

namespace App\Models;

class Payment extends Model
{
    protected $table = 'doctor_payments';
    protected $fillable = [
        'doctor_id',
        'receipt_number',
        'payment_date',
        'amount',
        'receipt_image'
    ];

    public function getByDoctorId(int $doctorId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE doctor_id = ? ORDER BY payment_date DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$doctorId]);
        return $stmt->fetchAll();
    }
}

