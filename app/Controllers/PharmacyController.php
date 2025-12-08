<?php

namespace App\Controllers;

use App\Models\Pharmacy;
use App\Services\FileUploadService;
use App\Services\ValidationService;
use App\Services\ActivityLogService;

class PharmacyController extends Controller
{
    private $pharmacyModel;
    private $fileUploadService;
    private $validationService;
    private $activityLogService;

    public function __construct()
    {
        $this->pharmacyModel = new Pharmacy();
        $this->fileUploadService = new FileUploadService();
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
            $pharmacies = $this->pharmacyModel->search($search, $limit, $offset);
            $totalCount = $this->pharmacyModel->searchCount($search);
        } else {
            $pharmacies = $this->pharmacyModel->all($limit, $offset);
            $totalCount = $this->pharmacyModel->count();
        }
        
        $this->view('pharmacies/list', [
            'pharmacies' => $pharmacies,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => ceil($totalCount / $limit),
            'totalCount' => $totalCount,
            'currentController' => 'pharmacies'
        ]);
    }

    public function create(): void
    {
        $this->view('pharmacies/add', [
            'errors' => [],
            'old' => [],
            'currentController' => 'pharmacies'
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/pharmacies/list');
            return;
        }

        $data = $this->preparePharmacyData($_POST);
        
        // Validation
        $errors = $this->validationService->validatePharmacy($data);
        if (!empty($errors)) {
            $this->view('pharmacies/add', [
                'errors' => $errors,
                'old' => $_POST,
                'currentController' => 'pharmacies'
            ]);
            return;
        }

        $id = $this->pharmacyModel->create($data);
        
        // Log activity
        $pharmacyName = $data['name'] ?? '';
        $this->activityLogService->logCreate('pharmacy', $id, $pharmacyName);
        
        $this->redirect('/pharmacies/details/' . $id);
    }

    public function show(?int $id): void
    {
        if (!$id) {
            $this->redirect('/pharmacies/list');
            return;
        }

        $pharmacy = $this->pharmacyModel->find($id);
        
        if (!$pharmacy) {
            http_response_code(404);
            echo "Pharmacy not found";
            return;
        }

        $this->view('pharmacies/details', [
            'pharmacy' => $pharmacy,
            'currentController' => 'pharmacies'
        ]);
    }

    public function mapSearch(): void
    {
        $province = $_GET['province'] ?? '';
        $city = $_GET['city'] ?? '';
        $district = $_GET['district'] ?? '';

        $pharmacies = $this->pharmacyModel->getByProvinceAndCity($province, $city, $district);
        
        // Get distinct values for dropdowns
        $provinces = $this->pharmacyModel->getDistinctProvinces();
        $cities = $this->pharmacyModel->getDistinctCities($province);
        $districts = $this->pharmacyModel->getDistinctDistricts($province, $city);

        $this->view('pharmacies/map-search', [
            'pharmacies' => $pharmacies,
            'province' => $province,
            'city' => $city,
            'district' => $district,
            'provinces' => $provinces,
            'cities' => $cities,
            'districts' => $districts,
            'currentController' => 'pharmacies'
        ]);
    }

    public function edit(?int $id): void
    {
        if (!$id) {
            $this->redirect('/pharmacies/list');
            return;
        }

        $pharmacy = $this->pharmacyModel->find($id);
        
        if (!$pharmacy) {
            http_response_code(404);
            echo "Pharmacy not found";
            return;
        }

        $this->view('pharmacies/edit', [
            'pharmacy' => $pharmacy,
            'errors' => [],
            'currentController' => 'pharmacies'
        ]);
    }

    public function update(?int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            $this->redirect('/pharmacies/list');
            return;
        }

        $data = $this->preparePharmacyData($_POST);
        
        // Validation
        $errors = $this->validationService->validatePharmacy($data);
        if (!empty($errors)) {
            $pharmacy = $this->pharmacyModel->find($id);
            $this->view('pharmacies/edit', [
                'pharmacy' => $pharmacy,
                'errors' => $errors,
                'currentController' => 'pharmacies'
            ]);
            return;
        }

        $pharmacy = $this->pharmacyModel->find($id);
        $this->pharmacyModel->update($id, $data);
        
        // Log activity
        $pharmacyName = $pharmacy['name'] ?? '';
        $this->activityLogService->logUpdate('pharmacy', $id, $pharmacyName);
        
        $this->redirect('/pharmacies/details/' . $id);
    }

    public function delete(?int $id): void
    {
        if (!$id) {
            $this->json(['success' => false, 'message' => 'Invalid ID'], 400);
            return;
        }

        $pharmacy = $this->pharmacyModel->find($id);
        $pharmacyName = $pharmacy['name'] ?? '';
        
        $result = $this->pharmacyModel->delete($id);
        
        if ($result) {
            // Log activity
            $this->activityLogService->logDelete('pharmacy', $id, $pharmacyName);
            $this->json(['success' => true, 'message' => 'Pharmacy deleted successfully']);
        } else {
            $this->json(['success' => false, 'message' => 'Failed to delete pharmacy'], 500);
        }
    }

    private function preparePharmacyData(array $post): array
    {
        return [
            'name' => $post['pharmacyName'] ?? '',
            'license_number' => $post['licenseNumber'] ?? '',
            'owner_name' => $post['ownerName'] ?? '',
            'owner_national_code' => $post['ownerNationalCode'] ?? '',
            'phone' => $post['phone'] ?? '',
            'mobile' => $post['mobile'] ?? '',
            'address' => $post['address'] ?? '',
            'province' => $post['province'] ?? '',
            'city' => $post['city'] ?? '',
            'district' => $post['district'] ?? '',
            'latitude' => !empty($post['latitude']) ? $post['latitude'] : null,
            'longitude' => !empty($post['longitude']) ? $post['longitude'] : null,
            'status' => $post['status'] ?? 'active',
        ];
    }
}

