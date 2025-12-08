<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Permission;
use App\Services\FileUploadService;
use App\Services\ValidationService;
use App\Services\ActivityLogService;
use App\Middleware\AuthMiddleware;

class UserController extends Controller
{
    private $userModel;
    private $fileUploadService;
    private $validationService;
    private $activityLogService;

    public function __construct()
    {
        $this->userModel = new User();
        $this->fileUploadService = new FileUploadService();
        $this->validationService = new ValidationService();
        $this->activityLogService = new ActivityLogService();
    }

    public function index(): void
    {
        // Check authentication and permission
        AuthMiddleware::requireAuth();
        
        if (!Permission::can('users.view')) {
            $_SESSION['error'] = 'شما دسترسی لازم برای مشاهده لیست کاربران را ندارید';
            $this->redirect('/dashboard');
            return;
        }
        $search = $_GET['search'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        $limit = 50;
        $offset = ($page - 1) * $limit;
        
        if (!empty($search)) {
            $users = $this->userModel->search($search, $limit, $offset);
            $totalCount = $this->userModel->searchCount($search);
        } else {
            $users = $this->userModel->all($limit, $offset);
            $totalCount = $this->userModel->count();
        }
        
        $this->view('users/list', [
            'users' => $users,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => ceil($totalCount / $limit),
            'totalCount' => $totalCount,
            'currentController' => 'users'
        ]);
    }

    public function create(): void
    {
        AuthMiddleware::requireAuth();
        
        if (!Permission::can('users.create')) {
            $_SESSION['error'] = 'شما دسترسی لازم برای افزودن کاربر را ندارید';
            $this->redirect('/users/list');
            return;
        }
        
        $this->view('users/add', [
            'errors' => [],
            'old' => [],
            'currentController' => 'users'
        ]);
    }

    public function store(): void
    {
        AuthMiddleware::requireAuth();
        
        if (!Permission::can('users.create')) {
            $_SESSION['error'] = 'شما دسترسی لازم برای افزودن کاربر را ندارید';
            $this->redirect('/users/list');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/users/list');
            return;
        }

        $data = [
            'first_name' => $_POST['firstName'] ?? '',
            'last_name' => $_POST['lastName'] ?? '',
            'national_code' => $_POST['nationalCode'] ?? '',
            'mobile' => $_POST['mobile'] ?? '',
            'email' => $_POST['email'] ?? '',
            'role' => $_POST['userRole'] ?? '',
            'address' => $_POST['address'] ?? '',
            'status' => $_POST['status'] ?? 'active',
        ];
        
        // Validation
        $errors = $this->validationService->validateUser($data);
        if (!empty($errors)) {
            $this->view('users/add', [
                'errors' => $errors,
                'old' => $_POST,
                'currentController' => 'users'
            ]);
            return;
        }

        // Handle file upload
        if (isset($_FILES['userImage']) && $_FILES['userImage']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->fileUploadService->upload($_FILES['userImage'], 'users');
            if ($uploadResult['success']) {
                $data['image'] = $uploadResult['path'];
            }
        }

        $id = $this->userModel->create($data);
        
        // Log activity
        $userName = ($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '');
        $this->activityLogService->logCreate('user', $id, $userName);
        
        $this->redirect('/users/list');
    }

    public function show(?int $id): void
    {
        if (!$id) {
            $this->redirect('/users/list');
            return;
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            http_response_code(404);
            echo "User not found";
            return;
        }

        $this->view('users/details', [
            'user' => $user,
            'currentController' => 'users'
        ]);
    }

    public function edit(?int $id): void
    {
        AuthMiddleware::requireAuth();
        
        if (!Permission::can('users.edit')) {
            $_SESSION['error'] = 'شما دسترسی لازم برای ویرایش کاربر را ندارید';
            $this->redirect('/users/list');
            return;
        }
        
        if (!$id) {
            $this->redirect('/users/list');
            return;
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            http_response_code(404);
            echo "User not found";
            return;
        }

        $this->view('users/edit', [
            'user' => $user,
            'errors' => [],
            'currentController' => 'users'
        ]);
    }

    public function update(?int $id): void
    {
        AuthMiddleware::requireAuth();
        
        if (!Permission::can('users.edit')) {
            $_SESSION['error'] = 'شما دسترسی لازم برای ویرایش کاربر را ندارید';
            $this->redirect('/users/list');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            $this->redirect('/users/list');
            return;
        }

        $data = [
            'first_name' => $_POST['firstName'] ?? '',
            'last_name' => $_POST['lastName'] ?? '',
            'national_code' => $_POST['nationalCode'] ?? '',
            'mobile' => $_POST['mobile'] ?? '',
            'email' => $_POST['email'] ?? '',
            'role' => $_POST['userRole'] ?? '',
            'address' => $_POST['address'] ?? '',
            'status' => $_POST['status'] ?? 'active',
        ];

        // Handle file upload
        if (isset($_FILES['userImage']) && $_FILES['userImage']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->fileUploadService->upload($_FILES['userImage'], 'users');
            if ($uploadResult['success']) {
                $data['image'] = $uploadResult['path'];
            }
        }

        $user = $this->userModel->find($id);
        $this->userModel->update($id, $data);
        
        // Log activity
        $userName = ($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '');
        $this->activityLogService->logUpdate('user', $id, $userName);
        
        $this->redirect('/users/details/' . $id);
    }

    public function delete(?int $id): void
    {
        AuthMiddleware::requireAuth();
        
        if (!Permission::can('users.delete')) {
            $this->json(['success' => false, 'message' => 'شما دسترسی لازم برای حذف کاربر را ندارید'], 403);
            return;
        }
        
        if (!$id) {
            $this->json(['success' => false, 'message' => 'Invalid ID'], 400);
            return;
        }

        $user = $this->userModel->find($id);
        $userName = ($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '');
        
        $result = $this->userModel->delete($id);
        
        if ($result) {
            // Log activity
            $this->activityLogService->logDelete('user', $id, $userName);
            $this->json(['success' => true, 'message' => 'User deleted successfully']);
        } else {
            $this->json(['success' => false, 'message' => 'Failed to delete user'], 500);
        }
    }
}

