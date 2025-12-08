<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogService
{
    private $activityLogModel;

    public function __construct()
    {
        $this->activityLogModel = new ActivityLog();
    }

    /**
     * ثبت فعالیت
     * 
     * @param string $action نوع فعالیت (create, update, delete, view, etc.)
     * @param string $entityType نوع موجودیت (doctor, user, pharmacy, etc.)
     * @param int|null $entityId شناسه موجودیت
     * @param string|null $entityName نام موجودیت
     * @param string|null $description توضیحات اضافی
     * @return void
     */
    public function log(
        string $action,
        string $entityType,
        ?int $entityId = null,
        ?string $entityName = null,
        ?string $description = null
    ): void {
        $userId = $_SESSION['user_id'] ?? null;
        
        $data = [
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'entity_name' => $entityName,
            'description' => $description,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ];

        try {
            $this->activityLogModel->create($data);
        } catch (\Exception $e) {
            // Log error but don't break the application
            error_log("Failed to log activity: " . $e->getMessage());
        }
    }

    /**
     * ثبت فعالیت ایجاد
     */
    public function logCreate(string $entityType, int $entityId, string $entityName, ?string $description = null): void
    {
        $this->log('create', $entityType, $entityId, $entityName, $description ?? "ایجاد {$entityName}");
    }

    /**
     * ثبت فعالیت ویرایش
     */
    public function logUpdate(string $entityType, int $entityId, string $entityName, ?string $description = null): void
    {
        $this->log('update', $entityType, $entityId, $entityName, $description ?? "ویرایش {$entityName}");
    }

    /**
     * ثبت فعالیت حذف
     */
    public function logDelete(string $entityType, int $entityId, string $entityName, ?string $description = null): void
    {
        $this->log('delete', $entityType, $entityId, $entityName, $description ?? "حذف {$entityName}");
    }

    /**
     * ثبت فعالیت مشاهده
     */
    public function logView(string $entityType, int $entityId, string $entityName): void
    {
        $this->log('view', $entityType, $entityId, $entityName, "مشاهده {$entityName}");
    }

    /**
     * ثبت فعالیت جستجو
     */
    public function logSearch(string $entityType, string $searchTerm): void
    {
        $this->log('search', $entityType, null, null, "جستجو: {$searchTerm}");
    }

    /**
     * دریافت آخرین فعالیت‌ها
     */
    public function getRecent(int $limit = 10): array
    {
        return $this->activityLogModel->getRecent($limit);
    }

    /**
     * دریافت همه فعالیت‌ها
     */
    public function getAll(int $limit = 50, int $offset = 0): array
    {
        return $this->activityLogModel->getAll($limit, $offset);
    }

    /**
     * دریافت فعالیت‌ها بر اساس نوع موجودیت
     */
    public function getByEntityType(string $entityType, int $limit = 50): array
    {
        return $this->activityLogModel->getByEntityType($entityType, $limit);
    }

    /**
     * دریافت فعالیت‌های یک کاربر
     */
    public function getByUser(int $userId, int $limit = 50): array
    {
        return $this->activityLogModel->getByUser($userId, $limit);
    }
}

