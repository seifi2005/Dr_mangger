# سیستم مدیریت پزشکان - راهنمای نصب و اجرا

## مراحل نصب

### 1. ایجاد دیتابیس
```sql
-- در phpMyAdmin یا MySQL Command Line اجرا کنید:
CREATE DATABASE medical_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

سپس فایل `database.sql` را import کنید.

### 2. تنظیمات دیتابیس
فایل `config/config.php` را ویرایش کنید:
```php
'database' => [
    'host' => 'localhost',
    'name' => 'medical_system',
    'user' => 'root',      // نام کاربری MySQL
    'password' => '',      // رمز عبور MySQL
    'charset' => 'utf8mb4',
],
```

### 3. تنظیم URL
در `config/config.php`:
```php
'app' => [
    'url' => 'http://localhost/medic/public',  // آدرس پروژه شما
],
```

### 4. دسترسی فولدرها
اطمینان حاصل کنید که فولدر `public/uploads/` قابل نوشتن است:
```bash
chmod -R 777 public/uploads
```

### 5. تنظیم Apache (اختیاری - برای URL زیبا)
اگر می‌خواهید بدون `/public` دسترسی داشته باشید، یک Virtual Host ایجاد کنید.

## نحوه استفاده

### دسترسی به پروژه
بعد از نصب، به آدرس زیر بروید:
```
http://localhost/medic/public/dashboard
```

### URLها
- **داشبورد**: `/dashboard`
- **لیست پزشکان**: `/doctors/list`
- **افزودن پزشک**: `/doctors/add`  
- **جزئیات پزشک**: `/doctors/details/{id}`
- **لیست داروخانه‌ها**: `/pharmacies/list`
- **افزودن داروخانه**: `/pharmacies/add`
- **نقشه داروخانه‌ها**: `/pharmacies/map-search`
- **لیست رشته‌ها**: `/specialties/list`
- **لیست کاربران**: `/users/list`
- **لیست مراکز**: `/medical-centers/list`

## ساختار پروژه
```
medic/
├── app/
│   ├── Controllers/     # کنترلرها
│   ├── Models/          # مدل‌ها
│   ├── Views/           # ویوها
│   └── Services/        # سرویس‌ها
├── config/              # تنظیمات
│   ├── config.php
│   └── database.php
├── public/              # فایل‌های عمومی
│   ├── assets/         # CSS, JS, Images
│   ├── uploads/        # فایل‌های آپلود
│   ├── index.php       # Entry Point
│   └── .htaccess       # URL Rewriting
└── database.sql        # ساختار دیتابیس
```

## نکات مهم

### امنیت
- فایل‌های حساس در خارج از `public/` قرار دارند
- از Prepared Statements برای تمام queryها استفاده شده
- اعتبارسنجی ورودی‌ها در سرور انجام می‌شود

### توسعه
- برای افزودن Model جدید: `app/Models/YourModel.php`
- برای افزودن Controller جدید: `app/Controllers/YourController.php`
- برای افزودن View جدید: `app/Views/your-section/your-view.php`

### عیب‌یابی
اگر خطایی دیدید:
1. مطمئن شوید دیتابیس ایجاد شده و import شده
2. تنظیمات در `config/config.php` را چک کنید
3. مطمئن شوید Apache/PHP نصب است
4. خطاهای PHP را در `php_error_log` بررسی کنید

## پشتیبانی
برای مشکلات و سوالات، به مستندات PHP و PDO مراجعه کنید.

## نسخه
- PHP: >= 7.4
- MySQL: >= 5.7
- Apache: >= 2.4

