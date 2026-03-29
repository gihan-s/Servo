<?php
// Change working directory to public/ so that relative paths in public/index.php resolve correctly
if (!chdir(__DIR__ . '/public')) {
    http_response_code(500);
    exit('Server configuration error: public directory not found.');
}
require __DIR__ . '/public/index.php';
