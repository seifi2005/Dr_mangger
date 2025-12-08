# 🎉 سیستم احراز هویت و مدیریت دسترسی - کامل شد!

## ✅ تغییرات انجام شده

### 1. ایجاد فایل‌های جدید

#### **app/Middleware/AuthMiddleware.php**
- Middleware برای بررسی احراز هویت و دسترسی‌ها
- متدها:
  - `requireAuth()`: بررسی لاگین بودن کاربر
  - `requireRole($roles)`: بررسی نقش کاربر
  - `checkRole($roles)`: بررسی نقش بدون redirect

#### **app/Models/Permission.php**
- مدل برای مدیریت دسترسی‌ها
- متدها:
  - `can($permission)`: بررسی دسترسی کاربر به یک عملیات خاص
  - `checkUserPermission()`: بررسی دسترسی سفارشی از دیتابیس
  - `setPermission()`: تنظیم دسترسی سفارشی برای کاربر

**دسترسی‌های پیش‌فرض:**

**System Admin:**
- ✅ دسترسی کامل به همه چیز

**Operator:** (قابل سفارشی‌سازی)
- ✅ dashboard.view
- ✅ specialties.view (فقط مشاهده تخصص‌ها)
- ❌ بقیه موارد (باید به صورت دستی فعال شود)

**Acceptor:**
- ✅ doctors.view, doctors.create
- ✅ users.view, users.create
- ✅ medical-centers.view
- ✅ specialties.view
- ✅ pharmacies.view

**Service Provider:**
- ✅ همه بخش‌ها (فقط مشاهده - view)

**Support:**
- ✅ همه بخش‌ها (فقط مشاهده - view)
- ✅ reports.view

#### **app/Views/layouts/permission_check.php**
- Helper functions برای استفاده در View ها:
  - `canView($permission)`: بررسی دسترسی
  - `isSystemAdmin()`: آیا کاربر مدیر سیستم است؟
  - `getUserRole()`: گرفتن نقش کاربر
  - `getRoleName($role)`: گرفتن نام فارسی نقش

#### **CREATE_USER_PERMISSIONS_TABLE.sql**
- جدول `user_permissions` برای دسترسی‌های سفارشی
- فیلدها:
  - `user_id`: شناسه کاربر
  - `permission_key`: کلید دسترسی (مثلاً 'pharmacies.view')
  - `value`: مقدار ('true' یا 'false')

### 2. کنترلرهای آپدیت شده

#### ✅ **DashboardController.php**
- بررسی احراز هویت برای مشاهده داشبورد
- فقط کاربران با نقش‌های مجاز می‌توانند داشبورد را ببینند

#### ✅ **DoctorController.php**
- تمام متدها محافظت شده:
  - `index()`: doctors.view
  - `create()`: doctors.create
  - `store()`: doctors.create
  - `edit()`: doctors.edit
  - `update()`: doctors.edit
  - `delete()`: doctors.delete
  - `exportExcel()`: doctors.export

#### ✅ **UserController.php**
- تمام متدها محافظت شده:
  - `index()`: users.view
  - `create()`: users.create
  - `store()`: users.create
  - `edit()`: users.edit
  - `update()`: users.edit
  - `delete()`: users.delete

### 3. View های آپدیت شده

#### ✅ **app/Views/doctors/list.php**
- دکمه "افزودن پزشک": فقط با دسترسی `doctors.create`
- دکمه "خروجی Excel": فقط با دسترسی `doctors.export`
- دکمه "ویرایش": فقط با دسترسی `doctors.edit`
- دکمه "حذف": فقط با دسترسی `doctors.delete`

#### ✅ **app/Views/doctors/add.php**
- فیلد "درمانگاه" تغییر به "مرکز درمانی"
- مقادیر از جدول `medical_centers` خوانده می‌شود
- نمایش به صورت dropdown

#### ✅ **app/Views/doctors/edit.php**
- فیلد "درمانگاه" تغییر به "مرکز درمانی"
- مقادیر از جدول `medical_centers` خوانده می‌شود
- نمایش به صورت dropdown با مقدار انتخاب شده

### 4. فایل‌های راهنما

#### **app/Controllers/APPLY_MIDDLEWARE_TO_ALL.php**
- راهنمای کامل برای اعمال Middleware به کنترلرهای باقی‌مانده
- مثال‌های کاربردی برای هر نوع متد

#### **README_AUTHENTICATION.md**
- مستندات کامل سیستم احراز هویت
- راهنمای سفارشی‌سازی دسترسی‌ها
- نحوه استفاده در کنترلرها و View ها
- مثال‌های عملی

---

## 🚀 نحوه استفاده

### در کنترلرها:

```php
use App\Middleware\AuthMiddleware;
use App\Models\Permission;

public function index(): void
{
    // بررسی لاگین بودن
    AuthMiddleware::requireAuth();
    
    // بررسی دسترسی
    if (!Permission::can('doctors.view')) {
        $_SESSION['error'] = 'شما دسترسی ندارید';
        $this->redirect('/dashboard');
        return;
    }
    
    // بقیه کد...
}
```

### در View ها:

```php
<?php require_once __DIR__ . '/../layouts/permission_check.php'; ?>

<!-- نمایش/مخفی دکمه ویرایش -->
<?php if (canView('doctors.edit')): ?>
    <button>ویرایش</button>
<?php endif; ?>

<!-- بررسی نقش -->
<?php if (isSystemAdmin()): ?>
    <div>فقط برای مدیر</div>
<?php endif; ?>
```

---

## 📋 کنترلرهای باقی‌مانده (نیاز به آپدیت)

- ⏳ PharmacyController.php
- ⏳ MedicalCenterController.php
- ⏳ SpecialtyController.php
- ⏳ ReportController.php
- ⏳ SettingsController.php

**راهنما:** `app/Controllers/APPLY_MIDDLEWARE_TO_ALL.php`

---

## 🔐 نکات امنیتی

1. ✅ همیشه در کنترلر بررسی دسترسی انجام دهید
2. ✅ View فقط برای UI استفاده می‌شود
3. ✅ برای عملیات حساس از `requireRole('system-admin')` استفاده کنید
4. ✅ برای API ها حتماً از `Permission::can()` استفاده کنید

---

## 🎯 سفارشی‌سازی دسترسی اپراتور

**مثال:** اپراتور بتواند داروخانه‌ها را مشاهده کند:

در `app/Models/Permission.php`:

```php
'operator' => [
    'dashboard.view' => true,
    'pharmacies.view' => true,  // اضافه شد
    'pharmacies.create' => true,  // اضافه شد
],
```

---

## ✅ تست نهایی

1. با یک کاربر operator لاگین کنید
2. سعی کنید به بخش‌های مختلف دسترسی پیدا کنید
3. باید پیام "شما دسترسی ندارید" را ببینید
4. با مدیر سیستم لاگین کنید
5. اکنون به همه چیز دسترسی دارید

**تمام! 🎉**


