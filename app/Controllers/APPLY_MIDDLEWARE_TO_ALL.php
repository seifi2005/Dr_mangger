<?php
/**
 * راهنمای اعمال Middleware به تمام کنترلرها
 * 
 * این فایل یک راهنما است - لطفاً این تغییرات را به صورت دستی در کنترلرهای زیر اعمال کنید:
 * 
 * 1. UserController.php
 * 2. PharmacyController.php  
 * 3. MedicalCenterController.php
 * 4. SpecialtyController.php
 * 5. ReportController.php
 * 6. SettingsController.php
 */

// ==================================================
// مثال 1: برای متدهای مشاهده (index, show)
// ==================================================
/*
use App\Middleware\AuthMiddleware;
use App\Models\Permission;

public function index(): void
{
    // Check authentication
    AuthMiddleware::requireAuth();
    
    // Check permission
    if (!Permission::can('users.view')) {  // یا pharmacies.view یا medical-centers.view و ...
        $_SESSION['error'] = 'شما دسترسی لازم برای مشاهده این بخش را ندارید';
        $this->redirect('/dashboard');
        return;
    }
    
    // بقیه کد...
}
*/

// ==================================================
// مثال 2: برای متدهای ایجاد (create, store)
// ==================================================
/*
public function create(): void
{
    AuthMiddleware::requireAuth();
    
    if (!Permission::can('users.create')) {
        $_SESSION['error'] = 'شما دسترسی لازم برای افزودن کاربر را ندارید';
        $this->redirect('/users/list');
        return;
    }
    
    // بقیه کد...
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
    
    // بقیه کد...
}
*/

// ==================================================
// مثال 3: برای متدهای ویرایش (edit, update)
// ==================================================
/*
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
    
    // بقیه کد...
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
    
    // بقیه کد...
}
*/

// ==================================================
// مثال 4: برای متدهای حذف (delete)
// ==================================================
/*
public function delete(?int $id): void
{
    AuthMiddleware::requireAuth();
    
    if (!Permission::can('users.delete')) {
        $this->json(['success' => false, 'message' => 'شما دسترسی لازم برای حذف را ندارید'], 403);
        return;
    }
    
    if (!$id) {
        $this->json(['success' => false, 'message' => 'Invalid ID'], 400);
        return;
    }
    
    // بقیه کد...
}
*/

// ==================================================
// مثال 5: برای متدهای گزارش و تنظیمات
// ==================================================
/*
// ReportController.php
public function activities(): void
{
    AuthMiddleware::requireAuth();
    
    if (!Permission::can('reports.view')) {
        $_SESSION['error'] = 'شما دسترسی لازم برای مشاهده گزارشات را ندارید';
        $this->redirect('/dashboard');
        return;
    }
    
    // بقیه کد...
}

// SettingsController.php
public function backup(): void
{
    AuthMiddleware::requireRole('system-admin'); // فقط مدیر سیستم
    
    // بقیه کد...
}
*/

// ==================================================
// لیست دسترسی‌ها برای هر بخش:
// ==================================================
/*
users:
- users.view
- users.create
- users.edit
- users.delete

doctors:
- doctors.view
- doctors.create
- doctors.edit
- doctors.delete
- doctors.export

pharmacies:
- pharmacies.view
- pharmacies.create
- pharmacies.edit
- pharmacies.delete

medical-centers:
- medical-centers.view
- medical-centers.create
- medical-centers.edit
- medical-centers.delete

specialties:
- specialties.view
- specialties.create
- specialties.edit
- specialties.delete

reports:
- reports.view
- reports.export

settings:
- settings.view
- settings.backup
*/


