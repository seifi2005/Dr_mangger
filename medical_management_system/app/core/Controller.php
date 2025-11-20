<?php

declare(strict_types=1);

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = []): Response
    {
        $viewPath = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            return Response::html('View ' . htmlspecialchars($view) . ' not found', 500);
        }

        extract($data, EXTR_SKIP);

        ob_start();
        include $viewPath;
        $content = (string) ob_get_clean();

        return Response::html($content);
    }
}
