<?php
/**
 * فایل Cron برای بک‌آپ روزانه دیتابیس
 * 
 * برای اجرای خودکار، این فایل را در Windows Task Scheduler یا Linux Cron اضافه کنید:
 * 
 * Windows Task Scheduler:
 * - برنامه: C:\xampp\php\php.exe
 * - آرگومان: C:\xampp\htdocs\medic\cron_backup.php
 * - زمان: هر روز در ساعت مشخص (مثلاً 2 صبح)
 * 
 * Linux Cron:
 * 0 2 * * * /usr/bin/php /path/to/medic/cron_backup.php
 */

// Set timezone
date_default_timezone_set('Asia/Tehran');

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/app/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Load Services
require_once __DIR__ . '/app/Services/BackupService.php';
require_once __DIR__ . '/app/Services/LogCleanupService.php';

use App\Services\BackupService;
use App\Services\LogCleanupService;

try {
    echo "[" . date('Y-m-d H:i:s') . "] شروع بک‌آپ روزانه...\n";
    
    // Create backup
    $backupService = new BackupService();
    $result = $backupService->createBackup();
    
    if ($result['success']) {
        echo "[" . date('Y-m-d H:i:s') . "] بک‌آپ با موفقیت ایجاد شد: {$result['filename']}\n";
        echo "[" . date('Y-m-d H:i:s') . "] حجم فایل: " . number_format($result['size'] / 1024, 2) . " KB\n";
    } else {
        echo "[" . date('Y-m-d H:i:s') . "] خطا در ایجاد بک‌آپ: {$result['message']}\n";
    }
    
    // Clean old logs
    echo "[" . date('Y-m-d H:i:s') . "] شروع پاک‌سازی لاگ‌های قدیمی...\n";
    $logCleanupService = new LogCleanupService();
    $cleanupResult = $logCleanupService->cleanOldLogs();
    
    if ($cleanupResult['success']) {
        echo "[" . date('Y-m-d H:i:s') . "] {$cleanupResult['message']}\n";
    } else {
        echo "[" . date('Y-m-d H:i:s') . "] خطا در پاک‌سازی لاگ‌ها: {$cleanupResult['message']}\n";
    }
    
    echo "[" . date('Y-m-d H:i:s') . "] بک‌آپ روزانه با موفقیت انجام شد.\n";
    
} catch (Exception $e) {
    echo "[" . date('Y-m-d H:i:s') . "] خطای غیرمنتظره: " . $e->getMessage() . "\n";
    exit(1);
}

