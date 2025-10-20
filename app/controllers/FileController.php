<?php
class FileController {
    public function showUserImage($filename) {
        $filename = basename($filename); // prevent ../../ attacks
        $path = __DIR__ . '/../../uploads/temp/' . $filename;

        if (!file_exists($path)) {
            http_response_code(404);
            exit('File not found');
        }

        $mime = mime_content_type($path);
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }
}
