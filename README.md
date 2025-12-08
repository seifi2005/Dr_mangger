# سیستم مدیریت پزشکان - Pure PHP MVC

سیستم مدیریت پزشکان با معماری MVC و PHP خالص

## ساختار پروژه

```
medical-system/
├── app/
│   ├── Controllers/          # کنترلرها
│   ├── Models/              # مدل‌ها
│   ├── Views/               # Viewها
│   └── Services/            # سرویس‌ها
├── config/                  # تنظیمات
├── public/                  # فایل‌های عمومی
│   ├── assets/             # CSS, JS, Images
│   ├── uploads/            # فایل‌های آپلود شده
│   └── index.php           # Entry Point
└── database.sql            # ساختار دیتابیس
```

## نصب و راه‌اندازی

### 1. ایجاد دیتابیس

```bash
mysql -u root -p < database.sql
```

### 2. تنظیمات

فایل `config/config.php` را ویرایش کنید:

```php
'database' => [
    'host' => 'localhost',
    'name' => 'medical_system',
    'user' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
],
```

### 3. تنظیم Virtual Host (Apache)

```apache
<VirtualHost *:80>
    ServerName medical-system.local
    DocumentRoot "C:/Users/mohammad.seifi/Desktop/medical-system/public"
    
    <Directory "C:/Users/mohammad.seifi/Desktop/medical-system/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 4. دسترسی فولدرها

```bash
chmod -R 777 public/uploads
```

## استفاده

### URL Structure

- Dashboard: `http://localhost/medical-system/public/dashboard`
- لیست پزشکان: `http://localhost/medical-system/public/doctors/list`
- افزودن پزشک: `http://localhost/medical-system/public/doctors/add`
- جزئیات پزشک: `http://localhost/medical-system/public/doctors/details/{id}`

### Routes

- `/dashboard` - داشبورد
- `/doctors/list` - لیست پزشکان
- `/doctors/add` - افزودن پزشک
- `/doctors/details/{id}` - جزئیات پزشک
- `/pharmacies/list` - لیست داروخانه‌ها
- `/pharmacies/add` - افزودن داروخانه
- `/specialties/list` - لیست رشته‌ها
- `/users/list` - لیست کاربران

## ویژگی‌ها

- ✅ معماری MVC
- ✅ PSR-4 Autoloading
- ✅ Prepared Statements (امنیت)
- ✅ File Upload Service
- ✅ Validation Service
- ✅ Responsive Design
- ✅ RTL Support

## TODO

- [ ] تکمیل Viewهای باقی‌مانده
- [ ] اضافه کردن Pagination
- [ ] اضافه کردن Search پیشرفته
- [ ] اضافه کردن Export به Excel
- [ ] اضافه کردن Authentication (در صورت نیاز)

## نکات

- تمام Viewها باید در `app/Views/` قرار گیرند
- تمام Controllerها باید از `Controller` base class ارث‌بری کنند
- تمام Modelها باید از `Model` base class ارث‌بری کنند
- از Prepared Statements برای تمام Queryها استفاده کنید

