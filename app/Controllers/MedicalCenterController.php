<?php

namespace App\Controllers;

use App\Models\MedicalCenter;
use App\Models\Doctor;
use App\Services\ValidationService;
use App\Services\ActivityLogService;

class MedicalCenterController extends Controller
{
    private $centerModel;
    private $doctorModel;
    private $validationService;
    private $activityLogService;

    public function __construct()
    {
        $this->centerModel = new MedicalCenter();
        $this->doctorModel = new Doctor();
        $this->validationService = new ValidationService();
        $this->activityLogService = new ActivityLogService();
    }

    public function index(): void
    {
        $search = $_GET['search'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        $limit = 50;
        $offset = ($page - 1) * $limit;
        
        if (!empty($search)) {
            $centers = $this->centerModel->search($search, $limit, $offset);
            $totalCount = $this->centerModel->searchCount($search);
        } else {
            $centers = $this->centerModel->all($limit, $offset);
            $totalCount = $this->centerModel->count();
        }
        
        $this->view('medical-centers/list', [
            'centers' => $centers,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => ceil($totalCount / $limit),
            'totalCount' => $totalCount,
            'currentController' => 'medical-centers'
        ]);
    }

    public function create(): void
    {
        $this->view('medical-centers/add', [
            'errors' => [],
            'old' => [],
            'currentController' => 'medical-centers'
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/medical-centers/list');
            return;
        }

        $data = [
            'name' => $_POST['centerName'] ?? '',
            'type' => $_POST['centerType'] ?? '',
            'license_number' => $_POST['licenseNumber'] ?? '',
            'manager_name' => $_POST['managerName'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? '',
            'status' => $_POST['status'] ?? 'active',
        ];

        $id = $this->centerModel->create($data);
        
        // Log activity
        $centerName = $data['name'] ?? '';
        $this->activityLogService->logCreate('medical_center', $id, $centerName);
        
        $_SESSION['success'] = 'مرکز درمانی با موفقیت افزوده شد';
        $this->redirect('/medical-centers/list');
    }

    public function show(?int $id): void
    {
        if (!$id) {
            $this->redirect('/medical-centers/list');
            return;
        }

        $center = $this->centerModel->find($id);
        
        if (!$center) {
            http_response_code(404);
            echo "Medical center not found";
            return;
        }

        // Get doctors for this center
        $page = (int)($_GET['page'] ?? 1);
        $limit = 50;
        $offset = ($page - 1) * $limit;
        
        $doctors = $this->doctorModel->getByClinicName($center['name'], $limit, $offset);
        $totalCount = $this->doctorModel->countByClinicName($center['name']);

        $this->view('medical-centers/details', [
            'center' => $center,
            'doctors' => $doctors,
            'currentPage' => $page,
            'totalPages' => ceil($totalCount / $limit),
            'totalCount' => $totalCount,
            'currentController' => 'medical-centers'
        ]);
    }

    public function edit(?int $id): void
    {
        if (!$id) {
            $this->redirect('/medical-centers/list');
            return;
        }

        $center = $this->centerModel->find($id);
        
        if (!$center) {
            http_response_code(404);
            echo "Medical center not found";
            return;
        }

        $this->view('medical-centers/edit', [
            'center' => $center,
            'errors' => [],
            'currentController' => 'medical-centers'
        ]);
    }

    public function update(?int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            $this->redirect('/medical-centers/list');
            return;
        }

        $data = [
            'name' => $_POST['centerName'] ?? '',
            'type' => $_POST['centerType'] ?? '',
            'license_number' => $_POST['licenseNumber'] ?? '',
            'manager_name' => $_POST['managerName'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? '',
            'status' => $_POST['status'] ?? 'active',
        ];

        $center = $this->centerModel->find($id);
        $this->centerModel->update($id, $data);
        
        // Log activity
        $centerName = $center['name'] ?? '';
        $this->activityLogService->logUpdate('medical_center', $id, $centerName);
        
        $_SESSION['success'] = 'اطلاعات مرکز درمانی با موفقیت ویرایش شد';
        $this->redirect('/medical-centers/details/' . $id);
    }

    public function delete(?int $id): void
    {
        if (!$id) {
            $this->json(['success' => false, 'message' => 'Invalid ID'], 400);
            return;
        }

        $center = $this->centerModel->find($id);
        $centerName = $center['name'] ?? '';
        
        $result = $this->centerModel->delete($id);
        
        if ($result) {
            // Log activity
            $this->activityLogService->logDelete('medical_center', $id, $centerName);
            $this->json(['success' => true, 'message' => 'Medical center deleted successfully']);
        } else {
            $this->json(['success' => false, 'message' => 'Failed to delete medical center'], 500);
        }
    }
}

