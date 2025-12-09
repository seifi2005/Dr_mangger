<?php

namespace App\Controllers;

use App\Models\Specialty;
use App\Services\ValidationService;
use App\Services\ActivityLogService;

class SpecialtyController extends Controller
{
    private $specialtyModel;
    private $validationService;
    private $activityLogService;

    public function __construct()
    {
        $this->specialtyModel = new Specialty();
        $this->validationService = new ValidationService();
        $this->activityLogService = new ActivityLogService();
    }

    public function index(): void
    {
        $page = (int)($_GET['page'] ?? 1);
        $limit = 50;
        $offset = ($page - 1) * $limit;
        
        $specialties = $this->specialtyModel->all($limit, $offset);
        
        // Add doctors count to each specialty
        foreach ($specialties as &$specialty) {
            $specialty['doctors_count'] = $this->specialtyModel->getDoctorsCount($specialty['id']);
        }
        
        $totalCount = $this->specialtyModel->count();
        
        $this->view('specialties/list', [
            'specialties' => $specialties,
            'currentPage' => $page,
            'totalPages' => ceil($totalCount / $limit),
            'totalCount' => $totalCount,
            'currentController' => 'specialties'
        ]);
    }

    public function create(): void
    {
        $this->view('specialties/add', [
            'errors' => [],
            'old' => [],
            'currentController' => 'specialties'
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/specialties/list');
            return;
        }

        $data = [
            'name_fa' => $_POST['specialtyNameFa'] ?? '',
            'name_en' => $_POST['specialtyNameEn'] ?? '',
            'description' => $_POST['description'] ?? '',
            'status' => $_POST['status'] ?? 'active',
        ];
        
        // Validation
        $errors = $this->validationService->validateSpecialty($data);
        if (!empty($errors)) {
            $this->view('specialties/add', [
                'errors' => $errors,
                'old' => $_POST,
                'currentController' => 'specialties'
            ]);
            return;
        }

        $id = $this->specialtyModel->create($data);
        
        // Log activity
        $specialtyName = $data['name_fa'] ?? '';
        $this->activityLogService->logCreate('specialty', $id, $specialtyName);
        
        $_SESSION['success'] = 'رشته پزشکی با موفقیت افزوده شد';
        $this->redirect('/specialties/details/' . $id);
    }

    public function show(?int $id): void
    {
        if (!$id) {
            $this->redirect('/specialties/list');
            return;
        }

        $specialty = $this->specialtyModel->getWithDoctorsCount($id);
        
        if (!$specialty) {
            http_response_code(404);
            echo "Specialty not found";
            return;
        }

        // Get doctors with this specialty
        $doctorModel = new \App\Models\Doctor();
        $doctors = $doctorModel->where('specialty_id', '=', $id);

        $this->view('specialties/details', [
            'specialty' => $specialty,
            'doctors' => $doctors,
            'currentController' => 'specialties'
        ]);
    }

    public function edit(?int $id): void
    {
        if (!$id) {
            $this->redirect('/specialties/list');
            return;
        }

        $specialty = $this->specialtyModel->find($id);
        
        if (!$specialty) {
            http_response_code(404);
            echo "Specialty not found";
            return;
        }

        $this->view('specialties/edit', [
            'specialty' => $specialty,
            'errors' => [],
            'currentController' => 'specialties'
        ]);
    }

    public function update(?int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            $this->redirect('/specialties/list');
            return;
        }

        $data = [
            'name_fa' => $_POST['specialtyNameFa'] ?? '',
            'name_en' => $_POST['specialtyNameEn'] ?? '',
            'description' => $_POST['description'] ?? '',
            'status' => $_POST['status'] ?? 'active',
        ];
        
        // Validation
        $errors = $this->validationService->validateSpecialty($data);
        if (!empty($errors)) {
            $specialty = $this->specialtyModel->find($id);
            $this->view('specialties/edit', [
                'specialty' => $specialty,
                'errors' => $errors,
                'currentController' => 'specialties'
            ]);
            return;
        }

        $specialty = $this->specialtyModel->find($id);
        $this->specialtyModel->update($id, $data);
        
        // Log activity
        $specialtyName = $specialty['name_fa'] ?? '';
        $this->activityLogService->logUpdate('specialty', $id, $specialtyName);
        
        $_SESSION['success'] = 'اطلاعات رشته پزشکی با موفقیت ویرایش شد';
        $this->redirect('/specialties/details/' . $id);
    }

    public function delete(?int $id): void
    {
        if (!$id) {
            $this->json(['success' => false, 'message' => 'Invalid ID'], 400);
            return;
        }

        $specialty = $this->specialtyModel->find($id);
        $specialtyName = $specialty['name_fa'] ?? '';
        
        $result = $this->specialtyModel->delete($id);
        
        if ($result) {
            // Log activity
            $this->activityLogService->logDelete('specialty', $id, $specialtyName);
            $this->json(['success' => true, 'message' => 'Specialty deleted successfully']);
        } else {
            $this->json(['success' => false, 'message' => 'Failed to delete specialty'], 500);
        }
    }
}

