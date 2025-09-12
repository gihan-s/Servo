<?php
namespace Core;

class Controller
{
    protected function view(string $path, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $file = BASE_PATH . '/app/views/' . $path . '.php';
        if (!file_exists($file)) {
            http_response_code(500);
            echo 'View not found: ' . htmlspecialchars($path);
            return;
        }
        include $file;
    }

    protected function redirect(string $path): void
    {
        // Accept absolute or relative; always prefix with BASE_URL's path
        $base = rtrim(BASE_URL, '/');
        if ($path === '' || $path[0] !== '/') {
            $path = '/' . ltrim($path, '/');
        }
        header('Location: ' . $base . $path);
        exit;
    }
}


