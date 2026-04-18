<?php
class FileController
{

    public function showTempImage($filename)
    {
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

    public function showUserImage($filename)
    {
        $filename = urldecode($filename);   // ⭐ ADD THIS LINE
        $filename = basename($filename);    // keep security

        $path = __DIR__ . '/../../uploads/Users/' . $filename;

        if (!file_exists($path)) {
            http_response_code(404);
            exit('File not found');
        }

        $mime = mime_content_type($path);
        $size = filesize($path);
        $lastModified = gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT';

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . $size);
        header('Cache-Control: public, max-age=604800');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 604800) . ' GMT');
        header('Last-Modified: ' . $lastModified);
        readfile($path);
        exit;
    }

    public function getCategoryIcons($filename)
    {
        $filename = urldecode($filename);   // ⭐ ADD THIS LINE
        $filename = basename($filename);    // keep security

        $path = __DIR__ . '/../../uploads/Categories/' . $filename;

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

    public function showProjectUpdateFile($filename)
    {
        $filename = urldecode($filename);   // ⭐ ADD THIS LINE
        $filename = basename($filename);    // keep security

        $path = __DIR__ . '/../../uploads/Projects/updates/' . $filename;

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

    public function showProjectRequirementFile($filename)
    {
        $filename = urldecode($filename);   // ⭐ ADD THIS LINE
        $filename = basename($filename);    // keep security

        $path = __DIR__ . '/../../uploads/Projects/requirements/' . $filename;

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
