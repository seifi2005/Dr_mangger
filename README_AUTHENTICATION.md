# سیستم احراز هویت و مدیریت دسترسی‌ها

## ✅ نصب و راه‌اندازی

### 1. اجرای فایل SQL برای جدول دسترسی‌های سفارشی (اختیاری)
```sql
-- اجرا کنید: CREATE_USER_PERMISSIONS_TABLE.sql
```

### 2. ساختار نقش‌ها (Roles) و دسترسی‌ها (Permissions)

#### نقش‌های موجود:
- **system-admin**: مدیر سیستم (دسترسی کامل به تمام بخش‌ها)
- **operator**: اپراتور (دسترسی محدود - قابل تنظیم برای هر کاربر)
- **acceptor**: پذیرش (ثبت و مشاهده اطلاعات)
- **service-provider**: ارائه دهنده خدمات (فقط مشاهده)
- **support**: پشتیبانی (فقط مشاهده)

#### دسترسی‌های پیش‌فرض:

**System Admin**: دسترسی کامل به همه چیز

**Operator**: (پیش‌فرض محدود - قابل سفارشی‌سازی)
- ❌ doctors (پیش‌فرض ندارد)
- ❌ users (پیش‌فرض ندارد)
- ❌ medical-centers (پیش‌فرض ندارد)
- ✅ specialties.view (فقط مشاهده تخصص‌ها)
- ❌ pharmacies (پیش‌فرض ندارد)

**Acceptor**:
- ✅ doctors.view, doctors.create
- ✅ users.view, users.create
- ✅ medical-centers.view
- ✅ specialties.view
- ✅ pharmacies.view

**Service Provider**:
- ✅ همه بخش‌ها را می‌تواند ببیند (فقط مشاهده)

**Support**:
- ✅ همه بخش‌ها را می‌تواند ببیند + reports (فقط مشاهده)

## 🔧 نحوه سفارشی‌سازی دسترسی برای هر اپراتور

### روش 1: از طریق مدل `Permission.php`
فایل `app/Models/Permission.php` را باز کنید و در آرایه `$permissions` تغییرات را اعمال کنید:

```php
'operator' => [
    'dashboard.view' => true,
    
    // مثال: دادن دسترسی مشاهده پزشکان به اپراتور
    'doctors.view' => true,
    'doctors.create' => false,
    
    // مثال: دادن دسترسی کامل داروخانه‌ها
    'pharmacies.view' => true,
    'pharmacies.create' => true,
    'pharmacies.edit' => true,
    'pharmacies.delete' => true,
],
```

### روش 2: دسترسی سفارشی برای هر کاربر (پیشرفته)
در آینده می‌توانید از جدول `user_permissions` استفاده کنید:

```sql
-- مثال: به کاربر با ID=3 دسترسی مشاهده داروخانه‌ها داده شود
INSERT INTO user_permissions (user_id, permission_key, has_access) 
VALUES (3, 'pharmacies.view', TRUE);

-- مثال: از کاربر با ID=5 دسترسی حذف پزشک گرفته شود
INSERT INTO user_permissions (user_id, permission_key, has_access) 
VALUES (5, 'doctors.delete', FALSE);
```

## 📝 نحوه استفاده در کنترلرها

### مثال 1: بررسی احراز هویت ساده
```php
use App\Middleware\AuthMiddleware;

public function index(): void
{
    // فقط کاربران لاگین شده
    AuthMiddleware::requireAuth();
    
    // بقیه کد...
}
```

### مثال 2: بررسی نقش خاص
```php
public function backup(): void
{
    // فقط مدیر سیستم
    AuthMiddleware::requireRole('system-admin');
    
    // بقیه کد...
}
```

### مثال 3: بررسی چند نقش
```php
public function dashboard(): void
{
    // مدیر یا اپراتور
    AuthMiddleware::requireRole(['system-admin', 'operator']);
    
    // بقیه کد...
}
```

### مثال 4: بررسی دسترسی خاص
```php
use App\Models\Permission;

public function index(): void
{
    AuthMiddleware::requireAuth();
    
    if (!Permission::can('doctors.view')) {
        $_SESSION['error'] = 'شما دسترسی لازم را ندارید';
        $this->redirect('/dashboard');
        return;
    }
    
    // بقیه کد...
}
```

## 🎨 نحوه استفاده در View ها

### در فایل View، این فایل را include کنید:
```php
<?php require_once __DIR__ . '/../layouts/permission_check.php'; ?>
```

### سپس می‌توانید از توابع helper استفاده کنید:

```php
<!-- نمایش/مخفی کردن دکمه ویرایش -->
<?php if (canView('doctors.edit')): ?>
    <a href="<?php echo $baseUrl; ?>/doctors/edit/<?php echo $doctor['id']; ?>" class="btn btn-edit">
        <i class="fas fa-edit"></i> ویرایش
    </a>
<?php endif; ?>

<!-- نمایش/مخفی کردن دکمه حذف -->
<?php if (canView('doctors.delete')): ?>
    <button onclick="deleteDoctor(<?php echo $doctor['id']; ?>)" class="btn btn-delete">
        <i class="fas fa-trash"></i> حذف
    </button>
<?php endif; ?>

<!-- نمایش/مخفی کردن دکمه افزودن -->
<?php if (canView('doctors.create')): ?>
    <a href="<?php echo $baseUrl; ?>/doctors/create" class="btn btn-add">
        <i class="fas fa-plus"></i> افزودن پزشک
    </a>
<?php endif; ?>

<!-- بررسی نقش کاربر -->
<?php if (isSystemAdmin()): ?>
    <div class="admin-panel">
        <!-- فقط برای مدیر سیستم -->
    </div>
<?php endif; ?>

<!-- نمایش نام نقش -->
<span>نقش: <?php echo getRoleName(getUserRole()); ?></span>
```

## 🔐 نکات امنیتی

1. **همیشه** در کنترلرها بررسی دسترسی انجام دهید (نه فقط در View)
2. View فقط برای UI استفاده می‌شود
3. برای API ها حتماً از `Permission::can()` استفاده کنید
4. برای عملیات حساس (حذف، تنظیمات) از `requireRole('system-admin')` استفاده کنید

## 📋 لیست کامل کنترلرهایی که آپدیت شدند

✅ **DashboardController.php** - آپدیت شد
✅ **DoctorController.php** - آپدیت شد  
✅ **UserController.php** - آپدیت شد
⏳ **PharmacyController.php** - نیاز به آپدیت
⏳ **MedicalCenterController.php** - نیاز به آپدیت
⏳ **SpecialtyController.php** - نیاز به آپدیت
⏳ **ReportController.php** - نیاز به آپدیت
⏳ **SettingsController.php** - نیاز به آپدیت

**راهنمای اعمال تغییرات**: `app/Controllers/APPLY_MIDDLEWARE_TO_ALL.php`

## 🧪 تست کردن

1. ابتدا از حساب فعلی خارج شوید (Logout)
2. با یک کاربر عادی (مثلاً operator) لاگین کنید
3. سعی کنید به بخش‌های مختلف دسترسی پیدا کنید
4. باید پیام "شما دسترسی ندارید" را ببینید
5. با حساب مدیر سیستم وارد شوید
6. اکنون باید به همه چیز دسترسی داشته باشید

## 🎯 مثال عملی: سفارشی‌سازی برای یک اپراتور

**سناریو**: می‌خواهیم اپراتور بتواند:
- ✅ داروخانه‌ها را مشاهده، اضافه و ویرایش کند
- ✅ لیست پزشکان را ببیند (بدون ویرایش/حذف)
- ❌ به بخش کاربران دسترسی نداشته باشد

**راه حل**: در `app/Models/Permission.php`:

```php
'operator' => [
    'dashboard.view' => true,
    
    // Pharmacies - دسترسی کامل
    'pharmacies.view' => true,
    'pharmacies.create' => true,
    'pharmacies.edit' => true,
    'pharmacies.delete' => false, // نمی‌تواند حذف کند
    
    // Doctors - فقط مشاهده
    'doctors.view' => true,
    'doctors.create' => false,
    'doctors.edit' => false,
    'doctors.delete' => false,
    
    // Users - بدون دسترسی
    'users.view' => false,
],
```

**تمام!** 🎉


