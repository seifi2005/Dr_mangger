-- ایجاد جدول لاگ فعالیت‌ها
-- این فایل را در phpMyAdmin اجرا کنید یا با دستور زیر:
-- mysql -u root -p medical_system < CREATE_ACTIVITY_LOGS_TABLE.sql

USE medical_system;

-- حذف جدول در صورت وجود (اختیاری - فقط برای تست)
-- DROP TABLE IF EXISTS activity_logs;

-- ایجاد جدول لاگ فعالیت‌ها
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

-- نمایش پیام موفقیت
SELECT 'جدول activity_logs با موفقیت ایجاد شد!' as message;

