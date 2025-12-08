<?php
/**
 * Helper برای بررسی دسترسی در View
 * 
 * استفاده:
 * <?php if (canView('doctors.edit')): ?>
 *     <a href="...">ویرایش</a>
 * <?php endif; ?>
 */

use App\Models\Permission;

if (!function_exists('canView')) {
    function canView(string $permission): bool
    {
        return Permission::can($permission);
    }
}

if (!function_exists('isSystemAdmin')) {
    function isSystemAdmin(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'system-admin';
    }
}

if (!function_exists('getUserRole')) {
    function getUserRole(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['user_role'] ?? 'guest';
    }
}

if (!function_exists('getRoleName')) {
    function getRoleName(string $role): string
    {
        $roleNames = [
            'system-admin' => 'مدیر سیستم',
            'operator' => 'اپراتور',
            'acceptor' => 'پذیرش',
            'service-provider' => 'ارائه دهنده خدمات',
            'support' => 'پشتیبانی'
        ];
        
        return $roleNames[$role] ?? 'نامشخص';
    }
}


