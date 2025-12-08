-- Medical System Database
-- Create Database
CREATE DATABASE IF NOT EXISTS medical_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE medical_system;

-- جدول کاربران
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    national_code VARCHAR(10) UNIQUE NOT NULL,
    mobile VARCHAR(11) NOT NULL,
    email VARCHAR(100),
    password VARCHAR(255),
    role ENUM('system-admin', 'operator', 'acceptor', 'service-provider', 'support') NOT NULL,
    address TEXT,
    image VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_national_code (national_code),
    INDEX idx_mobile (mobile)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول رشته‌های پزشکی
CREATE TABLE IF NOT EXISTS medical_specialties (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name_fa VARCHAR(200) NOT NULL,
    name_en VARCHAR(200),
    description TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_name_fa (name_fa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول پزشکان
CREATE TABLE IF NOT EXISTS doctors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    national_code VARCHAR(10) UNIQUE NOT NULL,
    id_number VARCHAR(20),
    birth_date DATE,
    gender ENUM('male', 'female'),
    father_name VARCHAR(100),
    is_deceased BOOLEAN DEFAULT FALSE,
    
    -- اطلاعات حرفه‌ای
    medical_license VARCHAR(50) UNIQUE NOT NULL,
    specialty_id INT,
    employment_status VARCHAR(50),
    medical_org_membership VARCHAR(100),
    
    -- اطلاعات تماس
    mobile VARCHAR(11),
    clinic_phone VARCHAR(20),
    home_phone VARCHAR(20),
    email VARCHAR(100),
    
    -- اطلاعات مکانی
    from_qom BOOLEAN DEFAULT FALSE,
    clinic_postal_address TEXT,
    work_address TEXT,
    home_postal_address TEXT,
    clinic_latitude DECIMAL(10, 8),
    clinic_longitude DECIMAL(11, 8),
    
    -- اطلاعات سازمانی
    clinic_name VARCHAR(200),
    description TEXT,
    registration_date DATE,
    file_number VARCHAR(50),
    image VARCHAR(255),
    
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (specialty_id) REFERENCES medical_specialties(id) ON DELETE SET NULL,
    INDEX idx_national_code (national_code),
    INDEX idx_medical_license (medical_license),
    INDEX idx_specialty_id (specialty_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول پرداخت‌های پزشکان
CREATE TABLE IF NOT EXISTS doctor_payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    doctor_id INT NOT NULL,
    receipt_number VARCHAR(50) NOT NULL,
    payment_date DATE NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    receipt_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
    INDEX idx_doctor_id (doctor_id),
    INDEX idx_payment_date (payment_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول داروخانه‌ها
CREATE TABLE IF NOT EXISTS pharmacies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    license_number VARCHAR(50) UNIQUE NOT NULL,
    owner_name VARCHAR(100) NOT NULL,
    owner_national_code VARCHAR(10) NOT NULL,
    phone VARCHAR(20),
    mobile VARCHAR(11),
    address TEXT NOT NULL,
    province VARCHAR(50),
    city VARCHAR(50),
    district VARCHAR(100),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_license_number (license_number),
    INDEX idx_province_city (province, city),
    INDEX idx_district (district)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول مراکز درمانی
CREATE TABLE IF NOT EXISTS medical_centers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    type VARCHAR(50) NOT NULL,
    license_number VARCHAR(50) UNIQUE NOT NULL,
    manager_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_license_number (license_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول لاگ فعالیت‌ها
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(50) NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id INT,
    entity_name VARCHAR(255),
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_entity_type (entity_type),
    INDEX idx_entity_id (entity_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- داده‌های نمونه برای رشته‌های پزشکی
INSERT INTO medical_specialties (name_fa, name_en, description, status) VALUES
('قلب و عروق', 'Cardiology', 'تخصص در بیماری‌های قلب و عروق', 'active'),
('پزشکی عمومی', 'General Medicine', 'پزشکی عمومی و خانواده', 'active'),
('ارتوپدی', 'Orthopedics', 'تخصص در بیماری‌های استخوان و مفاصل', 'active'),
('جراحی', 'Surgery', 'جراحی عمومی و تخصصی', 'active');

