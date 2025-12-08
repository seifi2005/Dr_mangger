<?php

namespace App\Controllers;

use App\Models\Doctor;
use App\Models\Pharmacy;
use App\Models\Specialty;
use App\Models\User;
use App\Models\MedicalCenter;
use App\Services\ActivityLogService;
use App\Middleware\AuthMiddleware;

class DashboardController extends Controller
{
    public function index(): void
    {
        // Require authentication and check permission
        AuthMiddleware::requireAuth();
        
        if (!AuthMiddleware::checkRole(['system-admin', 'operator', 'acceptor', 'service-provider', 'support'])) {
            $_SESSION['error'] = 'شما دسترسی لازم برای مشاهده داشبورد را ندارید';
            $this->redirect('/login');
            return;
        }
        $doctorModel = new Doctor();
        $pharmacyModel = new Pharmacy();
        $specialtyModel = new Specialty();
        $userModel = new User();
        $centerModel = new MedicalCenter();
        $activityLogService = new ActivityLogService();

        $stats = [
            'doctors' => $doctorModel->count(),
            'pharmacies' => $pharmacyModel->count(),
            'specialties' => $specialtyModel->count(),
            'users' => $userModel->count(),
            'centers' => $centerModel->count(),
        ];

        $recentDoctors = array_slice($doctorModel->getAllWithSpecialty(), 0, 5);
        $recentActivities = $activityLogService->getRecent(10);

        $this->view('dashboard/index', [
            'stats' => $stats,
            'recentDoctors' => $recentDoctors,
            'recentActivities' => $recentActivities,
            'currentController' => 'dashboard'
        ]);
    }
}

