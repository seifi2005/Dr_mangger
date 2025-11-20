<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\UserController;
use App\Controllers\DoctorController;
use App\Controllers\MedicalCenterController;
use App\Controllers\PharmacyController;
use App\Controllers\ReportController;
use App\Controllers\ExportController;

return [
    'GET' => [
        '/' => [DashboardController::class, 'index'],
        '/login' => [AuthController::class, 'showLoginForm'],
        '/users' => [UserController::class, 'index'],
        '/users/create' => [UserController::class, 'create'],
        '/doctors' => [DoctorController::class, 'index'],
        '/doctors/create' => [DoctorController::class, 'create'],
        '/doctors/documents' => [DoctorController::class, 'documents'],
        '/medical-centers' => [MedicalCenterController::class, 'index'],
        '/medical-centers/create' => [MedicalCenterController::class, 'create'],
        '/pharmacies' => [PharmacyController::class, 'index'],
        '/pharmacies/map' => [PharmacyController::class, 'mapSearch'],
        '/reports' => [ReportController::class, 'index'],
        '/reports/activity' => [ReportController::class, 'activity'],
    ],
    'POST' => [
        '/login' => [AuthController::class, 'login'],
        '/users' => [UserController::class, 'store'],
        '/export/excel' => [ExportController::class, 'excel'],
    ],
];
