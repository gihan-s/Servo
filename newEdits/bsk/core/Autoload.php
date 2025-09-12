<?php
spl_autoload_register(function ($class) {
    $prefixes = [
        'Core\\' => BASE_PATH . '/core/',
        'App\\Controllers\\' => BASE_PATH . '/app/controllers/',
        'App\\Models\\' => BASE_PATH . '/app/models/',
    ];

    foreach ($prefixes as $prefix => $dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }
        $relative = substr($class, $len);
        $file = $dir . str_replace('\\', '/', $relative) . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});


