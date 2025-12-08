-- داده‌های تست برای سیستم مدیریت پزشکان
-- استفاده: mysql -u root -p medical_system < database_test_data.sql

USE medical_system;

-- حذف داده‌های قبلی (اختیاری)
-- TRUNCATE TABLE doctors;
-- TRUNCATE TABLE users;
-- TRUNCATE TABLE medical_centers;
-- TRUNCATE TABLE medical_specialties;
-- TRUNCATE TABLE pharmacies;

-- ============================================
-- 1. اضافه کردن 100 رشته پزشکی
-- ============================================
INSERT INTO medical_specialties (name_fa, name_en, description, status) VALUES
('قلب و عروق', 'Cardiology', 'تخصص در بیماری‌های قلب و عروق', 'active'),
('پزشکی عمومی', 'General Medicine', 'پزشکی عمومی و خانواده', 'active'),
('ارتوپدی', 'Orthopedics', 'تخصص در بیماری‌های استخوان و مفاصل', 'active'),
('جراحی', 'Surgery', 'جراحی عمومی و تخصصی', 'active'),
('داخلی', 'Internal Medicine', 'بیماری‌های داخلی', 'active'),
('پزشکی کودکان', 'Pediatrics', 'تخصص در بیماری‌های کودکان', 'active'),
('زنان و زایمان', 'Obstetrics and Gynecology', 'تخصص در بیماری‌های زنان', 'active'),
('چشم پزشکی', 'Ophthalmology', 'تخصص در بیماری‌های چشم', 'active'),
('گوش و حلق و بینی', 'ENT', 'تخصص در بیماری‌های گوش و حلق و بینی', 'active'),
('پوست و مو', 'Dermatology', 'تخصص در بیماری‌های پوست و مو', 'active'),
('مغز و اعصاب', 'Neurology', 'تخصص در بیماری‌های مغز و اعصاب', 'active'),
('روانپزشکی', 'Psychiatry', 'تخصص در بیماری‌های روانی', 'active'),
('اورولوژی', 'Urology', 'تخصص در بیماری‌های مجاری ادراری', 'active'),
('رادیولوژی', 'Radiology', 'تخصص در تصویربرداری پزشکی', 'active'),
('آسیب‌شناسی', 'Pathology', 'تخصص در آسیب‌شناسی', 'active'),
('بیهوشی', 'Anesthesiology', 'تخصص در بیهوشی', 'active'),
('اورژانس', 'Emergency Medicine', 'تخصص در پزشکی اورژانس', 'active'),
('طب فیزیکی', 'Physical Medicine', 'تخصص در طب فیزیکی و توانبخشی', 'active'),
('عفونی', 'Infectious Diseases', 'تخصص در بیماری‌های عفونی', 'active'),
('روماتولوژی', 'Rheumatology', 'تخصص در بیماری‌های روماتیسمی', 'active'),
('غدد', 'Endocrinology', 'تخصص در بیماری‌های غدد', 'active'),
('گوارش', 'Gastroenterology', 'تخصص در بیماری‌های گوارش', 'active'),
('خون و سرطان', 'Hematology and Oncology', 'تخصص در بیماری‌های خون و سرطان', 'active'),
('ریه', 'Pulmonology', 'تخصص در بیماری‌های ریه', 'active'),
('کلیه', 'Nephrology', 'تخصص در بیماری‌های کلیه', 'active'),
('جراحی مغز و اعصاب', 'Neurosurgery', 'جراحی مغز و اعصاب', 'active'),
('جراحی قلب', 'Cardiac Surgery', 'جراحی قلب و عروق', 'active'),
('جراحی پلاستیک', 'Plastic Surgery', 'جراحی پلاستیک و زیبایی', 'active'),
('جراحی اطفال', 'Pediatric Surgery', 'جراحی کودکان', 'active'),
('جراحی عروق', 'Vascular Surgery', 'جراحی عروق', 'active'),
('جراحی توراکس', 'Thoracic Surgery', 'جراحی قفسه سینه', 'active'),
('جراحی کولورکتال', 'Colorectal Surgery', 'جراحی روده و مقعد', 'active'),
('جراحی کبد', 'Hepatobiliary Surgery', 'جراحی کبد و مجاری صفراوی', 'active'),
('جراحی پانکراس', 'Pancreatic Surgery', 'جراحی پانکراس', 'active'),
('جراحی تیروئید', 'Thyroid Surgery', 'جراحی تیروئید', 'active'),
('جراحی پستان', 'Breast Surgery', 'جراحی پستان', 'active'),
('جراحی لاپاراسکوپی', 'Laparoscopic Surgery', 'جراحی لاپاراسکوپی', 'active'),
('جراحی چاقی', 'Bariatric Surgery', 'جراحی چاقی', 'active'),
('جراحی دست', 'Hand Surgery', 'جراحی دست', 'active'),
('جراحی شانه', 'Shoulder Surgery', 'جراحی شانه', 'active'),
('جراحی زانو', 'Knee Surgery', 'جراحی زانو', 'active'),
('جراحی ستون فقرات', 'Spine Surgery', 'جراحی ستون فقرات', 'active'),
('جراحی لگن', 'Hip Surgery', 'جراحی لگن', 'active'),
('جراحی مچ پا', 'Ankle Surgery', 'جراحی مچ پا', 'active'),
('جراحی فک و صورت', 'Maxillofacial Surgery', 'جراحی فک و صورت', 'active'),
('جراحی گوش', 'Otology', 'جراحی گوش', 'active'),
('جراحی بینی', 'Rhinology', 'جراحی بینی', 'active'),
('جراحی حنجره', 'Laryngology', 'جراحی حنجره', 'active'),
('جراحی تیروئید و پاراتیروئید', 'Thyroid and Parathyroid Surgery', 'جراحی تیروئید و پاراتیروئید', 'active'),
('جراحی آدرنال', 'Adrenal Surgery', 'جراحی آدرنال', 'active'),
('جراحی طحال', 'Splenic Surgery', 'جراحی طحال', 'active'),
('جراحی معده', 'Gastric Surgery', 'جراحی معده', 'active'),
('جراحی مری', 'Esophageal Surgery', 'جراحی مری', 'active'),
('جراحی روده کوچک', 'Small Bowel Surgery', 'جراحی روده کوچک', 'active'),
('جراحی روده بزرگ', 'Large Bowel Surgery', 'جراحی روده بزرگ', 'active'),
('جراحی مقعد', 'Anal Surgery', 'جراحی مقعد', 'active'),
('جراحی فتق', 'Hernia Surgery', 'جراحی فتق', 'active'),
('جراحی آپاندیس', 'Appendectomy', 'جراحی آپاندیس', 'active'),
('جراحی کیسه صفرا', 'Cholecystectomy', 'جراحی کیسه صفرا', 'active'),
('جراحی طحال', 'Splenectomy', 'جراحی طحال', 'active'),
('جراحی کبد', 'Hepatectomy', 'جراحی کبد', 'active'),
('جراحی پانکراس', 'Pancreatectomy', 'جراحی پانکراس', 'active'),
('جراحی کلیه', 'Nephrectomy', 'جراحی کلیه', 'active'),
('جراحی مثانه', 'Cystectomy', 'جراحی مثانه', 'active'),
('جراحی پروستات', 'Prostatectomy', 'جراحی پروستات', 'active'),
('جراحی بیضه', 'Orchiectomy', 'جراحی بیضه', 'active'),
('جراحی رحم', 'Hysterectomy', 'جراحی رحم', 'active'),
('جراحی تخمدان', 'Oophorectomy', 'جراحی تخمدان', 'active'),
('جراحی لوله فالوپ', 'Salpingectomy', 'جراحی لوله فالوپ', 'active'),
('جراحی سزارین', 'Cesarean Section', 'جراحی سزارین', 'active'),
('جراحی واژن', 'Vaginal Surgery', 'جراحی واژن', 'active'),
('جراحی دهانه رحم', 'Cervical Surgery', 'جراحی دهانه رحم', 'active'),
('جراحی پستان', 'Mastectomy', 'جراحی پستان', 'active'),
('جراحی تیروئید', 'Thyroidectomy', 'جراحی تیروئید', 'active'),
('جراحی پاراتیروئید', 'Parathyroidectomy', 'جراحی پاراتیروئید', 'active'),
('جراحی آدرنال', 'Adrenalectomy', 'جراحی آدرنال', 'active'),
('جراحی هیپوفیز', 'Hypophysectomy', 'جراحی هیپوفیز', 'active'),
('جراحی تیموس', 'Thymectomy', 'جراحی تیموس', 'active'),
('جراحی طحال', 'Splenectomy', 'جراحی طحال', 'active'),
('جراحی لنف', 'Lymph Node Surgery', 'جراحی لنف', 'active'),
('جراحی عروق', 'Vascular Surgery', 'جراحی عروق', 'active'),
('جراحی شریان', 'Arterial Surgery', 'جراحی شریان', 'active'),
('جراحی ورید', 'Venous Surgery', 'جراحی ورید', 'active'),
('جراحی لنف', 'Lymphatic Surgery', 'جراحی لنف', 'active'),
('جراحی قلب باز', 'Open Heart Surgery', 'جراحی قلب باز', 'active'),
('جراحی قلب بسته', 'Closed Heart Surgery', 'جراحی قلب بسته', 'active'),
('جراحی عروق کرونر', 'Coronary Artery Surgery', 'جراحی عروق کرونر', 'active'),
('جراحی دریچه قلب', 'Heart Valve Surgery', 'جراحی دریچه قلب', 'active'),
('جراحی آئورت', 'Aortic Surgery', 'جراحی آئورت', 'active'),
('جراحی شریان کاروتید', 'Carotid Artery Surgery', 'جراحی شریان کاروتید', 'active'),
('جراحی شریان فمورال', 'Femoral Artery Surgery', 'جراحی شریان فمورال', 'active'),
('جراحی شریان پوپلیتئال', 'Popliteal Artery Surgery', 'جراحی شریان پوپلیتئال', 'active'),
('جراحی شریان تیبیال', 'Tibial Artery Surgery', 'جراحی شریان تیبیال', 'active'),
('جراحی شریان رادیال', 'Radial Artery Surgery', 'جراحی شریان رادیال', 'active'),
('جراحی شریان اولنار', 'Ulnar Artery Surgery', 'جراحی شریان اولنار', 'active'),
('جراحی شریان براکیال', 'Brachial Artery Surgery', 'جراحی شریان براکیال', 'active'),
('جراحی شریان ساب‌کلاویان', 'Subclavian Artery Surgery', 'جراحی شریان ساب‌کلاویان', 'active'),
('جراحی شریان آگزیلاری', 'Axillary Artery Surgery', 'جراحی شریان آگزیلاری', 'active'),
('جراحی شریان ایلیاک', 'Iliac Artery Surgery', 'جراحی شریان ایلیاک', 'active'),
('جراحی شریان مزانتریک', 'Mesenteric Artery Surgery', 'جراحی شریان مزانتریک', 'active'),
('جراحی شریان کلیوی', 'Renal Artery Surgery', 'جراحی شریان کلیوی', 'active'),
('جراحی شریان کبدی', 'Hepatic Artery Surgery', 'جراحی شریان کبدی', 'active'),
('جراحی شریان طحالی', 'Splenic Artery Surgery', 'جراحی شریان طحالی', 'active'),
('جراحی شریان پانکراس', 'Pancreatic Artery Surgery', 'جراحی شریان پانکراس', 'active'),
('جراحی شریان آدرنال', 'Adrenal Artery Surgery', 'جراحی شریان آدرنال', 'active'),
('جراحی شریان تیروئید', 'Thyroid Artery Surgery', 'جراحی شریان تیروئید', 'active'),
('جراحی شریان پاراتیروئید', 'Parathyroid Artery Surgery', 'جراحی شریان پاراتیروئید', 'active'),
('جراحی شریان هیپوفیز', 'Hypophyseal Artery Surgery', 'جراحی شریان هیپوفیز', 'active'),
('جراحی شریان تیموس', 'Thymic Artery Surgery', 'جراحی شریان تیموس', 'active');

-- ============================================
-- 2. اضافه کردن 500 کاربر با دسترسی‌های مختلف
-- ============================================
INSERT INTO users (first_name, last_name, national_code, mobile, email, password, role, address, status)
SELECT 
    CONCAT('کاربر', n) as first_name,
    CONCAT('تست', FLOOR(1 + RAND() * 100)) as last_name,
    LPAD(1000000000 + n, 10, '0') as national_code,
    CONCAT('09', LPAD(10000000 + n, 9, '0')) as mobile,
    CONCAT('user', n, '@test.com') as email,
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' as password,
    ELT(1 + FLOOR(RAND() * 5), 'system-admin', 'operator', 'acceptor', 'service-provider', 'support') as role,
    CONCAT('آدرس تست شماره ', n) as address,
    IF(RAND() > 0.1, 'active', 'inactive') as status
FROM 
    (SELECT @row := @row + 1 as n FROM 
        (SELECT 0 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1
        CROSS JOIN (SELECT 0 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2
        CROSS JOIN (SELECT 0 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) t3
        CROSS JOIN (SELECT @row := 0) r
    ) numbers
LIMIT 500;

-- ============================================
-- 3. اضافه کردن 500 پزشک
-- ============================================
INSERT INTO doctors (
    first_name, last_name, national_code, id_number, birth_date, gender, father_name, is_deceased,
    medical_license, specialty_id, employment_status, medical_org_membership,
    mobile, clinic_phone, home_phone, email,
    from_qom, clinic_postal_address, work_address, home_postal_address,
    clinic_latitude, clinic_longitude,
    clinic_name, description, registration_date, file_number, status
)
SELECT 
    CONCAT('دکتر', n) as first_name,
    ELT(1 + FLOOR(RAND() * 20), 'احمدی', 'محمدی', 'حسینی', 'کریمی', 'رضایی', 'نوری', 'صادقی', 'جعفری', 'موسوی', 'قاسمی', 'اکبری', 'امینی', 'باقری', 'جعفری', 'حیدری', 'خانی', 'داوودی', 'رستمی', 'سلیمی', 'طاهری') as last_name,
    LPAD(2000000000 + n, 10, '0') as national_code,
    LPAD(100000 + n, 6, '0') as id_number,
    DATE_SUB(CURDATE(), INTERVAL FLOOR(25 + RAND() * 50) YEAR) as birth_date,
    IF(RAND() > 0.5, 'male', 'female') as gender,
    CONCAT('پدر', FLOOR(1 + RAND() * 100)) as father_name,
    IF(RAND() > 0.95, TRUE, FALSE) as is_deceased,
    CONCAT('MD-', LPAD(n, 6, '0')) as medical_license,
    FLOOR(1 + RAND() * 100) as specialty_id,
    ELT(1 + FLOOR(RAND() * 3), 'active', 'inactive', 'retired') as employment_status,
    CONCAT('ORG-', LPAD(FLOOR(1000 + RAND() * 8999), 4, '0')) as medical_org_membership,
    CONCAT('09', LPAD(20000000 + n, 9, '0')) as mobile,
    CONCAT('021-', LPAD(1000000 + n, 7, '0')) as clinic_phone,
    CONCAT('021-', LPAD(2000000 + n, 7, '0')) as home_phone,
    CONCAT('doctor', n, '@test.com') as email,
    IF(RAND() > 0.7, TRUE, FALSE) as from_qom,
    CONCAT('آدرس پستی مطب شماره ', n) as clinic_postal_address,
    CONCAT('آدرس محل کار شماره ', n) as work_address,
    CONCAT('آدرس پستی منزل شماره ', n) as home_postal_address,
    34.5 + (RAND() * 2.5) as clinic_latitude,
    50.5 + (RAND() * 2.5) as clinic_longitude,
    CONCAT('درمانگاه ', ELT(1 + FLOOR(RAND() * 10), 'سلامت', 'امید', 'پارس', 'نیک', 'سینا', 'آریا', 'پارسا', 'دکتر', 'پزشک', 'درمان')) as clinic_name,
    CONCAT('توضیحات پزشک شماره ', n) as description,
    DATE_SUB(CURDATE(), INTERVAL FLOOR(RAND() * 3650) DAY) as registration_date,
    CONCAT('FILE-', LPAD(n, 6, '0')) as file_number,
    IF(RAND() > 0.1, 'active', 'inactive') as status
FROM 
    (SELECT @row := @row + 1 as n FROM 
        (SELECT 0 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1
        CROSS JOIN (SELECT 0 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2
        CROSS JOIN (SELECT 0 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) t3
        CROSS JOIN (SELECT @row := 0) r
    ) numbers
LIMIT 500;

-- ============================================
-- 4. اضافه کردن 1000 مرکز درمانی
-- ============================================
INSERT INTO medical_centers (name, type, license_number, manager_name, phone, address, status)
SELECT 
    CONCAT(ELT(1 + FLOOR(RAND() * 20), 'بیمارستان', 'درمانگاه', 'مرکز درمانی', 'کلینیک', 'پلی‌کلینیک'), ' ', ELT(1 + FLOOR(RAND() * 15), 'شهید', 'امام', 'ولیعصر', 'امید', 'سلامت', 'پارس', 'نیک', 'سینا', 'آریا', 'پارسا', 'ملی', 'دولتی', 'خصوصی', 'تخصصی', 'عمومی'), ' ', n) as name,
    ELT(1 + FLOOR(RAND() * 3), 'hospital', 'clinic', 'medical_center') as type,
    CONCAT('CENTER-', LPAD(n, 6, '0')) as license_number,
    CONCAT(ELT(1 + FLOOR(RAND() * 10), 'دکتر', 'مهندس', 'آقای', 'خانم'), ' ', ELT(1 + FLOOR(RAND() * 20), 'احمدی', 'محمدی', 'حسینی', 'کریمی', 'رضایی', 'نوری', 'صادقی', 'جعفری', 'موسوی', 'قاسمی', 'اکبری', 'امینی', 'باقری', 'جعفری', 'حیدری', 'خانی', 'داوودی', 'رستمی', 'سلیمی', 'طاهری')) as manager_name,
    CONCAT('021-', LPAD(3000000 + n, 7, '0')) as phone,
    CONCAT('تهران، خیابان ', ELT(1 + FLOOR(RAND() * 10), 'ولیعصر', 'انقلاب', 'آزادی', 'جمهوری', 'شریعتی', 'کارگر', 'فاطمی', 'میرداماد', 'نیاوران', 'پاسداران'), '، پلاک ', FLOOR(1 + RAND() * 999)) as address,
    IF(RAND() > 0.1, 'active', 'inactive') as status
FROM 
    (SELECT @row := @row + 1 as n FROM 
        (SELECT 0 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1
        CROSS JOIN (SELECT 0 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2
        CROSS JOIN (SELECT 0 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3
        CROSS JOIN (SELECT @row := 0) r
    ) numbers
LIMIT 1000;

-- ============================================
-- 5. اضافه کردن 100 داروخانه با لوکیشن روی نقشه
-- ============================================
INSERT INTO pharmacies (name, license_number, owner_name, owner_national_code, phone, mobile, address, province, city, district, latitude, longitude, status)
SELECT 
    CONCAT('داروخانه ', ELT(1 + FLOOR(RAND() * 15), 'سلامت', 'امید', 'پارس', 'نیک', 'سینا', 'آریا', 'پارسا', 'دکتر', 'پزشک', 'درمان', 'شهید', 'امام', 'ولیعصر', 'ملی', 'دولتی'), ' ', n) as name,
    CONCAT('PH-', LPAD(n, 6, '0')) as license_number,
    CONCAT(ELT(1 + FLOOR(RAND() * 10), 'دکتر', 'مهندس', 'آقای', 'خانم'), ' ', ELT(1 + FLOOR(RAND() * 20), 'احمدی', 'محمدی', 'حسینی', 'کریمی', 'رضایی', 'نوری', 'صادقی', 'جعفری', 'موسوی', 'قاسمی', 'اکبری', 'امینی', 'باقری', 'جعفری', 'حیدری', 'خانی', 'داوودی', 'رستمی', 'سلیمی', 'طاهری')) as owner_name,
    LPAD(3000000000 + n, 10, '0') as owner_national_code,
    CONCAT('021-', LPAD(4000000 + n, 7, '0')) as phone,
    CONCAT('09', LPAD(30000000 + n, 9, '0')) as mobile,
    CONCAT('تهران، خیابان ', ELT(1 + FLOOR(RAND() * 10), 'ولیعصر', 'انقلاب', 'آزادی', 'جمهوری', 'شریعتی', 'کارگر', 'فاطمی', 'میرداماد', 'نیاوران', 'پاسداران'), '، پلاک ', FLOOR(1 + RAND() * 999)) as address,
    ELT(1 + FLOOR(RAND() * 4), 'tehran', 'qom', 'isfahan', 'shiraz') as province,
    ELT(1 + FLOOR(RAND() * 4), 'tehran', 'qom', 'isfahan', 'shiraz') as city,
    ELT(1 + FLOOR(RAND() * 5), 'منطقه 1', 'منطقه 2', 'منطقه 3', 'منطقه 4', 'منطقه 5') as district,
    -- لوکیشن‌های واقعی در ایران (تهران: 35.6-35.8, 51.2-51.6)
    35.6 + (RAND() * 0.2) as latitude,
    51.2 + (RAND() * 0.4) as longitude,
    IF(RAND() > 0.1, 'active', 'inactive') as status
FROM 
    (SELECT @row := @row + 1 as n FROM 
        (SELECT 0 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1
        CROSS JOIN (SELECT 0 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2
        CROSS JOIN (SELECT @row := 0) r
    ) numbers
LIMIT 100;

-- نمایش تعداد رکوردهای اضافه شده
SELECT 'رشته‌های پزشکی' as table_name, COUNT(*) as count FROM medical_specialties
UNION ALL
SELECT 'کاربران', COUNT(*) FROM users
UNION ALL
SELECT 'پزشکان', COUNT(*) FROM doctors
UNION ALL
SELECT 'مراکز درمانی', COUNT(*) FROM medical_centers
UNION ALL
SELECT 'داروخانه‌ها', COUNT(*) FROM pharmacies;

