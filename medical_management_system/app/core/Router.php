<?php

declare(strict_types=1);

namespace App\Core;

use Throwable;

class Router
{
    /**
     * @param array<string, array<string, array{0: string, 1: string}>> $routes
     */
    public function __construct(private array $routes = [])
    {
    }

    public function dispatch(Request $request): Response
    {
        $method = $request->getMethod();
        $path = rtrim($request->getPath(), '/') ?: '/';

        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            return Response::html('<h1>404 Not Found</h1>', 404);
        }

        [$class, $action] = $handler;

        if (!class_exists($class)) {
            return Response::html('<h1>Controller not found</h1>', 500);
        }

        $controller = new $class();

        if (!method_exists($controller, $action)) {
            return Response::html('<h1>Action not found</h1>', 500);
        }

        try {
            $result = $controller->{$action}($request);
            return $result instanceof Response ? $result : Response::html((string) $result);
        } catch (Throwable $throwable) {
            return Response::html('<h1>Application Error</h1><p>' . htmlspecialchars($throwable->getMessage()) . '</p>', 500);
        }
    }
}
