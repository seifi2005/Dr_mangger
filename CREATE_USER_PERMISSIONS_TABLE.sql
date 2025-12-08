-- جدول دسترسی‌های سفارشی کاربران (اختیاری - برای آینده)
-- این جدول اجازه می‌دهد به هر کاربر دسترسی‌های خاصی داده شود که متفاوت از نقش پیش‌فرضش باشد

CREATE TABLE IF NOT EXISTS user_permissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    permission_key VARCHAR(100) NOT NULL COMMENT 'مثل: doctors.view, pharmacies.edit',
    has_access BOOLEAN DEFAULT TRUE COMMENT 'آیا این دسترسی فعال است؟',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_permission (user_id, permission_key),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- مثال: اگر بخواهیم به یک اپراتور خاص دسترسی مشاهده داروخانه‌ها بدهیم:
-- INSERT INTO user_permissions (user_id, permission_key, has_access) VALUES (3, 'pharmacies.view', TRUE);

