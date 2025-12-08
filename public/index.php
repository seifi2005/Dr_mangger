<?php

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';
    
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

// Load Database class
require_once __DIR__ . '/../config/database.php';

// Load Helper functions
require_once __DIR__ . '/../app/Helpers/DateHelper.php';

// Set timezone
date_default_timezone_set('Asia/Tehran');

// Global helper function for Persian date
if (!function_exists('toPersianDate')) {
    function toPersianDate($date, $format = 'Y/m/d') {
        return \App\Helpers\DateHelper::toPersian($date, $format);
    }
}

if (!function_exists('toPersianDateWithMonth')) {
    function toPersianDateWithMonth($date) {
        return \App\Helpers\DateHelper::toPersianWithMonthName($date);
    }
}

// Get URL
$url = $_GET['url'] ?? 'dashboard';
$url = rtrim($url, '/');
$urlParts = explode('/', $url);

// Routing
$controllerName = $urlParts[0] ?? 'dashboard';
$action = $urlParts[1] ?? 'index';
$id = isset($urlParts[2]) ? (int)$urlParts[2] : null;
$param = $urlParts[2] ?? null;
$param2 = $urlParts[3] ?? null;

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    switch ($controllerName) {
        case 'login':
        case 'auth':
            $controller = new App\Controllers\AuthController();
            switch ($action) {
                case 'login':
                case 'index':
                    $controller->login();
                    break;
                case 'authenticate':
                    $controller->authenticate();
                    break;
                case 'logout':
                    $controller->logout();
                    break;
                default:
                    $controller->login();
            }
            break;
            
        case 'dashboard':
            $controller = new App\Controllers\DashboardController();
            $controller->index();
            break;
            
        case 'doctors':
            $controller = new App\Controllers\DoctorController();
            switch ($action) {
                case 'list':
                case 'index':
                    $controller->index();
                    break;
                case 'add':
                case 'create':
                    $controller->create();
                    break;
                case 'store':
                    $controller->store();
                    break;
                case 'show':
                case 'details':
                    $controller->show($id);
                    break;
                case 'edit':
                    $controller->edit($id);
                    break;
                case 'update':
                    $controller->update($id);
                    break;
                case 'delete':
                    $controller->delete($id);
                    break;
                case 'add-payment':
                    $controller->addPayment();
                    break;
                case 'export':
                case 'export-excel':
                    $controller->exportExcel();
                    break;
                case 'check-national-code':
                    $controller->checkNationalCode();
                    break;
                default:
                    $controller->index();
            }
            break;
            
        case 'pharmacies':
            $controller = new App\Controllers\PharmacyController();
            switch ($action) {
                case 'list':
                case 'index':
                    $controller->index();
                    break;
                case 'add':
                case 'create':
                    $controller->create();
                    break;
                case 'store':
                    $controller->store();
                    break;
                case 'show':
                case 'details':
                    $controller->show($id);
                    break;
                case 'edit':
                    $controller->edit($id);
                    break;
                case 'update':
                    $controller->update($id);
                    break;
                case 'delete':
                    $controller->delete($id);
                    break;
                case 'map-search':
                    $controller->mapSearch();
                    break;
                default:
                    $controller->index();
            }
            break;
            
        case 'specialties':
            $controller = new App\Controllers\SpecialtyController();
            switch ($action) {
                case 'list':
                case 'index':
                    $controller->index();
                    break;
                case 'add':
                case 'create':
                    $controller->create();
                    break;
                case 'store':
                    $controller->store();
                    break;
                case 'show':
                case 'details':
                    $controller->show($id);
                    break;
                case 'edit':
                    $controller->edit($id);
                    break;
                case 'update':
                    $controller->update($id);
                    break;
                case 'delete':
                    $controller->delete($id);
                    break;
                default:
                    $controller->index();
            }
            break;
            
        case 'users':
            $controller = new App\Controllers\UserController();
            switch ($action) {
                case 'list':
                case 'index':
                    $controller->index();
                    break;
                case 'add':
                case 'create':
                    $controller->create();
                    break;
                case 'store':
                    $controller->store();
                    break;
                case 'show':
                case 'details':
                    $controller->show($id);
                    break;
                case 'edit':
                    $controller->edit($id);
                    break;
                case 'update':
                    $controller->update($id);
                    break;
                case 'delete':
                    $controller->delete($id);
                    break;
                default:
                    $controller->index();
            }
            break;
            
        case 'medical-centers':
            $controller = new App\Controllers\MedicalCenterController();
            switch ($action) {
                case 'list':
                case 'index':
                    $controller->index();
                    break;
                case 'add':
                case 'create':
                    $controller->create();
                    break;
                case 'store':
                    $controller->store();
                    break;
                case 'show':
                case 'details':
                    $controller->show($id);
                    break;
                case 'edit':
                    $controller->edit($id);
                    break;
                case 'update':
                    $controller->update($id);
                    break;
                case 'delete':
                    $controller->delete($id);
                    break;
                default:
                    $controller->index();
            }
            break;
            
        case 'reports':
            $controller = new App\Controllers\ReportController();
            switch ($action) {
                case 'activities':
                    $controller->activities();
                    break;
                default:
                    $controller->activities();
            }
            break;
            
        case 'settings':
            $controller = new App\Controllers\SettingsController();
            switch ($action) {
                case 'backup':
                    if ($param === 'create') {
                        $controller->createBackup();
                    } elseif ($param === 'download' && $param2) {
                        $controller->downloadBackup($param2);
                    } elseif ($param === 'delete' && $param2) {
                        $controller->deleteBackup($param2);
                    } elseif ($param === 'clean-logs') {
                        $controller->cleanLogs();
                    } else {
                        $controller->backup();
                    }
                    break;
                default:
                    $controller->backup();
            }
            break;
            
        default:
            http_response_code(404);
            echo "Page not found";
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
    if (ini_get('display_errors')) {
        echo "<br><pre>" . $e->getTraceAsString() . "</pre>";
    }
}

