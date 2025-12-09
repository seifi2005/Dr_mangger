<?php

namespace App\Controllers;

use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\Payment;
use App\Models\MedicalCenter;
use App\Models\Permission;
use App\Services\FileUploadService;
use App\Services\ValidationService;
use App\Services\ActivityLogService;
use App\Middleware\AuthMiddleware;

class DoctorController extends Controller
{
    private $doctorModel;
    private $specialtyModel;
    private $paymentModel;
    private $centerModel;
    private $fileUploadService;
    private $validationService;
    private $activityLogService;

    public function __construct()
    {
        $this->doctorModel = new Doctor();
        $this->specialtyModel = new Specialty();
        $this->paymentModel = new Payment();
        $this->centerModel = new MedicalCenter();
        $this->fileUploadService = new FileUploadService();
        $this->validationService = new ValidationService();
        $this->activityLogService = new ActivityLogService();
    }

    public function index(): void
    {
        // Check authentication and permission
        AuthMiddleware::requireAuth();
        
        if (!Permission::can('doctors.view')) {
            $_SESSION['error'] = 'شما دسترسی لازم برای مشاهده لیست پزشکان را ندارید';
            $this->redirect('/dashboard');
            return;
        }
        $search = $_GET['search'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        $limit = 50;
        $offset = ($page - 1) * $limit;
        
        // Advanced filters
        $filters = [
            'first_name' => $_GET['first_name'] ?? '',
            'last_name' => $_GET['last_name'] ?? '',
            'national_code' => $_GET['national_code'] ?? '',
            'id_number' => $_GET['id_number'] ?? '',
            'medical_license' => $_GET['medical_license'] ?? '',
            'mobile' => $_GET['mobile'] ?? '',
            'from_qom' => $_GET['from_qom'] ?? '',
            'file_number' => $_GET['file_number'] ?? '',
            'is_deceased' => $_GET['is_deceased'] ?? '',
            'specialty_id' => $_GET['specialty_id'] ?? '',
            'clinic_name' => $_GET['clinic_name'] ?? '',
            'status' => $_GET['status'] ?? ''
        ];
        
        // Remove empty filters
        $filters = array_filter($filters, function($value) {
            return $value !== '';
        });
        
        $specialties = $this->specialtyModel->all(1000, 0);
        $clinicNames = $this->doctorModel->getDistinctClinicNames();
        
        // Check if any filter is set
        $hasFilters = !empty($filters) || !empty($search);
        
        if (!empty($search)) {
            $doctors = $this->doctorModel->search($search, $limit, $offset);
            $totalCount = $this->doctorModel->searchCount($search);
        } elseif (!empty($filters)) {
            $doctors = $this->doctorModel->filter($filters, $limit, $offset);
            $totalCount = $this->doctorModel->filterCount($filters);
        } else {
            $doctors = $this->doctorModel->getAllWithSpecialty($limit, $offset);
            $totalCount = $this->doctorModel->count();
        }
        
        $this->view('doctors/list', [
            'doctors' => $doctors,
            'search' => $search,
            'filters' => $filters,
            'specialties' => $specialties,
            'clinicNames' => $clinicNames,
            'hasFilters' => $hasFilters,
            'currentPage' => $page,
            'totalPages' => ceil($totalCount / $limit),
            'totalCount' => $totalCount,
            'currentController' => 'doctors'
        ]);
    }

    public function create(): void
    {
        // Check authentication and permission
        AuthMiddleware::requireAuth();
        
        if (!Permission::can('doctors.create')) {
            $_SESSION['error'] = 'شما دسترسی لازم برای افزودن پزشک را ندارید';
            $this->redirect('/doctors/list');
            return;
        }
        $specialties = $this->specialtyModel->all();
        $medicalCenters = $this->centerModel->all();
        
        $this->view('doctors/add', [
            'specialties' => $specialties,
            'medicalCenters' => $medicalCenters,
            'errors' => [],
            'old' => [],
            'currentController' => 'doctors'
        ]);
    }

    public function store(): void
    {
        // Check authentication and permission
        AuthMiddleware::requireAuth();
        
        if (!Permission::can('doctors.create')) {
            $_SESSION['error'] = 'شما دسترسی لازم برای افزودن پزشک را ندارید';
            $this->redirect('/doctors/list');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/doctors/list');
            return;
        }

        $data = $this->prepareDoctorData($_POST);
        
        // Validation
        $errors = $this->validationService->validateDoctor($data);
        if (!empty($errors)) {
            $specialties = $this->specialtyModel->all();
            $this->view('doctors/add', [
                'errors' => $errors,
                'old' => $_POST,
                'specialties' => $specialties,
                'currentController' => 'doctors'
            ]);
            return;
        }

        // Handle file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->fileUploadService->upload($_FILES['image'], 'doctors');
            if ($uploadResult['success']) {
                $data['image'] = $uploadResult['path'];
            }
        }

        try {
            $id = $this->doctorModel->create($data);
            
            // Log activity
            $doctorName = ($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '');
            $this->activityLogService->logCreate('doctor', $id, $doctorName);
            
            $_SESSION['success'] = 'پزشک با موفقیت افزوده شد';
            $this->redirect('/doctors/details/' . $id);
        } catch (\PDOException $e) {
            // Handle database errors (like duplicate national_code)
            $errorCode = $e->getCode();
            if ($errorCode == 23000) { // Integrity constraint violation
                $specialties = $this->specialtyModel->all();
                $errors = [];
                
                // Check if it's a duplicate national_code error
                if (strpos($e->getMessage(), 'national_code') !== false) {
                    $errors['nationalCode'] = 'این کد ملی قبلاً ثبت شده است';
                } elseif (strpos($e->getMessage(), 'medical_license') !== false) {
                    $errors['medicalLicense'] = 'این شماره نظام پزشکی قبلاً ثبت شده است';
                } else {
                    $errors['general'] = 'خطا در ثبت اطلاعات: ' . $e->getMessage();
                }
                
                $this->view('doctors/add', [
                    'errors' => $errors,
                    'old' => $_POST,
                    'specialties' => $specialties,
                    'currentController' => 'doctors'
                ]);
                return;
            }
            
            // Re-throw if it's not a constraint violation
            throw $e;
        }
    }

    public function show(?int $id): void
    {
        if (!$id) {
            $this->redirect('/doctors/list');
            return;
        }

        $doctor = $this->doctorModel->getWithSpecialty($id);
        
        if (!$doctor) {
            http_response_code(404);
            echo "Doctor not found";
            return;
        }

        $payments = $this->paymentModel->getByDoctorId($id);

        $this->view('doctors/details', [
            'doctor' => $doctor,
            'payments' => $payments,
            'currentController' => 'doctors'
        ]);
    }

    public function edit(?int $id): void
    {
        // Check authentication and permission
        AuthMiddleware::requireAuth();
        
        if (!Permission::can('doctors.edit')) {
            $_SESSION['error'] = 'شما دسترسی لازم برای ویرایش پزشک را ندارید';
            $this->redirect('/doctors/list');
            return;
        }
        
        if (!$id) {
            $this->redirect('/doctors/list');
            return;
        }

        $doctor = $this->doctorModel->find($id);
        
        if (!$doctor) {
            http_response_code(404);
            echo "Doctor not found";
            return;
        }

        $specialties = $this->specialtyModel->all();
        $medicalCenters = $this->centerModel->all();

        $this->view('doctors/edit', [
            'doctor' => $doctor,
            'specialties' => $specialties,
            'medicalCenters' => $medicalCenters,
            'errors' => [],
            'currentController' => 'doctors'
        ]);
    }

    public function update(?int $id): void
    {
        // Check authentication and permission
        AuthMiddleware::requireAuth();
        
        if (!Permission::can('doctors.edit')) {
            $_SESSION['error'] = 'شما دسترسی لازم برای ویرایش پزشک را ندارید';
            $this->redirect('/doctors/list');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            $this->redirect('/doctors/list');
            return;
        }

        $data = $this->prepareDoctorData($_POST);
        
        // Handle file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->fileUploadService->upload($_FILES['image'], 'doctors');
            if ($uploadResult['success']) {
                $data['image'] = $uploadResult['path'];
            }
        }

        $doctor = $this->doctorModel->find($id);
        $this->doctorModel->update($id, $data);
        
        // Log activity
        $doctorName = ($doctor['first_name'] ?? '') . ' ' . ($doctor['last_name'] ?? '');
        $this->activityLogService->logUpdate('doctor', $id, $doctorName);
        
        $_SESSION['success'] = 'اطلاعات پزشک با موفقیت ویرایش شد';
        $this->redirect('/doctors/details/' . $id);
    }

    public function delete(?int $id): void
    {
        // Check authentication and permission
        AuthMiddleware::requireAuth();
        
        if (!Permission::can('doctors.delete')) {
            $this->json(['success' => false, 'message' => 'شما دسترسی لازم برای حذف پزشک را ندارید'], 403);
            return;
        }
        
        if (!$id) {
            $this->json(['success' => false, 'message' => 'Invalid ID'], 400);
            return;
        }

        $doctor = $this->doctorModel->find($id);
        $doctorName = ($doctor['first_name'] ?? '') . ' ' . ($doctor['last_name'] ?? '');
        
        $result = $this->doctorModel->delete($id);
        
        if ($result) {
            // Log activity
            $this->activityLogService->logDelete('doctor', $id, $doctorName);
            $this->json(['success' => true, 'message' => 'Doctor deleted successfully']);
        } else {
            $this->json(['success' => false, 'message' => 'Failed to delete doctor'], 500);
        }
    }

    public function addPayment(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid request'], 400);
            return;
        }

        $doctorId = $_POST['doctor_id'] ?? 0;
        $receiptNumber = $_POST['receipt_number'] ?? '';
        $paymentDate = $_POST['payment_date'] ?? '';
        $amount = $_POST['amount'] ?? 0;

        if (empty($receiptNumber) || empty($paymentDate) || empty($amount)) {
            $this->json(['success' => false, 'message' => 'تمام فیلدها الزامی است'], 400);
            return;
        }

        $data = [
            'doctor_id' => $doctorId,
            'receipt_number' => $receiptNumber,
            'payment_date' => $paymentDate,
            'amount' => $amount,
        ];

        // Handle receipt image upload
        if (isset($_FILES['receipt_image']) && $_FILES['receipt_image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->fileUploadService->upload($_FILES['receipt_image'], 'receipts');
            if ($uploadResult['success']) {
                $data['receipt_image'] = $uploadResult['path'];
            }
        }

        $this->paymentModel->create($data);
        
        // Log activity
        $doctor = $this->doctorModel->find($doctorId);
        $doctorName = ($doctor['first_name'] ?? '') . ' ' . ($doctor['last_name'] ?? '');
        $this->activityLogService->log('create', 'payment', null, "پرداخت برای {$doctorName}", "ثبت پرداخت با شماره رسید: {$receiptNumber}");
        
        $this->json(['success' => true, 'message' => 'پرداخت با موفقیت ثبت شد']);
    }

    public function deletePayment(?int $id): void
    {
        if (!$id) {
            $this->json(['success' => false, 'message' => 'Invalid ID'], 400);
            return;
        }

        $payment = $this->paymentModel->find($id);
        if (!$payment) {
            $this->json(['success' => false, 'message' => 'پرداخت یافت نشد'], 404);
            return;
        }

        $doctorId = $payment['doctor_id'];
        $doctor = $this->doctorModel->find($doctorId);
        $doctorName = ($doctor['first_name'] ?? '') . ' ' . ($doctor['last_name'] ?? '');
        
        $result = $this->paymentModel->delete($id);
        
        if ($result) {
            // Log activity
            $this->activityLogService->log('delete', 'payment', $id, "پرداخت برای {$doctorName}", "حذف پرداخت با شماره رسید: {$payment['receipt_number']}");
            $this->json(['success' => true, 'message' => 'پرداخت با موفقیت حذف شد']);
        } else {
            $this->json(['success' => false, 'message' => 'خطا در حذف پرداخت'], 500);
        }
    }

    private function prepareDoctorData(array $post): array
    {
        return [
            'first_name' => $post['firstName'] ?? '',
            'last_name' => $post['lastName'] ?? '',
            'national_code' => $post['nationalCode'] ?? '',
            'id_number' => $post['idNumber'] ?? '',
            'birth_date' => !empty($post['birthDate']) ? $post['birthDate'] : null,
            'gender' => $post['gender'] ?? '',
            'father_name' => $post['fatherName'] ?? '',
            'is_deceased' => isset($post['isDeceased']) ? 1 : 0,
            'medical_license' => $post['medicalLicense'] ?? '',
            'specialty_id' => !empty($post['specialtyId']) ? (int)$post['specialtyId'] : null,
            'employment_status' => $post['employmentStatus'] ?? '',
            'medical_org_membership' => $post['medicalOrgMembership'] ?? '',
            'mobile' => $post['mobile'] ?? '',
            'clinic_phone' => $post['clinicPhone'] ?? '',
            'home_phone' => $post['homePhone'] ?? '',
            'email' => $post['email'] ?? '',
            'from_qom' => isset($post['fromQom']) ? 1 : 0,
            'clinic_postal_address' => $post['clinicPostalAddress'] ?? '',
            'work_address' => $post['workAddress'] ?? '',
            'home_postal_address' => $post['homePostalAddress'] ?? '',
            'clinic_latitude' => !empty($post['clinicLatitude']) ? $post['clinicLatitude'] : null,
            'clinic_longitude' => !empty($post['clinicLongitude']) ? $post['clinicLongitude'] : null,
            'clinic_name' => $post['clinicName'] ?? '',
            'description' => $post['description'] ?? '',
            'registration_date' => !empty($post['registrationDate']) ? $post['registrationDate'] : null,
            'file_number' => $post['fileNumber'] ?? '',
            'status' => $post['status'] ?? 'active',
        ];
    }

    public function exportExcel(): void
    {
        // Check authentication and permission
        AuthMiddleware::requireAuth();
        
        if (!Permission::can('doctors.export')) {
            $_SESSION['error'] = 'شما دسترسی لازم برای خروجی اکسل را ندارید';
            $this->redirect('/doctors/list');
            return;
        }
        
        // Get filters from GET parameters
        $filters = [
            'first_name' => $_GET['first_name'] ?? '',
            'last_name' => $_GET['last_name'] ?? '',
            'national_code' => $_GET['national_code'] ?? '',
            'id_number' => $_GET['id_number'] ?? '',
            'medical_license' => $_GET['medical_license'] ?? '',
            'mobile' => $_GET['mobile'] ?? '',
            'from_qom' => $_GET['from_qom'] ?? '',
            'file_number' => $_GET['file_number'] ?? '',
            'is_deceased' => $_GET['is_deceased'] ?? '',
            'specialty_id' => $_GET['specialty_id'] ?? '',
            'clinic_name' => $_GET['clinic_name'] ?? '',
            'status' => $_GET['status'] ?? ''
        ];
        
        // Remove empty filters
        $filters = array_filter($filters, function($value) {
            return $value !== '';
        });
        
        // Get all filtered doctors (no pagination for export)
        if (!empty($filters)) {
            $doctors = $this->doctorModel->filter($filters, 10000, 0);
        } else {
            $doctors = $this->doctorModel->getAllWithSpecialty(10000, 0);
        }
        
        // Set headers for Excel download
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="لیست_پزشکان_' . date('Y-m-d') . '.xls"');
        header('Cache-Control: max-age=0');
        
        // Start output
        echo '<html dir="rtl" lang="fa">';
        echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>';
        echo '<body>';
        echo '<table border="1" cellpadding="5" cellspacing="0">';
        
        // Header row
        echo '<tr style="background-color: #7239ea; color: white; font-weight: bold;">';
        echo '<th>ردیف</th>';
        echo '<th>نام</th>';
        echo '<th>نام خانوادگی</th>';
        echo '<th>کد ملی</th>';
        echo '<th>شماره شناسنامه</th>';
        echo '<th>شماره نظام پزشکی</th>';
        echo '<th>موبایل</th>';
        echo '<th>از قم رفته</th>';
        echo '<th>شماره پرونده</th>';
        echo '<th>فوت شده</th>';
        echo '<th>رشته</th>';
        echo '<th>مرکز درمانی</th>';
        echo '<th>وضعیت</th>';
        echo '</tr>';
        
        // Data rows
        $rowNum = 1;
        foreach ($doctors as $doctor) {
            echo '<tr>';
            echo '<td>' . $rowNum++ . '</td>';
            echo '<td>' . htmlspecialchars($doctor['first_name'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($doctor['last_name'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($doctor['national_code'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($doctor['id_number'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($doctor['medical_license'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($doctor['mobile'] ?? '') . '</td>';
            echo '<td>' . ($doctor['from_qom'] ? 'بله' : 'خیر') . '</td>';
            echo '<td>' . htmlspecialchars($doctor['file_number'] ?? '') . '</td>';
            echo '<td>' . ($doctor['is_deceased'] ? 'بله' : 'خیر') . '</td>';
            echo '<td>' . htmlspecialchars($doctor['specialty_name'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($doctor['clinic_name'] ?? '') . '</td>';
            echo '<td>' . ($doctor['status'] === 'active' ? 'فعال' : 'غیرفعال') . '</td>';
            echo '</tr>';
        }
        
        echo '</table>';
        echo '</body>';
        echo '</html>';
        exit;
    }

    public function checkNationalCode(): void
    {
        $nationalCode = $_GET['national_code'] ?? '';
        
        if (empty($nationalCode)) {
            $this->json(['exists' => false, 'message' => 'کد ملی وارد نشده است'], 400);
            return;
        }
        
        $existing = $this->doctorModel->findByNationalCode($nationalCode);
        
        $this->json([
            'exists' => $existing !== null,
            'message' => $existing ? 'این کد ملی قبلاً ثبت شده است' : 'کد ملی قابل استفاده است'
        ]);
    }
}

