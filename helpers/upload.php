<?php
/**
 * Upload a file safely
 *
 * @param string $inputName        Name of the HTML input
 * @param string $targetDir        Directory to save uploaded file
 * @param array|string $allowedMimeTypes  Allowed MIME types or 'image/*'
 * @param int $maxSize             Maximum file size in bytes (default 5MB)
 * @return string|null             Returns stored filename or null if no file uploaded
 * @throws Exception               Throws exception on invalid file
 */

function uploadFile(
    string $inputName,
    string $targetDir,
    array|string $allowedMimeTypes = [],
    int $maxSize = 5242880 // 5MB
): ?string {

    if (empty($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
        return null; // No file uploaded
    }

    $tmpPath = $_FILES[$inputName]['tmp_name'];
    $originalName = $_FILES[$inputName]['name'];
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    // Check file size
    if ($_FILES[$inputName]['size'] > $maxSize) {
        throw new Exception('File size exceeds limit');
    }

    // Ensure target directory exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Clean filename
    // $cleanName = preg_replace('/[^a-zA-Z0-9._-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
    // $cleanName = trim($cleanName, '_') ?: 'file';
    $filename = bin2hex(random_bytes(16)) . '.' . $ext;
    $targetFile = rtrim($targetDir, '/') . '/' . $filename;

    // Validate MIME type
    if ($allowedMimeTypes) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $tmpPath);
        finfo_close($finfo);

        // Handle 'image/*' wildcard
        if ($allowedMimeTypes === 'image/*') {
            if (strpos($mime, 'image/') !== 0) {
                throw new Exception('Invalid file type, only images allowed');
            }
        } else {
            if (!in_array($mime, (array)$allowedMimeTypes, true)) {
                throw new Exception('Invalid file type');
            }
        }
    }

    // Move uploaded file
    if (!move_uploaded_file($tmpPath, $targetFile)) {
        throw new Exception('Failed to move uploaded file');
    }

    return $filename;
}
