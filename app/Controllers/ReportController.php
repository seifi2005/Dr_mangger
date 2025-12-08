<?php

namespace App\Controllers;

use App\Services\ActivityLogService;

class ReportController extends Controller
{
    private $activityLogService;

    public function __construct()
    {
        $this->activityLogService = new ActivityLogService();
    }

    public function activities(): void
    {
        $page = (int)($_GET['page'] ?? 1);
        $limit = 50;
        $offset = ($page - 1) * $limit;

        $entityType = $_GET['entity_type'] ?? '';
        $action = $_GET['action'] ?? '';

        $activityLogModel = new \App\Models\ActivityLog();
        
        $activities = $activityLogModel->getAll($limit, $offset, $entityType ?: null, $action ?: null);
        $totalCount = $activityLogModel->count($entityType ?: null, $action ?: null);

        $this->view('reports/activities', [
            'activities' => $activities,
            'currentPage' => $page,
            'totalPages' => ceil($totalCount / $limit),
            'entityType' => $entityType,
            'action' => $action,
            'currentController' => 'reports'
        ]);
    }
}

