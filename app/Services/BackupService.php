<?php

namespace App\Services;

class BackupService
{
    private $config;
    private $backupPath;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/config.php';
        $this->backupPath = __DIR__ . '/../../backups/';
        
        // Create backup directory if it doesn't exist
        if (!is_dir($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
    }

    /**
     * ایجاد بک‌آپ از دیتابیس
     * 
     * @return array ['success' => bool, 'message' => string, 'filename' => string|null]
     */
    public function createBackup(): array
    {
        try {
            $dbConfig = $this->config['database'];
            
            // Generate backup filename with timestamp
            $timestamp = date('Y-m-d_H-i-s');
            $filename = "backup_{$timestamp}.sql";
            $filepath = $this->backupPath . $filename;
            
            // Try to find mysqldump path
            $mysqldumpPath = '';
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Windows - try common XAMPP paths
                $possiblePaths = [
                    'C:\\xampp\\mysql\\bin\\mysqldump.exe',
                    'C:\\xampp\\mysql\\bin\\mysqldump',
                    'mysqldump'
                ];
                foreach ($possiblePaths as $path) {
                    if (file_exists($path) || $path === 'mysqldump') {
                        $mysqldumpPath = $path;
                        break;
                    }
                }
            } else {
                $mysqldumpPath = 'mysqldump';
            }
            
            // Build command
            $passwordPart = !empty($dbConfig['password']) ? '-p' . escapeshellarg($dbConfig['password']) : '';
            $command = sprintf(
                '%s -h %s -u %s %s %s > %s 2>&1',
                escapeshellarg($mysqldumpPath),
                escapeshellarg($dbConfig['host']),
                escapeshellarg($dbConfig['user']),
                $passwordPart,
                escapeshellarg($dbConfig['name']),
                escapeshellarg($filepath)
            );
            
            // Execute backup
            exec($command, $output, $returnVar);
            
            if ($returnVar !== 0 || !file_exists($filepath) || filesize($filepath) == 0) {
                // Try alternative method using PHP
                return $this->createBackupPhp($filename, $filepath);
            }
            
            // Clean old backups (keep only 2 latest)
            $this->cleanOldBackups();
            
            return [
                'success' => true,
                'message' => 'بک‌آپ با موفقیت ایجاد شد',
                'filename' => $filename,
                'filepath' => $filepath,
                'size' => filesize($filepath)
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'خطا در ایجاد بک‌آپ: ' . $e->getMessage()
            ];
        }
    }

    /**
     * ایجاد بک‌آپ با استفاده از PHP (fallback)
     */
    private function createBackupPhp(string $filename, string $filepath): array
    {
        try {
            $dbConfig = $this->config['database'];
            $connection = new \PDO(
                "mysql:host={$dbConfig['host']};dbname={$dbConfig['name']};charset={$dbConfig['charset']}",
                $dbConfig['user'],
                $dbConfig['password']
            );
            
            $tables = $connection->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
            
            $output = "-- Database Backup\n";
            $output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
            $output .= "-- Database: {$dbConfig['name']}\n\n";
            $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
            
            foreach ($tables as $table) {
                // Get table structure
                $createTable = $connection->query("SHOW CREATE TABLE `{$table}`")->fetch();
                $output .= "DROP TABLE IF EXISTS `{$table}`;\n";
                $output .= $createTable['Create Table'] . ";\n\n";
                
                // Get table data
                $rows = $connection->query("SELECT * FROM `{$table}`")->fetchAll(\PDO::FETCH_ASSOC);
                if (!empty($rows)) {
                    $output .= "INSERT INTO `{$table}` VALUES\n";
                    $values = [];
                    foreach ($rows as $row) {
                        $rowValues = [];
                        foreach ($row as $value) {
                            if ($value === null) {
                                $rowValues[] = 'NULL';
                            } else {
                                $rowValues[] = $connection->quote($value);
                            }
                        }
                        $values[] = '(' . implode(',', $rowValues) . ')';
                    }
                    $output .= implode(",\n", $values) . ";\n\n";
                }
            }
            
            $output .= "SET FOREIGN_KEY_CHECKS=1;\n";
            
            file_put_contents($filepath, $output);
            
            // Clean old backups
            $this->cleanOldBackups();
            
            return [
                'success' => true,
                'message' => 'بک‌آپ با موفقیت ایجاد شد',
                'filename' => $filename,
                'filepath' => $filepath,
                'size' => filesize($filepath)
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'خطا در ایجاد بک‌آپ: ' . $e->getMessage()
            ];
        }
    }

    /**
     * پاک‌سازی بک‌آپ‌های قدیمی (فقط 2 تا آخرین را نگه می‌دارد)
     */
    private function cleanOldBackups(): void
    {
        $files = glob($this->backupPath . 'backup_*.sql');
        
        if (count($files) <= 2) {
            return;
        }
        
        // Sort by modification time (newest first)
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        
        // Keep only 2 latest, delete the rest
        $filesToDelete = array_slice($files, 2);
        foreach ($filesToDelete as $file) {
            @unlink($file);
        }
    }

    /**
     * دریافت لیست بک‌آپ‌ها
     */
    public function getBackups(): array
    {
        $files = glob($this->backupPath . 'backup_*.sql');
        $backups = [];
        
        foreach ($files as $file) {
            $backups[] = [
                'filename' => basename($file),
                'filepath' => $file,
                'size' => filesize($file),
                'created_at' => date('Y-m-d H:i:s', filemtime($file)),
                'size_formatted' => $this->formatBytes(filesize($file))
            ];
        }
        
        // Sort by creation time (newest first)
        usort($backups, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return $backups;
    }

    /**
     * دانلود بک‌آپ
     */
    public function downloadBackup(string $filename): ?string
    {
        $filepath = $this->backupPath . $filename;
        
        if (!file_exists($filepath) || !is_readable($filepath)) {
            return null;
        }
        
        return $filepath;
    }

    /**
     * حذف بک‌آپ
     */
    public function deleteBackup(string $filename): bool
    {
        $filepath = $this->backupPath . $filename;
        
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        
        return false;
    }

    /**
     * فرمت کردن حجم فایل
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

