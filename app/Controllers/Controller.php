<?php

namespace App\Controllers;

class Controller
{
    protected function getBaseUrl(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        
        // Get script name and directory
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/public/index.php';
        $scriptDir = dirname($scriptName);
        
        // The script is always in public folder, so use its directory as base
        $baseUrl = $protocol . '://' . $host . $scriptDir;
        
        return rtrim($baseUrl, '/');
    }

    protected function view(string $view, array $data = []): void
    {
        // Set current controller for sidebar
        $viewParts = explode('/', $view);
        $currentController = $data['currentController'] ?? $viewParts[0];
        $data['currentController'] = $currentController;
        
        // Add baseUrl to all views
        $data['baseUrl'] = $this->getBaseUrl();
        
        extract($data);
        
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: {$view}");
        }
        
        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/layouts/sidebar.php';
        require_once $viewFile;
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function redirect(string $url): void
    {
        $config = require __DIR__ . '/../../config/config.php';
        $baseUrl = $config['app']['url'];
        header("Location: " . $baseUrl . $url);
        exit;
    }

    protected function escape(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

