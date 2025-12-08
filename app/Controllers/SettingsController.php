<?php

namespace App\Controllers;

use App\Services\BackupService;
use App\Services\LogCleanupService;

class SettingsController extends Controller
{
    private $backupService;
    private $logCleanupService;

    public function __construct()
    {
        $this->backupService = new BackupService();
        $this->logCleanupService = new LogCleanupService();
    }

    public function index(): void
    {
        $this->backup();
    }

    public function backup(): void
    {
        $backups = $this->backupService->getBackups();
        $oldLogsCount = $this->logCleanupService->getOldLogsCount();

        $this->view('settings/backup', [
            'backups' => $backups,
            'oldLogsCount' => $oldLogsCount,
            'currentController' => 'settings'
        ]);
    }

    public function createBackup(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/settings/backup');
            return;
        }

        $result = $this->backupService->createBackup();
        
        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
        } else {
            $_SESSION['error_message'] = $result['message'];
        }

        $this->redirect('/settings/backup');
    }

    public function downloadBackup(string $filename): void
    {
        $filepath = $this->backupService->downloadBackup($filename);
        
        if (!$filepath) {
            $_SESSION['error_message'] = 'فایل بک‌آپ یافت نشد';
            $this->redirect('/settings/backup');
            return;
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }

    public function deleteBackup(string $filename): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid request'], 400);
            return;
        }

        $result = $this->backupService->deleteBackup($filename);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'بک‌آپ با موفقیت حذف شد']);
        } else {
            $this->json(['success' => false, 'message' => 'خطا در حذف بک‌آپ'], 500);
        }
    }

    public function cleanLogs(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid request'], 400);
            return;
        }

        $result = $this->logCleanupService->cleanOldLogs();
        
        $this->json($result);
    }
}

