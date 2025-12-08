<?php

namespace App\Services;

use App\Models\ActivityLog;

class LogCleanupService
{
    private $activityLogModel;

    public function __construct()
    {
        $this->activityLogModel = new ActivityLog();
    }

    /**
     * پاک‌سازی لاگ‌های قدیمی (بیش از 20 روز)
     * 
     * @return array ['success' => bool, 'deleted_count' => int, 'message' => string]
     */
    public function cleanOldLogs(): array
    {
        try {
            $connection = $this->activityLogModel->getConnection();
            
            // Count logs to be deleted first
            $countSql = "SELECT COUNT(*) as count FROM activity_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 20 DAY)";
            $countStmt = $connection->query($countSql);
            $countResult = $countStmt->fetch();
            $deletedCount = (int) $countResult['count'];
            
            if ($deletedCount > 0) {
                // Delete logs older than 20 days
                $sql = "DELETE FROM activity_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 20 DAY)";
                $stmt = $connection->prepare($sql);
                $stmt->execute();
            }
            
            return [
                'success' => true,
                'deleted_count' => $deletedCount,
                'message' => $deletedCount > 0 ? "{$deletedCount} لاگ قدیمی حذف شد" : "لاگ قدیمی برای حذف وجود ندارد"
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'deleted_count' => 0,
                'message' => 'خطا در پاک‌سازی لاگ‌ها: ' . $e->getMessage()
            ];
        }
    }

    /**
     * دریافت تعداد لاگ‌های قدیمی
     */
    public function getOldLogsCount(): int
    {
        try {
            $connection = $this->activityLogModel->getConnection();
            
            $sql = "SELECT COUNT(*) as count FROM activity_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 20 DAY)";
            $stmt = $connection->query($sql);
            $result = $stmt->fetch();
            
            return (int) $result['count'];
            
        } catch (\Exception $e) {
            return 0;
        }
    }
}

