<?php
namespace Core;

class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->routes['GET'][$this->normalize($path)] = $handler;
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->routes['POST'][$this->normalize($path)] = $handler;
    }

    public function any(string $path, callable|array $handler): void
    {
        $n = $this->normalize($path);
        $this->routes['GET'][$n] = $handler;
        $this->routes['POST'][$n] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = $this->normalize($uri);
        $handler = $this->routes[$method][$path] ?? $this->routes['GET']['/'] ?? null;

        if ($handler === null) {
            http_response_code(404);
            echo '404 Not Found';
            return;
        }

        if (is_array($handler)) {
            [$class, $methodName] = $handler;
            $controller = new $class();
            $controller->$methodName();
            return;
        }

        call_user_func($handler);
    }

    private function normalize(string $path): string
    {
        $path = rtrim($path, '/');
        if ($path === '') { return '/'; }
        return $path;
    }
}


