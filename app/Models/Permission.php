<?php

namespace App\Models;

class Permission
{
    /**
     * Role permissions mapping
     * 
     * Roles:
     * - system-admin: مدیر سیستم (دسترسی کامل)
     * - operator: اپراتور (دسترسی محدود)
     * - acceptor: پذیرش (فقط مشاهده و ثبت)
     * - service-provider: ارائه دهنده خدمات (دسترسی محدود)
     * - support: پشتیبانی (فقط مشاهده)
     */
    private static $permissions = [
        'system-admin' => [
            // Dashboard
            'dashboard.view' => true,
            
            // Doctors
            'doctors.view' => true,
            'doctors.create' => true,
            'doctors.edit' => true,
            'doctors.delete' => true,
            'doctors.export' => true,
            
            // Users
            'users.view' => true,
            'users.create' => true,
            'users.edit' => true,
            'users.delete' => true,
            
            // Medical Centers
            'medical-centers.view' => true,
            'medical-centers.create' => true,
            'medical-centers.edit' => true,
            'medical-centers.delete' => true,
            
            // Specialties
            'specialties.view' => true,
            'specialties.create' => true,
            'specialties.edit' => true,
            'specialties.delete' => true,
            
            // Pharmacies
            'pharmacies.view' => true,
            'pharmacies.create' => true,
            'pharmacies.edit' => true,
            'pharmacies.delete' => true,
            
            // Reports
            'reports.view' => true,
            'reports.export' => true,
            
            // Settings
            'settings.view' => true,
            'settings.backup' => true,
        ],
        
        'operator' => [
            'dashboard.view' => true,
            
            // Can be customized per operator
            // By default, operators have limited access
            'doctors.view' => false,
            'doctors.create' => false,
            'doctors.edit' => false,
            
            'users.view' => false,
            
            'medical-centers.view' => false,
            
            'specialties.view' => true, // Can view specialties
            
            'pharmacies.view' => false, // Default: no access to pharmacies
            'pharmacies.create' => false,
            'pharmacies.edit' => false,
        ],
        
        'acceptor' => [
            'dashboard.view' => true,
            
            'doctors.view' => true,
            'doctors.create' => true,
            'doctors.edit' => false, // Cannot edit
            'doctors.delete' => false,
            
            'users.view' => true,
            'users.create' => true,
            'users.edit' => false,
            
            'medical-centers.view' => true,
            'specialties.view' => true,
            'pharmacies.view' => true,
        ],
        
        'service-provider' => [
            'dashboard.view' => true,
            
            'doctors.view' => true,
            'users.view' => true,
            'medical-centers.view' => true,
            'specialties.view' => true,
            'pharmacies.view' => true,
        ],
        
        'support' => [
            'dashboard.view' => true,
            
            // Support can only view, not modify
            'doctors.view' => true,
            'users.view' => true,
            'medical-centers.view' => true,
            'specialties.view' => true,
            'pharmacies.view' => true,
            
            'reports.view' => true,
        ],
    ];

    /**
     * Check if user has permission
     */
    public static function hasPermission(string $role, string $permission): bool
    {
        // System admin has all permissions
        if ($role === 'system-admin') {
            return true;
        }

        // Check if role exists
        if (!isset(self::$permissions[$role])) {
            return false;
        }

        // Check if permission exists for role
        return self::$permissions[$role][$permission] ?? false;
    }

    /**
     * Get all permissions for a role
     */
    public static function getRolePermissions(string $role): array
    {
        return self::$permissions[$role] ?? [];
    }

    /**
     * Get custom permissions for a user (from database)
     * This allows customizing individual user permissions
     */
    public static function getUserCustomPermissions(int $userId): array
    {
        // TODO: Implement database-based custom permissions per user
        // For now, return empty array
        return [];
    }

    /**
     * Check if current user has permission
     */
    public static function can(string $permission): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userRole = $_SESSION['user_role'] ?? null;
        
        if (!$userRole) {
            return false;
        }

        return self::hasPermission($userRole, $permission);
    }
}

