<?php

namespace App\Middleware;

class AuthMiddleware
{
    /**
     * Check if user is logged in
     */
    public static function checkAuth(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Check if user has required role
     * 
     * @param string|array $requiredRoles - Single role or array of roles
     * @return bool
     */
    public static function checkRole($requiredRoles): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_role'])) {
            return false;
        }

        $userRole = $_SESSION['user_role'];

        // If requiredRoles is a string, convert to array
        if (is_string($requiredRoles)) {
            $requiredRoles = [$requiredRoles];
        }

        // Check if user role is in required roles
        return in_array($userRole, $requiredRoles);
    }

    /**
     * Require authentication - redirect to login if not authenticated
     */
    public static function requireAuth(): void
    {
        if (!self::checkAuth()) {
            header('Location: ' . self::getBaseUrl() . '/login');
            exit;
        }
    }

    /**
     * Require specific role - redirect to dashboard with error if unauthorized
     * 
     * @param string|array $requiredRoles
     */
    public static function requireRole($requiredRoles): void
    {
        // First check if logged in
        if (!self::checkAuth()) {
            header('Location: ' . self::getBaseUrl() . '/login');
            exit;
        }

        // Then check role
        if (!self::checkRole($requiredRoles)) {
            $_SESSION['error'] = 'شما دسترسی لازم برای این بخش را ندارید';
            header('Location: ' . self::getBaseUrl() . '/dashboard');
            exit;
        }
    }

    /**
     * Get base URL
     */
    private static function getBaseUrl(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        return $protocol . '://' . $host . rtrim($scriptName, '/');
    }

    /**
     * Get current user data
     */
    public static function getCurrentUser(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!self::checkAuth()) {
            return null;
        }

        return [
            'id' => $_SESSION['user_id'] ?? null,
            'name' => $_SESSION['user_name'] ?? '',
            'email' => $_SESSION['user_email'] ?? '',
            'role' => $_SESSION['user_role'] ?? 'user'
        ];
    }
}

