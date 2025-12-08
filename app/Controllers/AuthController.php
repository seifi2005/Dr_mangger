<?php

namespace App\Controllers;

use App\Models\User;

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function login(): void
    {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
            return;
        }

        $config = require __DIR__ . '/../../config/config.php';
        $baseUrl = $config['app']['url'];
        
        // Auto-detect base URL if not set correctly
        if (empty($baseUrl) || $baseUrl === 'http://localhost/medic/public') {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $scriptName = dirname($_SERVER['SCRIPT_NAME']);
            $baseUrl = $protocol . '://' . $host . rtrim($scriptName, '/');
        }
        
        $pageTitle = 'ورود به سیستم - سیستم مدیریت پزشکان';
        $error = $_GET['error'] ?? '';

        // Load login view without header/sidebar
        $viewFile = __DIR__ . '/../Views/auth/login.php';
        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: auth/login");
        }

        extract(['baseUrl' => $baseUrl, 'pageTitle' => $pageTitle, 'error' => $error]);
        require_once $viewFile;
    }

    public function authenticate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login?error=invalid_request');
            return;
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        if (empty($username) || empty($password)) {
            $this->redirect('/login?error=empty_fields');
            return;
        }

        // Find user by email or national_code
        $user = $this->userModel->where('email', '=', $username);
        if (empty($user)) {
            $user = $this->userModel->where('national_code', '=', $username);
        }

        if (empty($user) || !isset($user[0])) {
            $this->redirect('/login?error=invalid_credentials');
            return;
        }

        $user = $user[0];

        // Check password
        // First try password_verify (for hashed passwords)
        $passwordValid = false;
        if (!empty($user['password'])) {
            // Check if password is hashed (starts with $2y$ or similar)
            if (strpos($user['password'], '$2y$') === 0 || strpos($user['password'], '$2a$') === 0) {
                $passwordValid = password_verify($password, $user['password']);
            } else {
                // Plain text password (for backward compatibility)
                $passwordValid = ($user['password'] === $password);
            }
        }

        if (!$passwordValid) {
            $this->redirect('/login?error=invalid_credentials');
            return;
        }

        // Check if user is active
        if ($user['status'] !== 'active') {
            $this->redirect('/login?error=inactive_user');
            return;
        }

        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['user_role'] = $user['role'] ?? 'user';
        $_SESSION['user_email'] = $user['email'] ?? '';

        // Set remember me cookie if checked
        if ($remember) {
            setcookie('remember_token', base64_encode($user['id'] . '|' . $user['email']), time() + (86400 * 30), '/'); // 30 days
        }

        $this->redirect('/dashboard');
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Destroy session
        $_SESSION = [];
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        session_destroy();

        // Clear remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }

        $this->redirect('/login');
    }
}

